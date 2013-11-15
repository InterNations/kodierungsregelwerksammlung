<?php
class OverlongInvocation
{
    public function correct()
    {
        $this->call();
        $this->call($param);
        OverlongInvocation::call($param);
        static::call($param);
        self::call($param);

        $this->call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6
        );
        static::call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6
        );
        self::call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6
        );
        OverlongInvocation::call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6
        );
        call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6
        );
    }

    public function simpleWrong()
    {
        $this->call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
    }

    public function dynamicInvocation()
    {
        $method = 'call';
        $this->$method(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
        $this->{$methodName}(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
    }

    public function staticInvocation()
    {
        static::call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
    }

    public function selfInvocation()
    {
        self::call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
    }

    public function classInvocation()
    {
        OverlongInvocation::call(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
    }

    public function dynamicStaticInvocation()
    {
        $method = 'call';
        static::$method(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
        self::$method(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
        OverlongInvocation::$method(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
        OverlongInvocation::{$methodName}(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
        static::{$methodName}(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
        self::{$methodName}(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
    }

    public function functionInvocation()
    {
        func(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );

        $functionName(
            $overlyLongArgumentName1,
            $overlyLongArgumentName2,
            $overlyLongArgumentName3,
            $overlyLongArgumentName4,
            $overlyLongArgumentName5,
            $overlyLongArgumentName6, $overlyLongArgumentName7
        );
    }
}
