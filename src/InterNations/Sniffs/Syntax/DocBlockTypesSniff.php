<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class DocBlockTypesSniff implements Sniff
{
    private static $typeMap = [
        'boolean' => 'bool',
        'real' => 'float',
        'double' => 'float',
        'binary' => 'string',
        'integer' => 'int',
        'void' => 'null',
        '$this' => 'self',
        'this' => 'self',
    ];

    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();
        $content = $file->getTokensAsString($stackPtr, 3);
        $regex = '/@(?<annotation>var|param|return)\s+(?<types>[^\s]+)(?:\s+|$)/xi';

        if (!preg_match($regex, $content, $matches)) {
            return;
        }

        foreach (explode('|', $matches['types']) as $type) {

            if (!isset(static::$typeMap[$type])) {
                continue;
            }

            $file->addFixableError(
                sprintf(
                    'Found "@%1$s %2$s", expected "@%1$s %3$s"',
                    $matches['annotation'],
                    $matches['types'],
                    str_replace($type, static::$typeMap[$type], $matches['types'])
                ),
                $stackPtr,
                'ShortDocCommentTypes'
            );

            $commentStrPtr = $file->findNext([T_WHITESPACE, T_DOC_COMMENT_WHITESPACE], ($stackPtr + 1), null, true);

            $file->fixer->beginChangeset();
            $file->fixer->replaceToken(
                $commentStrPtr,
                str_replace($type, static::$typeMap[$type], $tokens[$commentStrPtr]['content'])
            );
            $file->fixer->endChangeset();
        }
    }
}
