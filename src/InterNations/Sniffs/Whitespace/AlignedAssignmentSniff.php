<?php
namespace InterNations\Sniffs\Whitespace;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class AlignedAssignmentSniff implements Sniff
{
    public function register()
    {
        return [T_EQUAL];
    }

    public function process(File $file, $stackPtr)
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
