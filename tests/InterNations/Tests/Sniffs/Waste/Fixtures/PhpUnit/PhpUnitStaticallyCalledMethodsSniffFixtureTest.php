<?php
namespace Foo;

/**
 * Fixture for PhpUnitStaticallyCalledMethodsSniff / PhpUnitStaticallyCalledMethodsSniffTest .
 * The class name needs to end in "Test(Case)" to trigger the Sniff
 */
class PhpUnitStaticallyCalledMethodsSniffFixtureTest
{
    public function foo()
    {
        $this->assertNull(null);
        static::assertNull(null);
        parent::assertNull(null);
        self::assertNull(null);
    }
}
