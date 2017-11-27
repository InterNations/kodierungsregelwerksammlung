<?php
namespace InterNations\Tests\Sniffs\Waste;

use InterNations\Tests\Sniffs\AbstractTestCase;

class PhpUnitStaticallyCalledMethodsSniffTest extends AbstractTestCase
{
    public function testPhpUnitStaticallyCalledMethods(): void
    {
        $fixture = __DIR__ . '/Fixtures/PhpUnit/PhpUnitStaticallyCalledMethodsSniffFixtureTest.php';
        $errors = self::analyze(['InterNations/Sniffs/Waste/PhpUnitStaticallyCalledMethodsSniff'], [$fixture]);

        self::assertReportCount(2, 0, $errors, $fixture);
        self::assertReportContains(
            $errors,
            $fixture,
            'errors',
            'Call PHPUnit methods statically, replace $this->assertNull() with self::assertNull()',
            '..InterNations\Sniffs\Waste\PhpUnitStaticallyCalledMethods.StaticallyCallPhpUnitMethods',
            5
        );

        self::assertReportContains(
            $errors,
            $fixture,
            'errors',
            'Call PHPUnit methods statically, replace static::assertNull() with self::assertNull()',
            '..InterNations\Sniffs\Waste\PhpUnitStaticallyCalledMethods.StaticallyCallPhpUnitMethods',
            5
        );
    }
}
