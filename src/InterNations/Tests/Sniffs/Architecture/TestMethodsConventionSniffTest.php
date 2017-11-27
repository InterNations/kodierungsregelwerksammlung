<?php
namespace InterNations\Tests\Sniffs\Architecture;

use InterNations\Tests\Sniffs\AbstractTestCase;

class TestMethodsConventionSniffTest extends AbstractTestCase
{
    public function testPhpunitTestsMethodsConventions(): void
    {
        $file = __DIR__ . '/Fixtures/InterNations/Bundle/PhpunitTestBundle/Test/PhpunitMethodConventionTest.php';
        $errors = $this->analyze(['InterNations/Sniffs/Architecture/TestMethodsConventionSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'All public methods in a PHPUnit test must either start with test* or be data providers. This is for to make sure, we are not accidentally skipping a test because for example a typo (tsetSomething()).'
        );
    }
}
