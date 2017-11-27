<?php
namespace InterNations\Sniffs\ControlStructures;

// @codingStandardsIgnoreFile
require_once __DIR__ . '/../NamespaceSniffTrait.php';

use InterNations\Sniffs\NamespaceSniffTrait;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class CatchUndefinedExceptionSniff implements Sniff
{
    use NamespaceSniffTrait;

    private static $aliases = [];

    public function register()
    {
        return [T_USE, T_CATCH];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();
        $fileName = $file->getFilename();

        // Collect use statement aliases
        if ($tokens[$stackPtr]['code'] === T_USE) {

            /** function () use ($var) {} */
            $previousPtr = $file->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
            if ($tokens[$previousPtr]['code'] === T_CLOSE_PARENTHESIS) {
                return;
            }

            /** use Trait; */
            if ($file->findPrevious([T_CLASS, T_TRAIT], $stackPtr)) {
                return;
            }

            list($stackPtr, $namespaceAlias, $fullyQualifiedNamespace) = $this->getNamespace($stackPtr + 1, $file);

            if (!isset(static::$aliases[$fileName])) {
                static::$aliases[$fileName] = [];
            }

            static::$aliases[$fileName][] = $namespaceAlias;

            return;
        }

        // Check if aliased exist for caught exceptions
        $catchPtr = $tokens[$stackPtr]['parenthesis_opener'] + 1;
        $exceptionPtr = $file->findNext(
            [T_CLASS, T_INTERFACE],
            $catchPtr,
            $tokens[$stackPtr]['parenthesis_closer'],
            true
        );

        $exceptionName = $tokens[$exceptionPtr]['content'];

        if (!in_array($exceptionName, static::$aliases[$fileName], false)) {
            $file->addError(
                sprintf('Trying to catch an undefined exception. Please add use-statement for "%s"', $exceptionName),
                $exceptionPtr,
                'UndefinedException'
            );
        }
    }
}
