<?php
namespace InterNations\Tests\Sniffs\Architecture;

use InterNations\Tests\Sniffs\AbstractTestCase;

class ControllerMethodsConventionSniffTest extends AbstractTestCase
{
    public function testWebControllerConventions(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Controller/TestController.php';
        $errors = static::analyze(['InterNations/Sniffs/Architecture/ControllerMethodsConventionSniff'], [$file]);

        static::assertReportCount(1, 0, $errors, $file);
        static::assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in web controllers are limited to "deleteAction()", "editAction()", "getAction()", "headAction()", "indexAction()", "newAction()", "patchAction()", "postAction()", "putAction()" but "invalidAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies its own controller and should be extracted into its own controller.'
        );
    }

    public function testNonControllersAreIgnored(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Controller/Ignored.php';
        $errors = static::analyze(['InterNations/Sniffs/Architecture/ControllerMethodsConventionSniff'], [$file]);

        static::assertReportCount(0, 0, $errors, $file);
    }

    public function testApiControllerConventions(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Controller/Api/TestController.php';
        $errors = static::analyze(['InterNations/Sniffs/Architecture/ControllerMethodsConventionSniff'], [$file]);

        static::assertReportCount(3, 0, $errors, $file);
        static::assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in API controllers are limited to "deleteAction()", "getAction()", "headAction()", "indexAction()", "patchAction()", "postAction()", "putAction()" but "newAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies its own controller and should be extracted into its own controller.'
        );
        static::assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in API controllers are limited to "deleteAction()", "getAction()", "headAction()", "indexAction()", "patchAction()", "postAction()", "putAction()" but "editAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies its own controller and should be extracted into its own controller.'
        );
        static::assertReportContains(
            $errors,
            $file,
            'errors',
            'Public methods in API controllers are limited to "deleteAction()", "getAction()", "headAction()", "indexAction()", "patchAction()", "postAction()", "putAction()" but "invalidAction()" found. This is often a hint that you are dealing with a sub resource of the current resource that justifies its own controller and should be extracted into its own controller.'
        );
    }
}
