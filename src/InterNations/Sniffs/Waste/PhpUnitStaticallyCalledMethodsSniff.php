<?php
namespace InterNations\Sniffs\Waste;

use InvalidArgumentException;
use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

/** Identifying and fixing non-statically called PHPUnit Methods */
class PhpUnitStaticallyCalledMethodsSniff implements CodeSnifferSniff
{
    private const VALID_MODIFIERS = [
        ReflectionMethod::IS_STATIC,
        ReflectionMethod::IS_PUBLIC,
        ReflectionMethod::IS_PROTECTED,
        ReflectionMethod::IS_PRIVATE,
        ReflectionMethod::IS_ABSTRACT,
        ReflectionMethod::IS_FINAL,
    ];

    /** @var string[] */
    private static $testCasePublicStaticMethods;

    /** @return integer[] */
    public function register(): array
    {
        return [T_OBJECT_OPERATOR, T_DOUBLE_COLON];
    }

    /** @param integer $stackPtr */
    public function process(CodeSnifferFile $file, $stackPtr): void
    {
        $classNameToken = static::findCurrentClassNameToken($file, $stackPtr);

        if (!$classNameToken || !preg_match('/^.+Test(?:Case)?$/', $classNameToken['content'])) {
            return;
        }

        $tokens = $file->getTokens();
        $previousStackPtr = $file->findPrevious([T_WHITESPACE, T_COMMENT], $stackPtr - 1, null, true);
        $previousToken = $tokens[$previousStackPtr];

        if (!in_array($previousToken['content'], ['$this', 'static'], true)) {
            return;
        }

        $nextToken = $tokens[$file->findNext(T_STRING, $stackPtr + 1)];

        if (!static::$testCasePublicStaticMethods) {
            static::$testCasePublicStaticMethods =
                static::findClassMethods(TestCase::class, [ReflectionMethod::IS_PUBLIC, ReflectionMethod::IS_STATIC]);
        }

        if (!in_array($nextToken['content'], static::$testCasePublicStaticMethods, true)) {
            return;
        }

        $currentToken = $tokens[$stackPtr];

        $file->addFixableError(
            'Call PHPUnit methods statically, replace %s%s%s() with self::%s()',
            $stackPtr,
            'StaticallyCallPhpUnitMethods',
            [$previousToken['content'], $currentToken['content'], $nextToken['content'], $nextToken['content']]
        );

        $file->fixer->beginChangeset();
        $file->fixer->replaceToken($previousStackPtr, 'self');
        $file->fixer->replaceToken($stackPtr, '::');
        $file->fixer->endChangeset();
    }

    /**
     * @param integer[] $andMethodModifiers
     * @return string[]
     */
    private static function findClassMethods(string $className, array $andMethodModifiers): array
    {
        static::validateMethodModifiers($andMethodModifiers);

        $filter = 0;

        foreach ($andMethodModifiers as $andMethodModifier) {
            $filter |= $andMethodModifier;
        }

        $methods = [];

        foreach ((new ReflectionClass($className))->getMethods($filter) as $method) {
            if (($method->getModifiers() & $filter) === $filter) {
                $methods[] = $method->name;
            }
        }

        return array_unique($methods);
    }

    /** @param integer[] $methodModifiers */
    private static function validateMethodModifiers(array $methodModifiers): void
    {
        $diff = array_diff($methodModifiers, static::VALID_MODIFIERS);

        if ($diff) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid ReflectionMethod modifier(s): %s. Valid Modifiers are: %s',
                    implode(', ', $diff),
                    implode(', ', static::VALID_MODIFIERS)
                )
            );
        }
    }

    /** @return mixed[] */
    private static function findCurrentClassNameToken(CodeSnifferFile $file, int $stackPtr): ?array
    {
        $classStackPtr = $file->findPrevious(T_CLASS, $stackPtr);

        if (!$classStackPtr) {
            return null;
        }

        $tokens = $file->getTokens();

        return $tokens[$file->findNext([T_WHITESPACE, T_COMMENT], $classStackPtr + 1, null, true)];
    }
}
