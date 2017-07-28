<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;
use InterNations\Sniffs\NamespaceSniffTrait;

class RestViewSniff implements CodeSnifferSniff
{
    use NamespaceSniffTrait;

    private $className;

    private $nameSpace;

    public function register()
    {
        return [T_CLASS, T_NAMESPACE, T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if ($tokens[$stackPtr]['type'] === 'T_NAMESPACE') {
            $namespaceData = self::getNamespace($stackPtr, $file);
            $this->nameSpace = $namespaceData[2];
        }

        if ($tokens[$stackPtr]['type'] === 'T_CLASS') {
            $this->className = $tokens[$stackPtr + 2]['content'];
        }

        if ($tokens[$stackPtr]['type'] === 'T_FUNCTION' && $this->isApi()
            && $this->isApiMethod($stackPtr, $tokens) && $this->className && $this->nameSpace
        ) {
            if (!$this->isAnnotationDefined($stackPtr, $tokens, $file)) {
                $error = 'Missing @Rest\View annotation for method ' . $tokens[$stackPtr + 2]['content'];
                $file->addError($error, $stackPtr, 'RestAnnotationError');
            }
        }
    }

    private function isApiMethod($stackPtr, &$tokens)
    {
        if($tokens[$stackPtr - 2]['type'] === 'T_PUBLIC' && preg_match('/Action$/', $tokens[$stackPtr + 2]['content'])) {
            return true;
        }

        return false;
    }

    private function isApi()
    {
        if(strpos($this->nameSpace, '\\Api\\') !== false && preg_match('/Controller$/', $this->className)) {
            return true;
        }

        return false;
    }

    private function isAnnotationDefined($start, &$tokens, CodeSnifferFile $file)
    {
        for ($currentIndex = $start; $currentIndex > 0, $tokens[$currentIndex]['content'] !== '/**'; $currentIndex--) {
            if ($tokens[$currentIndex]['content'] === '@Rest\View') {
                return true;
            }
        }

        return false;
    }
}