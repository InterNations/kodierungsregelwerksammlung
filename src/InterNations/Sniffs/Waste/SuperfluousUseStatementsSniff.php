<?php
namespace InterNations\Sniffs\Waste;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class SuperfluousUseStatementsSniff implements CodeSnifferSniff
{
    private $docBlocks = [];

    private $namespaceUsages = [];

    public function register()
    {
        return [T_USE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        /** function () use ($var) {} */
        if ($file->findPrevious(T_FUNCTION, $stackPtr)) {
            return;
        }

        /** use Trait; */
        if ($file->findPrevious(T_CLASS, $stackPtr)) {
            return;
        }

        $fileName = $file->getFilename();
        $tokens = $file->getTokens();
        list($stackPtr, $namespace) = $this->getNamespace($stackPtr + 1, $file);

        $class = false;
        $annotation = false;

        if (!isset($this->docBlocks[$fileName])) {
            $this->docBlocks[$fileName] = [];
            $commentPtr = $stackPtr;
            while ($commentPtr = $file->findNext(T_DOC_COMMENT, $commentPtr + 1)) {
                $this->docBlocks[$fileName][] = $tokens[$commentPtr]['content'];
            }
        }

        foreach ($this->docBlocks[$fileName] as $docBlock) {
            $namespaceRegexPart = '(?:[\w\d\[\]]+\|)?' . preg_quote($namespace, '/') . '(?:\[\])?(?:\|[\w\d\[\]]+)?';

            // @<namespace>(...)
            if (strstr($docBlock, '@' . $namespace . '(') !== false) {
                $annotation = true;
                break;
            } elseif (strstr($docBlock, '@' . $namespace . "\n") !== false) {
                $annotation = true;
                break;
            // @<namespace>\Foo
            } elseif (strstr($docBlock, '@' . $namespace . '\\') !== false) {
                $annotation = true;
                break;
            // @param <namespace> $var
            } elseif (preg_match('/@param ' . $namespaceRegexPart . ' \$/i', $docBlock)) {
                $annotation = true;
                break;
            // @var <namespace>
            // @return <namespace>
            } elseif (preg_match('/@(var|return) ' . $namespaceRegexPart . '/i', $docBlock)) {
                $annotation = true;
                break;
            // @throws <namespace>
            } elseif (strstr($docBlock, '@throws ' . $namespace) !== false) {
                $annotation = true;
                break;
            // @var $variable <namespace>
            } elseif (preg_match('/@var \$[^\s]+ ' . $namespaceRegexPart . '/i', $docBlock)) {
                $annotation = true;
                break;
            // @method <namespace> methodName()
            // @property <namespace> property
            // @property-read <namespace> property
            // @property-write <namespace> property
            } elseif (preg_match('/@(property(?:-read|-write)?|method) ' . $namespaceRegexPart . ' /i', $docBlock)) {
                $annotation = true;
                break;
            // @method foo(<namespace> $var) @method foo(\string $bla, <namespace> $var)
            } elseif (preg_match('/@method .*(?:\(|, )' . $namespaceRegexPart . ' \$/i', $docBlock)) {
                $annotation = true;
                break;
            }
        }

        if (!$annotation) {
            if (!isset($this->namespaceUsages[$fileName])) {
                $this->namespaceUsages[$fileName] = [];
                $strPtr = $stackPtr;
                while ($strPtr = $file->findNext(T_STRING, $strPtr + 1)) {
                    $namespaceUsed = $this->getNamespaceUsage($strPtr, $file);
                    if ($namespaceUsed) {
                        $this->namespaceUsages[$fileName][$strPtr] = $namespaceUsed;
                    }
                }
            }

            $found = 0;
            foreach ($this->namespaceUsages[$fileName] as $ptr => $namespaceUsed) {
                if ($ptr > $stackPtr && strpos($namespaceUsed, $namespace) === 0) {
                    ++$found;

                    if ($found > 1) {
                        $class = true;
                        break;
                    }
                }
            }
        }

        if (!$class && !$annotation) {
            $file->addError(
                'Superfluous use-statement found for symbol "%s", but no further reference',
                $stackPtr,
                'SuperfluousUseStatement',
                [$namespace]
            );
        }
    }

    private function getNamespace($stackPtr, CodeSnifferFile $phpcsFile)
    {
        $tokens = $phpcsFile->getTokens();
        $namespace = '';
        $firstWhitespace = true;
        $asToken = false;
        $latestStackPtr = 0;
        while ($stackPtr = $phpcsFile->findNext(
            [T_STRING, T_NS_SEPARATOR, T_WHITESPACE, T_AS],
            $stackPtr + 1,
            null,
            null,
            null,
            true
        )
        ) {
            if ($firstWhitespace && $tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $firstWhitespace = false;
                $namespace = $tokens[$stackPtr]['content'];
            } elseif ($tokens[$stackPtr]['code'] === T_AS) {
                $asToken = true;
                $namespace = '';
            } elseif ($tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $namespace = $tokens[$stackPtr]['content'];
            } elseif ($asToken && $tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $namespace = $tokens[$stackPtr]['content'];
            }

            if ($tokens[$stackPtr]['code'] !== T_WHITESPACE) {
                $latestStackPtr = $stackPtr - 1;
            }
        }

        return [$latestStackPtr, ltrim($namespace, '\\')];
    }

    private function getNamespaceUsage($stackPtr, CodeSnifferFile $phpcsFile)
    {
        $tokens = $phpcsFile->getTokens();
        $namespace = '';
        while ($stackPtr = $phpcsFile->findNext([T_STRING, T_NS_SEPARATOR], $stackPtr, $stackPtr + 1)) {
            $namespace .= $tokens[$stackPtr]['content'];
            $stackPtr++;
        }

        return $namespace;
    }
}
