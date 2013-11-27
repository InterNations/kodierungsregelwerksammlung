<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

// @codingStandardsIgnoreStart
class InterNations_Sniffs_Naming_AlternativeMethodSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
{
    protected $alternatives = [
        'join'       => 'implode',
        'sizeof'     => 'count',
        'fputs'      => 'fwrite',
        'chop'       => 'rtrim',
        'is_real'    => 'is_float',
        'strchr'     => 'strstr',
        'doubleval'  => 'floatval',
        'key_exists' => 'array_key_exists',
        'is_double'  => 'is_float',
        'ini_alter'  => 'ini_set',
    ];

    public function register()
    {
        return [T_STRING];
    }

    /**
     * @param PHP_CodeSniffer_File $file
     * @param integer $stackPtr
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

        $beforeWhitespacePtr = $file->findPrevious(
            [T_WHITESPACE],
            $stackPtr - 1,
            null,
            true,
            null,
            true
        );

        if ($tokens[$beforeWhitespacePtr]['code'] == T_SEMICOLON) {
            $beforeWhitespacePtr++;
        }

        $objectOperatorPtr = $file->findPrevious(
            [T_PAAMAYIM_NEKUDOTAYIM, T_OBJECT_OPERATOR, T_FUNCTION],
            $beforeWhitespacePtr,
            $beforeWhitespacePtr - 1,
            false,
            null,
            true
        );

        if ($objectOperatorPtr !== false) {
            return;
        }

        $methodName = $tokens[$stackPtr]['content'];

        if (isset($this->alternatives[$methodName])) {
            $file->addError(
                sprintf(
                    'Method name "%s()" is not allowed. Use "%s()" instead',
                    $methodName,
                    $this->alternatives[$methodName]
                ),
                $stackPtr,
                'UseAlternative'
            );
        }
    }
}
