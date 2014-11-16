<?php
namespace InterNations\Sniffs\Whitespace;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class NamespaceWhitespaceSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_NAMESPACE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if ($tokens[$stackPtr - 1]['code'] === T_WHITESPACE) {
            $file->addError('Invalid newline(s) before namespace declaration', $stackPtr, 'WhitespaceBeforeNamespace');
        }
    }
}
