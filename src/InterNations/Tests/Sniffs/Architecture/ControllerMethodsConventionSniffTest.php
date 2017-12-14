<?php
namespace InterNations\Tests\Sniffs\Architecture;

use InterNations\Tests\Sniffs\AbstractTestCase;

class ControllerMethodsConventionSniffTest extends AbstractTestCase
{
    public function testWebControllerConventions(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Controller/TestController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/ControllerMethodsConventionSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in web controllers are limited to "deleteAction()", "editAction()", "getAction()", "indexAction()", "newAction()", "patchAction()", "postAction()", "putAction()" but "invalidAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies it’s own controller and should be extracted into it’s own controller.'
        );
    }

    public function testNonControllersAreIgnored(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Controller/Ignored.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/ControllerMethodsConventionSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testApiControllerConventions(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Controller/Api/TestController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/ControllerMethodsConventionSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in API controllers are limited to "deleteAction()", "getAction()", "indexAction()", "patchAction()", "postAction()", "putAction()" but "newAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies it’s own controller and should be extracted into it’s own controller.'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in API controllers are limited to "deleteAction()", "getAction()", "indexAction()", "patchAction()", "postAction()", "putAction()" but "editAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies it’s own controller and should be extracted into it’s own controller.'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in API controllers are limited to "deleteAction()", "getAction()", "indexAction()", "patchAction()", "postAction()", "putAction()" but "invalidAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies it’s own controller and should be extracted into it’s own controller.'
        );
    }
}
