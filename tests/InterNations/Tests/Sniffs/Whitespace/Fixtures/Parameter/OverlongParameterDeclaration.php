<?php
class OverlongParameterDeclaration
{
    public function sameLine(
        $arg1, $arg2
    )
    {
    }

    public function variousLines(
        $arg1,
        array $arg2, stdClass $arg3,
        array $arg4 = array(), array $arg5 = [], stdClass $arg6 = null,
        callable $arg7 = null,
        $arg8
            = 'string'
    )
    {
    }

    public function shouldNotBeOnTheSameLine(
        $one,
        $two,
        $three,
        $four,
        $five,
        $six,
        $seven,
        $eight,
        $nine,
        $ten,
        $eleve
    )
    {
    }

    public function __construct(
        array $documentStruct,
        array $highlighting,
        EntityAdapterInterface $entityAdapter = null
    )
    {
    }
}
