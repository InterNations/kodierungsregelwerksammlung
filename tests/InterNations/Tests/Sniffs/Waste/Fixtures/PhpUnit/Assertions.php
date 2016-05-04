<?php
$this->assertSomething("something");

$this->assertSame(true, true);
static::assertSame(true, true);
self::assertSame(true, true);

$this->assertSame(false, false);
$this->assertSame(null, null);

$this->assertTrue(empty([]));

$this->assertFalse(empty([]));

$this->assertTrue(isset($foo));
$this->assertTrue(array_key_exists('bar', $foo));

$this->assertFalse(isset($foo));
$this->assertFalse(array_key_exists('bar', $foo));

$this->assertTrue(in_array('bar', $foo));
$this->assertFalse(in_array('bar', $foo));
