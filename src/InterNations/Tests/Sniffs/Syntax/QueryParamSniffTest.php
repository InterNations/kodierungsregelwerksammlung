<?php
namespace InterNations\Tests\Sniffs\Syntax;

use InterNations\Tests\Sniffs\AbstractTestCase;

class QueryParamSniffTest extends AbstractTestCase
{
    public function testOneLineOk(): void
    {
        $file = __DIR__ . '/Fixtures/QueryParam/OneLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testOneLineMissingDesc(): void
    {
        $file = __DIR__ . '/Fixtures/QueryParam/OneLineMissingDescription.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            '..InterNations\Sniffs\Syntax\QueryParam.QueryParameterAttributeMissing',
            5
        );
    }

    public function testOneLineMissingStrict(): void
    {
        $file = __DIR__ . '/Fixtures/QueryParam/OneLineMissingStrict.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            '..InterNations\Sniffs\Syntax\QueryParam.QueryParameterAttributeMissing',
            5
        );
    }

    public function testMultiLineOk(): void
    {
        $file = __DIR__ . '/Fixtures/QueryParam/MultiLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testMultiLineMissing(): void
    {
        $file = __DIR__ . '/Fixtures/QueryParam/MultiLineMissing.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/QueryParamSniff'], [$file]);

        $this->assertReportCount(2, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "description" is missing for QueryParam',
            '..InterNations\Sniffs\Syntax\QueryParam.QueryParameterAttributeMissing',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Attribute "strict" is missing for QueryParam',
            '..InterNations\Sniffs\Syntax\QueryParam.QueryParameterAttributeMissing',
            5
        );
    }
}
