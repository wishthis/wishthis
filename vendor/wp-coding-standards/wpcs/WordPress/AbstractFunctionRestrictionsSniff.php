<?php
/**
 * WordPress Coding Standard.
 *
 * @package WPCS\WordPressCodingStandards
 * @link    https://github.com/WordPress/WordPress-Coding-Standards
 * @license https://opensource.org/licenses/MIT MIT
 */

namespace WordPressCS\WordPress;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Utils\Context;
use PHPCSUtils\Utils\MessageHelper;
use WordPressCS\WordPress\Helpers\ContextHelper;
use WordPressCS\WordPress\Helpers\RulesetPropertyHelper;
use WordPressCS\WordPress\Sniff;

/**
 * Restricts usage of some functions.
 *
 * @since 0.3.0
 * @since 0.10.0 Class became a proper abstract class. This was already the behaviour.
 *               Moved the file and renamed the class from
 *               `\WordPressCS\WordPress\Sniffs\Functions\FunctionRestrictionsSniff` to
 *               `\WordPressCS\WordPress\AbstractFunctionRestrictionsSniff`.
 * @since 0.11.0 Extends the WordPressCS native `Sniff` class.
 */
abstract class AbstractFunctionRestrictionsSniff extends Sniff {

	/**
	 * Exclude groups.
	 *
	 * Example: 'switch_to_blog,user_meta'
	 *
	 * @since 0.3.0
	 * @since 1.0.0 This property now expects to be passed an array.
	 *              Previously a comma-delimited string was expected.
	 *
	 * @var array
	 */
	public $exclude = array();

	/**
	 * Groups of function data to check against.
	 * Don't use this in extended classes, override getGroups() instead.
	 * This is only used for Unit tests.
	 *
	 * @since 0.10.0
	 *
	 * @var array
	 */
	public static $unittest_groups = array();

	/**
	 * Regex pattern with placeholder for the function names.
	 *
	 * @since 0.10.0
	 *
	 * @var string
	 */
	protected $regex_pattern = '`^(?:%s)$`i';

	/**
	 * Cache for the group information.
	 *
	 * @since 0.10.0
	 *
	 * @var array
	 */
	protected $groups = array();

	/**
	 * Cache for the excluded groups information.
	 *
	 * @since 0.11.0
	 *
	 * @var array
	 */
	protected $excluded_groups = array();

	/**
	 * Regex containing the name of all functions handled by a sniff.
	 *
	 * Set in `register()` and used to do an initial check.
	 *
	 * @var string
	 */
	private $prelim_check_regex;

	/**
	 * Groups of functions to restrict.
	 *
	 * This method should be overridden in extending classes.
	 *
	 * Example: groups => array(
	 *     'lambda' => array(
	 *         'type'      => 'error' | 'warning',
	 *         'message'   => 'Use anonymous functions instead please!',
	 *         'functions' => array( 'file_get_contents', 'create_function', 'mysql_*' ),
	 *         // Only useful when using wildcards:
	 *         'allow' => array( 'mysql_to_rfc3339' => true, ),
	 *     )
	 * )
	 *
	 * You can use * wildcards to target a group of functions.
	 * When you use * wildcards, you may inadvertently restrict too many
	 * functions. In that case you can add the `allow` key to
	 * safe list individual functions to prevent false positives.
	 *
	 * @return array
	 */
	abstract public function getGroups();

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		// Prepare the function group regular expressions only once.
		if ( false === $this->setup_groups( 'functions' ) ) {
			return array();
		}

		return array(
			\T_STRING,
		);
	}

	/**
	 * Set up the regular expressions for each group.
	 *
	 * @since 0.10.0
	 *
	 * @param string $key The group array index key where the input for the regular expression can be found.
	 * @return bool True if the groups were setup. False if not.
	 */
	protected function setup_groups( $key ) {
		// Prepare the function group regular expressions only once.
		$this->groups = $this->getGroups();

		if ( empty( $this->groups ) && empty( self::$unittest_groups ) ) {
			return false;
		}

		// Allow for adding extra unit tests.
		if ( ! empty( self::$unittest_groups ) ) {
			$this->groups = array_merge( $this->groups, self::$unittest_groups );
		}

		$all_items = array();
		foreach ( $this->groups as $groupName => $group ) {
			if ( empty( $group[ $key ] ) ) {
				unset( $this->groups[ $groupName ] );
				continue;
			}

			// Lowercase the items and potential allows as the comparisons should be done case-insensitively.
			// Note: this disregards non-ascii names, but as we don't have any of those, that is okay for now.
			$items                              = array_map( 'strtolower', $group[ $key ] );
			$this->groups[ $groupName ][ $key ] = $items;

			if ( ! empty( $group['allow'] ) ) {
				$this->groups[ $groupName ]['allow'] = array_change_key_case( $group['allow'], \CASE_LOWER );
			}

			$items       = array_map( array( $this, 'prepare_name_for_regex' ), $items );
			$all_items[] = $items;
			$items       = implode( '|', $items );

			$this->groups[ $groupName ]['regex'] = sprintf( $this->regex_pattern, $items );
		}

		if ( empty( $this->groups ) ) {
			return false;
		}

		// Create one "super-regex" to allow for initial filtering.
		$all_items                = \call_user_func_array( 'array_merge', $all_items );
		$all_items                = implode( '|', array_unique( $all_items ) );
		$this->prelim_check_regex = sprintf( $this->regex_pattern, $all_items );

		return true;
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_token( $stackPtr ) {

		$this->excluded_groups = RulesetPropertyHelper::merge_custom_array( $this->exclude );
		if ( array_diff_key( $this->groups, $this->excluded_groups ) === array() ) {
			// All groups have been excluded.
			// Don't remove the listener as the exclude property can be changed inline.
			return;
		}

		// Preliminary check. If the content of the T_STRING is not one of the functions we're
		// looking for, we can bow out before doing the heavy lifting of checking whether
		// this is a function call.
		if ( preg_match( $this->prelim_check_regex, $this->tokens[ $stackPtr ]['content'] ) !== 1 ) {
			return;
		}

		if ( true === $this->is_targetted_token( $stackPtr ) ) {
			return $this->check_for_matches( $stackPtr );
		}
	}

	/**
	 * Verify is the current token is a function call.
	 *
	 * @since 0.11.0 Split out from the `process()` method.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return bool
	 */
	public function is_targetted_token( $stackPtr ) {
		// Exclude function definitions, class methods, and namespaced calls.
		if ( ContextHelper::has_object_operator_before( $this->phpcsFile, $stackPtr ) === true ) {
			return false;
		}

		if ( ContextHelper::is_token_namespaced( $this->phpcsFile, $stackPtr ) === true ) {
			return false;
		}

		if ( Context::inAttribute( $this->phpcsFile, $stackPtr ) ) {
			// Class instantiation or constant in attribute, not function call.
			return false;
		}

		$search                   = Tokens::$emptyTokens;
		$search[ \T_BITWISE_AND ] = \T_BITWISE_AND;

		$prev = $this->phpcsFile->findPrevious( $search, ( $stackPtr - 1 ), null, true );

		// Skip sniffing on function, OO definitions or for function aliases in use statements.
		$invalid_tokens  = Tokens::$ooScopeTokens;
		$invalid_tokens += array(
			\T_FUNCTION => \T_FUNCTION,
			\T_NEW      => \T_NEW,
			\T_AS       => \T_AS, // Use declaration alias.
		);

		if ( isset( $invalid_tokens[ $this->tokens[ $prev ]['code'] ] ) ) {
			return false;
		}

		// Check if this could even be a function call.
		$next = $this->phpcsFile->findNext( Tokens::$emptyTokens, ( $stackPtr + 1 ), null, true );
		if ( false === $next ) {
			return false;
		}

		// Check for `use function ... (as|;)`.
		if ( ( \T_STRING === $this->tokens[ $prev ]['code'] && 'function' === $this->tokens[ $prev ]['content'] )
			&& ( \T_AS === $this->tokens[ $next ]['code'] || \T_SEMICOLON === $this->tokens[ $next ]['code'] )
		) {
			return true;
		}

		// If it's not a `use` statement, there should be parenthesis.
		if ( \T_OPEN_PARENTHESIS !== $this->tokens[ $next ]['code'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Verify if the current token is one of the targetted functions.
	 *
	 * @since 0.11.0 Split out from the `process()` method.
	 *
	 * @param int $stackPtr The position of the current token in the stack.
	 *
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function check_for_matches( $stackPtr ) {
		$token_content = strtolower( $this->tokens[ $stackPtr ]['content'] );
		$skip_to       = array();

		foreach ( $this->groups as $groupName => $group ) {

			if ( isset( $this->excluded_groups[ $groupName ] ) ) {
				continue;
			}

			if ( isset( $group['allow'][ $token_content ] ) ) {
				continue;
			}

			if ( preg_match( $group['regex'], $token_content ) === 1 ) {
				$skip_to[] = $this->process_matched_token( $stackPtr, $groupName, $token_content );
			}
		}

		if ( empty( $skip_to ) || min( $skip_to ) === 0 ) {
			return;
		}

		return min( $skip_to );
	}

	/**
	 * Process a matched token.
	 *
	 * @since 0.11.0 Split out from the `process()` method.
	 *
	 * @param int    $stackPtr        The position of the current token in the stack.
	 * @param string $group_name      The name of the group which was matched.
	 * @param string $matched_content The token content (function name) which was matched
	 *                                in lowercase.
	 *
	 * @return int|void Integer stack pointer to skip forward or void to continue
	 *                  normal file processing.
	 */
	public function process_matched_token( $stackPtr, $group_name, $matched_content ) {

		MessageHelper::addMessage(
			$this->phpcsFile,
			$this->groups[ $group_name ]['message'],
			$stackPtr,
			( 'error' === $this->groups[ $group_name ]['type'] ),
			MessageHelper::stringToErrorcode( $group_name . '_' . $matched_content ),
			array( $matched_content )
		);
	}

	/**
	 * Prepare the function name for use in a regular expression.
	 *
	 * The getGroups() method allows for providing function names with a wildcard * to target
	 * a group of functions. This prepare routine takes that into account while still safely
	 * escaping the function name for use in a regular expression.
	 *
	 * @since 0.10.0
	 *
	 * @param string $function_name Function name.
	 * @return string Regex escaped function name.
	 */
	protected function prepare_name_for_regex( $function_name ) {
		$function_name = str_replace( array( '.*', '*' ), '@@', $function_name ); // Replace wildcards with placeholder.
		$function_name = preg_quote( $function_name, '`' );
		$function_name = str_replace( '@@', '.*', $function_name ); // Replace placeholder with regex wildcard.

		return $function_name;
	}
}
