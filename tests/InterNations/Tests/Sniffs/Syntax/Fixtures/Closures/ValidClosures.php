<?php
$static = static function () {

};

$bound = function () {
    $this->something();
};

$closure = static function () use (
    $dependency1,
    $dependency2,
    $dependency3,
    $dependency4,
    $dependency5,
    $dependency6,
    $dependency7
) {

};
