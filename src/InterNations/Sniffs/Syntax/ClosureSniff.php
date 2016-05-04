<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ClosureSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_CLOSURE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $parenthesisOpener = $tokens[$stackPtr]['parenthesis_opener'];
        $parenthesisCloser = $tokens[$stackPtr]['parenthesis_closer'];
        $scopeOpener = $tokens[$stackPtr]['scope_opener'];
        $scopeCloser = $tokens[$stackPtr]['scope_closer'];

        $qualifierPtr = $file->findPrevious([T_WHITESPACE], $stackPtr - 1, null, true);
        $isStaticClosure = $tokens[$qualifierPtr]['code'] === T_STATIC;

        $afterQualifierWs = '';

        if ($isStaticClosure) {
            $afterQualifierWsPtr = $file->findPrevious([T_WHITESPACE], $stackPtr - 1, $qualifierPtr);

            if ($afterQualifierWsPtr) {
                $afterQualifierWs = $tokens[$afterQualifierWsPtr]['content'];
            }
        }

        $afterClosureWs = '';
        $afterClosureWsPtr = $file->findNext([T_WHITESPACE], $stackPtr + 1, $parenthesisOpener);

        if ($afterClosureWsPtr) {
            $afterClosureWs = $tokens[$afterClosureWsPtr]['content'];
        }

        $beforeUseWs = ' ';
        $afterUseWs = ' ';
        $usePtr = $file->findNext([T_USE], $tokens[$stackPtr]['parenthesis_closer'], $scopeOpener);

        if ($usePtr !== false) {
            $beforeUseWs = '';
            $useBeforeWsPtr = $file->findPrevious([T_WHITESPACE], $usePtr, $parenthesisCloser);

            if ($useBeforeWsPtr) {
                $beforeUseWs = $tokens[$useBeforeWsPtr]['content'];
            }

            $afterUseWs = '';
            $useClosePtr = $file->findNext(T_CLOSE_PARENTHESIS, $usePtr);
            $useAfterWsPtr = $file->findNext(T_WHITESPACE, $useClosePtr, $scopeOpener);

        } else {
            $useAfterWsPtr = $file->findNext(T_WHITESPACE, $tokens[$stackPtr]['parenthesis_closer'], $scopeOpener);
        }

        if ($useAfterWsPtr) {
            $afterUseWs = $tokens[$useAfterWsPtr]['content'];
        }

        //$openingBracketPtr = $file->findNext(T_OPEN_CURLY_BRACKET, $tokens[$stackPtr]['parenthesis_closer']);
        //var_dump($tokens[$openingBracketPtr]);

        if (($isStaticClosure && strlen($afterQualifierWs) !== 1)
            || $afterClosureWs !==  ' '
            || $beforeUseWs !== ' '
            || $afterUseWs !== ' ') {

            $file->addError(
                sprintf(
                    'Expected "%1$sfunction (...) use (...) {", found "%2$sfunction%3$s(...)%4$suse(...)%5$s{"',
                    ($isStaticClosure ? 'static '  : ''),
                    ($isStaticClosure ? 'static ' : '') . $afterQualifierWs,
                    $afterClosureWs,
                    $beforeUseWs,
                    $afterUseWs
                ),
                $stackPtr,
                'useWhitespace'
            );
        }

        $thisPtr = $file->findNext(T_VARIABLE, $scopeOpener, $scopeCloser, false, '$this');

        if ($isStaticClosure && $thisPtr !== false) {
            $file->addError(
                'Static closure references %s but static qualifier exists. Remove "static" qualifier from the closure',
                $thisPtr,
                'closureInvalidStatic',
                [$tokens[$thisPtr]['content']]
            );
        }

        if (!$isStaticClosure && $thisPtr === false) {
            $file->addError(
                'Closure does not reference $this, static:: or self::. Add "static" qualifier to the closure',
                $stackPtr,
                'closureShouldBeStatic'
            );
        }
    }
}
