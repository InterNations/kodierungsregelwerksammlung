<?php
namespace InterNations\Sniffs;

use PHP_CodeSniffer\Files\File;

final class Util
{
    public static function isController(File $file): bool
    {
        return strpos($file->getFilename(), '/Controller/') !== false
            && strpos($file->getFilename(), 'Controller.php') !== false;
    }
}
