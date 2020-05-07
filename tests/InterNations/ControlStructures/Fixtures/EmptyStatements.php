<?php
namespace MyNamespace;

use Exceptions\ExistingException;
use Exceptions\ExistingExceptionInterface;

class EmptyStatements
{
    public function emptyTryAndCatch()
    {
        try {
        } catch (ExistingException $e) {
        }
    }

    public function emptyIfAndElse()
    {
        if (true) {

        } elseif {

        } else {

        }
    }

    public function emptyforAndForeach()
    {
        for($x = 0, $x > 4, $x++) {

        }

        foreach($numbers as $number) {

        }
    }
}
