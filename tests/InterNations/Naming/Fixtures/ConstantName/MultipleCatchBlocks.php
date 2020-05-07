<?php
use const Foo\Bar\BAZ;
use function Foo\funcName;


class Clazz
{
    public function lalala()
    {
        try {
            // Try block
        } catch (FirstException | SecondException $e) {
           // Multiple catch block
        }
    }
}

