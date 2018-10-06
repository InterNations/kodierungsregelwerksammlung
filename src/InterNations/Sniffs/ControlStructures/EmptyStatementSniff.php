<?php
namespace InterNations\Sniffs\ControlStructures;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class EmptyStatementSniff implements Sniff
{
    public function register()
    {
        return [
            T_TRY,
            T_FINALLY,
            T_DO,
            T_ELSE,
            T_ELSEIF,
            T_FOR,
            T_FOREACH,
            T_IF,
            T_SWITCH,
            T_WHILE,
        ];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();
        $token = $tokens[$stackPtr];
        // Skip statements without a body.
        if (isset($token['scope_opener']) === false) {
            return;
        }

        $curlyOpen = $token['scope_opener'];

        if ($tokens[$curlyOpen]['type'] !== 'T_OPEN_CURLY_BRACKET') {
            return;
        }

        $curlyClose = $file->findNext([T_WHITESPACE, T_SEMICOLON], $curlyOpen + 1, null, true);

        if ($tokens[$curlyClose]['type'] !== 'T_CLOSE_CURLY_BRACKET') {
            return;
        }

        // Get token identifier.
        $name = strtoupper($token['content']);
        $error = 'Empty %s statement detected';
        $file->addError($error, $stackPtr, 'Detected' . ucfirst(strtolower($name)), [$name]);
    }
}
