<?php
namespace Foo\Bar;

use Foo\Bar\Superfluous;
use Foo\Bar\Baz\Needed;

class TestClass
{
    public function test()
    {
        return new Superfluous();
    }

    public function test2()
    {
        return new Needed();
    }
}
