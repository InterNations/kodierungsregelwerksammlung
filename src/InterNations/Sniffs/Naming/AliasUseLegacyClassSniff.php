<?php
namespace InterNations\Sniffs\Naming;

// @codingStandardsIgnoreFile
require_once __DIR__ . '/../NamespaceSniffTrait.php';

use InterNations\Sniffs\NamespaceSniffTrait;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class AliasUseLegacyClassSniff implements Sniff
{
    use NamespaceSniffTrait;

    public function register()
    {
        return [T_USE];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        /** function () use ($var) {} */
        $previousToken = $file->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
        if ($tokens[$previousToken]['code'] === T_CLOSE_PARENTHESIS) {
            return;
        }

        /** use Trait; */
        if ($file->findPrevious(T_CLASS, $stackPtr)) {
            return;
        }


        list($namespacePtr, $symbol, $namespace, $isAlias) = $this->getNamespace($stackPtr + 1, $file);


        $nextToken = $file->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
        switch ($tokens[$nextToken]['content']) {
            case 'function':
                if (strtolower($symbol) !== $symbol) {
                    $this->addError(
                        $file,
                        $namespacePtr,
                        'lower_case underscore separated',
                        $symbol,
                        $namespace,
                        $isAlias
                    );
                }
                break;

            case 'const':
                if (strtoupper($symbol) !== $symbol) {
                    $this->addError(
                        $file,
                        $namespacePtr,
                        'UPPER_CASE underscore separated',
                        $symbol,
                        $namespace,
                        $isAlias
                    );
                }
                break;

            default:
                if ($symbol === 'stdClass') {
                    return;
                }

                // lowerCamelCase or Under_Score?
                if ($symbol[0] === strtolower($symbol[0]) || strpos($symbol, '_') !== false) {
                    $this->addError($file, $namespacePtr, 'UpperCamelCased', $symbol, $namespace, $isAlias);
                }
                break;
        }
    }

    private static function addError(File $file, $namespacePtr, $alias, $symbol, $namespace, $isAlias)
    {
        if ($isAlias) {
            $file->addError(
                'Create %s %s alias for symbol "%s" or fix the target symbol name "%s"',
                $namespacePtr,
                'AliasUseLegacyClass',
                [(strpos('aeiouAEIOU', $alias[0]) !== false ? 'an' : 'a'), $alias, $symbol, $namespace]
            );
        } else {
            $file->addError(
                'Fix the alias "%s" to be %s or fix the target symbol name "%s"',
                $namespacePtr,
                'AliasUseLegacyClass',
                [$symbol, $alias, $namespace]
            );
        }
    }
}
