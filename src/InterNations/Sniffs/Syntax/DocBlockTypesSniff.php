<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class DocBlockTypesSniff implements CodeSnifferSniff
{
    private static $typeMap = [
        'bool'   => 'boolean',
        'real'   => 'float',
        'double' => 'float',
        'binary' => 'string',
        'int'    => 'integer',
        'void'   => 'null',
        '$this'  => 'self',
        'this'   => 'self',
    ];

    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $content = $file->getTokensAsString($stackPtr, 3);
        $regex = '/@(?<annotation>var|param|return)\s+(?<types>[^\s]+)(?:\s+|$)/xi';

        if (!preg_match($regex, $content, $matches)) {
            return;
        }

        foreach (explode('|', $matches['types']) as $type) {

            if (!isset(static::$typeMap[$type])) {
                continue;
            }

            $file->addError(
                sprintf(
                    'Found "@%1$s %2$s", expected "@%1$s %3$s"',
                    $matches['annotation'],
                    $matches['types'],
                    str_replace($type, static::$typeMap[$type], $matches['types'])
                ),
                $stackPtr,
                'ShortDocCommentTypes'
            );
        }
    }
}
