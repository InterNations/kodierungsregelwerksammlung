<?php
namespace InterNations\Tests\Sniffs\Syntax;

use InterNations\Tests\Sniffs\AbstractTestCase;

class RequestParamSniffTest extends AbstractTestCase
{
    public function testOneLineOk(): void
    {
        $file = __DIR__ . '/Fixtures/RequestParam/OneLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RequestParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testOneLineMissingDesc(): void
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
            5
        );
    }

    public function testMultiLineOk(): void
    {
        $file = __DIR__ . '/Fixtures/RequestParam/MultiLineOk.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RequestParamSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testMultiLineMissing(): void
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
            5
        );
    }
}
