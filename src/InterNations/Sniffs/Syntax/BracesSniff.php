<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class BracesSniff implements Sniff
{
    public function register()
    {
        return [T_CLASS, T_INTERFACE, T_TRAIT, T_FUNCTION];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if ($tokens[$stackPtr]['code'] === T_FUNCTION && $file->getDeclarationName($stackPtr) === NULL) {
            return;
        }

        $bracketPos = $file->findNext(T_OPEN_CURLY_BRACKET, $stackPtr, null, false, null, true);

        if (!$bracketPos) {
            return;
        }

        $symbolName = $file->getDeclarationName($stackPtr);
        $symbolType = $tokens[$stackPtr]['content'];

        if ($tokens[$bracketPos]['column'] !== (4 * $tokens[$stackPtr]['level']) + 1) {
            $file->addError(
                'Opening bracket of "%s %s" must be in the next line',
                $stackPtr,
                sprintf('MissingNewline%sBrace', ucfirst($symbolType)),
                [$symbolType, $symbolName]
            );
        }

        $previousStringTokenPos = $file->findPrevious([T_STRING, T_CLOSE_PARENTHESIS], $bracketPos);

        if (1 + $tokens[$previousStringTokenPos]['line'] < $tokens[$bracketPos]['line']) {
            $file->addError(
                'Exactly a single newline must follow after the declaration of "%s %s"',
                $stackPtr,
                sprintf('TooManyNewlines%sBrace', ucfirst($symbolType)),
                [$symbolType, $symbolName]
            );
        }
    }
}
