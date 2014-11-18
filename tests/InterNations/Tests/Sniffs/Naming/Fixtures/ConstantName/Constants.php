<?php
use const Foo\Bar\BAZ;
use function Foo\funcName;

const lowercase_const = 1;

const UPPERCASE_CONST = 3;

define('lowercase_define', 1);

class Clazz
{
    const lowercase_const = 2;

    const UPPERCASE_CONST = 3;

    public function lalala()
    {
        Clazz::class;
    }
}

class EventType
{
    const onEventHappening = 'name';

    const beforeEvent = 'before';

    const afterEvent = 'after';

    const invalidName = 'invalid';

    const INVALID_CONSTANT_IN_EVENT_CLASS = null;
}

