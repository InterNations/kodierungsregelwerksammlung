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

    private static $namespaces = [];

    public function register()
    {
        return [T_NAMESPACE, T_USE];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();
        $fileName = $file->getFilename();

        if ($tokens[$stackPtr]['code'] === T_NAMESPACE) {
            list(, , $fqNs) = $this->getNamespace($stackPtr + 1, $file);
            static::$namespaces[$fileName] = $fqNs;
            return;
        }

        /** function () use ($var) {} */
        $previousPtr = $file->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
        if ($tokens[$previousPtr]['code'] === T_CLOSE_PARENTHESIS) {
            return;
        }

        /** use Trait; */
        if ($file->findPrevious([T_CLASS, T_TRAIT], $stackPtr)) {
            return;
        }

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

        foreach (static::$docBlocks[$fileName] as $docBlock) {
            $namespaceRegexPart = '(?:[\w\d\[\]]+\|)?'
                . preg_quote($namespaceAlias, '/')
                . '(?:\[\])?(?:\|[\w\d\[\]]+)?';

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

        //var_dump(static::$namespaceUsages);
        if (!$annotation) {
            if (!isset(static::$namespaceUsages[$fileName])) {
                static::$namespaceUsages[$fileName] = [];
                $strPtr = 0;
                while ($strPtr = $file->findNext([T_STRING, T_TRUE, T_FALSE], $strPtr + 1)) {
                    var_dump($strPtr);
                    list($namespaceUsed, $updatedPtr) = $this->getNamespaceUsage($strPtr, $file);

                    if ($namespaceUsed) {
                        static::$namespaceUsages[$fileName][$strPtr] = $namespaceUsed;
                        $strPtr = $updatedPtr;
                        var_dump($strPtr);
                    }
                }

                var_dump(static::$namespaceUsages);
            }

            $found = 0;
            foreach (static::$namespaceUsages[$fileName] as $ptr => $namespaceUsed) {
                if ($ptr < $stackPtr) {
                    continue;
                }


                $fileNamespace = static::getNamespaceOfFile($fileName);
                $fileNamespaceLength = strlen($fileNamespace) + 1;
                if ($fileNamespace
                    && substr($namespaceUsed, 0, $fileNamespaceLength) === $fileNamespace . '\\'
                    && substr($namespaceUsed, $fileNamespaceLength) === $namespaceAlias) {
                    continue;
                }

                $identicalMatch = $namespaceUsed === $namespaceAlias;
                $partialMatch = strpos($namespaceUsed, $namespaceAlias . '\\') === 0;
                //var_dump('NEW', $namespaceUsed, $namespaceAlias);


                if ($identicalMatch || $partialMatch) {

                    //var_dump($ptr, $namespaceAlias, $namespaceUsed, $identicalMatch, $partialMatch);
                    ++$found;

                    if ($found > 0) {
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
                'SuperfluousUseStatement',
                [$fullyQualifiedNamespace]
            );
        }
    }

    private static function getNamespaceOfFile($fileName)
    {
        return isset(static::$namespaces[$fileName]) ? static::$namespaces[$fileName] : null;
    }

    private static function getNamespaceUsage($stackPtr, CodeSnifferFile $file)
    {
        $tokens = $file->getTokens();
        $namespace = '';
        $lastStackPtr = $stackPtr;
        while ($stackPtr = $file->findNext([T_STRING, T_NS_SEPARATOR, T_DOUBLE_COLON, T_TRUE, T_FALSE], $stackPtr, $stackPtr + 1)) {

            if (in_array($tokens[$stackPtr]['code'], [T_TRUE, T_FALSE], true)) {
                if ($tokens[$stackPtr + 1]['code'] !== T_OPEN_PARENTHESIS) {
                    $lastStackPtr = ++$stackPtr;
                    continue;
                }
            }

            $namespace .= $tokens[$stackPtr]['content'];
            $lastStackPtr = ++$stackPtr;
        }

        $namespaceToken = [$namespace, $namespace];
        if (preg_match('/^(.*?)(?::|\\\\)(.*)$/', $namespace, $matches)) {
            $namespaceToken = [$matches[1], $matches[2]];
        }

        return [$namespaceToken, $lastStackPtr];
    }
}
