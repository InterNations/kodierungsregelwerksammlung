<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

// @codingStandardsIgnoreStart
class InterNations_Sniffs_Naming_ConcatenationSpacingSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
{
    public function register()
    {
        return [T_STRING_CONCAT];
    }

    public function process(CodeSnifferFile $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE
            || $tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE
            || $tokens[($stackPtr + 2)]['code'] === T_WHITESPACE
        ) {
            $message = 'Concat operator must be surrounded by exactly one space';
            $phpcsFile->addError($message, $stackPtr, 'Missing');
        }
    }
}
