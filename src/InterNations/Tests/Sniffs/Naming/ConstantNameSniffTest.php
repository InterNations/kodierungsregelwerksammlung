<?php
namespace InterNations\Tests\Sniffs\Naming;

use InterNations\Tests\Sniffs\AbstractTestCase;

class ConstantNameSniffTest extends AbstractTestCase
{
    public function testConstants(): void
    {
        $file = __DIR__ . '/Fixtures/ConstantName/Constants.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/ConstantNameSniff'], [$file]);

        $this->assertReportCount(7, 0, $errors, $file);


        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Class constants must be uppercase; expected LOWERCASE_CONST but found lowercase_const'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Constants must be uppercase; expected 'LOWERCASE_DEFINE' but found 'lowercase_define'"
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Class constants for event types must be camelcase and start with "on", "before" or "after". Found INVALID_CONSTANT_IN_EVENT_CLASS'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Class constants must be uppercase; expected LOWERCASE_CONST but found lowercase_const'
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Class constants for event types must be camelcase and start with "on", "before" or "after". Found invalidName'
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Class constants for event types must be camelcase and start with "on", "before" or "after". Found onBeforeInvalid'
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Class constants for event types must be camelcase and start with "on", "before" or "after". Found onAfterInvalid'
        );
    }
}
