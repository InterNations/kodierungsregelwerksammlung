<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class MethodNameSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $namePtr = $file->findNext(T_STRING, $stackPtr, $stackPtr + 3);

        $tokens = $file->getTokens();
        $methodName = $tokens[$namePtr]['content'];

        if (preg_match('/^(getIs|does)(?P<remainder>[A-Z].*)$/', $methodName, $matches)) {
            $file->addError(
                'Method name "%1$s()" is not allowed. Use "is%2$s()" or "has%2$s()" instead',
                $stackPtr,
                'BadIsser',
                [$methodName, $matches['remainder']]
            );
        } elseif (preg_match('/^setIs(?P<remainder>[A-Z].*)$/', $methodName, $matches)) {
            $file->addError(
                'Method name "%s()" is not allowed. Use "set%s()" instead',
                $stackPtr,
                'BadSetter',
                [$methodName, $matches['remainder']]
            );
        }
    }
}
