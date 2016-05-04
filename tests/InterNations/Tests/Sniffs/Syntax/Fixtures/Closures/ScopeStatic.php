<?php
$c1 = static function () {
    return $this;
};

$c2 = static function () {
    return static::foo();
};

$c3 = static function () {
    return self::foo();
};
