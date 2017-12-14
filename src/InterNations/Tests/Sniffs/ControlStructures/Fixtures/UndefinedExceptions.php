<?php
namespace MyNamespace;

use Exceptions\ExistingException;
use Exceptions\ExistingExceptionInterface;

class UndefinedExceptions
{
    public function catchExistingException()
    {
        try {
            ; // Something might throw an exception
        } catch (ExistingException $e) {
        }
    }

    public function catchExistingExceptionInterface()
    {
        try {
            ; // Something might throw an exception
        } catch (ExistingExceptionInterface $e) {
        }
    }

    public function catchUndefinedException()
    {
        try {
            ; // Something might throw an exception
        } catch (UndefinedException $e) {
        }
    }

    public function catchUndefinedExceptionInterface()
    {
        try {
            ; // Something might throw an exception
        } catch (UndefinedExceptionInterface $e) {
        }
    }
}
