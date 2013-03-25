<?php
use PHP_CodeSniffer_File as CodeSnifferFile;

// @codingStandardsIgnoreStart
class InterNations_Sniffs_Naming_FinalSniff implements PHP_CodeSniffer_Sniff
// @codingStandardsIgnoreEnd
{
    public function register()
    {
        return [T_CLASS];
    }

    public function process(CodeSnifferFile $phpcsFile, $stackPtr)
    {
        $isFinal = (bool) $phpcsFile->findPrevious(T_FINAL, $stackPtr - 1, $stackPtr - 3);
        $className = $phpcsFile->getDeclarationName($stackPtr);

        $methods = 0;
        $staticMethods = 0;
        $methodPtr = $stackPtr;
        while ($methodPtr = $phpcsFile->findNext(T_FUNCTION, $methodPtr + 1)) {
            $methods++;
            if ($phpcsFile->findPrevious(T_STATIC, $methodPtr - 1, $methodPtr - 3)) {
                $staticMethods++;
            }
        }

        $properties = 0;
        $staticProperties = 0;
        $propertyPtr = $stackPtr;
        while ($propertyPtr = $phpcsFile->findNext(T_VARIABLE, $propertyPtr + 1)) {
            if ($phpcsFile->findPrevious([T_PUBLIC, T_PROTECTED, T_PRIVATE], $propertyPtr - 1, $propertyPtr - 3)) {
                $properties++;
                if ($phpcsFile->findPrevious(T_STATIC, $propertyPtr - 1, $propertyPtr - 3)) {
                    $staticProperties++;
                }
            }
        }

        $extendsClass = (bool) $phpcsFile->findNext(T_EXTENDS, $stackPtr + 1);
        $implementsInterface = (bool) $phpcsFile->findNext(T_INTERFACE, $stackPtr + 1);
        $shouldBeFinal = ($methods === $staticMethods
                        && $properties === $staticProperties
                        && !$extendsClass
                        && !$implementsInterface)
                        || $className == 'EventType';

        if (!$isFinal && $shouldBeFinal && $methods === 0) {
            $phpcsFile->addError(
                'Enum "%s" must be final',
                $stackPtr,
                'FinalClassEnum',
                [$className]
            );
        }

        if (!$isFinal && $shouldBeFinal && $methods === $staticMethods) {
            $phpcsFile->addError(
                'Static class "%s" must be final',
                $stackPtr,
                'FinalClassStatic',
                [$className]
            );
        }

        if ($isFinal && !$shouldBeFinal) {
            $phpcsFile->addError(
                'Class "%s" may not be final',
                $stackPtr,
                'FinalClassDisallowed',
                [$className]
            );
        }
    }
}
