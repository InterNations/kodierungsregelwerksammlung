<?php
namespace InterNations\Sniffs\BestPractice;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class CommonDependenciesSniff implements CodeSnifferSniff
{
    private static $commonSymbolNames = [
        'EntityManager' => 'em',
        'EntityManagerInterface' => 'em',
        'ObjectManager' => 'om',
        'EngineInterface' => 'templating',
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
        $typeHint = $methodParameter['type_hint'];

        if (!isset(self::$commonSymbolNames[$typeHint]) || self::$commonSymbolNames[$typeHint] === $name) {
            return;
        }

        $file->addError(
            'Parameter "$%s" ("%s") of method "%s" must be called "$%s"',
            $stackPtr,
            'ParameterName',
            [$name, $typeHint, $methodName, self::$commonSymbolNames[$typeHint]]
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
        $typeHint = $methodParameter['type_hint'];

        if (!isset(self::$commonSymbolNames[$typeHint], $assignments[$methodParameter['name']])) {
            return;
        }


        $propertyName = $assignments[$methodParameter['name']];

        if (self::$commonSymbolNames[$typeHint] === $propertyName) {
            return;
        }


        $file->addError(
            'Property "$%s" assigned from parameter "$%s" ("%s") of method "%s" must be called "$%s"',
            $stackPtr,
            'PropertyName',
            [$propertyName, $parameterName, $typeHint, $methodName, self::$commonSymbolNames[$typeHint]]
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
}
