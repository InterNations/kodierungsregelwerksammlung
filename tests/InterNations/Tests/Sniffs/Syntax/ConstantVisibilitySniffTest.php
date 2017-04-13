<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_ConstantVisibilitySniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testValidConstantVisibility()
    {
        $file = __DIR__ . '/Fixtures/ConstantVisibility/ValidConstants.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ConstantVisibilitySniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testInvalidConstantVisibility()
    {
        $file = __DIR__ . '/Fixtures/ConstantVisibility/InvalidConstants.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ConstantVisibilitySniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
