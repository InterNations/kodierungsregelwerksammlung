<?php
func1([$foo]);

func2(
    [
        $foo,
        $bar
    ]
);

func3(
    array(
        $foo,
        $bar
    )
);

func4(
    array(
        $foo,
        $bar
    ), $baz
);

func5(
    $foo,
    [
        $bar
    ], $baz
);

func6(
    $foo,
    [
        $bar
    ],

    $baz
);

class ClassName
{
    public function returnValue()
    {
        return func3(
            $this->method()
                ->fluent()
                ->shiny()
        );
    }
}
