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
        $regex = '/\* @.*QueryParam\((.*)\)/';
        if (!preg_match($regex, $file->getTokens()[$stackPtr]['content'], $matches)) {
            return;
        }

        foreach (self::$requiredAttributes as $attribute) {
            if (strpos($matches[1], $attribute . '=') === false) {
                $file->addError(
                    sprintf('Attribute "%1$s" is missing for QueryParam', $attribute),
                    $stackPtr,
                    'QueryParameterAttributeMissing'
                );
            }
        }
    }
}
