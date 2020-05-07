<?php
$object
    ->expects($this->any())
    ->method('method');

$object
    ->expects(static::any())
    ->method('method');

$object
    ->expects(self::any())
    ->method('method');

$object->expects($this->any())->method('method');

$object
    ->expects($this->once())
    ->method('method');

$object
    ->method('method');
