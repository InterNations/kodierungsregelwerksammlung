<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ConstantVisibilitySniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_CONST];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $constVisibilityPtr = $file->findPrevious([T_WHITESPACE], ($stackPtr - 1), null, true);

        $constNamePtr = $file->findNext([T_WHITESPACE], ($stackPtr + 1), null, true);

        if (!in_array($tokens[$constVisibilityPtr]['code'], [T_PUBLIC, T_PRIVATE, T_PROTECTED])) {
            $error = sprintf('Expected constant visibility for "%1$s"', $tokens[$constNamePtr]['content']);
            $file->addError($error, $tokens[$constVisibilityPtr], 'missingConstantVisiblity');
        }
    }
}
