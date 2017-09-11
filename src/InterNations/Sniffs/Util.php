<?php
namespace InterNations\Sniffs;

use PHP_CodeSniffer_File as CodeSnifferFile;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\ComposerSourceLocator;

final class Util
{
    public static function isController(CodeSnifferFile $file): bool
    {
        return strpos($file->getFilename(), '/Controller/') !== false
            && strpos($file->getFilename(), 'Controller.php') !== false;
    }

    public static function reflectClass(string $className): ReflectionClass
    {
        $composerPath = __DIR__ . '/../../../vendor/autoload.php';
        if (!file_exists($composerPath)) {
            $composerPath = 'vendor/autoload.php';
        }

        $classLoader = require $composerPath;

        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new ClassReflector(new ComposerSourceLocator($classLoader, $astLocator));

        return $reflector->reflect($className);
    }
}
