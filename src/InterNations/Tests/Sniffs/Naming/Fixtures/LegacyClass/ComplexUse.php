<?php
use function foobar\invalidFunctionName;
use function foobar\invalidFunctionName as valid_function_name;
use function foobar\valid_function_name as invalidFunctionName;
use function foobar\valid_function_name as INVALID;
use function foobar\valid_function_name;
use function foobar\fn;

use const FooBar\invalid_constant;
use const FooBar\invalid_constant as VALID_CONSTANT;
use const FooBar\invalidconstant;
use const FooBar\invalidconstant as VALIDCONSTANT;
use const FooBar\CONSTANT_NAME;
use const FooBar\CONSTANT_NAME as constant_name;
use const FooBar\NAME;
