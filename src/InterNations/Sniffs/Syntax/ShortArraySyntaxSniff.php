<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ShortArraySyntaxSniff implements Sniff
{
    public function register()
    {
        return [T_ARRAY];
    }

    public function process(File $file, $stackPtr)
    {
        $file->addError('Legacy array syntax (array()) is discouraged. Use [] instead', $stackPtr, 'legacyArraySyntax');
    }
}
