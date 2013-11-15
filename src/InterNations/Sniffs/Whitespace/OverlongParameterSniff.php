<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

/**
 * @SuppressWarnings(PMD)
 */
// @codingStandardsIgnoreStart
class InterNations_Sniffs_Whitespace_OverlongParameterSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
{
    public function register()
    {
        return [T_STRING, T_VARIABLE];
    }

    /**
     * @todo Variable method calls/variable static calls
     * @todo Nested calls
     * @todo multiline strings
     *
     *
     * @param PHP_CodeSniffer_File $file
     * @param int $stackPtr
     */
    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        /**
         * Find out if the next relevant token is an opening parenthesis. Finding that would be an indicator for
         * a function/method call. Skip closing curly brackets as we might have a method call from a variable
         * surrounded by braces
         *   $this->{$foo}();
         */
        $openingBracePtr = $file->findNext(
            [T_WHITESPACE, T_CLOSE_CURLY_BRACKET],
            $stackPtr + 1,
            null,
            true,
            null,
            true
        );
        if ($tokens[$openingBracePtr]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        $closingBracePtr = $tokens[$openingBracePtr]['parenthesis_closer'];
        if ($tokens[$openingBracePtr]['line'] === $tokens[$closingBracePtr]['line']) {
            return;
        }

        /** Let’s find out if we have a method call, a static method call or a function declaration */
        $operatorPtr = $file->findPrevious([T_WHITESPACE, T_OPEN_CURLY_BRACKET], $stackPtr - 1, null, true, null, true);
        if ($operatorPtr && !in_array($tokens[$operatorPtr]['code'], [T_DOUBLE_COLON, T_OBJECT_OPERATOR, T_FUNCTION])) {
            $operatorPtr = false;
        }

        $contextPtr = false;
        if ($operatorPtr) {
            $symbolPtr = $file->findPrevious(T_WHITESPACE, $operatorPtr - 1, null, true, null, true);
            $symbols = [T_VARIABLE, T_STRING, T_STATIC, T_SELF, T_FUNCTION, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_STATIC];
            if (!in_array($tokens[$symbolPtr]['code'], $symbols)) {
                return;
            }

            if ($tokens[$operatorPtr]['code'] === T_OBJECT_OPERATOR) {
                $contextPtr = $file->findPrevious(T_VARIABLE, $operatorPtr, null, false, null, true);
            } elseif ($tokens[$operatorPtr]['code'] === T_DOUBLE_COLON) {
                $contextPtr = $file->findPrevious([T_STATIC, T_SELF, T_STRING], $operatorPtr, null, false, null, true);
            }
        }

        if ($tokens[$openingBracePtr]['line'] === $tokens[$closingBracePtr]['line']) {
            return;
        }

        $arguments = $this->getArguments($tokens, $openingBracePtr, $closingBracePtr);
        if ($operatorPtr && $tokens[$operatorPtr]['code'] === T_FUNCTION) {
            $this->lintFunctionDeclaration($file, $stackPtr, $tokens, $operatorPtr, $arguments, $openingBracePtr, $closingBracePtr);
            return;
        }

        $symbol = $tokens[$stackPtr]['content'];
        if ($operatorPtr) {
            $symbol = $tokens[$contextPtr]['content'] . $tokens[$operatorPtr]['content'] . $symbol;
        }

        $argumentCount = count($arguments);
        $adjustment = 0;

        $ignoredTokens = [T_CLOSE_SHORT_ARRAY, T_CLOSE_PARENTHESIS];
        $parenthesisTokens = [T_OPEN_PARENTHESIS, T_ARRAY];
        $bracketTokens = [T_OPEN_SHORT_ARRAY];

        for ($k = 0; $k < $argumentCount; ++$k) {
            foreach ($arguments[$k] as $token) {

                if (in_array($token['code'], $ignoredTokens)) {
                    continue;
                }

                /**
                 * Calculate an adjustment for nested function calls/arrays/anonymous functions spanning multiple lines
                 */
                if (in_array($token['code'], $parenthesisTokens) && isset($token['parenthesis_opener'])) {

                    $adjustment += $tokens[$token['parenthesis_closer']]['line'] - $tokens[$token['parenthesis_opener']]['line'];
                    break;
                } elseif (in_array($token['code'], $bracketTokens) && isset($token['bracket_opener'])) {
                    $adjustment += $tokens[$token['bracket_closer']]['line'] - $tokens[$token['bracket_opener']]['line'];
                    break;
                }

                $expectedLine = $tokens[$openingBracePtr]['line'] +  1 + $k + $adjustment;
                if (!in_array($token['code'], [T_WHITESPACE, T_COMMA]) &&  $expectedLine !== $token['line']) {


                    $identifier = $this->getVariable($arguments[$k], $token);
                    $file->addError(
                        'Argument "%s" of %s() should be in a distinct line. Expected line %d, got %d',
                        $identifier['ptr'],
                        'ArgumentDeclarationDistinctLine',
                        [$identifier['content'], $symbol, $expectedLine, $token['line']]
                    );
                    /*
                    error_log(print_r($token, 1));
                    error_log(
                        vsprintf(
                            'Argument "%s" of %s() should be in a distinct line. Expected %d, got %d' . PHP_EOL,
                            [$identifier['content'], $symbol, $expectedLine, $token['line']]
                        )
                    );*/

                    continue 2;
                }
            }
        }
    }
    private function getVariable(array $tokens, array $defaultToken = [])
    {
        foreach ($tokens as $token) {
            if ($token['code'] === T_VARIABLE) {
                return $token;
            }
        }

        return $defaultToken;
    }

    private function getArguments(array $tokens, $opener, $closer)
    {
        $arguments = [];
        $currentArgument = [];
        for ($i = ($opener + 1); $i < $closer; ++$i) {
            switch ($tokens[$i]['code']) {

                case T_COMMA:
                    $token = $tokens[$i];
                    $token['ptr'] = $i;
                    $currentArgument[] = $token;
                    $arguments[] = $currentArgument;
                    $currentArgument = [];
                    break;

                default:
                    select:
                    $token = $tokens[$i];
                    $token['ptr'] = $i;
                    $currentArgument[] = $token;

                    if (isset($token['parenthesis_opener'])) {
                        if ($token['parenthesis_closer'] !== $i) {
                            $i = $token['parenthesis_closer'];
                        }
                    }

                    if (isset($token['bracket_opener'])) {
                        if ($token['bracket_closer'] !== $i) {
                            $i = $token['bracket_closer'];
                        }
                    }
                    break;
            }
        }
        if ($currentArgument) {
            $count = count($currentArgument) - 1;
            for ($j = $count; $j >= 0; --$j) {
                if ($currentArgument[$j]['code'] === T_WHITESPACE) {
                    unset($currentArgument[$j]);
                }
            }
            $arguments[] = $currentArgument;
        }


        /*
        $l = [];
        foreach ($arguments as $argument) {
            $combined = [];
            foreach ($argument as $token) {
                $str = $token['type'];
                if ($token['code'] !== T_WHITESPACE) {
                    $str = $str . '(' . $token['content'] . ')';
                }
                $combined[] = $str;
            }
            $l[] = join(" ", $combined);
        }
        error_log(join("\n", $l));
        */


        return $arguments;
    }

    private function lintFunctionDeclaration(
        CodeSnifferFile $file,
        $stackPtr,
        array $tokens,
        $operatorPtr,
        array $arguments,
        $openingBracePtr,
        $closingBracePtr
    )
    {
        $openingToken = $tokens[$openingBracePtr];
        if ($this->argumentDeclarationFitsOneLine($file, $tokens, $openingBracePtr, $closingBracePtr)) {
            foreach ($arguments as $argument) {
                foreach ($argument as $token) {
                    if ($token['line'] !== $openingToken['line']) {
                        $file->addError(
                            'All arguments of %s() should be in the same line',
                            $stackPtr,
                            'ArgumentDeclarationSameLine',
                            [$file->getDeclarationName($operatorPtr)]
                        );
                        break 2;
                    }
                }
            }
        } else {
            $argumentCount = count($arguments);
            for ($k = 0; $k < $argumentCount; ++$k) {
                foreach ($arguments[$k] as $token) {
                    $expectedLine = $openingToken['line'] +  1 + $k;
                    if ($token['code'] !== T_WHITESPACE && $expectedLine !== $token['line']) {
                        $variableToken = $this->getVariable($arguments[$k]);
                        $file->addError(
                            'Argument "%s" of %s() should be in a distinct line. Expected line %d, got %d',
                            $variableToken['ptr'],
                            'ArgumentDeclarationDistinctLine',
                            [
                                $variableToken['content'],
                                $file->getDeclarationName($operatorPtr),
                                $expectedLine,
                                $token['line']
                            ]
                        );
                        continue 2;
                    }
                }
            }
        }
    }

    private function argumentDeclarationFitsOneLine(CodeSnifferFile $file, array $tokens, $openingBracePtr, $closingBracePtr)
    {
        $code = '';
        $previousWhitespace = false;
        for ($a = $openingBracePtr + 1; $a < $closingBracePtr; $a++) {

            if ($tokens[$a]['code'] === T_WHITESPACE && !$previousWhitespace) {
                $code .= ' ';
                $previousWhitespace = true;
            } elseif ($tokens[$a]['code'] !== T_WHITESPACE) {
                $code .= $tokens[$a]['content'];
                $previousWhitespace = false;
            }
        }

        return $tokens[$openingBracePtr]['column'] + strlen(trim($code)) + 1 < 120;
    }
}
