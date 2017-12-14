<?php
namespace InterNations\Tests\Sniffs\Formatting;

use InterNations\Tests\Sniffs\AbstractTestCase;

class ExpressionSniffTest extends AbstractTestCase
{
    public function testSimpleInvocations(): void
    {
        $file = __DIR__ . '/Fixtures/SimpleInvocations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(6, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"call('argument');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$object->methodCall('argument');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$object->methodCall('argument', 'argument');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"StaticClass::methodCall('argument');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"return \$this->invoke('argument');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"invoke(123);\" should be in one line"
        );
    }

    public function testMultiLineInvocations(): void
    {
        $file = __DIR__ . '/Fixtures/MultiLineInvocations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testMultiLineInvocationsReturnType(): void
    {
        $file = __DIR__ . '/Fixtures/MultiLineInvocationReturnType.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expression "function methodCall4($arg = \'444444444444444444444444444444444\', '
            . '$arg2 = 555555555555555555555555555555555555): ?Clazz" should be in one line'
        );
    }

    public function testClosureInvocations(): void
    {
        $file = __DIR__ . '/Fixtures/ClosureInvocations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testArrayInvocations(): void
    {
        $file = __DIR__ . '/Fixtures/ArrayInvocations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(7, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$object->methodCall('argument', 'argument', [['foo', 'bar']]);\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$object->methodCall('argument', 'argument', [array('foo', 'bar')]);\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$object->methodCall(['argument']);\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$object->methodCall('argument', 'argument', [['foo', 'bar' => 'test', \$key => 'value', 'key' => \$value, \$value]]);\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$object->methodCall('argument', 'argument', [array('foo', 'bar')]);\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"functionCall('argument', 'argument', [array('foo', 'bar', 'baz')]);\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"functionCall(['foo' => true, 'bar' => false]);\" should be in one line"
        );
    }

    public function testComplexInvocations(): void
    {
        $file = __DIR__ . '/Fixtures/ComplexInvocations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(9, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"func(func(func(1, 2, ['required' => true])), 1.2, 'STRING');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"func(func(1, 2, ['required' => true])),\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$instance = new ClassName(['foo' => 'bar', 'member' => ClassName::class]);\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"functionCall('this', 'is', 'a', 'very', 'very', 'very', 'very', 'very', 'very', 'very', 'very', 'looong', 'invocation');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"parent::__construct('canBeOneLine');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"new self('canBeInOneLine');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"new static('canBeInOneLine');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"new Namespaced\\ClassName('canBeOneLine');\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"namespaced\\functionName('canBeOneLine');\" should be in one line"
        );
    }

    public function testDeclarations(): void
    {
        $file = __DIR__ . '/Fixtures/Declarations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"function test(\$argument1, \$argument2)\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"public function __construct(SomeClass \$foo)\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"public function createTest(array \$fakeVariable, int \$secondFakeArgument, " .
            "string \$thirdFakeArgument):Test\" should be in one line"
        );

    }

    public function testArrayAssignments(): void
    {
        $this->markTestSkipped('Skipped for now');
        $file = __DIR__ . '/Fixtures/ArrayAssignments.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"\$array = ['canBeOneLine'];\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"'foo6' => ['can be one line']\" should be in one line"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expression \"return ['canTotallyBeOneLine'];\" should be in one line"
        );
    }

    public function testStringConcatBug(): void
    {
        $file = __DIR__ . '/Fixtures/StringConcatBug.php';
        $errors = $this->analyze(['InterNations/Sniffs/Formatting/ExpressionFormattingSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
