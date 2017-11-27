<?php
namespace InterNations\Tests\Sniffs\Naming;

use InterNations\Tests\Sniffs\AbstractTestCase;

class BooleanSniffTest extends AbstractTestCase
{
    public function testBadIsser(): void
    {
        $file = __DIR__ . '/Fixtures/Bool/InvalidBooleans.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/BooleanSniff'], [$file]);

        $this->assertReportCount(6, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected boolean to be defined as "true", got "True"',
            '..InterNations\Sniffs\Naming\Boolean.InvalidBoolean',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected boolean to be defined as "true", got "TRUE"',
            '..InterNations\Sniffs\Naming\Boolean.InvalidBoolean',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected boolean to be defined as "true", got "TrUe"',
            '..InterNations\Sniffs\Naming\Boolean.InvalidBoolean',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected boolean to be defined as "false", got "False"',
            '..InterNations\Sniffs\Naming\Boolean.InvalidBoolean',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected boolean to be defined as "false", got "FALSE"',
            '..InterNations\Sniffs\Naming\Boolean.InvalidBoolean',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected boolean to be defined as "false", got "FaLsE"',
            '..InterNations\Sniffs\Naming\Boolean.InvalidBoolean',
            5
        );
    }
}
