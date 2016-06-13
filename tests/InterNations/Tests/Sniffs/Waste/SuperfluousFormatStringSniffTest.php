<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Waste_SuperfluousFormatStringSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testSuperfluousFormatStrings()
    {
        $file = __DIR__ . '/Fixtures/SuperfluousFormatString/Functions.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousFormatStringSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous printf() call as no parameters are passed. Use plain "echo …;" instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous sprintf() call as no parameters are passed. You can safely remove the function call'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous sprintf() call as no parameters are passed. You can safely remove the function call'
        );
    }
}
