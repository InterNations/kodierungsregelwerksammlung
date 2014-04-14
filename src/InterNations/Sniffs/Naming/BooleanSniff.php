<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class BooleanSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_TRUE, T_FALSE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
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
