<?php

class WrongArgumentForMethod
{
    public function __clone(Request $request)
    {
        echo 'blah..';
    }

    public function test(int $x string $y = null, float $z = null): void
    {
        echo 'blah..';
    }
}