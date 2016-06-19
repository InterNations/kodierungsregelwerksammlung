<?php
class ExceptionTest extends PHPUnit_Framework_TestCase
{
    /** @expectedException Exception */
    public function testException()
    {
        throw new Exception();
    }

    /**
     * @expectedExceptionCode 123
     * @expectedException Exception
     * @expectedExceptionMessage Test
     */
    public function testAnotherException()
    {
        throw new Exception('Test', 123);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionCode 123
     */
    public function testYetAnotherException()
    {
        throw new Exception('Test', 123);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessageRegExp (This|That)
     * @expectedExceptionCode 123
     */
    public function testAndYetAnotherException()
    {
        throw new Exception('This', 123);
    }
}
