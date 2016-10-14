<?php
namespace InterNations\Sniffs\BestPractice;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class CommonDependenciesSniff implements CodeSnifferSniff
{
    private static $commonSymbolNames = [
        'Doctrine\ORM\EntityManager' => 'em',
        'Doctrine\ORM\EntityManagerInterface' => 'em',
        'Doctrine\Common\Persistence\ObjectManager' => 'om',
        'Symfony\Component\Templating\EngineInterface' => 'templating',
        'Symfony\Bundle\FrameworkBundle\Templating\EngineInterface' => 'templating',
    ];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $methodName = $file->getDeclarationName($stackPtr);

        if ($methodName !== '__construct' && strpos($methodName, 'set') !== 0) {
            return;
        }


        $methodParameters = $file->getMethodParameters($stackPtr);

        foreach ($methodParameters as $methodParameter) {
            $this->verifyParameterName($file, $stackPtr, $methodName, $methodParameter);
        }


        $propertyAssignments = $this->getPropertyAssignments($file, $stackPtr);

        foreach ($methodParameters as $methodParameter) {
            $this->verifyPropertyName($file, $stackPtr, $methodName, $methodParameter, $propertyAssignments);
        }
    }

    private function verifyParameterName(CodeSnifferFile $file, $stackPtr, $methodName, array $methodParameter)
    {
        $name = ltrim($methodParameter['name'], '$');
        list($fqNs, $className) = $this->getFullQualifiedName($file, $methodParameter['type_hint']);

        if (!isset(self::$commonSymbolNames[$fqNs]) || self::$commonSymbolNames[$fqNs] === $name) {
            return;
        }

        $file->addError(
            'Parameter "$%s" ("%s") of method "%s" must be called "$%s"',
            $stackPtr,
            'ParameterName',
            [$name, $className, $methodName, self::$commonSymbolNames[$fqNs]]
        );
    }

    private function verifyPropertyName(
        CodeSnifferFile $file,
        $stackPtr,
        $methodName,
        array $methodParameter,
        array $assignments
    )
    {
        $parameterName = ltrim($methodParameter['name'], '$');
        list($fqNs, $className) = $this->getFullQualifiedName($file, $methodParameter['type_hint']);

        if (!isset(self::$commonSymbolNames[$fqNs], $assignments[$methodParameter['name']])) {
            return;
        }


        $propertyName = $assignments[$methodParameter['name']];

        if (self::$commonSymbolNames[$fqNs] === $propertyName) {
            return;
        }


        $file->addError(
            'Property "$%s" assigned from parameter "$%s" ("%s") of method "%s" must be called "$%s"',
            $stackPtr,
            'PropertyName',
            [$propertyName, $parameterName, $className, $methodName, self::$commonSymbolNames[$fqNs]]
        );
    }

    private function getPropertyAssignments(CodeSnifferFile $file, $stackPtr)
    {
        $tokens = $file->getTokens();
        $currentToken = $tokens[$stackPtr];

        if (!isset($currentToken['scope_opener'])) {
            return [];
        }

        $ptr = $currentToken['scope_opener'];
        $endOfMethodPtr = $currentToken['scope_closer'];


        $assignments = [];

        while ($ptr && $ptr < $endOfMethodPtr) {

            $thisPtr = $file->findNext([T_SELF, T_STATIC, T_VARIABLE], $ptr, $endOfMethodPtr, false, null, true);
            $ptr = $file->findNext([], $ptr + 1, $endOfMethodPtr, true, null, true);

            if (!$thisPtr
                || !in_array($tokens[$thisPtr]['content'], ['$this', 'static', 'self'], true)
                || !in_array($tokens[$thisPtr + 1]['code'], [T_OBJECT_OPERATOR, T_DOUBLE_COLON], true)
                || !in_array($tokens[$thisPtr + 2]['code'], [T_STRING, T_VARIABLE], true)
            ) {
                continue;
            }


            $equalPtr = $file->findNext(T_WHITESPACE, $thisPtr + 3, $endOfMethodPtr, true, null, true);

            if (!$equalPtr || $tokens[$equalPtr]['code'] !== T_EQUAL) {
                continue;
            }


            $variablePtr = $file->findNext(T_WHITESPACE, $equalPtr + 1, $endOfMethodPtr, true, null, true);

            if (!$variablePtr || $tokens[$variablePtr]['code'] !== T_VARIABLE) {
                continue;
            }

            $assignments[$tokens[$variablePtr]['content']] = ltrim($tokens[$thisPtr + 2]['content'], '$');
        }

        return $assignments;
    }

    private function getFullQualifiedName(CodeSnifferFile $file, $symbolName)
    {
        $tokens = $file->getTokens();

        $stackPtr = 0;

        while ($stackPtr !== false) {
            $usePtr = $file->findNext(T_USE, $stackPtr);
            $nextPtr = $file->findNext(T_WHITESPACE, $usePtr + 1, null, true);

            if ($tokens[$nextPtr]['code'] !== T_STRING) {
                break;
            }

            $endPtr = $file->findNext(T_SEMICOLON, $usePtr + 1);

            $asPtr = $file->findNext(T_AS, $usePtr + 1, $endPtr);
            $endOfFqNsPtr = $asPtr ? $asPtr - 3 : $endPtr - 2;

            $fqNs = $file->getTokensAsString($usePtr + 2, $endOfFqNsPtr - $usePtr);
            $name = $file->getTokensAsString($endPtr - 1, 1);

            if ($name === $symbolName) {
                return [$fqNs, $name];
            }

            $stackPtr = $endPtr;
        }

        return [null, null];
    }
}
