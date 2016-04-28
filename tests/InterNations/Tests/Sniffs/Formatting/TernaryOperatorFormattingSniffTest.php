<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Formatting_TernaryOperatorFormattingSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testSingleLineTernary()
    {
        $file = __DIR__ . '/Fixtures/TernaryOperatorSingleLine.php';
        $errors = $this->analyze(
            ['InterNations/Sniffs/Formatting/TernaryOperatorFormattingSniff'],
            [$file]
        );

        $this->assertReportCount(7, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Ternary operator incorrectly formatted: expected true ? 1 : 0 got true ?1 : 0',
            'InterNations.Formatting.TernaryOperatorFormatting.SingleLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Ternary operator incorrectly formatted: expected true ? 1 : 0 got true? 1  :  0',
            'InterNations.Formatting.TernaryOperatorFormatting.SingleLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Ternary operator incorrectly formatted: expected func() ? 1 : 0 got func()? 1  :  0',
            'InterNations.Formatting.TernaryOperatorFormatting.SingleLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Ternary operator incorrectly formatted: expected $this->method() ? 1 : 0 got $this->method()? 1 :0',
            'InterNations.Formatting.TernaryOperatorFormatting.SingleLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Ternary operator incorrectly formatted: expected func($this->method()) ? 1 : 0 got func($this->method())?1:0',
            'InterNations.Formatting.TernaryOperatorFormatting.SingleLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Ternary operator incorrectly formatted: expected $this->method($this->method()) ? 1 : 0 got $this->method($this->method())?1:0',
            'InterNations.Formatting.TernaryOperatorFormatting.SingleLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Ternary operator incorrectly formatted: expected $value ?: ($value = true) got $value ? :  ($value = true)',
            'InterNations.Formatting.TernaryOperatorFormatting.SingleLineTernaryOperator'
        );
    }

    public function testMultiLineTernary()
    {
        $file = __DIR__ . '/Fixtures/TernaryOperatorMultiLine.php';
        $errors = $this->analyze(
            ['InterNations/Sniffs/Formatting/TernaryOperatorFormattingSniff'],
            [$file]
        );

        $this->assertReportCount(3, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Ternary operator incorrectly formatted: expected invalid1() ?\n    \$trueValue :\n    \$falseValue got invalid1()\n    ? \$trueValue\n    : \$falseValue",
            'InterNations.Formatting.TernaryOperatorFormatting.MultiLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Ternary operator incorrectly formatted: expected invalid2() ?\n        \$trueValue :\n        \$falseValue got invalid2()\n        ? \$trueValue\n        : \$falseValue",
            'InterNations.Formatting.TernaryOperatorFormatting.MultiLineTernaryOperator'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Nested ternary not allowed',
            'InterNations.Formatting.TernaryOperatorFormatting.NestedTernary'
        );
    }
}
