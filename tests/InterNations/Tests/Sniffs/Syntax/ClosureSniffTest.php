<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_ClosureSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testValidClosures()
    {
        $file = __DIR__ . '/Fixtures/Closures/ValidClosures.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ClosureSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testNoScopeUsageButNonStatic()
    {
        $file = __DIR__ . '/Fixtures/Closures/NoScopeNoStatic.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ClosureSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Closure does not reference $this, static:: or self::. Add "static" qualifier to the closure'
        );
    }

    public function testScopeUsageButStatic()
    {
        $file = __DIR__ . '/Fixtures/Closures/ScopeStatic.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ClosureSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Static closure references $this but static qualifier exists. Remove "static" qualifier from the closure'
        );
    }

    public function testInvalidFormatting()
    {
        $file = __DIR__ . '/Fixtures/Closures/InvalidFormatting.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ClosureSniff'], [$file]);

        $this->assertReportCount(6, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected "static function (...) use (...) {", found "static  function(...) use(...) {"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected "static function (...) use (...) {", found "static  function  (...) use(...) {"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected "static function (...) use (...) {", found "static  function (...)use(...) {"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected "static function (...) use (...) {", found "static  function (...)  use(...) {"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected "static function (...) use (...) {", found "static  function (...)  use(...) {"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            "Expected \"static function (...) use (...) {\", found \"static  function (...) use(...)\n{\""
        );
    }
}
