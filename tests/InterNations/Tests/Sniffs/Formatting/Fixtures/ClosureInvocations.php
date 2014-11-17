<?php

higherOrderFuncton(
    static function (...$arguments) {
        return $arguments;
    }
);


$fn = function() {
    $this->foo(function() {
        return 1;
    });
};
