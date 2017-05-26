<?php

class missingArgument
{
    public function __isset(): bool
    {
        parent::__construct();
    }

    /**
     * @param string[] $x
     */
    public function __construct(array $x)
    {
        parent::__construct();
    }
}