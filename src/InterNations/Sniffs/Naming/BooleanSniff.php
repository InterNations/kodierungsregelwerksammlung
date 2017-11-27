<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class BooleanSniff implements Sniff
{
    public function register()
    {
        return [T_TRUE, T_FALSE];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $expected = $tokens[$stackPtr]['code'] === T_TRUE ? 'true' : 'false';

        if ($tokens[$stackPtr]['content'] !== $expected) {
            $file->addError(
                'Expected boolean to be defined as "%s", got "%s"',
                $stackPtr,
                'InvalidBoolean',
                [$expected, $tokens[$stackPtr]['content']]
            );
        }
    }
}
