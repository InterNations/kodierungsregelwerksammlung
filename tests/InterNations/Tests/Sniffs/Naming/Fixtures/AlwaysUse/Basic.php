<?php
namespace Foo;

use Legacy_Use;

class Foo extends Legacy_Extend implements Legacy_Implements
{
    public function method(Legacy_TypeHint $typeHint)
    {
    }

    public function method2()
    {
        new Legacy_New();

        $foo instanceof Legacy_InstanceOf;

        $fn = function (Legacy_TypeHintClosure $arg) {
        };

        Legacy_Call::method();
    }
}
