<?php
namespace InterNations\Bundle\SomethingBundle\Test;

class SomethingTest
{
    /**
     * @dataProvider provideSomethingX
     * @param $setter
     */
    public function testSomethingX()
    {
        $this->assertTrue(true);
    }

    public function provideSomethingX()
    {
        return [];
    }

    public function findSomething()
    {
        return [];
    }

    /** @dataProvider provideSomethingY */
    public function testSomethingY()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider provideSomethingZ
     */
    public function testSomethingZ()
    {
        $this->assertTrue(true);
    }
}
