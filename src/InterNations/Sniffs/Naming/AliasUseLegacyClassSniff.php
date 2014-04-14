<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class AliasUseLegacyClassSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_USE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        /** function () use ($var) {} */
        if ($file->findPrevious(T_FUNCTION, $stackPtr)) {
            return;
        }

        /** use Trait; */
        if ($file->findPrevious(T_CLASS, $stackPtr)) {
            return;
        }

        list($stackPtr, $namespace) = $this->getNamespace($stackPtr + 1, $file);

        if ($namespace == 'stdClass') {
            return;
        }

        // lowerCamelCase or Under_Score?
        if ($namespace[0] === strtolower($namespace[0]) || strpos($namespace, '_') !== false) {
            $file->addError(
                'Create an UpperCamelCased alias for symbol "%s"',
                $stackPtr,
                'AliasUseLegacyClass',
                [$namespace]
            );
        }
    }

    private function getNamespace($stackPtr, CodeSnifferFile $phpcsFile)
    {
        $tokens = $phpcsFile->getTokens();
        $namespace = '';
        $firstWhitespace = true;
        $asToken = false;
        while ($stackPtr = $phpcsFile->findNext(
            [T_STRING, T_NS_SEPARATOR, T_WHITESPACE, T_AS],
            $stackPtr + 1,
            null,
            null,
            null,
            true
        )
        ) {
            if ($firstWhitespace && $tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $firstWhitespace = false;
                $namespace = $tokens[$stackPtr]['content'];
            } elseif ($tokens[$stackPtr]['code'] === T_AS) {
                $asToken = true;
                $namespace = '';
            } elseif ($tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $namespace = $tokens[$stackPtr]['content'];
            } elseif ($asToken && $tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $namespace = $tokens[$stackPtr]['content'];
            }

            if ($tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $latestStackPtr = $stackPtr - 1;
            }
        }

        return [$latestStackPtr, ltrim($namespace, '\\')];
    }
}
