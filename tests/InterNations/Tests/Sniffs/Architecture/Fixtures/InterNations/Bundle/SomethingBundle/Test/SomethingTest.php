<?php
namespace InterNations\Bundle\SomethingBundle\Test;

class SomethingTest
{
    protected function setUp()
    {
        $this->roleExtension = new RoleExtension();
    }

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

    public function findSomethingX()
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

    private function findSomethingY()
    {
        return [];
    }

    protected function findSomethingZ()
    {
        return [];
    }
}
