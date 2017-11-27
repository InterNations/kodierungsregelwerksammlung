<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ConstantVisibilitySniff implements Sniff
{
    public function register()
    {
        return [T_CONST];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $constVisibilityPtr = $file->findPrevious([T_WHITESPACE], ($stackPtr - 1), null, true);

        $constNamePtr = $file->findNext([T_WHITESPACE], ($stackPtr + 1), null, true);

        if (!in_array($tokens[$constVisibilityPtr]['code'], [T_PUBLIC, T_PRIVATE, T_PROTECTED])) {
            $error = sprintf('Expected constant visibility for "%1$s"', $tokens[$constNamePtr]['content']);
            $file->addError($error, $stackPtr, 'missingConstantVisibility');
        }
    }
}
