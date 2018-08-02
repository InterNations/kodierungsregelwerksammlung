<?php
namespace InterNations\Sniffs\Syntax;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

class MethodTypeHintsSniff implements Sniff
{
    private const NATIVE_TYPES = [
        'string',
        'bool',
        'array',
        'float',
        'resource',
        'int',
        'integer',
        'double',
        'callable',
        'iterable',
    ];
    private const NON_NATIVE_UNION_TYPES = ['mixed', 'object'];

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
        return [T_FUNCTION, T_CLOSURE];
    }

    public function process(File $file, $stackPtr)
    {
        if ($this->ignoreTypeHintWhitelist && !empty($this->ignoreTypeHintWhitelist)) {
            foreach ($this->ignoreTypeHintWhitelist as $classes => $methodsToBeWhitelisted) {
                if (!is_string($methodsToBeWhitelisted)) {
                    continue;
                }
                $this->ignoreTypeHintWhitelist[$classes] = explode(':', $methodsToBeWhitelisted);
            }
        }

        $tokens = $file->getTokens();

        // Class name
        $classPtr = $file->findPrevious(T_CLASS, $stackPtr);

        if (!$classPtr) {
            $classPtr = $file->findPrevious(T_TRAIT, $stackPtr);
        }
        $className = $tokens[$file->findNext(T_WHITESPACE, $classPtr + 1, null, true)]['content'];

        $parentClassPtr = $file->findPrevious(T_EXTENDS, $stackPtr);
        $parentClassName = null;

        if ($parentClassPtr) {
            $parentClassName = $tokens[$file->findNext(T_WHITESPACE, $parentClassPtr + 1, null, true)]['content'];
        }

        $interfacePtr = $file->findPrevious(T_IMPLEMENTS, $stackPtr);
        $interfaces = null;

        if ($interfacePtr) {
            $phpOpenCurlyBracket = $file->findNext(T_OPEN_CURLY_BRACKET, $interfacePtr);

            for ($j = $interfacePtr; $j < $phpOpenCurlyBracket; $j++) {
                if ($tokens[$j]['code'] === T_STRING) {
                    $interfaces[] = $tokens[$j]['content'];
                }
            }
        }

        if (preg_match('/Sniff$/D', $className)) {
            return;
        }

        // Is Closure
        $isClosure = $tokens[$stackPtr]['code'] == T_CLOSURE;

        // Method name
        $namePtr = $file->findNext(T_WHITESPACE, $stackPtr + 1, null, true);
        $methodName = $tokens[$namePtr]['content'];

        $parenthesisPtr = $isClosure ? $namePtr : $namePtr + 1;

        // Skip invalid statement.
        if (!isset($tokens[$parenthesisPtr]['parenthesis_opener'])) {
            return;
        }

        // Ignore whitelisted methods by class
        if (isset($this->ignoreTypeHintWhitelist[$className]) 
            && in_array($methodName, $this->ignoreTypeHintWhitelist[$className])
        ) {
            return;
        }

        // Ignore whitelisted methods by parent class
        if ($parentClassName 
            && isset($this->ignoreTypeHintWhitelist[$parentClassName]) 
            && in_array($methodName, $this->ignoreTypeHintWhitelist[$parentClassName])
        ) {
            return;
        }

        // Ignore whitelisted methods by interface
        if ($interfaces) {
            foreach ($interfaces as $interface) {

                if (isset($this->ignoreTypeHintWhitelist[$interface]) 
                    && in_array($methodName, $this->ignoreTypeHintWhitelist[$interface])
                ) {
                    return;
                }
            }
        }

        // Comments block
        $paramDoc = $returnDoc = $dataProvider = [];
        $commentEnd = $file->findPrevious(
            [T_WHITESPACE, T_STATIC, T_PUBLIC, T_PRIVATE, T_PROTECTED, T_ABSTRACT],
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
                        trim($tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content']),
                        3
                    );
                }

                if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG
                    && $tokens[$j]['content'] === '@return'
                    && $tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['code'] ===
                    T_DOC_COMMENT_STRING
                ) {
                    $returnDoc[$j] = preg_split(
                        '/[\s]+/',
                        trim($tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content']),
                        2
                    );

                }

                if ($tokens[$j]['code'] === T_DOC_COMMENT_TAG && $tokens[$j]['content'] === '@dataProvider') {
                    $dataProvider[$j] =
                        $tokens[$file->findNext(T_DOC_COMMENT_WHITESPACE, $j + 1, null, true)]['content'];
                }
            }
        }

        $startBracket = $tokens[$parenthesisPtr]['parenthesis_opener'];
        $endBracket = $tokens[$parenthesisPtr]['parenthesis_closer'];

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

                $typeHintPtr = $file->findPrevious([T_WHITESPACE, T_ELLIPSIS], $i - 1, null, true);

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
                if (!in_array($tokens[$typeHintPtr]['code'], [T_STRING, T_CALLABLE], true)) {

                    $docBlockType = $this->getDocBlockType($tokens[$i]['content'], $paramDoc);

                    if (self::isWhitelistedDocBlockType($docBlockType)) {
                        continue;
                    }

                    $error = $isClosure
                        ? sprintf('Expected Type hint for the parameter "%1$s" in closure', $tokens[$i]['content'])
                        : sprintf(
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
                        $str .= 'specify the exact type. Use "@param Class[] %1$s" for a list of objects of type ';
                        $str .= '"Class", use "@param integer[] %1$s" for a list of integers and so on...';
                        $error = sprintf($str, $tokens[$i]['content'], $className, $methodName);
                        $file->addError($error, $typeHintPtr, 'MissingParamDoc');

                        continue;
                    }
                }

                // If iterable type hint, enforce more specific documentation at @param
                if ($tokens[$typeHintPtr]['content'] === 'iterable' && empty($dataProvider)) {
                    if (!$this->unsetrParamType($tokens[$i]['content'], $paramDoc)) {
                        $str = 'Iterable type hint for the parameter "%1$s" in method "%2$s::%3$s" must be documented ';
                        $str .= 'to specify the exact type. Use "@param Class[] %1$s" for a list of objects of type ';
                        $str .= '"Class", use "@param integer[] %1$s" for a list of integers and so on...';
                        $error = sprintf($str, $tokens[$i]['content'], $className, $methodName);
                        $file->addError($error, $typeHintPtr, 'MissingParamDoc');

                        continue;
                    }
                }

                // If Collection type hint, enforce more specific documentation at @param
                if ($tokens[$typeHintPtr]['content'] === 'Collection' && empty($dataProvider)) {
                    if (!$this->unsetrParamType($tokens[$i]['content'], $paramDoc)) {
                        $str = 'Collection type hint for the parameter "%1$s" in method "%2$s::%3$s" must be ';
                        $str .= 'documented to to specify the exact type. Use Collection|Class[]';
                        $error = sprintf($str, $tokens[$i]['content'], $className, $methodName);
                        $file->addError($error, $typeHintPtr, 'MissingParamDoc');

                        continue;
                    }
                }

                // Forbid ArrayCollection
                if ($tokens[$typeHintPtr]['content'] === 'ArrayCollection') {
                    $str = 'Found param type "%1$s" a for a method "%2$s::%3$s", ';
                    $str .= 'param type "ArrayCollection" is forbidden, use Collection|Class[] instead';
                    $error = sprintf($str, $tokens[$typeHintPtr]['content'], $className, $methodName);
                    $file->addError($error, $typeHintPtr, 'ForbiddenParamTypeHint');

                    continue;
                }

                // Have strict nullable operator for default null parameters
                if ($tokens[$typeHintPtr-1]['code'] !== T_NULLABLE 
                    && $tokens[$typeHintPtr+4]['code'] === T_EQUAL 
                    && $tokens[$typeHintPtr+6]['code'] === T_NULL
                ) {
                    $str = 'Expected type hint "?%1$s" a for a method "%2$s::%3$s", found "%1$s"';
                    $error = sprintf($str, $tokens[$typeHintPtr]['content'], $className, $methodName);
                    $file->addError($error, $typeHintPtr, 'nullableParamTypeHint');

                    continue;
                }
            }
        }

        // Catch Superfluous parameter docs..
        if (empty($dataProvider)) {
            foreach ($paramDoc as $key => $value) {

                if (count($value) === 3) {
                    continue;
                }

                if (self::isWhitelistedDocBlockType($value[0])) {
                    continue;
                }

                $error = 'Superfluous parameter comment doc: ' . implode(' ', $value);
                $file->addError($error, $key, 'superfluousParamDoc');
            }
        }

        // Check return type hints for functions
        $methodProperties = $file->getMethodProperties($stackPtr);
        $returnTypeHint = $methodProperties['return_type'];
        $returnTypeHintPtr = $methodProperties['return_type_token'];

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
        if (preg_match('/Controller$/D', $className) && preg_match('/Action$/D', $methodName)) {
            return;
        }

        // PHP7 return type hint check
        if (!$returnTypeHint) {

            if (!self::isWhitelistedDocBlockType(array_values($returnDoc)[0][0] ?? null)) {

                $error = $isClosure
                ? 'PHP 7 style return type hint is required for closure'
                : sprintf('PHP 7 style return type hint is required for method "%1$s::%2$s"', $className, $methodName);

                $file->addError($error, $namePtr, 'MissingReturnTypeHint');
            }

            return;
        }

        // PHP7 return type style check
        if ($file->findNext(T_WHITESPACE, $endBracket) === ($endBracket + 1)
            && $tokens[$endBracket+2]['code'] === T_COLON
        ) {
            $str = 'No leading space allowed before colon, exactly one space after colon, expected return type ';
            $str .= 'formatting to be "): %1$s" got ") : %1$s"';
            $error = sprintf($str, $tokens[$returnTypeHintPtr]['content']);
            $file->addError($error, $namePtr, 'WrongStyleTypeHint');

            return;
        }

        // PHP7 return type style check
        $colonPtr = $file->findPrevious([T_WHITESPACE, T_NULLABLE], $returnTypeHintPtr - 1, null, true);
        
        if ($tokens[$colonPtr]['code'] !== T_COLON) {
            $error = sprintf(
                'PHP 7 style return type hint, colon is required for method "%1$s::%2$s"',
                $className,
                $methodName
            );
            $file->addError($error, $namePtr, 'MissingReturnTypeHintColon');

            return;
        }

        if (!in_array(
            $tokens[$file->findNext(T_COLON, $endBracket)+2]['code'],
            [T_NULLABLE, T_STRING, T_CALLABLE, T_SELF]
        )) {
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
        if (($tokens[$returnTypeHintPtr]['content'] === 'array' && empty($returnDoc)) 
            || ($tokens[$returnTypeHintPtr]['content'] === 'array' 
            && strpos(array_values($returnDoc)[0][0], '[]') === false) 
            || ($tokens[$returnTypeHintPtr]['content'] === 'array' && count(array_values($returnDoc)[0]) === 2)
        ) {
            $str = 'Return type hint for a method "%1$s::%2$s" must be documented to specify their exact type, ';
            $str .= 'Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers ';
            $str .= 'and so on...';
            $error = sprintf($str, $className, $methodName);
            $file->addError($error, $returnTypeHintPtr, 'MissingDocForReturnTypeHint');

            return;
        }

        // Check for iterable return type hint
        if ($tokens[$returnTypeHintPtr]['content'] === 'iterable' && empty($returnDoc)) {
            $str = 'Return type hint for a method "%1$s::%2$s" must be documented to specify their exact type, ';
            $str .= 'Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers ';
            $str .= 'and so on...';
            $error = sprintf($str, $className, $methodName);
            $file->addError($error, $returnTypeHintPtr, 'MissingDocForReturnTypeHint');

            return;
        }

        // Check for Collection return type hint
        if ($tokens[$returnTypeHintPtr]['content'] === 'Collection' && empty($returnDoc)) {
            $str = 'Return type hint for a method "%1$s::%2$s" must be documented to specify their exact type, ';
            $str .= 'use Collection::toArray() instead';
            $error = sprintf($str, $className, $methodName);
            $file->addError($error, $returnTypeHintPtr, 'MissingDocForReturnTypeHint');

            return;
        }

        // Make self as a strict type
        if ($className === $tokens[$returnTypeHintPtr]['content']) {
            $str = 'Expected return type "self" a for a method "%1$s::%2$s", found "%3$s"';
            $error = sprintf($str, $className, $methodName, $tokens[$returnTypeHintPtr]['content']);
            $file->addError($error, $returnTypeHintPtr, 'StrictReturnSelf');

            return;
        }

        // Forbid ArrayCollection or PersistentCollection
        if (in_array($tokens[$returnTypeHintPtr]['content'], ['ArrayCollection', 'PersistentCollection'])) {
            $str = 'Found return type "%1$s" a for a method "%2$s::%3$s", ';
            $str .= 'return type "ArrayCollection" and "PersistentCollection" is forbidden, ';
            $str .= 'use Collection::toArray() instead';
            $error = sprintf($str, $tokens[$returnTypeHintPtr]['content'], $className, $methodName);
            $file->addError($error, $returnTypeHintPtr, 'ForbiddenReturnTypeHint');

            return;
        }

        // Catch Superfluous return comment doc
        if ($returnDoc 
            && !in_array(
                $tokens[$returnTypeHintPtr]['content'],
                ['array', 'Traversable', 'IterableResult', 'MockObject', 'iterable', 'Collection']
            )
            && count(array_values($returnDoc)[0]) < 2
        ) {
            $error = 'Superfluous return type doc';
            $file->addError($error, array_keys($returnDoc)[0], 'superfluousParamDoc');
        }
    }

    private function unsetrParamType($needle, &$paramDoc): bool
    {
        foreach ($paramDoc as $key => $param) {
            if (in_array($needle, $param, true) && count($param) >= 2) {

                if (strpos(array_values($param)[0], '[]') !== false) {
                    unset($paramDoc[$key]);

                    return true;
                }
            }
        }

        return false;
    }

    private function getDocBlockType(string $variableName, array $docBlocks): ?string
    {
        foreach ($docBlocks as $block) {

            if (count($block) === 1) {
                continue;
            }

            [$type, $docBlockVariableName] = $block;

            if ($docBlockVariableName === $variableName) {
                return $type;
            }
        }

        return null;
    }

    private static function isWhitelistedDocBlockType(?string $compoundType)
    {
        $types = explode('|', $compoundType);

        if (count($types) === 1) {
            return in_array($types[0], self::NON_NATIVE_UNION_TYPES, true);
        }

        if (count($types) === 2) {

            // We want string|null to replaced with a native type hint
            $index = array_search('null', $types, true);

            if ($index !== false) {
                unset($types[$index]);

                return !in_array(current($types), self::NATIVE_TYPES, true);
            }
        }

        return true;
    }
}
