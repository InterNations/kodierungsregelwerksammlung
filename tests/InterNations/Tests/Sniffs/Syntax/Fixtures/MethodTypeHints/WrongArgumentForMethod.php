<?php

class WrongArgumentForMethod

{
    public function __clone(Request $request)
    {
        echo 'blah..';
    }
}