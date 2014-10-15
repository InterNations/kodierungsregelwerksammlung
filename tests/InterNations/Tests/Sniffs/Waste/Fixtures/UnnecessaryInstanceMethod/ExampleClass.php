<?php
class ExampleClass
{
    public function isPublicInstanceMethodAndShouldNotBeStatic()
    {
        return static::filter('lalala');
    }

    public static function isStaticMethodAndShouldBeStatic($string)
    {
        return $string;
    }

    protected function isProtectedInstanceMethodButShouldBeStatic($string)
    {
        return static function () use ($string) {
            return $string;
        };
    }

    private function isPrivateInstanceMethodButShouldBeStatic($string)
    {
        return call_some_function($string);
    }

    protected function isProtectedInstanceMethodButShouldBeStatic2($string)
    {
        $func = static function () use ($string) {
            return $string;
        };
    }

    protected function isProtectedInstanceMethodAndShouldNotBeStatic($string)
    {
        return function () use ($string) {
            return $string;
        };
    }

    private function isPrivateInstanceMethodAndShouldNotBeStatic($string)
    {
        return $this->something($string);
    }

    abstract protected function test();
}
