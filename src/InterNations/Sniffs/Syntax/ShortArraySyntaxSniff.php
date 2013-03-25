<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

// @codingStandardsIgnoreStart
class InterNations_Sniffs_Syntax_ShortArraySyntaxSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
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
