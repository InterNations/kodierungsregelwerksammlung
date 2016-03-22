<?php
namespace InterNations\Sniffs\Formatting;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class EmptyLineBeforeControlStructureFormattingSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_IF, T_SWITCH, T_FOR, T_FOREACH, T_WHILE, T_DO, T_RETURN];
    }

    public function process(CodeSnifferFile $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $current = $stackPtr;

        $structureType = $tokens[$current]['content'];

        $previousLine = $tokens[$stackPtr]['line'] - 1;
        $prevLineTokens = [];

        while ($current >= 0 && $tokens[$current]['line'] >= $previousLine) {
            if ($tokens[$current]['line'] === $previousLine
                && $tokens[$current]['type'] !== 'T_WHITESPACE'
                && $tokens[$current]['type'] !== 'T_COMMENT'
            ) {
                $prevLineTokens[] = $tokens[$current]['type'];
            }
            $current--;
        }

        if (isset($prevLineTokens[0])
            && ($prevLineTokens[0] === 'T_OPEN_CURLY_BRACKET'
                || $prevLineTokens[0] === 'T_COLON')
        ) {
            return;
        } elseif (count($prevLineTokens) > 0) {
            $phpcsFile->addError('Missing blank line before ' . $structureType . ' statement', $stackPtr);
        }
    }
}
