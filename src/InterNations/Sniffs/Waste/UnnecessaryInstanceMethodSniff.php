<?php
namespace InterNations\Sniffs\Waste;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class UnnecessaryInstanceMethodSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        if (!$file->findPrevious([T_CLASS, T_TRAIT], $stackPtr)) {
            return;
        }

        if ($file->findPrevious([T_STATIC, T_PUBLIC], $stackPtr, null, false, null, true)) {
            return;
        }

        $tokens = $file->getTokens();
        if (!isset($tokens[$stackPtr]['scope_closer'])) {
            return;
        }

        $closingBracePtr = $tokens[$stackPtr]['scope_closer'];

        /*for ($a = $stackPtr; $a < $closingBracePtr; $a++) {
            print $tokens[$a]['content'];
        }*/

        $isInstance = false;
        $symbolPtr = $stackPtr;
        while ($symbolPtr = $file->findNext([T_CLOSURE, T_VARIABLE], $symbolPtr + 1, $closingBracePtr)) {
            switch ($tokens[$symbolPtr]['code']) {
                case T_VARIABLE:
                    if ($tokens[$symbolPtr]['content'] === '$this') {
                        $isInstance = true;
                        break 2;
                    }
                    break;

                case T_CLOSURE:
                    $qualifierPtr = $file->findPrevious(T_WHITESPACE, $symbolPtr - 1, null, true, null, true);
                    if ($tokens[$qualifierPtr]['code'] !== T_STATIC) {
                        $isInstance = true;
                        break 2;
                    }
                    break;
            }
        }

        if (!$isInstance) {
            $method = $file->getDeclarationName($stackPtr);
            $class = $file->getDeclarationName($file->findPrevious([T_CLASS, T_TRAIT], $stackPtr));

            $file->addError(
                sprintf(
                    'Method %s::%s() should be static as it does not access $this or indirectly use $this with an instance closure',
                    $class,
                    $method
                ),
                $stackPtr,
                'Waste.UnnecessaryInstanceMethod'
            );
        }
    }
}
