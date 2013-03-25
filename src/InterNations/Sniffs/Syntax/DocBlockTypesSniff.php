<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

// @codingStandardsIgnoreStart
class InterNations_Sniffs_Syntax_DocBlockTypesSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
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
        return [T_DOC_COMMENT];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $typePart = join('|', array_map('preg_quote', array_keys(static::$typeMap)));

        $regex = '/@(?<annotation>var|param|return)\s+(?<invalidType>' . $typePart . ')(?:\s+|$)/xi';
        if (!preg_match($regex, $file->getTokens()[$stackPtr]['content'], $matches)) {
            return;
        }

        if (!isset(static::$typeMap[$matches['invalidType']])) {
            return;
        }

        $file->addError(
            sprintf(
                'Found "@%1$s %2$s", expected "@%1$s %3$s"',
                $matches['annotation'],
                $matches['invalidType'],
                static::$typeMap[$matches['invalidType']]
            ),
            $stackPtr,
            'ShortDocCommentTypes'
        );
    }
}
