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


    public function testMissingDocForArrayReturnTypeHing(): array
    {
        return;
    }
}