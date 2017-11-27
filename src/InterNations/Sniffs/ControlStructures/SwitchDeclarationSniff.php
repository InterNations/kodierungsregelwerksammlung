<?php
namespace InterNations\Sniffs\ControlStructures;

/**
 * Squiz_Sniffs_ControlStructures_SwitchDeclarationSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: SwitchDeclarationSniff.php 304909 2010-10-26 05:30:04Z squiz $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Squiz_Sniffs_ControlStructures_SwitchDeclarationSniff.
 *
 * Ensures all the breaks and cases are aligned correctly according to their
 * parent switch's alignment and enforces other switch formatting.
 *
 * @category              PHP
 * @package               PHP_CodeSniffer
 * @author                Greg Sherwood <gsherwood@squiz.net>
 * @author                Marc McIntyre <mmcintyre@squiz.net>
 * @copyright             2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license               http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version               Release: 1.3.1
 * @link                  http://pear.php.net/package/PHP_CodeSniffer
 * @SuppressWarnings(PMD)
 */

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Files\File;

class SwitchDeclarationSniff implements Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = [
                                   'PHP',
                                   'JS',
                                  ];


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_SWITCH];
    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File    $file     The file being scanned.
     * @param integer $stackPtr The position of the current token in the
     *                          stack passed in $tokens.
     *
     * @return null
     */
    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        // We can't process SWITCH statements unless we know where they start and end.
        if (isset($tokens[$stackPtr]['scope_opener']) === false
            || isset($tokens[$stackPtr]['scope_closer']) === false
        ) {
            return;
        }

        $switch = $tokens[$stackPtr];
        $nextCase = $stackPtr;
        $caseAlignment = ($switch['column'] + 4);
        $caseCount = 0;
        $foundDefault = false;

        while (($nextCase = $file->findNext([T_CASE, T_DEFAULT, T_SWITCH], ($nextCase + 1), $switch['scope_closer'])
            ) !== false
        ) {
            // Skip nested SWITCH statements; they are handled on their own.
            if ($tokens[$nextCase]['code'] === T_SWITCH) {
                $nextCase = $tokens[$nextCase]['scope_closer'];
                continue;
            }

            if ($tokens[$nextCase]['code'] === T_DEFAULT) {
                $type = 'Default';
                $foundDefault = true;
            } else {
                $type = 'Case';
                $caseCount++;
            }

            if ($tokens[$nextCase]['content'] !== strtolower($tokens[$nextCase]['content'])) {
                $expected = strtolower($tokens[$nextCase]['content']);
                $error = strtoupper($type) . ' keyword must be lowercase; expected "%s" but found "%s"';
                $data = [
                             $expected,
                             $tokens[$nextCase]['content'],
                            ];
                $file->addError($error, $nextCase, $type . 'NotLower', $data);
            }

            if ($tokens[$nextCase]['column'] !== $caseAlignment) {
                $error = strtoupper($type) . ' keyword must be indented 4 spaces from SWITCH keyword';
                $file->addError($error, $nextCase, $type . 'Indent');
            }

            if ($type === 'Case'
                && ($tokens[($nextCase + 1)]['type'] !== 'T_WHITESPACE'
                || $tokens[($nextCase + 1)]['content'] !== ' ')
            ) {
                $error = 'CASE keyword must be followed by a single space';
                $file->addError($error, $nextCase, 'SpacingAfterCase');
            }

            $opener = $tokens[$nextCase]['scope_opener'];

            if ($tokens[($opener - 1)]['type'] === 'T_WHITESPACE') {
                $error = 'There must be no space before the colon in a ' . strtoupper($type) . ' statement';
                $file->addError($error, $nextCase, 'SpaceBeforeColon' . $type);
            }

            $nextBreak = $tokens[$nextCase]['scope_closer'];
            $breakAfterThrow = false;

            if ($tokens[$nextBreak]['code'] === T_THROW) {
                if ($pos = $file->findNext([T_OPEN_PARENTHESIS], $nextBreak)) {
                    // +4: ")" ";" "\n" "<whitespace>"
                    $breakAfterThrow = $tokens[$pos]['parenthesis_closer'] + 4;
                }
            }

            if ($tokens[$nextBreak]['code'] === T_BREAK
                || ($breakAfterThrow && $tokens[$breakAfterThrow]['code'] === T_BREAK)
            ) {

                if ($tokens[$nextBreak]['scope_condition'] === $nextCase) {
                    // Only need to check a couple of things once, even if the
                    // break is shared between multiple case statements, or even
                    // the default case.
                    if ($tokens[$nextBreak]['column'] !== $caseAlignment + 4) {
                        $error = 'BREAK statement must be indented 4 spaces from SWITCH keyword';
                        $file->addError($error, $nextBreak, 'BreakIndent');
                    }

                    $breakLine = $tokens[$nextBreak]['line'];
                    $prevLine = 0;

                    for ($i = ($nextBreak - 1); $i > $stackPtr; $i--) {
                        if ($tokens[$i]['type'] !== 'T_WHITESPACE') {
                            $prevLine = $tokens[$i]['line'];
                            break;
                        }
                    }

                    if ($prevLine !== ($breakLine - 1)) {
                        $error = 'Blank lines are not allowed before BREAK statements';
                        $file->addError($error, $nextBreak, 'SpacingBeforeBreak');
                    }

                    $breakLine = $tokens[$nextBreak]['line'];
                    $nextLine = $tokens[$tokens[$stackPtr]['scope_closer']]['line'];
                    $semicolon = $file->findNext(T_SEMICOLON, $nextBreak);

                    for ($i = ($semicolon + 1); $i < $tokens[$stackPtr]['scope_closer']; $i++) {
                        if ($tokens[$i]['type'] !== 'T_WHITESPACE') {
                            $nextLine = $tokens[$i]['line'];
                            break;
                        }
                    }

                    if ($type === 'Case') {
                        // Ensure the BREAK statement is followed by
                        // a single blank line, or the end switch brace.
                        if ($nextLine !== ($breakLine + 2) && $i !== $tokens[$stackPtr]['scope_closer']) {
                            $error = 'BREAK statements must be followed by a single blank line';
                            $file->addError($error, $nextBreak, 'SpacingAfterBreak');
                        }
                    } else {
                        // Ensure the BREAK statement is not followed by a blank line.
                        if ($nextLine !== ($breakLine + 1)) {
                            $error = 'Blank lines are not allowed after the DEFAULT case\'s BREAK statement';
                            $file->addError($error, $nextBreak, 'SpacingAfterDefaultBreak');
                        }
                    }

                    $caseLine = $tokens[$nextCase]['line'];
                    $nextLine = $tokens[$nextBreak]['line'];

                    for ($i = ($opener + 1); $i < $nextBreak; $i++) {
                        if ($tokens[$i]['type'] !== 'T_WHITESPACE') {
                            $nextLine = $tokens[$i]['line'];
                            break;
                        }
                    }

                    if ($nextLine !== ($caseLine + 1)) {
                        $error = 'Blank lines are not allowed after ' . strtoupper($type) . ' statements';
                        $file->addError($error, $nextCase, 'SpacingAfter' . $type);
                    }
                }//end if

                if ($tokens[$nextBreak]['code'] === T_BREAK) {
                    if ($type === 'Case') {
                        // Ensure empty CASE statements are not allowed.
                        // They must have some code content in them. A comment is not enough.
                        // But count RETURN statements as valid content if they also
                        // happen to close the CASE statement.
                        $foundContent = false;

                        for ($i = ($tokens[$nextCase]['scope_opener'] + 1); $i < $nextBreak; $i++) {
                            if ($tokens[$i]['code'] === T_CASE) {
                                $i = $tokens[$i]['scope_opener'];
                                continue;
                            }

                            if (in_array($tokens[$i]['code'], Tokens::$emptyTokens) === false) {
                                $foundContent = true;
                                break;
                            }
                        }

                        if ($foundContent === false) {
                            $error = 'Empty CASE statements are not allowed';
                            $file->addError($error, $nextCase, 'EmptyCase');
                        }
                    } else {
                        // Ensure empty DEFAULT statements are not allowed.
                        // They must (at least) have a comment describing why
                        // the default case is being ignored.
                        $foundContent = false;

                        for ($i = ($tokens[$nextCase]['scope_opener'] + 1); $i < $nextBreak; $i++) {
                            if ($tokens[$i]['type'] !== 'T_WHITESPACE') {
                                $foundContent = true;
                                break;
                            }
                        }

                        if ($foundContent === false) {
                            $error = 'Comment required for empty DEFAULT case';
                            $file->addError($error, $nextCase, 'EmptyDefault');
                        }
                    }//end if
                }//end if
            } elseif ($type === 'Default') {
                $error = 'DEFAULT case must have a breaking statement';
                $file->addError($error, $nextCase, 'DefaultNoBreak');
            }//end if
        }//end while

        if ($foundDefault === false) {
            $error = 'All SWITCH statements must contain a DEFAULT case';
            $file->addError($error, $stackPtr, 'MissingDefault');
        }

        if ($tokens[$switch['scope_closer']]['column'] !== $switch['column']) {
            $error = 'Closing brace of SWITCH statement must be aligned with SWITCH keyword';
            $file->addError($error, $switch['scope_closer'], 'CloseBraceAlign');
        }

        if ($caseCount === 0) {
            $error = 'SWITCH statements must contain at least one CASE statement';
            $file->addError($error, $stackPtr, 'MissingCase');
        }
    }//end process()
}//end class
