<?php
namespace InterNations\Sniffs\Tests\ControlStructures;

use InterNations\Sniffs\Tests\AbstractTestCase;

class CatchUndefinedExceptionSniffTest extends AbstractTestCase
{
    public function testExistingExceptions(): void
    {
        $file = __DIR__ . '/Fixtures/ImportedExceptions.php';
        $errors = $this->analyze(['InterNations/Sniffs/ControlStructures/CatchUndefinedExceptionSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testAliasedExceptions(): void
    {
        $file = __DIR__ . '/Fixtures/AliasedExceptions.php';
        $errors = $this->analyze(['InterNations/Sniffs/ControlStructures/CatchUndefinedExceptionSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testUndefinedExceptions(): void
    {
        $file = __DIR__ . '/Fixtures/UndefinedExceptions.php';
        $errors = $this->analyze(['InterNations/Sniffs/ControlStructures/CatchUndefinedExceptionSniff'], [$file]);

        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Trying to catch an undefined exception. Please add use-statement for "UndefinedException"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Trying to catch an undefined exception. Please add use-statement for "UndefinedExceptionInterface"'
        );
    }
}
