<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ShortListSyntaxSniff implements Sniff
{
    public function register()
    {
        return [T_LIST];
    }

    public function process(File $file, $stackPtr)
    {
        $file->addError('Legacy list syntax (list()) is discouraged. Use [] instead', $stackPtr, 'legacyListSyntax');
    }
}
