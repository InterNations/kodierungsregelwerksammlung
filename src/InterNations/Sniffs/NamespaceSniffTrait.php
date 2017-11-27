<?php
namespace InterNations\Sniffs;

use PHP_CodeSniffer\Files\File;

trait NamespaceSniffTrait
{
    /**
     * @return mixed[]
     */
    private static function getNamespace(int $stackPtr, File $file): array
    {
        $namespace = '';
        $symbolName = null;
        $isAlias = false;

        $tokens = $file->getTokens();

        $nextPtr = $stackPtr + 1;

        while ($currentPtr = $file->findNext(
            [T_STRING, T_NS_SEPARATOR, T_WHITESPACE, T_AS, T_PAAMAYIM_NEKUDOTAYIM],
            $nextPtr,
            null,
            null,
            null,
            true
        )
        ) {
            $nextPtr = $currentPtr + 1;

            if ($tokens[$currentPtr]['code'] === T_STRING
                && in_array($tokens[$currentPtr]['content'], ['const', 'function'], true)
            ) {
                continue;
            }

            switch ($tokens[$currentPtr]['code']) {
            case T_STRING:
            case T_NS_SEPARATOR:
            case T_PAAMAYIM_NEKUDOTAYIM:
                if (!$isAlias) {
                    $namespace .= $tokens[$currentPtr]['content'];
                }
                $symbolName = $tokens[$currentPtr]['content'];
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
