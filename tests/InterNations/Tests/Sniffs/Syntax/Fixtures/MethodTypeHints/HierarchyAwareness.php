<?php
namespace InterNations\Tests\Sniffs\Syntax\Fixtures\MethodTypeHints;

class HierarchyAwareness extends IgnoredClass
{
    public function suppressedMethod()
    {
        parent::suppressedMethod();
    }

    public function isSomething()
    {
        return true;
    }
}
