<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_ControlStructures_CatchUndefinedExceptionSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testExistingExceptions()
    {
        $file = __DIR__ . '/Fixtures/ImportedExceptions.php';
        $errors = $this->analyze(['InterNations/Sniffs/ControlStructures/CatchUndefinedExceptionSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testAliasedExceptions()
    {
        $file = __DIR__ . '/Fixtures/AliasedExceptions.php';
        $errors = $this->analyze(['InterNations/Sniffs/ControlStructures/CatchUndefinedExceptionSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testUndefinedExceptions()
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
