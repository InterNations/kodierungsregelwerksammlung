<?php

class InvalidReturnTypeHints

{
    public function createFunction(string $x, float ...$test)
    {
        echo $x . ' ' . $y;
    }
}