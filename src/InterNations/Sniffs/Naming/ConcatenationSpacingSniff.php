<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ConcatenationSpacingSniff implements Sniff
{
    public function register()
    {
        return [T_STRING_CONCAT];
    }

    public function process(File $file, $stackPtr)
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
