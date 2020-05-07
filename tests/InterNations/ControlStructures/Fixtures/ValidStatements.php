<?php
namespace MyNamespace;

use Exceptions\ExistingException;
use Exceptions\ExistingExceptionInterface;

class EmptyStatements
{
    public function validTryAndCatch()
    {
        try {
            $x = $a - $b;
        } catch (ExistingException $e) {
        }
    }

    public function validIfAndElse()
    {
        if (true) {
            $a = 'a';
        } elseif {
            $a = 'b';
        } else {
            $a = 'c';
        }
    }

    public function validforAndForeach()
    {
        for($x = 0, $x > 4, $x++) {
            echo $x;
        }

        foreach($numbers as $number) {
            echo $number;
        }
    }

    public function validSwitch()
    {
        switch ($i) {
            case 0:
                echo "i equals 0";
                break;
            case 1:
                echo "i equals 1";
                break;
            case 2:
                echo "i equals 2";
                break;
        }
    }
}
