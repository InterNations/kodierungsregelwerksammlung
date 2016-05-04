<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Waste_PhpUnitAssertionsSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testAssertions()
    {
        $file = __DIR__ . '/Fixtures/PhpUnit/Assertions.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/PhpUnitAssertionsSniff'], [$file]);

        $this->assertReportCount(13, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertTrue()" instead of "assertSame(true, …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertFalse()" instead of "assertSame(false, …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNull()" instead of "assertSame(null, …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertEmpty()" instead of "assertTrue(empty(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotEmpty()" instead of "assertFalse(empty(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertArrayHasKey()" instead of "assertTrue(isset(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertArrayHasKey()" instead of "assertTrue(array_key_exists(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertArrayNotHasKey()" instead of "assertFalse(isset(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertArrayNotHasKey()" instead of "assertFalse(array_key_exists(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertContains()" instead of "assertTrue(in_array(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotContains()" instead of "assertFalse(in_array(), …)"'
        );
    }
}
