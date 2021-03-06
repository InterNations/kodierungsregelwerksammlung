<?php
namespace InterNations\Sniffs\Naming;

/**
 * Fork of Generic_Sniffs_NamingConventions_UpperCaseConstantNameSniff.
 *
 * Ensures that constant names follow the conventions
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 */
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ConstantNameSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_STRING];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param  File $file     The file being scanned.
     * @param  int  $stackPtr The position of the current token in the stack passed in $tokens.
     * @return null
     */
    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();
        $constName = $tokens[$stackPtr]['content'];

        // If this token is in a heredoc, ignore it.
        if ($file->hasCondition($stackPtr, T_START_HEREDOC) === true) {
            return;
        }

        $previousNonStringPtr = $file->findPrevious(
            [T_STRING, T_WHITESPACE, T_CONST, T_NS_SEPARATOR, T_FUNCTION],
            $stackPtr - 1,
            null,
            true
        );

        if ($tokens[$previousNonStringPtr]['code'] === T_USE) {
            return;
        }

        // Special case for PHPUnit.
        if ($constName === 'PHPUnit_MAIN_METHOD') {
            return;
        }

        if ($constName === 'class') {
            return;
        }

        // If the next non-whitespace token after this token
        // is not an opening parenthesis then it is not a function call.
        $openBracket = $file->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);

        if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
            $functionKeyword = $file->findPrevious(
                [T_WHITESPACE, T_COMMA, T_COMMENT, T_STRING, T_NS_SEPARATOR],
                ($stackPtr - 1),
                null,
                true
            );

            $declarations = [
                T_FUNCTION,
                T_CLASS,
                T_INTERFACE,
                T_TRAIT,
                T_IMPLEMENTS,
                T_EXTENDS,
                T_INSTANCEOF,
                T_NEW,
                T_NAMESPACE,
                T_USE,
                T_AS,
                T_GOTO,
                T_INSTEADOF,
            ];

            if (in_array($tokens[$functionKeyword]['code'], $declarations) === true) {
                // This is just a declaration; no constants here.
                return;
            }

            if ($tokens[$functionKeyword]['code'] === T_CONST) {
                $class = $file->findPrevious(T_CLASS, ($stackPtr - 1), null, false, null);
                $className = $file->findNext(T_STRING, ($class + 1), null, false, null, true);

                if (self::isEventClassName($tokens[$className]['content'])) {

                    if (!self::isValidEventConstName($constName)) {
                        $error = 'Class constants for event types must be camelcase and start with '
                            . '"on", "before" or "after". Found %s';
                        $file->addError($error, $stackPtr, 'EventClassConstantNotCamelCase', [$constName]);
                    }

                    return;
                }


                // This is a class constant.
                if (mb_strtoupper($constName, 'UTF-8') !== $constName) {
                    $error = 'Class constants must be uppercase; expected %s but found %s';
                    $data = [
                        mb_strtoupper($constName, 'UTF-8'),
                        $constName,
                    ];
                    $file->addError($error, $stackPtr, 'ClassConstantNotUpperCase', $data);
                }

                return;
            }

        } elseif (strtolower($constName) === 'define' || strtolower($constName) === 'constant') {

            /*
                This may be a "define" or "constant" function call.
            */

            // Make sure this is not a method call.
            $prev = $file->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);

            if ($tokens[$prev]['code'] === T_OBJECT_OPERATOR || $tokens[$prev]['code'] === T_DOUBLE_COLON) {
                return;
            }

            // The next non-whitespace token must be the constant name.
            $constPtr = $file->findNext(T_WHITESPACE, ($openBracket + 1), null, true);

            if ($tokens[$constPtr]['code'] !== T_CONSTANT_ENCAPSED_STRING) {
                return;
            }

            $constName = $tokens[$constPtr]['content'];

            // Check for constants like self::CONSTANT.
            $prefix = '';
            $splitPos = strpos($constName, '::');

            if ($splitPos !== false) {
                $prefix = substr($constName, 0, ($splitPos + 2));
                $constName = substr($constName, ($splitPos + 2));
            }

            if (strtoupper($constName) !== $constName) {
                $error = 'Constants must be uppercase; expected %s but found %s';
                $data = [
                    $prefix . strtoupper($constName),
                    $prefix . $constName,
                ];
                $file->addError($error, $stackPtr, 'ConstantNotUpperCase', $data);
            }
        }
    }

    private static function isEventClassName($className)
    {
        return preg_match('/^.*Events$/', $className);
    }

    private static function isAnException($exception)
    {
        return preg_match('/^.*Exception$/', $exception);
    }

    private static function isValidEventConstName($constantName)
    {
        return preg_match('/^(on(?!(After|Before))|after|before)/', $constantName)
            && !preg_match('/[A-Z]{2}/', $constantName);
    }
}
