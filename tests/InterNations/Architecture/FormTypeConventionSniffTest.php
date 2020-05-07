<?php
namespace InterNations\Sniffs\Tests\Architecture;

use InterNations\Sniffs\Tests\AbstractTestCase;

class FormTypeConventionSniffTest extends AbstractTestCase
{
    public function testInvalidFormName(): void
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

    public function testValidFormName(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/SomethingBundle/Form/ValidFormType.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/FormTypeConventionSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
