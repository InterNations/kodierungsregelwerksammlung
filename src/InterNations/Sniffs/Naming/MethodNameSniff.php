<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class MethodNameSniff implements Sniff
{
    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(File $file, $stackPtr)
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
