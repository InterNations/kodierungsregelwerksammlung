<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Waste_PhpUnitStaticallyCalledMethodsSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testPhpUnitStaticallyCalledMethods()
    {
        $fixture = __DIR__ . '/Fixtures/PhpUnit/PhpUnitStaticallyCalledMethodsSniffFixtureTest.php';
        $errors = self::analyze(['InterNations/Sniffs/Waste/PhpUnitStaticallyCalledMethodsSniff'], [$fixture]);

        self::assertReportCount(2, 0, $errors, $fixture);
        self::assertReportContains(
            $errors,
            $fixture,
            'errors',
            'Call PHPUnit methods statically, replace $this->assertNull() with self::assertNull()',
            'InterNations.Waste.PhpUnitStaticallyCalledMethods.StaticallyCallPhpUnitMethods',
            5
        );

        self::assertReportContains(
            $errors,
            $fixture,
            'errors',
            'Call PHPUnit methods statically, replace static::assertNull() with self::assertNull()',
            'InterNations.Waste.PhpUnitStaticallyCalledMethods.StaticallyCallPhpUnitMethods',
            5
        );
    }
}
