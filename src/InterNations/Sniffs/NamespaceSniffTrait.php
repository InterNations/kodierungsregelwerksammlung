<?php
namespace InterNations\Sniffs;

use PHP_CodeSniffer_File as CodeSnifferFile;

trait NamespaceSniffTrait
{
    private static function getNamespace($stackPtr, CodeSnifferFile $file)
    {
        $namespace = '';
        $symbolName = null;
        $isAlias = false;

        $tokens = $file->getTokens();

        while ($stackPtr = $file->findNext(
            [T_STRING, T_NS_SEPARATOR, T_WHITESPACE, T_AS, T_PAAMAYIM_NEKUDOTAYIM],
            $stackPtr + 1,
            null,
            null,
            null,
            true
        )
        ) {
            switch ($tokens[$stackPtr]['code']) {
                case T_STRING:
                case T_NS_SEPARATOR:
                case T_PAAMAYIM_NEKUDOTAYIM:
                    if (!$isAlias) {
                        $namespace .= $tokens[$stackPtr]['content'];
                    }
                    $symbolName = $tokens[$stackPtr]['content'];
                    break;

                case T_AS:
                    $isAlias = true;
                    break;

                default:
                    // Just continue
                    break;
            }
        }

        return [$stackPtr, $symbolName, $namespace, $isAlias];
    }
}
