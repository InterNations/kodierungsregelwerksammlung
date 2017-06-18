<?php
namespace InterNations\Sniffs\Architecture;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class TestMethodsConventionSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $dataProvider = [];

        for ($i = 0; $i < count($tokens); $i++) {
            if ($tokens[$i]['code'] === T_DOC_COMMENT_OPEN_TAG) {
                $commentEndPtr = $file->findNext(T_DOC_COMMENT_CLOSE_TAG, ($i + 1));

                for ($j = $i; $j <= $commentEndPtr; $j++) {
                    if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG && $tokens[$j]['content'] === '@dataProvider') {
                        $dataProvider[$j] =
                            trim($tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content']);
                    }
                }
            }
        }

        // Class name
        $classPtr = $file->findPrevious(T_CLASS, $stackPtr);
        $className = $tokens[$file->findNext(T_WHITESPACE, $classPtr + 1, null, true)]['content'];

        if (!preg_match('/Test$/D', $className)) {
            return;
        }

        // Method name
        $namePtr = $file->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
        $methodName = $tokens[$namePtr]['content'];

        // Visibility
        $visibilityPtr = $file->findPrevious([T_FUNCTION, T_WHITESPACE, T_STATIC], $namePtr - 1, null, true);

        // Skip invalid statement.
        if (!isset($tokens[$namePtr + 1]['parenthesis_opener'])) {
            return;
        }

        // Skip test methods
        if (preg_match('/^test/D', $methodName)) {
            return;
        }

        // Skip @dataProvider
        if (in_array($methodName, $dataProvider)) {
            return;
        }

        if ($tokens[$visibilityPtr]['code'] === T_PUBLIC) {
            $file->addError(
                'All public methods in a PHPUnit test must either start with test* or be data providers. '
                . 'This is for to make sure, we are not accidentally skipping a test because for example a typo '
                . '(tsetSomething()).',
                $stackPtr,
                'verbLimit'
            );
        }
    }
}
