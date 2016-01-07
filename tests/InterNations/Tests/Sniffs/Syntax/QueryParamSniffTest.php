<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_QueryParamSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testInvalidQueryParams()
    {
        $file = __DIR__ . '/Fixtures/QueryParam/QueryParams.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(10, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            14
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            14
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            19
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            19
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            24
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            24
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            29
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            34
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            49
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            'InterNations.Syntax.QueryParam.QueryParameterAttributeMissing',
            PHPCS_DEFAULT_ERROR_SEV,
            49
        );
    }
}
