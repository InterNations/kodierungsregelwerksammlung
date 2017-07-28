<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_RestViewSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testInvalidMultiActionsController()
    {
        $file = __DIR__ . '/Fixtures/RestView/InvalidMultiActionsController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(4, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method indexAction',
            'InterNations.Syntax.RestView.RestAnnotationError',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method getAction',
            'InterNations.Syntax.RestView.RestAnnotationError',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method putAction',
            'InterNations.Syntax.RestView.RestAnnotationError',
            5
        );

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method deleteAction',
            'InterNations.Syntax.RestView.RestAnnotationError',
            5
        );
    }

    public function testInvalidOneActionController()
    {
        $file = __DIR__ . '/Fixtures/RestView/InvalidOneActionController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Missing @Rest\View annotation for method indexAction',
            'InterNations.Syntax.RestView.RestAnnotationError',
            5
        );
    }

    public function testValidMultiActionsController()
    {
        $file = __DIR__ . '/Fixtures/RestView/ValidMultiActionsController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testValidOneActionController()
    {
        $file = __DIR__ . '/Fixtures/RestView/ValidOneActionController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testNonControllerClass()
    {
        $file = __DIR__ . '/Fixtures/RestView/NonControllerClass.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testNonApiClass()
    {
        $file = __DIR__ . '/Fixtures/RestView/NonApiController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/RestViewSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}