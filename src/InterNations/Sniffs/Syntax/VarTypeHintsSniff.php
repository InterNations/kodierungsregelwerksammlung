<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class VarTypeHintsSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_VARIABLE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $commentEndPtr = $file->findPrevious(
            [T_WHITESPACE, T_STATIC, T_PUBLIC, T_PRIVATE, T_PROTECTED],
            $stackPtr - 1,
            null,
            true
        );

        if ($tokens[$commentEndPtr]['code'] !== T_DOC_COMMENT_CLOSE_TAG) {
            return;
        }

        $commentStartPtr = $file->findPrevious(T_DOC_COMMENT_OPEN_TAG, ($commentEndPtr - 1));

        for ($j = $commentStartPtr; $j <= $commentEndPtr; $j++) {
            if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG && $tokens[$j]['content'] !== '@var') {
                return;
            }

            $varDoc = preg_split(
                '/[\s]+/',
                trim($tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content'])
            );

            if (preg_match('/^ArrayCollection/D', $varDoc[0])) {
                $str = 'Found type "%1$s" for property "%2$s", ';
                $str .= '"@var ArrayCollection" is forbidden, use "@var Collection|Class[]" instead';
                $error = sprintf($str, $varDoc[0], $tokens[$stackPtr]['content']);
                $file->addError($error, $j, 'ForbiddenVarTypeHint');

                return;
            }
        }
    }
}
