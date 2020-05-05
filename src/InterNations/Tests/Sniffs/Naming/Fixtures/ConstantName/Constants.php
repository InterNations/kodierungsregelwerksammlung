<?php
use const Foo\Bar\BAZ;
use function Foo\funcName;

use Symfony\Component\HttpKernel\KernelEvents;

const lowercase_const = 1;

const UPPERCASE_CONST = 3;

define('lowercase_define', 1);

class Clazz
{
    const lowercase_const = 2;

    const UPPERCASE_CONST = 3;

    const … = "foo";

    public function lalala()
    {
        Clazz::class;
    }

    public function something()
    {
        return KernelEvents::RESPONSE;
    }
}

class SomethingEvents
{
    const onEventHappening = 'name';

    const beforeEvent = 'before';

    const afterEvent = 'after';

    const invalidName = 'invalid';

    const INVALID_CONSTANT_IN_EVENT_CLASS = null;

    const onBeforeInvalid = 'invalid';

    const onAfterInvalid = 'invalid';
}

