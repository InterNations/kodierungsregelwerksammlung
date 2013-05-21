<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

// @codingStandardsIgnoreStart
class InterNations_Sniffs_Naming_MethodNameSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
{
    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $phpcsFile, $stackPtr)
    {
        $namePtr = $phpcsFile->findNext(T_STRING, $stackPtr, $stackPtr + 3);

        $tokens = $phpcsFile->getTokens();
        $methodName = $tokens[$namePtr]['content'];

        if (preg_match('/^getIs(?P<remainder>[A-Z].*)$/', $methodName, $matches)) {
            $phpcsFile->addError(
                sprintf(
                    'Method name "%s()" is not allowed. Use "is%s()" instead',
                    $methodName,
                    $matches['remainder']
                ),
                $stackPtr,
                'BadIsser'
            );
        } elseif (preg_match('/^setIs(?P<remainder>[A-Z].*)$/', $methodName, $matches)) {
            $phpcsFile->addError(
                sprintf(
                    'Method name "%s()" is not allowed. Use "set%s()" instead',
                    $methodName,
                    $matches['remainder']
                ),
                $stackPtr,
                'BadSetter'
            );
        }
    }
}
