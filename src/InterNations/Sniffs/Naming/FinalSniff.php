<?php
namespace InterNations\Sniffs\Naming;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class FinalSniff implements CodeSnifferSniff
{
    public function register()
    {
        return [T_CLASS];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        $isFinal = (bool) $file->findPrevious(T_FINAL, $stackPtr - 1, $stackPtr - 3);
        $className = $file->getDeclarationName($stackPtr);

        $methods = 0;
        $staticMethods = 0;
        $methodPtr = $stackPtr;

        while ($methodPtr = $file->findNext(T_FUNCTION, $methodPtr + 1)) {
            $methods++;

            if ($file->findPrevious(T_STATIC, $methodPtr - 1, $methodPtr - 3)) {
                $staticMethods++;
            }
        }

        $properties = 0;
        $staticProperties = 0;
        $propertyPtr = $stackPtr;

        while ($propertyPtr = $file->findNext(T_VARIABLE, $propertyPtr + 1)) {

            if ($file->findPrevious([T_PUBLIC, T_PROTECTED, T_PRIVATE], $propertyPtr - 1, $propertyPtr - 3)) {
                $properties++;

                if ($file->findPrevious(T_STATIC, $propertyPtr - 1, $propertyPtr - 3)) {
                    $staticProperties++;
                }
            }
        }

        $extendsClass = (bool) $file->findNext(T_EXTENDS, $stackPtr + 1);
        $implementsInterface = (bool) $file->findNext(T_INTERFACE, $stackPtr + 1);
        $shouldBeFinal = ($methods === $staticMethods
                        && $properties === $staticProperties
                        && !$extendsClass
                        && !$implementsInterface)
                        || $className === 'EventType';

        if (!$isFinal && $shouldBeFinal && $methods === 0) {
            $file->addError('Enum "%s" must be final', $stackPtr, 'FinalClassEnum', [$className]);
        }

        if (!$isFinal && $shouldBeFinal && $methods === $staticMethods) {
            $file->addError('Static class "%s" must be final', $stackPtr, 'FinalClassStatic', [$className]);
        }

        if ($isFinal && !$shouldBeFinal) {
            $file->addError('Class "%s" may not be final', $stackPtr, 'FinalClassDisallowed', [$className]);
        }
    }
}
