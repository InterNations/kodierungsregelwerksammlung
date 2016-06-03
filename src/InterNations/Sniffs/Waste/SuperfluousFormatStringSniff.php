<?php
namespace InterNations\Sniffs\Waste;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class SuperfluousFormatStringSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_STRING];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if (!in_array($tokens[$stackPtr]['content'], ['printf', 'sprintf'], true)) {
            return;
        }

        $openingBrace = $tokens[$stackPtr + 1];

        for ($pos = $openingBrace['parenthesis_opener'] + 1; $pos < $openingBrace['parenthesis_closer']; $pos++) {
            if (!in_array($tokens[$pos]['code'], [T_CONSTANT_ENCAPSED_STRING, T_WHITESPACE, T_STRING_CONCAT], true)) {
                return;
            }
        }

        $file->addError(
            'Superfluous %s() call as no parameters are passed',
            $stackPtr,
            'SuperfluousFormatString',
            [$tokens[$stackPtr]['content']]
        );
    }
}
