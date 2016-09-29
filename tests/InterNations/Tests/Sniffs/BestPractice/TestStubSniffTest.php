<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_BestPractice_TestStubSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testStubbingBestPracticesAreEnforced()
    {
        $file = __DIR__ . '/Fixtures/TestStub/Stub.php';
        $errors = $this->analyze(['InterNations/Sniffs/BestPractice/TestStubSniff'], [$file]);

        $this->assertReportCount(4, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            '"->expects($this->any())" is implied and does not need to be specified. Simply remove it.'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            '"->expects(static::any())" is implied and does not need to be specified. Simply remove it.'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            '"->expects(self::any())" is implied and does not need to be specified. Simply remove it.'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            '"->expects($this->any())" is implied and does not need to be specified. Simply remove it.'
        );
    }
}
