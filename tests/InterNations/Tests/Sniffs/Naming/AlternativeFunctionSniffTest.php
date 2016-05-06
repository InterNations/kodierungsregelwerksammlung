<?php
use InterNations\Sniffs\Naming\AlternativeFunctionSniff;

require_once __DIR__ . '/../AbstractTestCase.php';
require_once __DIR__ . '/../../../../../src/InterNations/Sniffs/Naming/AlternativeFunctionSniff.php';

class InterNations_Tests_Sniffs_Naming_AlternativeFunctionSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public static function provideAlternativeNames()
    {
        $arguments = [];

        foreach (AlternativeFunctionSniff::$alternatives as $function => $alternative) {
            $arguments[$function] = [$function, $alternative];
        }

        return $arguments;
   }

    /**
     * @param string $method
     * @param string|null $alternative
     * @dataProvider provideAlternativeNames
     */
    public function testFunctionNames($method, $alternative = null)
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);

        $message = $alternative
            ? 'Function "' . $method . '()" is not allowed. Use "' . $alternative . '" instead'
            : 'Function "' . $method . '()" is not allowed. Please remove it';

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            $message,
            'InterNations.Naming.AlternativeFunction.UseAlternative',
            5
        );
    }


    public function testEcho()
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "echo" is not allowed. Please remove it',
            'InterNations.Naming.AlternativeFunction.UseAlternative',
            5
        );
    }

    public function testPrint()
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "print" is not allowed. Please remove it',
            'InterNations.Naming.AlternativeFunction.UseAlternative',
            5
        );
    }
    public function testEval()
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "eval" is not allowed. Please remove it',
            'InterNations.Naming.AlternativeFunction.UseAlternative',
            5
        );
    }
}
