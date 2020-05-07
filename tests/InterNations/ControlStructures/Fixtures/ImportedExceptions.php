<?php
namespace MyNamespace;

use Exceptions\ExistingException;
use Exceptions\ExistingExceptionInterface;

class ImportedExceptions
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
}
