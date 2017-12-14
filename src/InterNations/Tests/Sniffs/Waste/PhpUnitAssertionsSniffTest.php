<?php
namespace InterNations\Tests\Sniffs\Waste;

use InterNations\Tests\Sniffs\AbstractTestCase;

class PhpUnitAssertionsSniffTest extends AbstractTestCase
{
    public function testAssertions(): void
    {
        $file = __DIR__ . '/Fixtures/PhpUnit/Assertions.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/PhpUnitAssertionsSniff'], [$file]);

        $this->assertReportCount(26, 0, $errors, $file);

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
            'There is a better alternative for the assertion. Use "assertRegExp()" instead of "assertSame(1, preg_match(), …)"'
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
