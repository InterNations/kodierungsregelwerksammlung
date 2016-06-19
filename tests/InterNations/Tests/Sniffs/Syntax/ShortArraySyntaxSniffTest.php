<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_ShortArraySniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testOneLineOk()
    {
        $file = __DIR__ . '/Fixtures/ShortArray/Invalid.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ShortArraySyntaxSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
