<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Architecture_ControllerMethodsConventionSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testWebControllerConventions()
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

    public function testApiControllerConventions()
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
