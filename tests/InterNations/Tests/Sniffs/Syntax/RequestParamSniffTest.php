<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_RequestParamSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testOneLineOk()
    {
        $file = __DIR__ . '/Fixtures/RequestParam/OneLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RequestParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testOneLineMissingDesc()
    {
        $file = __DIR__ . '/Fixtures/RequestParam/OneLineMissingDescription.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RequestParamSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for RequestParam',
            'InterNations.Syntax.RequestParam.RequestParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV
        );
    }

    public function testMultiLineOk()
    {
        $file = __DIR__ . '/Fixtures/RequestParam/MultiLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RequestParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testMultiLineMissing()
    {
        $file = __DIR__ . '/Fixtures/RequestParam/MultiLineMissing.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RequestParamSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for RequestParam',
            'InterNations.Syntax.RequestParam.RequestParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV
        );
    }
}
