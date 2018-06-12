<?php
/**
 * Ensures doc blocks follow basic formatting.
 *
 * @author    Mystro Ken <mystroken@gmail.com>
 * @link.     https://laravel.com/docs/5.6/contributions#coding-style
 */

namespace PHP_CodeSniffer\Standards\Laravel\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DocCommentSniff implements Sniff
{
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return [
			T_DOC_COMMENT_OPEN_TAG,
			T_DOC_COMMENT_WHITESPACE,
			T_DOC_COMMENT_TAG,
			T_DOC_COMMENT_STRING,
			T_DOC_COMMENT_CLOSE_TAG,
		];
	}

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
	 * @param int                         $stackPtr  The position of the current token
	 *                                               in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr )
	{
		$tokens = $phpcsFile->getTokens();


		if ( isset( $tokens[$stackPtr]['content'] )
		     && $tokens[$stackPtr]['content'] === '@param' ) {

			$currentPtr = $stackPtr + 1;

			while (  $tokens[$currentPtr]['length'] > 0
			         && $tokens[$currentPtr]['type'] !== 'T_DOC_COMMENT_TAG' ) {

				if ( $tokens[$currentPtr]['type'] === 'T_DOC_COMMENT_WHITESPACE'
				     && $tokens[$currentPtr]['length'] !== 2 ) {
					$phpcsFile->addError('The @param attribute is followed by two spaces, the argument type, two more spaces, and finally the variable name', $currentPtr, 'NotSeparatedByTwoSpaces');
					return;
				}

				if ( $tokens[$currentPtr]['type'] === 'T_DOC_COMMENT_STRING' ) {
					$contentParts = explode(' ', $tokens[$currentPtr]['content']);

					if ( sizeof($contentParts) !== 3 ) {
						$phpcsFile->addError('The @param attribute is followed by two spaces, the argument type, two more spaces, and finally the variable name', $currentPtr, 'NotSeparatedByTwoSpaces');
						return;
					}

				}

				$currentPtr++;
			}

		}

		return;
	}
}