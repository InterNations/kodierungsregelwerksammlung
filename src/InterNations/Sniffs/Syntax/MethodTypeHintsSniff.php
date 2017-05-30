<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer_File as CodeSnifferFile;
use PHP_CodeSniffer_Sniff as CodeSnifferSniff;

class MethodTypeHintsSniff implements CodeSnifferSniff
{
    public $ignoreTypeHintWhitelist;

    /*
        '__construct'  (mixed $property) : force no return type
        '__destruct'   force no parameters : force no return type
        '__clone'      force no parameters : force no return type
        '__sleep'      force no parameters : array
        '__wakeup'     force no parameters : void
        '__toString'   force no parameters : string
        '__debugInfo'  force no parameters : array
        '__get'        (string $property): mixed
        '__isset'      (string $property): bool
        '__set'        (string $property, mixed $value): void
        '__unset'      (string $property): void
        '__call'       (string $method, array $arguments): mixed
        '__callStatic' (string $method, array $arguments): mixed
        '__set_state'  (array $property): self
        '__invoke'     (mixed $property): mixed
    */

    /* false => mixed
       null  => force no param/return
       ''  => no restriction on param */
    private static $whitelist = [
        '__construct' => ['', null],
        '__destruct' => [null, null],
        '__clone' => [null, null],
        '__sleep' => [null, 'array'],
        '__wakeup' => [null, 'void'],
        '__toString' => [null, 'string'],
        '__debugInfo' => [null, 'array'],
        '__get' => [['string'], false],
        '__isset' => [['string'], 'bool'],
        '__set' => [['string', false], 'void'],
        '__unset' => [['string'], 'void'],
        '__call' => [['string', 'array'], false],
        '__callStatic' => [['string', 'array'], false],
        '__set_state' => [['array'], 'self']
    ];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(CodeSnifferFile $file, $stackPtr)
    {
        if ($this->ignoreTypeHintWhitelist) {
            foreach ($this->ignoreTypeHintWhitelist as $k => $y) {
                $this->ignoreTypeHintWhitelist[$k] = explode(':', $y);
            }
        }

        $tokens = $file->getTokens();

        $namespacePtr = ($file->findNext(T_SEMICOLON, $file->findPrevious(T_NAMESPACE, $stackPtr))) - 1;
        $namespace = $tokens[$namespacePtr]['content'];

        // Class name
        $classPtr = $file->findPrevious(T_CLASS, $stackPtr);
        $className = $tokens[$file->findNext(T_WHITESPACE, $classPtr + 1, null, true)]['content'];

        if (preg_match('/Sniff$/D', $className)) {
            return;
        }

        // Method name
        $namePtr = $file->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
        $methodName = $tokens[$namePtr]['content'];

        // Skip invalid statement.
        if (!isset($tokens[$namePtr + 1]['parenthesis_opener'])) {
            return;
        }

        // Ignore whitelisted methods
        if (isset($this->ignoreTypeHintWhitelist[$namespace]) &&
            in_array($methodName, $this->ignoreTypeHintWhitelist[$namespace])
        ) {
            return;
        }

        // Comments block
        $paramDoc = $returnDoc = $dataProvider = [];
        $commentEnd = $file->findPrevious(
            [T_WHITESPACE, T_STATIC, T_PUBLIC, T_PRIVATE, T_PROTECTED],
            $stackPtr - 1,
            null,
            true
        );

        if ($tokens[$commentEnd]['code'] === T_DOC_COMMENT_CLOSE_TAG) {
            $commentStart = $file->findPrevious(T_DOC_COMMENT_OPEN_TAG, ($commentEnd - 1));

            for ($j = $commentStart; $j <= $commentEnd; $j++) {
                if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG && $tokens[$j]['content'] === '@param') {
                    $paramDoc[$j] = preg_split(
                        '/[\s]+/',
                        trim($tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content'])
                    );
                }

                if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG
                    && $tokens[$j]['content'] === '@return'
                    && $tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['code'] ===
                    T_DOC_COMMENT_STRING
                ) {
                    $returnDoc[$j] = $tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content'];
                }

                if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG && $tokens[$j]['content'] === '@dataProvider') {
                    $dataProvider[$j] =
                        $tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content'];
                }
            }
        }

        $startBracket = $tokens[$namePtr + 1]['parenthesis_opener'];
        $endBracket = $tokens[$namePtr + 1]['parenthesis_closer'];

        // Check, if no arguments for specific magic methods
        if ($startBracket + 1 === $endBracket
            && isset(self::$whitelist[$methodName])
            && self::$whitelist[$methodName][0] !== null
            && self::$whitelist[$methodName][0] !== ''
        ) {
            $error = sprintf(
                'Expected at least one argument for magic method "%1$s::%2$s" found none',
                $className,
                $methodName
            );

            $file->addError($error, $namePtr, 'NoArgumentFound');
        }

        $paramCount = -1;

        for ($i = $startBracket; $i <= $endBracket; $i++) {
            if ($tokens[$i]['code'] === T_VARIABLE) {
                $paramCount++;

                $typeHintPtr = $file->findPrevious([T_WHITESPACE, T_ELLIPSIS], ($i - 1), null, true);

                // Check for no param
                if (isset(self::$whitelist[$methodName]) && self::$whitelist[$methodName][0] === null) {
                    $error = sprintf(
                        'Expected no arguments for this magic method "%1$s::%2$s" found "%3$s"',
                        $className,
                        $methodName,
                        $tokens[$i]['content']
                    );

                    $file->addError($error, $typeHintPtr, 'WrongArgumentForMethod');

                    continue;
                }

                // Check for mandatory mixed type hints
                if (!in_array($tokens[$typeHintPtr]['code'], [T_STRING, T_ARRAY_HINT, T_CALLABLE])) {
                    $error = sprintf(
                        'Expected Type hint for the parameter "%1$s" in method "%2$s::%3$s"',
                        $tokens[$i]['content'],
                        $className,
                        $methodName
                    );
                    $file->addError($error, $typeHintPtr, 'MissingTypeHint');

                    continue;
                }

                // Check for magic methods specific type hints
                if (isset(self::$whitelist[$methodName]) && self::$whitelist[$methodName][0] !== false) {
                    if (isset(self::$whitelist[$methodName][0][$paramCount])
                        && self::$whitelist[$methodName][0][$paramCount] !== $tokens[$typeHintPtr]['content']
                        && self::$whitelist[$methodName][0][$paramCount] !== false
                    ) {
                        $str = 'Expected type hint "%1$s" for parameter "%2$s" found "%3$s" for the magic method ';
                        $str .= '"%4$s::%5$s"';
                        $error = sprintf(
                            $str,
                            self::$whitelist[$methodName][0][$paramCount],
                            $tokens[$i]['content'],
                            $tokens[$typeHintPtr]['content'],
                            $className,
                            $methodName
                        );
                        $file->addError($error, $typeHintPtr, 'WrongTypeHint');

                        continue;
                    }
                }

                // If array type hint, enforce more specific documentation at @param
                if ($tokens[$typeHintPtr]['content'] === 'array' && empty($dataProvider)) {
                    if (!$this->unsetrParamType($tokens[$i]['content'], $paramDoc)) {
                        $str = 'Array type hint for the parameter "%1$s" in method "%2$s::%3$s" must be documented to ';
                        $str .= 'to specify the exact type. Use "@param Class[] %1$s" for a list of objects of type ';
                        $str .= '"Class", use "@param integer[] %1$s" for a list of integers and so on...';
                        $error = sprintf($str, $tokens[$i]['content'], $className, $methodName);
                        $file->addError($error, $typeHintPtr, 'MissingParamDoc');

                        continue;
                    }
                }
            }
        }

        // Catch Superfluous parameter docs..
        foreach ($paramDoc as $key => $value) {
            $error = 'Superfluous parameter comment doc';
            $file->addError($error, $key, 'superfluousParamDoc');
        }

        // Check return type hints for functions
        $returnTypeHintPtr = $file->findNext([T_WHITESPACE, T_COLON, T_NULLABLE], $endBracket + 1, null, true);

        $returnTypeHint = $tokens[$returnTypeHintPtr]['code'] === T_RETURN_TYPE;

        if (isset(self::$whitelist[$methodName]) && self::$whitelist[$methodName][1] === null) {
            if ($returnTypeHint) {
                $error = sprintf(
                    'Expected no return type for the method "%1$s::%2$s" found "%3$s"',
                    $className,
                    $methodName,
                    $tokens[$returnTypeHintPtr]['content']
                );
                $file->addError($error, $returnTypeHintPtr, 'NoReturnTypeHint');
            }

            if ($returnDoc) {
                $error = 'Superfluous return type doc';
                $file->addError($error, array_keys($returnDoc)[0], 'superfluousParamDoc');
            }

            return;
        }

        // Escape return type hint check for controller's actions.
        if (preg_match('/Controller$/D', $className) && preg_match('/Action/D', $methodName)) {
            return;
        }

        // PHP7 return type hint check
        if (!$returnTypeHint) {

            $error = sprintf(
                'PHP 7 style return type hint is required for method "%1$s::%2$s"',
                $className,
                $methodName
            );
            $file->addError($error, $namePtr, 'MissingReturnTypeHint');

            return;
        }

        // PHP7 return type style check
        if ($file->findNext(T_WHITESPACE, $endBracket) === ($endBracket + 1)
            && $tokens[$endBracket+2]['code'] === T_COLON) {
            $str = 'No leading space allowed before colon, exactly one space after colon, expected return type ';
            $str .= 'formatting to be "): %1$s" got ") : %1$s"';
            $error = sprintf($str, $tokens[$returnTypeHintPtr]['content']);
            $file->addError($error, $namePtr, 'WrongStyleTypeHint');

            return;
        }

        // PHP7 return type style check
        if ($tokens[($endBracket + 1)]['code'] !== T_COLON) {
            $error = sprintf(
                'PHP 7 style return type hint, colon is required for method "%1$s::%2$s"',
                $className,
                $methodName
            );
            $file->addError($error, $namePtr, 'MissingReturnTypeHintColon');

            return;
        }

        if (!in_array($tokens[$file->findNext(T_COLON, $endBracket)+2]['code'], [T_NULLABLE, T_RETURN_TYPE])) {
            $error = 'Expected exactly one space after colon, multiple spaces or no space found for the return type';
            $file->addError($error, $namePtr, 'WrongStyleTypeHint');

            return;
        }

        // Check for mandatory return type hints
        if (isset(self::$whitelist[$methodName][1])
            && self::$whitelist[$methodName][1] !== $tokens[$returnTypeHintPtr]['content']
            && self::$whitelist[$methodName][1] !== false
        ) {
            $error = sprintf(
                'Magic method "%1$s::%2$s" can only contain "%3$s" as a return type hint',
                $className,
                $methodName,
                self::$whitelist[$methodName][1]
            );
            $file->addError($error, $returnTypeHintPtr, 'WrongReturnTypeHint');

            return;
        }

        // Check for array return type hint
        if (($tokens[$returnTypeHintPtr]['content'] === 'array' && empty($returnDoc)) ||
            ($tokens[$returnTypeHintPtr]['content'] === 'array' && strpos(array_values($returnDoc)[0], '[]') === false)
        ) {
            $str = 'Return type hint for a method "%1$s::%2$s" must be documented to specify their exact type, ';
            $str .= 'Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers ';
            $str .= 'and so on...';
            $error = sprintf($str, $className, $methodName);
            $file->addError($error, $returnTypeHintPtr, 'MissingDocForReturnTypeHint');

            return;
        }

        // Catch Superfluous return comment doc
        if ($tokens[$returnTypeHintPtr]['content'] !== 'array' && $returnDoc) {
            $error = 'Superfluous return type doc';
            $file->addError($error, array_keys($returnDoc)[0], 'superfluousParamDoc');
        }
    }

    private function unsetrParamType($needle, &$paramDoc): bool
    {
        foreach ($paramDoc as $key => $param) {
            if (in_array($needle, $param, true) && count($param) === 2) {

                if (strpos(array_values($param)[0], '[]') !== false) {
                    unset($paramDoc[$key]);

                    return true;
                }
            }
        }

        return false;
    }
}
