<?php

class NoReturnTypeHint
{
    /**
     * @return array[] $attendance
     */
    public function __clone(): array
    {
        return;
    }
}