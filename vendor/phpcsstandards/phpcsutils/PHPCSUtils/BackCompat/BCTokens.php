<?php
/**
 * PHPCSUtils, utility functions and classes for PHP_CodeSniffer sniff developers.
 *
 * @package   PHPCSUtils
 * @copyright 2019-2020 PHPCSUtils Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSUtils
 */

namespace PHPCSUtils\BackCompat;

use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Exceptions\InvalidTokenArray;
use PHPCSUtils\Tokens\Collections;

/**
 * Token arrays related utility methods.
 *
 * PHPCS provides a number of static token arrays in the {@see \PHP_CodeSniffer\Util\Tokens}
 * class.
 * Some of these token arrays will not be available in older PHPCS versions.
 * Some will not contain the same set of tokens across PHPCS versions.
 *
 * This class is a compatibility layer to allow for retrieving these token arrays
 * with a consistent token content across PHPCS versions.
 * The one caveat is that the token constants do need to be available.
 *
 * Recommended usage:
 * Only use the methods in this class when needed. I.e. when your sniff unit tests indicate
 * a PHPCS cross-version compatibility issue related to inconsistent token arrays.
 *
 * All PHPCS token arrays are supported, though only a limited number of them are different
 * across PHPCS versions.
 *
 * The names of the PHPCS native token arrays translate one-on-one to the methods in this class:
 * - `PHP_CodeSniffer\Util\Tokens::$emptyTokens` => `PHPCSUtils\BackCompat\BCTokens::emptyTokens()`
 * - `PHP_CodeSniffer\Util\Tokens::$operators`   => `PHPCSUtils\BackCompat\BCTokens::operators()`
 * - ... etc
 *
 * The order of the tokens in the arrays may differ between the PHPCS native token arrays and
 * the token arrays returned by this class.
 *
 * @since 1.0.0
 *
 * @method static array arithmeticTokens()         Tokens that represent arithmetic operators.
 * @method static array assignmentTokens()         Tokens that represent assignments.
 * @method static array blockOpeners()             Tokens that open code blocks.
 * @method static array booleanOperators()         Tokens that perform boolean operations.
 * @method static array bracketTokens()            Tokens that represent brackets and parenthesis.
 * @method static array castTokens()               Tokens that represent type casting.
 * @method static array commentTokens()            Tokens that are comments.
 * @method static array comparisonTokens()         Tokens that represent comparison operator.
 * @method static array contextSensitiveKeywords() Tokens representing context sensitive keywords in PHP.
 * @method static array emptyTokens()              Tokens that don't represent code.
 * @method static array equalityTokens()           Tokens that represent equality comparisons.
 * @method static array heredocTokens()            Tokens that make up a heredoc string.
 * @method static array includeTokens()            Tokens that include files.
 * @method static array magicConstants()           Tokens representing PHP magic constants.
 * @method static array methodPrefixes()           Tokens that can prefix a method name.
 * @method static array ooScopeTokens()            Tokens that open class and object scopes.
 * @method static array operators()                Tokens that perform operations.
 * @method static array parenthesisOpeners()       Token types that open parenthesis.
 * @method static array phpcsCommentTokens()       Tokens that are comments containing PHPCS instructions.
 * @method static array scopeModifiers()           Tokens that represent scope modifiers.
 * @method static array scopeOpeners()             Tokens that are allowed to open scopes.
 * @method static array stringTokens()             Tokens that represent strings.
 *                                                 Note that `T_STRING`s are NOT represented in this list as this list
 *                                                 is about _text_ strings.
 * @method static array textStringTokens()         Tokens that represent text strings.
 */
final class BCTokens
{

    /**
     * Handle calls to (undeclared) methods for token arrays which haven't received any
     * changes since PHPCS 3.10.0.
     *
     * @since 1.0.0
     *
     * @param string       $name The name of the method which has been called.
     * @param array<mixed> $args Any arguments passed to the method.
     *                           Unused as none of the methods take arguments.
     *
     * @return array<int|string, int|string> Token array
     *
     * @throws \PHPCSUtils\Exceptions\InvalidTokenArray When an invalid token array is requested.
     */
    public static function __callStatic($name, $args)
    {
        if (isset(Tokens::${$name})) {
            return Tokens::${$name};
        }

        // Unknown token array requested.
        throw InvalidTokenArray::create($name);
    }

    /**
     * Tokens that represent the names of called functions.
     *
     * Retrieve the PHPCS function name tokens array in a cross-version compatible manner.
     *
     * Changelog for the PHPCS native array:
     * - Introduced in PHPCS 2.3.3.
     * - PHPCS 3.7.2: `T_PARENT` added to the array.
     * - PHPCS 4.0.0: `T_NAME_QUALIFIED`, `T_NAME_FULLY_QUALIFIED` and `T_NAME_RELATIVE` added to the array.
     *
     * @see \PHP_CodeSniffer\Util\Tokens::$functionNameTokens Original array.
     *
     * @since 1.0.0
     *
     * @return array<int|string, int|string> Token array.
     */
    public static function functionNameTokens()
    {
        $tokens  = Tokens::$functionNameTokens;
        $tokens += Collections::ooHierarchyKeywords();
        $tokens += Collections::nameTokens();

        return $tokens;
    }
}
