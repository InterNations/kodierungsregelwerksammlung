<?php

valid2(
    valid2() ?
        $trueValue :
        $falseValue
);

$valid1 = valid1() ?
    $trueValue :
    $falseValue;


$valid3 = functionExpression() ?
    $trueValue :
    $falseValue;


$valid4 = true
    ?: false;

$valid5 = $value
    ?: ($value = $foo);

$invalid1 = invalid1()
    ? $trueValue
    : $falseValue;

invalid2(
    invalid2()
        ? $trueValue
        : $falseValue
);

$invalid3 = true ?
    true ?
        true :
        false :
    false;

