<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_ShortListSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testShortListSyntax()
    {
        $file = __DIR__ . '/Fixtures/ShortList/InvalidList.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ShortListSyntaxSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
