<?php
namespace InterNations\Tests\Sniffs\Naming;

use InterNations\Tests\Sniffs\AbstractTestCase;

class AliasUseLegacyClassSniffTest extends AbstractTestCase
{
    public function testLegacyClassUseStatements(): void
    {
        $file = __DIR__ . '/Fixtures/LegacyClass/Simple.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AliasUseLegacyClassSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Fix the alias "Invalid_Legacy_Class" to be UpperCamelCased or fix the target symbol name "Invalid_Legacy_Class"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Create an UpperCamelCased alias for symbol "Foo_Bar" or fix the target symbol name "Some\\FullyQualified"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Fix the alias "Another_Invalid_ClassName" to be UpperCamelCased or fix the target symbol name "Another_Invalid_ClassName"'
        );
    }

    public function testLegacyClassWithUseFunctionUseConst(): void
    {
        $file = __DIR__ . '/Fixtures/LegacyClass/ComplexUse.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AliasUseLegacyClassSniff'], [$file]);

        $this->assertReportCount(6, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Fix the alias "invalidFunctionName" to be lower_case underscore separated or fix the target symbol name "foobar\\invalidFunctionName"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Create a lower_case underscore separated alias for symbol "invalidFunctionName" or fix the target symbol name "foobar\\valid_function_name"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Create a lower_case underscore separated alias for symbol "INVALID" or fix the target symbol name "foobar\\valid_function_name"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Fix the alias "invalid_constant" to be UPPER_CASE underscore separated or fix the target symbol name "FooBar\\invalid_constant"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Fix the alias "invalidconstant" to be UPPER_CASE underscore separated or fix the target symbol name "FooBar\\invalidconstant"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Create an UPPER_CASE underscore separated alias for symbol "constant_name" or fix the target symbol name "FooBar\\CONSTANT_NAME"'
        );
    }

    public function testLegacyClassFalsePositives(): void
    {
        $file = __DIR__ . '/Fixtures/LegacyClass/FalsePositives.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AliasUseLegacyClassSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
