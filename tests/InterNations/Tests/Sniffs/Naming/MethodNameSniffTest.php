<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Naming_MethodNameSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testBadIsser()
    {
        $file = __DIR__ . '/Fixtures/MethodName/BadIsser.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/MethodNameSniff'], [$file]);

        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Method name "getIsSomething()" is not allowed. Use "isSomething()" or "hasSomething()" instead',
            'InterNations.Naming.MethodName.BadIsser',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Method name "doesSomething()" is not allowed. Use "isSomething()" or "hasSomething()" instead',
            'InterNations.Naming.MethodName.BadIsser',
            5
        );
    }

    public function testBadSetter()
    {
        $file = __DIR__ . '/Fixtures/MethodName/BadSetter.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/MethodNameSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Method name "setIsSomething()" is not allowed. Use "setSomething()" instead',
            'InterNations.Naming.MethodName.BadSetter',
            5
        );
    }

    public function testOtherFunctions()
    {
        $file = __DIR__ . '/Fixtures/MethodName/AnonymousFunction.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/MethodNameSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
