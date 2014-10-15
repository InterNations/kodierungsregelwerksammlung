<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Architecture_FormTypeConventionSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testInvalidFormName()
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Form/InvalidForm.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/FormTypeConventionSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Form types are expected to be named "<Something>FormType", "InvalidForm" found'
        );
    }

    public function testValidFormName()
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Form/ValidFormType.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/FormTypeConventionSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
