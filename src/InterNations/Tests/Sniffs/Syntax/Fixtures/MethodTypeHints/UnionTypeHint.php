<?php

class MixedTypeHint
{
    /**
     * @param string|null $var
     * @return string|null
     */
    public function makesNoSense($var)
    {}

    /**
     * @param SomeInterface|string $var
     * @return SomeInterface|string
     */
    public function makesSense($var)
    {
    }
}
