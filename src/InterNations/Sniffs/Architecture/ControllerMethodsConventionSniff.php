<?php
namespace InterNations\Sniffs\Architecture;

use InterNations\Sniffs\Util;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class ControllerMethodsConventionSniff implements Sniff
{
    private static array $whitelist = [
        '__construct',
        '__destruct',
        'set*'
    ];

    private static array $apiVerbs = ['index', 'get', 'post', 'put', 'patch', 'delete', 'head', 'options'];
    private static array $webVerbs = ['new', 'edit'];
    private static array $apiActions = [];
    private static array $webActions = [];

    public function register(): array
    {
        return [T_FUNCTION];
    }

    public function process(File $file, $stackPtr): void
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

        if (self::isWhitelistedName($name)) {
            return;
        }

        $isWebController = strpos($file->getFilename(), '/Controller/Api/') === false;
        $actions = $isWebController ? self::getWebActions() : self::getActions();

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

    private static function isWhitelistedName(string $needle): bool
    {
        foreach (self::$whitelist as $expression) {
            if (fnmatch($expression, $needle)) {
                return true;
            }
        }

        return false;
    }

    private static function getActions(): array
    {
        return self::$apiActions ?: self::$apiActions = self::verbsToActions(self::$apiVerbs);
    }

    private static function getWebActions(): array
    {
        return self::$webActions
            ?: self::$webActions = self::verbsToActions(array_merge(self::$webVerbs, self::$apiVerbs));
    }

    private static function verbsToActions(array $actions): array
    {
        sort($actions);

        return array_map(static fn (string $verb) => $verb . 'Action', $actions);
    }
}
