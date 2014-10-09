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
    ];

    public function register()
    {
        return [T_STRING];
    }

    /**
     * @param CodeSnifferFile $file
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
                    'Function "%s()" is not allowed. Use "%s" instead',
                    $methodName,
                    $this->alternatives[$methodName]
                ),
                $stackPtr,
                'UseAlternative'
            );
        }
    }
}
