<?php
namespace InterNations\Sniffs\Whitespace;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class AlignedAssignmentSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_EQUAL];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $whitespaceLeft = $tokens[$stackPtr - 1]['code'] === T_WHITESPACE;
        $whitespaceRight = $tokens[$stackPtr + 1]['code'] === T_WHITESPACE;
        $whitespaceLeftLength = strlen($tokens[$stackPtr - 1]['content']);
        $whitespaceRightLength = strlen($tokens[$stackPtr + 1]['content']);

        if (!$whitespaceLeft || !$whitespaceRight) {
            $file->addError('Assignment not separated by whitespace', $stackPtr, 'AssignmentWhitespace');
        }

        if ($whitespaceLeft && $whitespaceLeftLength !== 1) {
            $file->addError('Too much whitespace before equal sign', $stackPtr - 1, 'AssignmentWhitespace');
        }

        if ($whitespaceRight && $whitespaceRightLength !== 1) {
            $file->addError('Too much whitespace after equal sign', $stackPtr - 1, 'AssignmentWhitespace');
        }
    }
}
