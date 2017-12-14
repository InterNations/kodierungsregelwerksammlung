<?php
namespace InterNations\Sniffs\BestPractice;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ExceptionTestSniff implements Sniff
{
    public function register()
    {
        return [T_DOC_COMMENT_TAG];
    }

    public function process(File $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        if ($tokens[$stackPtr]['content'] !== '@expectedException') {
            return;
        }

        $docBlocks = [$tokens[$stackPtr]['content']];
        $docCommentTypes = [
            T_DOC_COMMENT_OPEN_TAG,
            T_DOC_COMMENT_CLOSE_TAG,
            T_DOC_COMMENT_STAR,
            T_DOC_COMMENT_TAG,
            T_DOC_COMMENT_STRING,
            T_DOC_COMMENT_WHITESPACE,
        ];
        $docBlockOpenTagPtr = $file->findPrevious(T_DOC_COMMENT_OPEN_TAG, $stackPtr - 1);
        $arguments = [
            'class'   => null,
            'message' => null,
            'code'    => null,
        ];
        $method = 'setExpectedException';

        while ($docBlockOpenTagPtr = $file->findNext([T_WHITESPACE], $docBlockOpenTagPtr + 1, null, true)) {
            if (!in_array($tokens[$docBlockOpenTagPtr]['code'], $docCommentTypes, true)) {
                break;
            }

            if ($tokens[$docBlockOpenTagPtr]['code'] !== T_DOC_COMMENT_TAG) {
                continue;
            }


            $annotation = $tokens[$docBlockOpenTagPtr]['content'];
            $parameter = trim($tokens[$file->findNext(T_DOC_COMMENT_STRING, $docBlockOpenTagPtr)]['content']);

            switch ($annotation) {
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
        $file->addError($message, $stackPtr, 'BestPractice_ExceptionAnnotation', [$method, implode(', ', $arguments)]);
    }
}
