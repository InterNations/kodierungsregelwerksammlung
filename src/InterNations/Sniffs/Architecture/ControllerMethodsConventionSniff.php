<?php
namespace InterNations\Sniffs\Architecture;

use InterNations\Sniffs\Util;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ControllerMethodsConventionSniff implements Sniff
{
    private static $whitelist = [
        '__construct',
        '__destruct',
        'set*'
    ];

    private static $apiVerbs = ['index', 'get', 'post', 'put', 'patch', 'delete'];

    private static $webVerbs = ['new', 'edit'];

    private static $apiActions = [];

    private static $webActions = [];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(File $file, $stackPtr)
    {
        if (!Util::isController($file)) {
            return;
        }

        $tokens = $file->getTokens();
        $peakPtr = $file->findPrevious([T_WHITESPACE, T_STATIC], $stackPtr - 1, null, true);

        if ($tokens[$peakPtr]['code'] !== T_PUBLIC) {
            return;
        }

        $namePtr = $file->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
        $name = $tokens[$namePtr]['content'];

        if (static::isWhitelistedName($name)) {
            return;
        }

        $isWebController = strpos($file->getFilename(), '/Controller/Api/') === false;
        $actions = $isWebController ? static::getWebActions() : static::getActions();

        if (!in_array($name, $actions, true)) {
            $file->addError(
                'Public methods in %s controllers are limited to "%s()" but "%s()" found. '
                . 'This is often a hint that you are dealing with a sub resource of the current resource '
                . 'that justifies its own controller and should be extracted into its own controller.',
                $stackPtr,
                'verbLimit',
                [$isWebController ? 'web' : 'API', implode('()", "', $actions), $name]
            );
        }
    }

    private static function isWhitelistedName($needle)
    {
        foreach (static::$whitelist as $expression) {
            if (fnmatch($expression, $needle)) {
                return true;
            }
        }

        return false;
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
