<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class TypeCastSniff implements Sniff
{
    public function register()
    {
        return [T_INT_CAST, T_BOOL_CAST, T_DOUBLE_CAST, T_STRING_CAST];
    }

    public function process(File $file, $stackPtr)
    {
        switch (strtolower($file->getTokens()[$stackPtr]['content'])) {
            case '(integer)':
                $file->addError('Expected (int), got (integer)', $stackPtr, 'LongCastOperator');
                break;

            case '(boolean)':
                $file->addError('Expected (bool), got (boolean)', $stackPtr, 'LongCastOperator');
                break;

            case '(double)':
                $file->addError('Expected (float), got (double)', $stackPtr, 'CorrectCastOperatorButStupidStandard');
                break;

            case '(real)':
                $file->addError('Expected (float), got (real)', $stackPtr, 'InvalidCastOperator');
                break;

            case '(binary)':
                $file->addError('This will never happen', $stackPtr, 'PhpSixWillNeverHappen');
                break;

            default:
                // No default case required
                break;
        }
    }
}
