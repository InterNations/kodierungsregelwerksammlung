<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_QueryParamSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testOneLineOk()
    {
        $file = __DIR__ . '/Fixtures/QueryParam/OneLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testOneLineMissingDesc()
    {
        $file = __DIR__ . '/Fixtures/QueryParam/OneLineMissingDescription.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV
        );
    }

    public function testOneLineMissingStrict()
    {
        $file = __DIR__ . '/Fixtures/QueryParam/OneLineMissingStrict.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV
        );
    }

    public function testMultiLineOk()
    {
        $file = __DIR__ . '/Fixtures/QueryParam/MultiLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testMultiLineMissing()
    {
        $file = __DIR__ . '/Fixtures/QueryParam/MultiLineMissing.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(2, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV
        );
    }
}
