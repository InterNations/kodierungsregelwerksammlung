<?php
namespace MyNamespace;

use Exceptions\ExistingException as AliasException;
use Exceptions\ExistingExceptionInterface as AliasExceptionInterface;

class AliasedExceptions
{
    public function catchExistingException()
    {
        try {
            ; // Something might throw an exception
        } catch (AliasException $e) {
        }
    }

    public function catchExistingExceptionInterface()
    {
        try {
            ; // Something might throw an exception
        } catch (AliasExceptionInterface $e) {
        }
    }
}
