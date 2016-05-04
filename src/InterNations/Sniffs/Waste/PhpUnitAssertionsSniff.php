<?php
namespace InterNations\Sniffs\Waste;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class PhpUnitAssertionsSniff implements CodeSnifferSniff
{
    private static $aliasMap = [
        'assertSame'  => [
            T_TRUE  => 'assertTrue',
            T_FALSE => 'assertFalse',
            T_NULL  => 'assertNull',
        ],
        'assertTrue'  => [
            T_EMPTY  => 'assertEmpty',
            T_STRING => [
                'array_key_exists' => 'assertArrayHasKey',
                'in_array'         => 'assertContains',
            ],
            T_ISSET  => 'assertArrayHasKey',
        ],
        'assertFalse' => [
            T_EMPTY  => 'assertNotEmpty',
            T_STRING => [
                'array_key_exists' => 'assertArrayNotHasKey',
                'in_array'         => 'assertNotContains',
            ],
            T_ISSET  => 'assertArrayNotHasKey',
        ],
    ];

    public function register()
    {
        return [T_OBJECT_OPERATOR, T_PAAMAYIM_NEKUDOTAYIM];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if (!in_array($tokens[$stackPtr - 1]['content'], ['$this', 'static', 'self'], true)) {
            return;
        }

        $methodName = $tokens[$stackPtr + 1]['content'];

        $firstArgumentPtr = $file->findNext([T_WHITESPACE, T_OPEN_PARENTHESIS], $stackPtr + 2, null, true);
        $firstArgument = $tokens[$firstArgumentPtr];
        list($alias, $code) = static::getAlias($file, $methodName, $firstArgument, $firstArgumentPtr);

        if ($alias !== null) {
            $file->addError(
                'There is a better alternative for the assertion. Use "%s()" instead of "%s"',
                $stackPtr + 1,
                'BetterAssertion',
                [$alias, $code]
            );
        }
    }

    private static function getAlias(CodeSnifferFile $file, $methodName, array $firstArgument, $firstArgumentPtr)
    {
        if (!isset(static::$aliasMap[$methodName][$firstArgument['code']])) {
            return [null, null];
        }

        $alias = static::$aliasMap[$methodName][$firstArgument['code']];

        if (!is_array($alias)) {
            return [$alias, static::getCode($file, $methodName, $firstArgument, $firstArgumentPtr)];
        }

        if (!isset($alias[$firstArgument['content']])) {
            return [null, null];
        }

        return [
            $alias[$firstArgument['content']],
            static::getCode($file, $methodName, $firstArgument, $firstArgumentPtr),
        ];
    }

    private static function getCode(CodeSnifferFile $file, $methodName, array $firstArgument, $firstArgumentPtr)
    {
        $nextPtr = $file->findNext(T_WHITESPACE, $firstArgumentPtr + 1, null, true);
        $next = '';

        if ($file->getTokens()[$nextPtr]['code'] === T_OPEN_PARENTHESIS) {
            $next = '()';
        }

        return sprintf('%s(%s%s, …)', $methodName, $firstArgument['content'], $next);
    }
}
