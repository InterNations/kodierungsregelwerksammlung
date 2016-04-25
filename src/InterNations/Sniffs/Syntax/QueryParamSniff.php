<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class QueryParamSniff implements CodeSnifferSniff
{
    private static $requiredAttributes = [
        'description',
        'strict'
    ];

    public function register()
    {
        return [T_DOC_COMMENT];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $regex = '/\* @[^\(]*QueryParam\(.*/';

        if (!preg_match($regex, $file->getTokens()[$stackPtr]['content'], $matches)) {
            return;
        }

        $content = '';
        $lineIndex = $stackPtr;
        $fileSize = count($file->getTokens());

        while ($lineIndex < $fileSize) {
            $content .= $file->getTokens()[$lineIndex]['content'];

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
