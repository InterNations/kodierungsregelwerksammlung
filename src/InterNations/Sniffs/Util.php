<?php
namespace InterNations\Sniffs;

use PHP_CodeSniffer_File as CodeSnifferFile;

final class Util
{
    public static function isController(CodeSnifferFile $file): bool
    {
        return strpos($file->getFilename(), '/Controller/') !== false
            && strpos($file->getFilename(), 'Controller.php') !== false;
    }
}
