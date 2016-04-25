<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ConcatenationSpacingSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_STRING_CONCAT];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE
            || $tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE
            || $tokens[($stackPtr + 2)]['code'] === T_WHITESPACE
        ) {
            $message = 'Concat operator must be surrounded by exactly one space';
            $file->addError($message, $stackPtr, 'Missing');
        }
    }
}
