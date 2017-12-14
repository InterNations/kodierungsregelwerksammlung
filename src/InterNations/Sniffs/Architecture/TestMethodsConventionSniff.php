<?php
namespace InterNations\Sniffs\Architecture;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class TestMethodsConventionSniff implements Sniff
{
    public $ignoreMandatoryPublicMethods;

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(File $file, $stackPtr)
    {
        $ignoreMandatoryPublicMethods = [];
        
        if ($this->ignoreMandatoryPublicMethods && !empty($this->ignoreMandatoryPublicMethods)) {
            $ignoreMandatoryPublicMethods = explode(':', $this->ignoreMandatoryPublicMethods);
        }

        $tokens = $file->getTokens();

        if (strpos($file->getFilename(), 'Test.php') === false) {
            return;
        }

        // Ignore anonymous classes
        if (in_array(T_ANON_CLASS, $tokens[$stackPtr]['conditions'])) {
            return;
        }

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
        if (strpos($methodName, 'test') === 0) {
            return;
        }

        // Skip @dataProvider + public methods
        if (in_array($methodName, array_merge($dataProvider, $ignoreMandatoryPublicMethods))) {
            return;
        }

        if ($tokens[$visibilityPtr]['code'] === T_PUBLIC) {
            $file->addError(
                'All public methods in a PHPUnit test must either start with test* or be data providers. '
                . 'This is for to make sure, we are not accidentally skipping a test because for example a typo '
                . '(tsetSomething()).',
                $stackPtr,
                'testMethodConvention'
            );
        }
    }
}
