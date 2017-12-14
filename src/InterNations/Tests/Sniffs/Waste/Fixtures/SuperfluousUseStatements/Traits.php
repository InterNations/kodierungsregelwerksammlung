<?php
use InvalidSymbol;
use ValidSymbol;

class TestClass
{
    use TestTrait;

    public function test()
    {
        return new ValidSymbol();
    }
}
