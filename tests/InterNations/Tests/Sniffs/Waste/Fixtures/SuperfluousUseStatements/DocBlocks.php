<?php
namespace Test;

use Foo as ClassName1;
use Bar as ClassName2;
use ClassName3;
use ClassName4;
use ClassName5;
use ClassName6;
use ClassName7;
use ClassName8;
use ClassName9;
use ClassName10;
use ClassName11;
use ClassName12;
use ClassName13;
use ClassName14;
use ClassName15;
use ClassName16;
use ClassName17;
use ClassName18;
use ClassName19;
use ClassName20;
use ClassName21;
use ClassName22;
use ClassName23;
use ClassName24;
use ClassName25;
use ClassName26;
use ClassName27;
use ClassName28 as Unused;
use ClassName29;
use ClassName30;
use ClassName31;
use ClassName32;
use ClassName33;
use ClassName34 as AliasNs;
use PrefixClass;
use Prefix;

/**
 * @property-read ClassName1 readProperty1
 * @property-read ClassName2|ClassName3 readProperty2
 * @property-write ClassName4 writeProperty1
 * @property-write ClassName5|ClassName6 writeProperty2
 * @property ClassName7 property1
 * @property ClassName8|ClassName9 property2
 * @method ClassName10|ClassName11 virtualMethod1(ClassName12|ClassName13 $arg)
 * @method ClassName14 virtualMethod2(ClassName15 $arg)
 */
class DocBlock
{
    /**
     * @var ClassName16
     */
    private $property;

    /**
     * @var ClassName30|ClassName31[int]
     */
    private $listProperty;

    /**
     * @var ClassName32[]
     */
    private $property2;

    /**
     * @param ClassName17 $var
     * @return ClassName18
     */
    public function getSingle($var)
    {
    }

    /**
     * @param ClassName19|ClassName20 $arg
     * @return ClassName21|ClassName22
     * @throws ClassName29
     */
    public function getMultiple($arg)
    {
    }

    /**
     * @param ClassName23[]|ClassName24[] $arg
     * @return ClassName25[]|ClassName26[string]
     */
    public function getList(array $arg)
    {
        /** @var $arg ClassName33 */
        /** @var $arg AliasNs */
    }

    public function withPrefixClass(PrefixClass $c)
    {
    }
}
