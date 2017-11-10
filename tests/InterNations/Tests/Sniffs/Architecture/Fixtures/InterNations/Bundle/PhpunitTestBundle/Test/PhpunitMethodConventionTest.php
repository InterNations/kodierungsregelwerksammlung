<?php
namespace InterNations\Bundle\PhpunitTestBundle\Test;

class PhpunitMethodConventionTest
{
    /**
     * @dataProvider providerX
     * @param $setter
     */
    public function testX()
    {
        $this->assertTrue(true);
    }

    public function providerX()
    {
        return [];
    }

    public function findX()
    {
        return [];
    }

    /** @dataProvider providerY */
    public function testY()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider providerZ
     */
    public function testZ()
    {
        $this->assertTrue(true);
    }

    public function providerY()
    {
        return [];
    }

    public function providerZ()
    {
        return [];
    }
}
