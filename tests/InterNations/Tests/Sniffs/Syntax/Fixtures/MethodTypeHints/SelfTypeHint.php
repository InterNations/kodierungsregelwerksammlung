<?php

class SelfTypeHint
{
    public static function x(int $x): self
    {
        return new static($x);
    }

    public static function Y(int $y): SelfTypeHint
    {
        return new static($y);
    }
}