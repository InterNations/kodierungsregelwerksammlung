<?php
namespace InterNations\Sniffs\Architecture;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class ControllerMethodsConventionSniff implements CodeSnifferSniff
{
    private static $apiVerbs = ['index', 'get', 'post', 'put', 'patch', 'delete'];

    private static $webVerbs = ['new', 'edit'];

    private static $apiActions = [];

    private static $webActions = [];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        if (strpos($file->getFilename(), '/Controller/') === false) {
            return;
        }

        $tokens = $file->getTokens();
        $peakPtr = $file->findPrevious([T_WHITESPACE, T_STATIC], $stackPtr - 1, null, true);

        if ($tokens[$peakPtr]['code'] !== T_PUBLIC) {
            return;
        }

        $namePtr = $file->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
        $name = $tokens[$namePtr]['content'];
        $isWebController = strpos($file->getFilename(), '/Controller/Api/') === false;
        $actions = $isWebController ? static::getWebActions() : static::getActions();

        if (!in_array($name, $actions, true)) {
            $file->addError(
                'Public methods in %s controllers are limited to "%s()" but "%s()" found. '
                . 'This is often a hint that you are dealing with a sub resource of the current resource '
                . 'that justifies it’s own controller and should be extracted into it’s own controller.',
                $stackPtr,
                'verbLimit',
                [$isWebController ? 'web' : 'API', implode('()", "', $actions), $name]
            );
        }
    }

    private static function getActions()
    {
        return static::$apiActions ?: static::$apiActions = static::verbsToActions(static::$apiVerbs);
    }

    private static function getWebActions()
    {
        return static::$webActions
            ?: static::$webActions = static::verbsToActions(array_merge(static::$webVerbs, static::$apiVerbs));
    }

    private static function verbsToActions(array $actions)
    {
        sort($actions);

        return array_map(
            static function ($verb) {
                return $verb . 'Action';
            },
            $actions
        );
    }
}
