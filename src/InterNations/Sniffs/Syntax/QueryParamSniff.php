<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class QueryParamSniff implements Sniff
{
    private static $requiredAttributes = [
        'description',
        'strict'
    ];

    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    public function process(File $file, $stackPtr)
    {
        $regex = '/^@[^\(]*QueryParam\(.*/';

        $tokens = $file->getTokens();
        $content = $tokens[$stackPtr]['content'];

        if (!preg_match($regex, $content, $matches)) {
            return;
        }

        $content = '';
        $lineIndex = $stackPtr;
        $fileSize = count($file->getTokens());

        while ($lineIndex < $fileSize) {
            $content .= $tokens[$lineIndex]['content'];

            if (substr_count($content, '(') <= substr_count($content, ')')) {
                break;
            }
            $lineIndex++;
        }

        foreach (self::$requiredAttributes as $attribute) {
            if (strpos($content, $attribute . '=') === false) {
                $file->addError(
                    sprintf('Attribute "%1$s" is missing for QueryParam', $attribute),
                    $stackPtr,
                    'QueryParameterAttributeMissing'
                );
            }
        }
    }
}
