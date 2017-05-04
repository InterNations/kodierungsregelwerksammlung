<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ShortListSyntaxSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_LIST];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $file->addError('Legacy list syntax (list()) is discouraged. Use [] instead', $stackPtr, 'legacyListSyntax');
    }
}
