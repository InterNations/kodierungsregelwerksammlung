<?php

class WrongTypeHint
{
    public function __call(Request $request, $test): array
    {
        return;
    }
}