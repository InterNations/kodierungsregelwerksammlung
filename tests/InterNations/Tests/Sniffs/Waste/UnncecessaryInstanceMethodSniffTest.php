<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Waste_UnnecessaryInstanceMethodSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testUnnecessaryInstanceMethod()
    {
        $file = __DIR__ . '/Fixtures/UnnecessaryInstanceMethod/ExampleClass.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/UnnecessaryInstanceMethodSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Method ExampleClass::isProtectedInstanceMethodButShouldBeStatic() should be static as it does not access $this or indirectly use $this with an instance closure'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Method ExampleClass::isPrivateInstanceMethodButShouldBeStatic() should be static as it does not access $this or indirectly use $this with an instance closure'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Method ExampleClass::isProtectedInstanceMethodButShouldBeStatic2() should be static as it does not access $this or indirectly use $this with an instance closure'
        );
    }
}
