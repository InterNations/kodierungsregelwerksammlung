<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Waste_PhpUnitAssertionsSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testAssertions()
    {
        $file = __DIR__ . '/Fixtures/PhpUnit/Assertions.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/PhpUnitAssertionsSniff'], [$file]);

        $this->assertReportCount(25, 0, $errors, $file);

        // Exists three times
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
            'There is a better alternative for the assertion. Use "assertContains()" instead of "assertTrue(in_array(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotContains()" instead of "assertFalse(in_array(), …)"'
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
            'There is a better alternative for the assertion. Use "assertCount()" instead of "assertSame(1, count(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertCount()" instead of "assertSame($var, count(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotCount()" instead of "assertNotSame(1, count(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotCount()" instead of "assertNotSame($var, count(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertInstanceOf()" instead of "assertSame(Foo::class, get_class(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertInstanceOf()" instead of "assertSame($var, get_class(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotInstanceOf()" instead of "assertNotSame(Foo::class, get_class(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotInstanceOf()" instead of "assertNotSame($var, get_class(), …)"'
        );


        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertInternalType()" instead of "assertSame(\'integer\', gettype(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertInternalType()" instead of "assertSame($var, gettype(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotInternalType()" instead of "assertNotSame(\'integer\', gettype(), …)"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'There is a better alternative for the assertion. Use "assertNotInternalType()" instead of "assertNotSame($var, gettype(), …)"'
        );
    }
}
