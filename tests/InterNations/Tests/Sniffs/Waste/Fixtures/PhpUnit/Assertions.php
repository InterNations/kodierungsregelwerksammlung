<?php

$this->assertSame(1, preg_match('/^[abc]{2}$/', 'kslfjdsklj4654656abc'));

$this->assertSomething("something");

$this->assertSame(true, true);
static::assertSame(true, true);
self::assertSame(true, true);

$this->assertSame(false, false);
$this->assertSame(null, null);

$this->assertTrue(empty([]));
$this->assertFalse(empty([]));

$this->assertTrue(in_array('bar', $foo));
$this->assertFalse(in_array('bar', $foo));

$this->assertTrue(array_key_exists('bar', $foo));
$this->assertTrue(isset($foo));
$this->assertFalse(array_key_exists('bar', $foo));
$this->assertFalse(isset($foo));

$this->assertSame(1, count($foo));
$this->assertSame($var, count($foo));
$this->assertNotSame(1, count($foo));
$this->assertNotSame($var, count($foo));

$this->assertSame(Foo::class, get_class($foo));
$this->assertSame($var, get_class($foo));
$this->assertNotSame(Foo::class, get_class($foo));
$this->assertNotSame($var, get_class($foo));

$this->assertSame('integer', gettype($foo));
$this->assertSame($var, gettype($foo));
$this->assertNotSame('integer', gettype($foo));
$this->assertNotSame($var, gettype($foo));
