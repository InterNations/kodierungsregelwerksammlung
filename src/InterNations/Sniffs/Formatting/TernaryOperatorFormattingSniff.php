<?php
namespace InterNations\Sniffs\Formatting;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class TernaryOperatorFormattingSniff implements CodeSnifferSniff
{
    const INDENT = 4;

    public function register()
    {
        return [T_INLINE_THEN];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        // Find the start of the ternary expression
        // Parentheses surrounding the entire ternary
        // Ex: ($condition ? $true : $false)
        // Ex: func($condition ? $true : $false)
        if (!empty($tokens[$stackPtr]['nested_parenthesis'])) {
            $startPtr = key($tokens[$stackPtr]['nested_parenthesis']);
            $endPtr = current($tokens[$stackPtr]['nested_parenthesis']);

            // Ternary is assigned, returned, or output
            // Ex: $foo = $condition ? $true : $false;
            //     return $condition ? $true : $false;
            //     echo $condition ? $true : $false;
        } else {
            $allowed = [
                T_EQUAL,
                T_RETURN,
                T_ECHO,
                T_AND_EQUAL,
                T_CONCAT_EQUAL,
                T_DIV_EQUAL,
                T_MINUS_EQUAL,
                T_MOD_EQUAL,
                T_MUL_EQUAL,
                T_OR_EQUAL,
                T_PLUS_EQUAL,
                T_SL_EQUAL,
                T_SR_EQUAL,
                T_XOR_EQUAL
            ];
            $startPtr = $file->findPrevious($allowed, $stackPtr);
            $endPtr = $file->findNext(T_SEMICOLON, $stackPtr);
        }

        // If the ternary is not assigned or returned, error and bail out
        if ($startPtr === false) {
            $error = 'Ternary operations must only occur within properly formatted assignment or return statements';
            $file->addError($error, $stackPtr);

            return;
        }

        // Find the colon separating the true and false values
        $colonPtr = $file->findNext([T_INLINE_ELSE, T_COLON], $stackPtr + 1);
        // Ensure the colon is on the same or next line, error and bail out if not
        if ($colonPtr === false) {
            $file->addError('Colon must appear on the same or following line of ternary operator', $stackPtr);

            return;
        }

        $nextColonPtr = $file->findNext([T_INLINE_ELSE, T_COLON], $colonPtr + 1, null, false, null, true);

        if ($nextColonPtr !== false) {

            // Was reported already
            if ($file->findPrevious(T_INLINE_THEN, $stackPtr - 1, null, false, null, true)) {
                return;
            }

            $file->addError('Nested ternary not allowed', $stackPtr, 'NestedTernary');

            return;
        }

        // Find the token bounds of each part of the ternary expression
        $elements = [
            'condition' => [$startPtr + 1, $stackPtr - 1],
            'then'      => [$stackPtr + 1, $colonPtr - 1],
            'else'      => [$colonPtr + 1, $endPtr - 1]
        ];

        if ($tokens[$startPtr + 1]['line'] === $tokens[$endPtr - 1]['line']) {
            $this->checkSingleLineTernary(
                $file,
                $file->findNext(T_WHITESPACE, $startPtr + 1, null, true),
                $file->findPrevious(T_WHITESPACE, $endPtr - 1, null, true),
                $elements
            );
        } else {
            $this->checkMultiLineTernary(
                $file,
                $file->findNext(T_WHITESPACE, $startPtr + 1, null, true),
                $file->findPrevious(T_WHITESPACE, $endPtr - 1, null, true),
                $elements
            );
        }
    }

    private function checkMultiLineTernary(CodeSnifferFile $file, $startPtr, $endPtr, array $elements)
    {
        $actual = $file->getTokensAsString($startPtr, $endPtr - $startPtr + 1);

        $parts = [];

        foreach ($elements as $part => list($leftPtr, $rightPtr)) {
            // Trim any surrounding whitespace
            $leftPtr = $file->findNext(T_WHITESPACE, $leftPtr, null, true);
            $rightPtr = $file->findPrevious(T_WHITESPACE, $rightPtr, null, true);

            $parts[] = $file->getTokensAsString($leftPtr, $rightPtr - $leftPtr + 1);
        }

        $indentLevel = $this->findIndentLevel($file, $startPtr);

        $args = array_merge($parts, [PHP_EOL, str_repeat(' ', ($indentLevel + 1) * static::INDENT)]);
        if ($parts[1] === '') {
            $expected = vsprintf('%1$s ?:%4$s%5$s%3$s', $args);
        } else {
            $expected = vsprintf('%1$s ?%4$s%5$s%2$s :%4$s%5$s%3$s', $args);
        }

        if ($actual !== $expected) {
            $file->addError(
                'Ternary operator incorrectly formatted: expected %s got %s',
                $startPtr,
                'MultiLineTernaryOperator',
                [$expected, $actual]
            );
        }
    }

    private function findIndentLevel(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $wsPtr = $stackPtr;
        $line = $tokens[$stackPtr]['line'];

        do {
            $wsPtr = $file->findPrevious(T_WHITESPACE, $wsPtr - 1);

            if ($line !== $tokens[$wsPtr]['line']) {
                return 0;
            }

        } while ($wsPtr && strlen($tokens[$wsPtr]['content']) < static::INDENT);

        return $wsPtr ? (strlen($tokens[$wsPtr]['content']) / static::INDENT) : 0;
    }

    private function checkSingleLineTernary(CodeSnifferFile $file, $startPtr, $endPtr, array $elements)
    {
        $actual = $file->getTokensAsString($startPtr, $endPtr - $startPtr + 1);

        $parts = [];

        foreach ($elements as $part => list($leftPtr, $rightPtr)) {
            $part = trim($file->getTokensAsString($leftPtr, $rightPtr - $leftPtr + 1), ' ');

            if (strlen($part) > 0) {
                $parts[] = ' ' . $part . ' ';
            } else {
                $parts[] = '';
            }
        }

        $expected = trim(vsprintf('%s?%s:%s', $parts), ' ');

        if ($actual !== $expected) {
            $file->addError(
                'Ternary operator incorrectly formatted: expected %s got %s',
                $startPtr,
                'SingleLineTernaryOperator',
                [$expected, $actual]
            );
        }
    }
}
