<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

/**
 * @SuppressWarnings(PMD)
 */
// @codingStandardsIgnoreStart
class InterNations_Sniffs_Whitespace_NamespaceWhitespaceSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
{
    public function register()
    {
        return [T_NAMESPACE];
    }

    public function process(CodeSnifferFile $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr - 1]['code'] === T_WHITESPACE) {
            $phpcsFile->addError(
                'Invalid newline(s) before namespace declaration',
                $stackPtr,
                'WhitespaceBeforeNamespace'
            );
        }
    }
}
