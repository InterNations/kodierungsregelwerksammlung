<?php
namespace InterNations\Sniffs\Formatting;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ExpressionFormattingSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_STRING, T_STATIC, T_SELF];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if ($tokens[$stackPtr]['code'] === T_OPEN_SHORT_ARRAY) {
            $leftNonWhitespaceTokenPtr = $file->findPrevious([T_WHITESPACE], $stackPtr - 1, null, true);
            $arrayOpenerToken = [T_EQUAL, T_RETURN, T_DOUBLE_ARROW];

            if (!in_array($tokens[$leftNonWhitespaceTokenPtr]['code'], $arrayOpenerToken, true)) {
                return;
            }
        } else {
            if ($tokens[$stackPtr + 1]['code'] !== T_OPEN_PARENTHESIS) {
                return;
            }
        }

        $leftTokenPtr = $file->findPrevious([T_STRING, T_NS_SEPARATOR], $stackPtr - 1, null, true);
        $invocationHints = [T_OBJECT_OPERATOR, T_PAAMAYIM_NEKUDOTAYIM, T_WHITESPACE, T_OPEN_TAG];

        if (!in_array($tokens[$leftTokenPtr]['code'], $invocationHints, true)) {
            return;
        }

        if (isset($tokens[$stackPtr - 2])
            && in_array($tokens[$stackPtr - 2]['code'], [T_CLASS, T_INTERFACE, T_TRAIT], true)) {
            return;
        }

        if ($tokens[$stackPtr]['code'] === T_OPEN_SHORT_ARRAY) {
            $openingToken = $tokens[$stackPtr];
            $closingPtr = $openingToken['bracket_closer'];
            $closingToken = $tokens[$closingPtr];
            $stackPtr -= 2;
        } else {
            $openingToken = $tokens[$stackPtr + 1];
            $closingPtr = $openingToken['parenthesis_closer'];

            $nextNonWhitespacePos = $file->findNext(T_WHITESPACE, $closingPtr + 1, null, true);
            $isCase = $file->findPrevious([T_CASE], $stackPtr - 1, null, false, null, true) !== false;

            if ($tokens[$nextNonWhitespacePos]['code'] === T_COLON && !$isCase) {
                $closingPtr = $file->findNext(T_RETURN_TYPE, $nextNonWhitespacePos + 1);
            }

            $closingToken = $tokens[$closingPtr];
        }

        if ($openingToken['line'] === $closingToken['line']) {
            return;
        }

        $nestingLevel = 0;
        $argumentsLine = '';
        $whitespaceCount = 0;
        $needsWhitespace = true;

        for ($expressionPtr = $stackPtr + 2; $expressionPtr < $closingPtr; $expressionPtr++) {

            $currentToken = $tokens[$expressionPtr];

            switch ($currentToken['code']) {
                case T_WHITESPACE:
                    if (static::nextSignificantTokenClosesArray($file, $expressionPtr)) {
                        $needsWhitespace = false;
                        break;
                    }
                    static::append($argumentsLine, ' ', $whitespaceCount, $needsWhitespace);
                    break;

                case T_OPEN_SHORT_ARRAY:
                case T_ARRAY:
                case T_OPEN_PARENTHESIS:
                    static::append($argumentsLine, $currentToken['content'], $whitespaceCount, $needsWhitespace);
                    $nestingLevel++;
                    $needsWhitespace = false;
                    break;

                case T_CLOSE_SHORT_ARRAY:
                case T_CLOSE_PARENTHESIS:
                    static::append($argumentsLine, $currentToken['content'], $whitespaceCount, $needsWhitespace);
                    $nestingLevel--;
                    $needsWhitespace = static::nextSignificantTokenConcatsString($file, $expressionPtr + 1);
                    break;

                case T_CONSTANT_ENCAPSED_STRING:
                case T_VARIABLE:
                    if (strpos($currentToken['content'], "\n") !== false) {
                        // Ignore strings spanning multiple lines
                        return;
                    }
                    static::append($argumentsLine, $currentToken['content'], $whitespaceCount, $needsWhitespace);
                    break;

                case T_HEREDOC:
                case T_NOWDOC:
                case T_CLOSURE:
                case T_COMMENT:
                case T_DOC_COMMENT:
                case T_DOC_COMMENT_OPEN_TAG:
                case T_DOC_COMMENT_CLOSE_TAG:
                case T_DOC_COMMENT_STAR:
                case T_DOC_COMMENT_TAG:
                case T_DOC_COMMENT_STRING:
                    // Don’t continue checking
                    return;
                    break;

                case T_COMMA:
                    if (static::nextSignificantTokenClosesArray($file, $expressionPtr)) {
                        $needsWhitespace = false;
                        break;
                    }
                    static::append($argumentsLine, $currentToken['content'], $whitespaceCount, $needsWhitespace);
                    break;

                default:
                    static::append($argumentsLine, $currentToken['content'], $whitespaceCount, $needsWhitespace);
                    break;
            }
        }

        $startPtr = $stackPtr;

        do {
            --$startPtr;

        } while ($tokens[$stackPtr]['line'] === $tokens[$startPtr]['line']);

        $endPtr = $expressionPtr + 1;

        while ($tokens[$stackPtr]['line'] === $tokens[$endPtr]['line']) {
            ++$endPtr;
        }

        $nextPtr = $endPtr;

        while ($tokens[$nextPtr]['code'] === T_WHITESPACE) {
            ++$nextPtr;
        }

        if ($tokens[$nextPtr]['code'] === T_COLON) {
            $endPtr = $nextPtr;

            while ($tokens[$endPtr]['code'] !== T_RETURN_TYPE) {
                ++$endPtr;
            }
        }

        $before = static::composeExpression($tokens, $startPtr + 1, $stackPtr + 1);
        $after = static::composeExpression($tokens, $expressionPtr, $endPtr);


        $expression = $before . trim($argumentsLine) . $after;

        if (mb_strlen($expression, 'UTF-8') <= 120) {
            $file->addError(
                sprintf('Expression "%s" should be in one line', trim($expression)),
                $stackPtr,
                'OneLineExpression'
            );
        }
    }

    private static function composeExpression(array $tokens, $startPtr, $endPtr)
    {
        $expression = '';

        for ($ptr = $startPtr; $ptr <= $endPtr; $ptr++) {
            $expression .= $tokens[$ptr]['content'];
        }

        return $expression;
    }

    private static function append(&$expression, $content, &$whitespaceCount, &$needsWhitespace)
    {
        if ($content === ' ') {

            if ($whitespaceCount === 0 && $needsWhitespace) {
                $expression .= $content;
                $whitespaceCount++;

                return;
            }

            return;
        }

        $expression .= $content;
        $whitespaceCount = 0;
        $needsWhitespace = true;
    }

    private static function nextSignificantTokenClosesArray(CodeSnifferFile $file, $ptr)
    {
        $tokens = $file->getTokens();
        $nextSignificantToken = $file->findNext(T_WHITESPACE, $ptr + 1, null, true);

        if (!$nextSignificantToken) {
            return false;
        }

        return in_array($tokens[$nextSignificantToken]['code'], [T_CLOSE_SHORT_ARRAY, T_CLOSE_PARENTHESIS], true);
    }

    private static function nextSignificantTokenConcatsString(CodeSnifferFile $file, $ptr)
    {
        $tokens = $file->getTokens();
        $nextSignificantToken = $file->findNext(T_WHITESPACE, $ptr + 1, null, true);

        if (!$nextSignificantToken) {
            return false;
        }

        return in_array($tokens[$nextSignificantToken]['code'], [T_STRING_CONCAT], true);
    }
}
