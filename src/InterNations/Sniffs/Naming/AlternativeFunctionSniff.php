<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as Sniff;

class AlternativeFunctionSniff implements Sniff
{
    private $alternatives = [
        'join'       => 'implode()',
        'sizeof'     => 'count()',
        'fputs'      => 'fwrite()',
        'chop'       => 'rtrim()',
        'is_real'    => 'is_float()',
        'strchr'     => 'strstr()',
        'doubleval'  => 'floatval()',
        'key_exists' => 'array_key_exists()',
        'is_double'  => 'is_float()',
        'ini_alter'  => 'ini_set()',
        'is_long'    => 'is_int()',
        'is_integer' => 'is_int()',
        'is_real'    => 'is_float()',
        'pos'        => 'current()',
        'md5'        => 'hash(\'sha256\', ...)',
        'md5_file'   => 'hash_file(\'sha256\', ...)',
        'sha1'       => 'hash(\'sha256\', ...)',
        'sha1_file'  => 'hash_file(\'sha256\', ...)',
        'var_dump'   => false,
        'print_r'    => false,
        'printf'     => false,
        'vprintf'    => false,
    ];

    public function register()
    {
        return [T_STRING, T_ECHO, T_PRINT];
    }

    /**
     * @param CodeSnifferFile $file
     * @param integer $stackPtr
     */
    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        // Special case for T_ECHO
        if (in_array($tokens[$stackPtr]['code'], [T_ECHO, T_PRINT])) {
            $this->createError($file, $stackPtr, $tokens[$stackPtr]['content'], 'Statement');
            return;
        }

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

        $functionName = $tokens[$stackPtr]['content'];

        if (isset($this->alternatives[$functionName])) {
            $this->createError($file, $stackPtr, $functionName . '()', 'Function', $this->alternatives[$functionName]);
        }
    }

    private function createError(CodeSnifferFile $file, $stackPtr, $functionName, $symbol, $alternative = false)
    {
        $message = sprintf('%s "%s" is not allowed. ', $symbol, $functionName);
        $message .= $alternative ? sprintf('Use "%s" instead', $alternative) : 'Please remove it';

        $file->addError($message, $stackPtr, 'UseAlternative');
    }
}
