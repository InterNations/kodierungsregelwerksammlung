<?php
namespace InterNations\Sniffs\Files;

/** Based on Generic_Sniffs_Files_LineLengthSniff */
use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class LineLengthSniff implements CodeSnifferSniff
{
    public $lineLimit = 120;

    public $absoluteLineLimit = 120;

    public function register()
    {
        return [T_OPEN_TAG];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        // Make sure this is the first open tag.
        $previousOpenTag = $file->findPrevious(T_OPEN_TAG, $stackPtr - 1);

        if ($previousOpenTag !== false) {
            return;
        }

        $tokenCount = 0;
        $currentLineContent = '';
        $currentLine = 1;

        $trim = (strlen($file->eolChar) * -1);

        for (; $tokenCount < $file->numTokens; $tokenCount++) {
            if ($tokens[$tokenCount]['line'] === $currentLine) {
                $currentLineContent .= $tokens[$tokenCount]['content'];
            } else {
                $currentLineContent = substr($currentLineContent, 0, $trim);
                $this->checkLineLength($file, $tokenCount - 1, $currentLineContent);
                $currentLineContent = $tokens[$tokenCount]['content'];
                $currentLine++;
            }
        }

        $currentLineContent = substr($currentLineContent, 0, $trim);
        $this->checkLineLength($file, $tokenCount - 1, $currentLineContent);
    }

    protected function checkLineLength(CodeSnifferFile $phpcsFile, $stackPtr, $lineContent)
    {
        // If the content is a CVS or SVN id in a version tag, or it is
        // a license tag with a name and URL, there is nothing the
        // developer can do to shorten the line, so don't throw errors.
        if (preg_match('|@version[^\$]+\$Id|', $lineContent) !== 0) {
            return;
        }

        if (preg_match('|@license|', $lineContent) !== 0) {
            return;
        }

        // Allow overlong declarations
        if (preg_match('/^(((abstract|final) )?class|interface|trait|use)/', $lineContent) !== 0) {
            return;
        }

        // Allow overlong comments
        if (preg_match('@^\s*\*@', $lineContent) !== 0) {
            return;
        }

        // Allow overlong constants
        if (preg_match('/^\s*const/', $lineContent) !== 0) {
            return;
        }

        $lineLength = mb_strlen($lineContent, 'UTF-8');

        if ($this->absoluteLineLimit > 0 && $lineLength > $this->absoluteLineLimit) {
            $data = [$this->absoluteLineLimit, $lineLength,];

            $error = 'Line exceeds maximum limit of %s characters; contains %s characters';
            $phpcsFile->addError($error, $stackPtr, 'MaxExceeded', $data);
        } elseif ($lineLength > $this->lineLimit) {
            $data = [$this->lineLimit, $lineLength,];

            $warning = 'Line exceeds %s characters; contains %s characters';
            $phpcsFile->addWarning($warning, $stackPtr, 'TooLong', $data);
        }
    }
}
