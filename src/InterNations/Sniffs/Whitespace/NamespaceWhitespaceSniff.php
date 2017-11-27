<?php
namespace InterNations\Sniffs\Whitespace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class NamespaceWhitespaceSniff implements Sniff
{
    public function register()
    {
        return [T_NAMESPACE];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if ($tokens[$stackPtr - 1]['code'] === T_WHITESPACE) {

            $file->addError('Invalid newline(s) before namespace declaration', $stackPtr, 'WhitespaceBeforeNamespace');
        }
    }
}
