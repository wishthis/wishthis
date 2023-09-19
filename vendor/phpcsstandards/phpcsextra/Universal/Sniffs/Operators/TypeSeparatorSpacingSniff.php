<?php
/**
 * PHPCSExtra, a collection of sniffs and standards for use with PHP_CodeSniffer.
 *
 * @package   PHPCSExtra
 * @copyright 2020 PHPCSExtra Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCSStandards/PHPCSExtra
 */

namespace PHPCSExtra\Universal\Sniffs\Operators;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHPCSUtils\Fixers\SpacesFixer;

/**
 * Enforce no space around union type and intersection type separators.
 *
 * @since 1.0.0
 */
final class TypeSeparatorSpacingSniff implements Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @since 1.0.0
     *
     * @return array<int|string>
     */
    public function register()
    {
        return [
            \T_TYPE_UNION,
            \T_TYPE_INTERSECTION,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @since 1.0.0
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $type = ($tokens[$stackPtr]['code'] === \T_TYPE_UNION) ? 'union' : 'intersection';
        $code = \ucfirst($type) . 'Type';

        $prevNonEmpty = $phpcsFile->findPrevious(Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        SpacesFixer::checkAndFix(
            $phpcsFile,
            $stackPtr,
            $prevNonEmpty,
            0, // Expected spaces.
            'Expected %s before the ' . $type . ' type separator. Found: %s',
            $code . 'SpacesBefore',
            'error',
            0, // Severity.
            'Space before ' . $type . ' type separator'
        );

        $nextNonEmpty = $phpcsFile->findNext(Tokens::$emptyTokens, ($stackPtr + 1), null, true);
        SpacesFixer::checkAndFix(
            $phpcsFile,
            $stackPtr,
            $nextNonEmpty,
            0, // Expected spaces.
            'Expected %s after the ' . $type . ' type separator. Found: %s',
            $code . 'SpacesAfter',
            'error',
            0, // Severity.
            'Space after ' . $type . ' type separator'
        );
    }
}
