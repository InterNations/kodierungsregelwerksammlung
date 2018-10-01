<?php
namespace InterNations\Sniffs\Architecture;

use InterNations\Sniffs\NamespaceSniffTrait;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class IntegrationTestsConventionSniff implements Sniff
{
    use NamespaceSniffTrait;

    public function register()
    {
        return [T_NAMESPACE];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        $namespaceData = self::getNamespace($stackPtr, $file);
        $nameSpaceString = $namespaceData[2];

        // Only consider integration tests
        if (strpos($file->getFilename(), 'Test.php') === false
            || strpos($nameSpaceString, 'Test\Integration') === false) {
            return;
        }

        $classPtr = $file->findNext(T_CLASS, $stackPtr);

        // Integration tests should be named as '*IntegrationTest.php'
        if (strpos($file->getFilename(), 'IntegrationTest.php') === false) {
            $error = 'All the integration tests name should end with *IntegrationTest.php';
            $file->addError($error, $classPtr, 'MissingGroupIntegrationAnnotation');
        }

        // Integration tests should have '@group integration' annotation
        if (!$this->isAnnotationDefined($classPtr, $tokens, $file)) {
            $error = 'All the integration tests must have @group integration annotation';
            $file->addError($error, $classPtr, 'MissingGroupIntegrationAnnotation');
        }
    }

    private function isAnnotationDefined($start, &$tokens, File $file)
    {
        $commentEnd = $file->findPrevious([T_WHITESPACE, T_ABSTRACT], $start - 1, null, true);

        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG) {
            return false;
        }

        $commentStart = $file->findPrevious(T_DOC_COMMENT_OPEN_TAG, ($commentEnd - 1));

        for ($j = $commentStart; $j <= $commentEnd; $j++) {
            if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG && $tokens[$j]['content'] === '@group') {
                $groupIntegration = preg_split(
                    '/[\s]+/',
                    trim($tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content']),
                    2
                );

                if ($groupIntegration[0] === 'integration') {
                    return true;
                }
            }
        }

        return false;
    }
}
