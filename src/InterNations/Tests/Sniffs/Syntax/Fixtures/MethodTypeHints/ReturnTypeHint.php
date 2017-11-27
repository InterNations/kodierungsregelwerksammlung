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
    public function testWrongDocForArrayReturnTypeHint(): array
    {
        return;
    }

    public function testMissingDocForArrayReturnTypeHint(): array
    {
        return;
    }
}