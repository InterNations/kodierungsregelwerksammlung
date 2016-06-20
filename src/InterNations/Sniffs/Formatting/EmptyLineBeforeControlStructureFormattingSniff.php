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

        $ignoredTokens = [
            T_WHITESPACE,
            T_COMMENT,
            T_DOC_COMMENT_CLOSE_TAG,
            T_DOC_COMMENT_WHITESPACE,
            T_DOC_COMMENT_STAR,
            T_DOC_COMMENT_OPEN_TAG,
            T_DOC_COMMENT_TAG,
            T_DOC_COMMENT_STRING,
        ];

        while ($current >= 0 && $tokens[$current]['line'] >= $previousLine) {

            if ($tokens[$current]['line'] === $previousLine
                && !in_array($tokens[$current]['code'], $ignoredTokens, true)) {
                $prevLineTokens[] = $tokens[$current]['code'];
            }
            $current--;
        }

        if (isset($prevLineTokens[0])
            && ($prevLineTokens[0] === T_OPEN_CURLY_BRACKET || $prevLineTokens[0] === T_COLON)
        ) {

            return;
        } elseif (count($prevLineTokens) > 0) {
            $phpcsFile->addError('Missing blank line before ' . $structureType . ' statement', $stackPtr);
        }
    }
}
