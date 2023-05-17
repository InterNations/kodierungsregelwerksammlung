<?php
namespace InterNations\Sniffs\BestPractice;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class DiscouragedConstantSniff implements Sniff
{
    public function register(): array
    {
        return [T_STRING, T_DOUBLE_COLON];
    }

    public function process(File $file, $stackPtr): void
    {
        $tokens = $file->getTokens();

        $constantName = $tokens[$stackPtr - 1]['content']
            . $tokens[$stackPtr]['content']
            . $tokens[$stackPtr + 1]['content'];

        if ($constantName === 'Types::SIMPLE_ARRAY') {
            $params = [
                $constantName,
                '\Doctrine\DBAL\Connection::PARAM_STR_ARRAY',
                '\Doctrine\DBAL\Connection::PARAM_INT_ARRAY',
            ];
            $file->addError(
                'Usage of %s is discouraged. '
                . 'It`s more likely that you want %s or %s instead to escape SQL IN() statements.',
                $stackPtr,
                'DiscouragedConstantFound',
                $params
            );
        }
    }
}
