<?php
namespace InterNations\Tests\Sniffs\Architecture;

use InterNations\Tests\Sniffs\AbstractTestCase;

class IntegrationTestsConventionSniffTest extends AbstractTestCase
{
    public function testPhpunitIntegrationTestsMethodsConventionsGroupIntegrationAvailable(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/PhpunitTestBundle/Test/Integration/GroupAnnotationIntegrationTest.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/IntegrationTestsConventionSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'All the integration tests must have @group integration annotation'
        );

    }

    public function testPhpunitIntegrationTestsMethodsConventionsGroupIntegrationNotAvailable(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/PhpunitTestBundle/Test/Integration/IntegrationTestForGroupAnnotationAvailableTest.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/IntegrationTestsConventionSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'All the integration tests name should end with *IntegrationTest.php'
        );
    }
}
