<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ShortArraySyntaxSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_ARRAY];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $file->addError(
            'Legacy array syntax (array()) is discouraged. Use [] instead',
            $stackPtr,
            'legacyArraySyntax'
        );
    }
}
