<?php
namespace InterNations\Tests\Sniffs\Syntax;

use InterNations\Tests\Sniffs\AbstractTestCase;

class RestViewSniffTest extends AbstractTestCase
{
    public function testInvalidMultiActionsController(): void
    {
        $file = __DIR__ . '/Fixtures/RestView/InvalidMultiActionsController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(4, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method indexAction',
            '..InterNations\Sniffs\Syntax\RestView.RestAnnotationError',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method getAction',
            '..InterNations\Sniffs\Syntax\RestView.RestAnnotationError',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method putAction',
            '..InterNations\Sniffs\Syntax\RestView.RestAnnotationError',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method deleteAction',
            '..InterNations\Sniffs\Syntax\RestView.RestAnnotationError',
            5
        );
    }

    public function testInvalidOneActionController(): void
    {
        $file = __DIR__ . '/Fixtures/RestView/InvalidOneActionController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method indexAction',
            '..InterNations\Sniffs\Syntax\RestView.RestAnnotationError',
            5
        );
    }

    public function testValidMultiActionsController(): void
    {
        $file = __DIR__ . '/Fixtures/RestView/ValidMultiActionsController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testValidOneActionController(): void
    {
        $file = __DIR__ . '/Fixtures/RestView/ValidOneActionController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testNonControllerClass(): void
    {
        $file = __DIR__ . '/Fixtures/RestView/NonControllerClass.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testNonApiClass(): void
    {
        $file = __DIR__ . '/Fixtures/RestView/NonApiController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}