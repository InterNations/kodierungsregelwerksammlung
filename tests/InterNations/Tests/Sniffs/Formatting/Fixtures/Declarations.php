<?php
function test(
    $argument1,
    $argument2
)
{

}

class Test
{
    public function __construct(
        SomeClass $foo
    )
    {

    }
}

class TestTwo
{
    public function createTest(
        array $fakeVariable,
        int $secondFakeArgument,
        string $thirdFakeArgument,
        bool $false
    ): Test
    {
        return new Test();
    }
}

class TestThree
{
    public function createTest(
        array $fakeVariable,
        int $secondFakeArgument,
        string $thirdFakeArgument
    ): Test
    {
        return new Test();
    }
}
