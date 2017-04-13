<?php
namespace InterNations\Sniffs\BestPractice;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class TestStubSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_OBJECT_OPERATOR];
    }

    public function process(CodeSnifferFile $file, $startPtr)
    {
        $tokens = $file->getTokens();
        $nextPtr = $startPtr;


        [$nextPtr, $expectsMethodToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if ($expectsMethodToken['code'] !== T_STRING || $expectsMethodToken['content'] !== 'expects') {
            return;
        }


        [$nextPtr, $expectsMethodOpenToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if ($expectsMethodOpenToken['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }


        [$nextPtr, $scopeToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if (!in_array($scopeToken['code'], [T_STATIC, T_SELF, T_VARIABLE], true)
            || !in_array($scopeToken['content'], ['static', 'self', '$this'], true)) {
            return;
        }


        [$nextPtr, $objectOperatorToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if (!in_array($objectOperatorToken['code'], [T_OBJECT_OPERATOR, T_PAAMAYIM_NEKUDOTAYIM], true)) {
            return;
        }


        [$nextPtr, $matcherMethodToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if ($matcherMethodToken['code'] !== T_STRING || $matcherMethodToken['content'] !== 'any') {
            return;
        }


        [$nextPtr, $matcherMethodOpenToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if ($matcherMethodOpenToken['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }


        [$nextPtr, $matcherMethodCloseToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if ($matcherMethodCloseToken['code'] !== T_CLOSE_PARENTHESIS) {
            return;
        }


        [$nextPtr, $expectsMethodCloseToken] = $this->findNextNonWhitespaceToken($file, $nextPtr, $tokens);

        if ($expectsMethodCloseToken['code'] !== T_CLOSE_PARENTHESIS) {
            return;
        }

        $file->addErrorOnLine(
            '"%s" is implied and does not need to be specified. Simply remove it.',
            $startPtr,
            'ImpliedAny',
            $file->getTokensAsString($startPtr, $nextPtr - $startPtr + 1)
        );
    }

    private function findNextNonWhitespaceToken(CodeSnifferFile $file, $stackPtr, array $tokens)
    {
        $nextPtr = $stackPtr + 1;

        $position = $file->findNext([T_WHITESPACE], $nextPtr, null, true);

        return [$nextPtr, $tokens[$position]];
    }
}
