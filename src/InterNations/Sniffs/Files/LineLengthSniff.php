<?php
/**
 * Generic_Sniffs_Files_LineLengthSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Generic_Sniffs_Files_LineLengthSniff.
 *
 * Checks all lines in the file, and throws warnings if they are over 80
 * characters in length and errors if they are over 100. Both these
 * figures can be changed by extending this sniff in your own standard.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: 1.4.4
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
// @codingStandardsIgnoreStart
use PHP_CodeSniffer_File as CodeSnifferFile;

class InterNations_Sniffs_Files_LineLengthSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
{

    /**
     * The limit that the length of a line should not exceed.
     *
     * @var integer
     */
    public $lineLimit = 120;

    /**
     * The limit that the length of a line must not exceed.
     *
     * Set to zero (0) to disable.
     *
     * @var integer
     */
    public $absoluteLineLimit = 120;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_OPEN_TAG];

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param integer                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return null
     */
    public function process(CodeSnifferFile $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Make sure this is the first open tag.
        $previousOpenTag = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
        if ($previousOpenTag !== false) {
            return;
        }

        $tokenCount = 0;
        $currentLineContent = '';
        $currentLine = 1;

        $trim = (strlen($phpcsFile->eolChar) * -1);
        for (; $tokenCount < $phpcsFile->numTokens; $tokenCount++) {
            if ($tokens[$tokenCount]['line'] === $currentLine) {
                $currentLineContent .= $tokens[$tokenCount]['content'];
            } else {
                $currentLineContent = substr($currentLineContent, 0, $trim);
                $this->checkLineLength($phpcsFile, ($tokenCount - 1), $currentLineContent);
                $currentLineContent = $tokens[$tokenCount]['content'];
                $currentLine++;
            }
        }

        $currentLineContent = substr($currentLineContent, 0, $trim);
        $this->checkLineLength($phpcsFile, ($tokenCount - 1), $currentLineContent);

    }//end process()


    /**
     * Checks if a line is too long.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param integer                  $stackPtr    The token at the end of the line.
     * @param string               $lineContent The content of the line.
     *
     * @return null
     */
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
        if (preg_match('/^((abstract )?class|interface|trait|use)/', $lineContent) !== 0) {
            return;
        }

        // Allow overlong comments
        if (preg_match('@^\s*\*@', $lineContent) !== 0) {
            return;
        }

        // Allow overlong constants
        if (preg_match('/^\s+const/', $lineContent) !== 0) {
            return;
        }

        if (PHP_CODESNIFFER_ENCODING !== 'iso-8859-1') {
            // Not using the detault encoding, so take a bit more care.
            $lineLength = iconv_strlen($lineContent, PHP_CODESNIFFER_ENCODING);
            if ($lineLength === false) {
                // String contained invalid characters, so revert to default.
                $lineLength = strlen($lineContent);
            }
        } else {
            $lineLength = strlen($lineContent);
        }

        if ($this->absoluteLineLimit > 0
            && $lineLength > $this->absoluteLineLimit
        ) {
            $data = [
                     $this->absoluteLineLimit,
                     $lineLength,
                    ];

            $error = 'Line exceeds maximum limit of %s characters; contains %s characters';
            $phpcsFile->addError($error, $stackPtr, 'MaxExceeded', $data);
        } elseif ($lineLength > $this->lineLimit) {
            $data = [
                     $this->lineLimit,
                     $lineLength,
                    ];

            $warning = 'Line exceeds %s characters; contains %s characters';
            $phpcsFile->addWarning($warning, $stackPtr, 'TooLong', $data);
        }
    }//end checkLineLength()


}//end class
