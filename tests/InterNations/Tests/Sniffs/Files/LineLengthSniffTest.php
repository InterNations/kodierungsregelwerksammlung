<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Formatting_LineLengthSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testLineLengthCheck()
    {
        $file = __DIR__ . '/Fixtures/LineLength/LineLength.php';
        $errors = $this->analyze(['InterNations/Sniffs/Files/LineLengthSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
