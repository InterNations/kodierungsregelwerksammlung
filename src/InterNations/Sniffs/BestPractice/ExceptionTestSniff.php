<?php
namespace InterNations\Sniffs\BestPractice;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ExceptionTestSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_DOC_COMMENT];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if (strpos($tokens[$stackPtr]['content'], '@expectedException ') === false) {
            return;
        }

        $docBlocks = [$tokens[$stackPtr]['content']];
        $ptr = $stackPtr;

        while ($ptr = $file->findPrevious([T_WHITESPACE], $ptr - 1, null, true)) {
            if ($tokens[$ptr]['code'] !== T_DOC_COMMENT) {
                break;
            }
            $docBlocks[] = $tokens[$ptr]['content'];
        }


        $ptr = $stackPtr;

        while ($ptr = $file->findNext([T_WHITESPACE], $ptr + 1, null, true)) {
            if ($tokens[$ptr]['code'] !== T_DOC_COMMENT) {
                break;
            }
            $docBlocks[] = $tokens[$ptr]['content'];
        }

        $arguments = [
            'class'   => null,
            'message' => null,
            'code'    => null,
        ];
        $method = 'setExpectedException';
        $regex = '#(?P<annotation>@expectedException(Code|Message(RegExp)?)?)\s+(?P<parameter>.+)(\s*\*/)?$#U';

        foreach ($docBlocks as $docBlock) {
            if (!preg_match($regex, $docBlock, $matches)) {
                continue;
            }


            $parameter = $matches['parameter'];

            switch ($matches['annotation']) {

                case '@expectedExceptionMessage':
                    $arguments['message'] = var_export($parameter, true);
                    break;

                case '@expectedExceptionMessageRegExp':
                    $method = 'setExpectedExceptionRegExp';
                    $arguments['message'] = var_export('/' . str_replace('/', '\/', $parameter) . '/', true);
                    break;

                case '@expectedExceptionCode':
                    $arguments['code'] = is_numeric($parameter) ? $parameter : var_export($parameter, true);
                    break;

                case '@expectedException':
                default:
                    $arguments['class'] = $parameter . '::class';
                    break;
            }
        }

        if (!isset($arguments['code'])) {
            unset($arguments['code']);

            if (!isset($arguments['message'])) {
                unset($arguments['message']);
            }
        }

        $message = 'Annotation @expectedException found. Using annotations in test cases is error-prone because a '
            . 'simple typo can lead to false positives. Use %s(%s) instead';
        $file->addError($message, $stackPtr, 'BestPractice.ExceptionAnnotation', [$method, implode(', ', $arguments)]);
    }
}
