<?php
namespace InterNations\Sniffs\Naming;

// @codingStandardsIgnoreFile
require_once __DIR__ . '/../NamespaceSniffTrait.php';

use InterNations\Sniffs\NamespaceSniffTrait;
use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class NamespaceNameSniff implements CodeSnifferSniff
{
    use NamespaceSniffTrait;

    public function register()
    {
        return [T_NAMESPACE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        list(, , $fqNs) = static::getNamespace($stackPtr, $file);

        $declarationPtr = $file->findNext([T_CLASS, T_INTERFACE, T_TRAIT], $stackPtr + 1);
        $symbolNamePtr = $file->findNext(T_STRING, $declarationPtr + 1);
        $symbol = $file->getTokens()[$declarationPtr]['content'];
        $namespace = $fqNs . '\\' . $file->getTokens()[$symbolNamePtr]['content'];

        $expectedPath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace, $namespaceLength) . '.php';

        $fileName = $file->getFilename();
        if (strrpos($fileName, $expectedPath) !== strlen($fileName) - strlen($expectedPath)) {
            $file->addError(
                sprintf(
                    'Namespace of %s "%s" does not match file "%s"',
                    $symbol,
                    $namespace,
                    implode(
                        DIRECTORY_SEPARATOR,
                        array_slice(explode(DIRECTORY_SEPARATOR, $fileName), - $namespaceLength - 1)
                    )
                ),
                $stackPtr,
                'InvalidNamespaceName'
            );
        }
    }
}
