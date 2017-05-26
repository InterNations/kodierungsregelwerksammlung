<?php

class ReturnTypeHint
{
    public function testNoReturnTypeHint()
    {
        return;
    }

    public function testNoWrongStyleTypeHint() : Attendance
    {
        return;
    }

    /** @return float $test */
    public function testWrongDocForArrayReturnTypeHing(): array
    {
        return;
    }

    public function testMissingDocForArrayReturnTypeHing(): array
    {
        return;
    }
}