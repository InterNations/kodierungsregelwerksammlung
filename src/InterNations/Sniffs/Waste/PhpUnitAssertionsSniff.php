<?php
namespace InterNations\Sniffs\Waste;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class PhpUnitAssertionsSniff implements CodeSnifferSniff
{
    private static $aliasMap = [
        'assertSame'    => [
            T_TRUE                     => 'assertTrue',
            T_FALSE                    => 'assertFalse',
            T_NULL                     => 'assertNull',
            T_LNUMBER                  => ['count' => 'assertCount'],
            T_VARIABLE                 => [
                'count'     => 'assertCount',
                'get_class' => 'assertInstanceOf',
                'gettype'   => 'assertInternalType',
            ],
            T_STRING                   => [T_PAAMAYIM_NEKUDOTAYIM => ['class' => ['get_class' => 'assertInstanceOf']]],
            T_CONSTANT_ENCAPSED_STRING => ['gettype' => 'assertInternalType'],
        ],
        'assertNotSame' => [
            T_LNUMBER                  => ['count' => 'assertNotCount'],
            T_VARIABLE                 => [
                'count'     => 'assertNotCount',
                'get_class' => 'assertNotInstanceOf',
                'gettype'   => 'assertNotInternalType',
            ],
            T_STRING                   => [
                T_PAAMAYIM_NEKUDOTAYIM => ['class' => ['get_class' => 'assertNotInstanceOf']],
            ],
            T_CONSTANT_ENCAPSED_STRING => ['gettype' => 'assertNotInternalType'],
        ],
        'assertTrue'    => [
            T_EMPTY            => 'assertEmpty',
            T_ISSET            => 'assertArrayHasKey',
            'array_key_exists' => 'assertArrayHasKey',
            'in_array'         => 'assertContains',
        ],
        'assertFalse'   => [
            T_EMPTY            => 'assertNotEmpty',
            'array_key_exists' => 'assertArrayNotHasKey',
            'in_array'         => 'assertNotContains',
            T_ISSET            => 'assertArrayNotHasKey',
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

        if (strpos($tokens[$stackPtr + 1]['content'], 'assert') !== 0) {
            return;
        }

        list($alias, $code) = static::getAlias($file, $stackPtr);

        if ($alias !== null) {
            $file->addError(
                'There is a better alternative for the assertion. Use "%s()" instead of "%s"',
                $stackPtr + 1,
                'BetterAssertion',
                [$alias, $code]
            );
        }
    }

    private static function getAlias(CodeSnifferFile $file, $stackPtr)
    {
        $nextPtr = $stackPtr;
        $alias = static::$aliasMap;
        $tokens = $file->getTokens();
        $foundParenthesis = false;

        while (is_array($alias)) {
            $nextPtr = $file->findNext([T_WHITESPACE, T_COMMA, T_SEMICOLON], ++$nextPtr, null, true, null, true);

            if ($nextPtr === false) {
                break;
            }

            if ($tokens[$nextPtr]['code'] === T_OPEN_PARENTHESIS) {
                if ($foundParenthesis) {
                    $nextPtr = $tokens[$nextPtr]['parenthesis_closer'] + 1;
                }
                $foundParenthesis = true;
                continue;
            }

            if (isset($alias[$tokens[$nextPtr]['code']])) {
                $alias = $alias[$tokens[$nextPtr]['code']];
            } elseif (isset($alias[$tokens[$nextPtr]['content']])) {
                $alias = $alias[$tokens[$nextPtr]['content']];
            } else {
                return [null, null];
            }

        }

        return [
            $alias,
            sprintf(
                '%s%s',
                $file->getTokensAsString($stackPtr + 1, $nextPtr - $stackPtr),
                static::getClosing($file, $nextPtr + 1)
            ),
        ];
    }

    private static function getClosing(CodeSnifferFile $file, $ptr)
    {
        $tokens = $file->getTokens();

        switch ($tokens[$ptr]['code']) {
            case T_OPEN_PARENTHESIS:
                $closing = '(), …)';
                break;

            case T_COMMA:
                $closing = ', …)';
                break;

            default:
                $closing = '…)';
                break;
        }

        return $closing;
    }
}
