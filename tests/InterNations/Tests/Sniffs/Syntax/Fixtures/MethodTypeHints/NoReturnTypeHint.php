<?php

class NoReturnTypeHint
{
    /**
     * @return string[] $attendance
     */
    public function __clone(): array
    {
        return;
    }
}