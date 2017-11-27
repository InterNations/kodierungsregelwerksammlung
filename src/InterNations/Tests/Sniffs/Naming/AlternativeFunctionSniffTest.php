<?php
namespace InterNations\Tests\Sniffs\Naming;

use InterNations\Sniffs\Naming\AlternativeFunctionSniff;
use InterNations\Tests\Sniffs\AbstractTestCase;


class AlternativeFunctionSniffTest extends AbstractTestCase
{
    public static function provideAlternativeNames(): array
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
    public function testFunctionNames(string $method, ?string $alternative = null): void
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
            '..InterNations\Sniffs\Naming\AlternativeFunction.UseAlternative',
            5
        );
    }


    public function testEcho(): void
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "echo" is not allowed. Please remove it',
            '..InterNations\Sniffs\Naming\AlternativeFunction.UseAlternative',
            5
        );
    }

    public function testPrint(): void
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "print" is not allowed. Please remove it',
            '..InterNations\Sniffs\Naming\AlternativeFunction.UseAlternative',
            5
        );
    }

    public function testEval(): void
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "eval" is not allowed. Please remove it',
            '..InterNations\Sniffs\Naming\AlternativeFunction.UseAlternative',
            5
        );
    }
}
