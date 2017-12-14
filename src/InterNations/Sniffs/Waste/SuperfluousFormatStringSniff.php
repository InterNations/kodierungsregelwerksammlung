<?php
namespace InterNations\Sniffs\Waste;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class SuperfluousFormatStringSniff implements Sniff
{
    public function register()
    {
        return [T_STRING];
    }

    public function process(File $file, $stackPtr)
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
            'Superfluous %s() call as no parameters are passed. %s',
            $stackPtr,
            'SuperfluousFormatString',
            [
                $tokens[$stackPtr]['content'],
                $tokens[$stackPtr]['content'] === 'printf' ?
                    'Use plain "echo …;" instead' :
                    'You can safely remove the function call'
            ]
        );
    }
}
