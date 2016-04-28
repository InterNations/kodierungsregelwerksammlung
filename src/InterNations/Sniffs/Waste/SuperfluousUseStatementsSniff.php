<?php
namespace InterNations\Sniffs\Waste;

// @codingStandardsIgnoreFile
require_once __DIR__ . '/../NamespaceSniffTrait.php';

use InterNations\Sniffs\NamespaceSniffTrait;
use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class SuperfluousUseStatementsSniff implements CodeSnifferSniff
{
    use NamespaceSniffTrait;

    private static $docBlocks = [];

    private static $namespaceUsages = [];

    public function register()
    {
        return [T_USE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();

        /** function () use ($var) {} */
        $previousPtr = $file->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
        if ($tokens[$previousPtr]['code'] === T_CLOSE_PARENTHESIS) {
            return;
        }

        /** use Trait; */
        if ($file->findPrevious([T_CLASS, T_TRAIT], $stackPtr)) {
            return;
        }

        $fileName = $file->getFilename();
        list($stackPtr, $namespaceAlias, $fullyQualifiedNamespace) = $this->getNamespace($stackPtr + 1, $file);

        $symbol = false;
        $annotation = false;

        if (!isset(static::$docBlocks[$fileName])) {
            static::$docBlocks[$fileName] = [];
            $commentPtr = $stackPtr;
            while ($commentPtr = $file->findNext(T_DOC_COMMENT, $commentPtr + 1)) {
                static::$docBlocks[$fileName][] = $tokens[$commentPtr]['content'];
            }
        }

        $namespaceRegexPart = '(?:[\w\d\[\]]+\|)?' . preg_quote($namespaceAlias, '/') . '(?:\[\])?(?:\|[\w\d\[\]]+)?';
        foreach (static::$docBlocks[$fileName] as $docBlock) {

            // @<namespace>(...)
            if (strstr($docBlock, '@' . $namespaceAlias . '(') !== false) {
                $annotation = true;
                break;
            // @<namespace><NEWLINE>
            } elseif (strstr($docBlock, '@' . $namespaceAlias . "\n") !== false) {
                $annotation = true;
                break;
            // @<namespace>\Foo
            } elseif (strstr($docBlock, '@' . $namespaceAlias . '\\') !== false) {
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
            } elseif (strstr($docBlock, '@throws ' . $namespaceAlias) !== false) {
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
            if (!isset(static::$namespaceUsages[$fileName])) {
                static::$namespaceUsages[$fileName] = [];
                $strPtr = $stackPtr;
                while ($strPtr = $file->findNext([T_STRING, T_TRUE, T_FALSE], $strPtr + 1)) {
                    $namespaceUsed = $this->getNamespaceUsage($strPtr, $file);
                    if ($namespaceUsed) {
                        static::$namespaceUsages[$fileName][$strPtr] = $namespaceUsed;
                    }
                }
            }

            $found = 0;
            foreach (static::$namespaceUsages[$fileName] as $ptr => $namespaceUsed) {
                if ($ptr < $stackPtr) {
                    continue;
                }

                $identicalNamespace = $namespaceUsed === $namespaceAlias;
                $partialNamespace = strpos($namespaceUsed, $namespaceAlias . '\\') === 0;

                if ($identicalNamespace || $partialNamespace) {
                    ++$found;

                    if ($found > 1) {
                        $symbol = true;
                        break;
                    }
                }
            }
        }

        if (!$symbol && !$annotation) {
            $file->addError(
                'Superfluous use-statement found for symbol "%s", but no further reference',
                $stackPtr,
                'UnusedUse',
                [$fullyQualifiedNamespace]
            );

            return;
        }

        $nsTokenPtr = $file->findPrevious(T_NAMESPACE, $stackPtr);
        list( , , $currentFullyQualifiedNamespace) = $this->getNamespace($nsTokenPtr + 1, $file);

        if ($this->isInSameNamespace($currentFullyQualifiedNamespace, $fullyQualifiedNamespace)) {
            $file->addError(
                'Superfluous use-statement found for symbol "%s", but no use statement needed as namespaces match',
                $stackPtr,
                'NamespaceMatch',
                [$fullyQualifiedNamespace]
            );
        }
    }

    private static function getNamespaceUsage($stackPtr, CodeSnifferFile $file)
    {
        $tokens = $file->getTokens();
        $namespace = '';
        while ($stackPtr = $file->findNext([T_STRING, T_NS_SEPARATOR, T_TRUE, T_FALSE], $stackPtr, $stackPtr + 1)) {

            if (in_array($tokens[$stackPtr]['code'], [T_TRUE, T_FALSE], true)) {
                if ($tokens[$stackPtr + 1]['code'] !== T_OPEN_PARENTHESIS) {
                    $stackPtr++;
                    continue;
                }
            }

            $namespace .= $tokens[$stackPtr]['content'];
            $stackPtr++;
        }

        return $namespace;
    }

    private static function isInSameNamespace($namespace, $symbol)
    {
        $namespace .= '\\';

        return strpos($symbol, $namespace) === 0
            && strpos(substr($symbol, strlen($namespace)), '\\') === false;
    }
}
