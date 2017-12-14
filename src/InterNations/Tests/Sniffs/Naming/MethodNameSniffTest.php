<?php
namespace InterNations\Tests\Sniffs\Naming;

use InterNations\Tests\Sniffs\AbstractTestCase;

class MethodNameSniffTest extends AbstractTestCase
{
    public function testBadIsser(): void
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

    public function testBadSetter(): void
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

    public function testOtherFunctions(): void
    {
        $file = __DIR__ . '/Fixtures/MethodName/AnonymousFunction.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/MethodNameSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
