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

    /** @dataProvider provideY */
    public function testY()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider provideZ
     */
    public function testZ()
    {
        $this->assertTrue(true);
    }

    private function findY()
    {
        return [];
    }

    protected function findZ()
    {
        return [];
    }
}
