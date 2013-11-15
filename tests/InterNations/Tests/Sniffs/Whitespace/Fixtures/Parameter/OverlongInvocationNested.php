<?php
func1(
    func2(
        $foo,
        $bar
    ), $baz
);

func2(
    func3(
        $foo,
        $bar
    ),
    $baz
);

func4(
    func5(
        $foo, $bar
    ),
    $baz
);


func6(
    func7($foo, $bar),
    $baz
);


func8(
    fun9($foo, $bar)
);


func10(
    func11(['foo', 'bar']),
    'bla'
);

func12(
    static function() {
        return '3';
    }, function foo() {
        return '2';
    }
)

func14(
    new ClassName('foo', 'bar'),
    [
        new ClassName(
            static function() {
                return 'hi';
            }, $foo
        )
    ]
);
