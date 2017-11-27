<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class AlwaysUseSniff implements Sniff
{
    private $analyzed = [];

    public function register()
    {
        return [T_STRING];
    }

    public function process(File $file, $originalStackPtr)
    {
        $fileName = $file->getFilename();

        if (isset($this->analyzed[$fileName]) && $originalStackPtr < $this->analyzed[$fileName]) {
            return;
        }

        $tokens = $file->getTokens();

        $stackPtr = $originalStackPtr;
        $type = null;

        while (--$stackPtr > 0) {
            switch ($tokens[$stackPtr]['code']) {
                case T_NS_SEPARATOR:
                case T_STRING:
                    continue 2;

                case T_WHITESPACE:
                    switch ($tokens[$stackPtr - 1]['code']) {
                        case T_NEW:
                            $stackPtr--;
                            $type = 'New';
                            break;

                        case T_IMPLEMENTS:
                            $stackPtr--;
                            $type = 'Implements';
                            break;

                        case T_EXTENDS:
                            $stackPtr--;
                            $type = 'Extends';
                            break;

                        case T_INSTANCEOF:
                            $stackPtr--;
                            $type = 'InstanceOf';
                            break;

                        default:
                            // Fall through
                            break;
                    }
                    break 2;

                case T_OPEN_PARENTHESIS:
                    if ($tokens[$stackPtr - 3]['code'] === T_FUNCTION || $tokens[$stackPtr - 2]['code'] === T_CLOSURE) {
                        $type = 'TypeHint';
                        break 2;
                    }
                    break 2;

                case T_COMMA:
                    $type = 'TypeHint';
                    break 2;

                default:
                    // Do nothing
                    break 2;
            }
        }

        $leftStackPtr = $stackPtr;

        if ($type === null) {
            $stackPtr = $originalStackPtr;

            while (++$stackPtr > 0) {
                switch ($tokens[$stackPtr]['code']) {
                    case T_NS_SEPARATOR:
                    case T_STRING:
                        continue 2;

                    case T_PAAMAYIM_NEKUDOTAYIM:
                        $type = 'Static';
                        break 2;

                    default:
                        $type = null;
                        break 2;
                }
            }
        }

        if ($type === null) {
            return;
        }

        [$className, $endStackPtr] = $this->getClassName($file, $leftStackPtr);


        if ($className[0] === '\\') {
            $file->addError(
                'Fully qualified namespaces are prohibited (' . $className . '). Introduce a "use"-statement',
                $originalStackPtr,
                'FullyQualifiedNamespace.' . $type
            );
        }

        if (strtoupper($className) === $className) {
            return;
        }

        if (strpos($className, '_') !== false) {
            $file->addError(
                'Legacy namespaces are prohibited (' . $className . '). Introduce a "use"-statement and alias properly',
                $originalStackPtr,
                'LegacyNamespace_' . $type
            );
        }

        $this->analyzed[$fileName] = $endStackPtr;
    }

    private function getClassName(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $className = '';

        while (++$stackPtr) {
            switch ($tokens[$stackPtr]['code']) {
                case T_WHITESPACE;
                    continue 2;

                case T_NS_SEPARATOR:
                case T_STRING:
                    $className .= $tokens[$stackPtr]['content'];
                    continue 2;

                default:
                    // End of class name reached
                    break 2;
            }
        }

        return [$className, $stackPtr];
    }
}
