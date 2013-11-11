<?php
class SameLine {
    public function createFunction() {
        return static function() {
        };
    }
}

class TooManyNewlines

{
    public function createFunction()

    {
        return static function() {
        };
    }
}

interface SameLineInterface {
    public function testFoo();
}

interface TooManyNewlinesInterface

{
    public function testFoo();
}

trait SameLineTrait {
}

trait TooManyNewlinesTrait

{
}


class Valid
{
    public function test()
    {
    }
}

abstract class AbstractValid extends Valid
{
    abstract public function hello();
}

interface ValidInterface
{
    public function test();
}

trait ValidTrait
{
    public function test()
    {
    }
}
