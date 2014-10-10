<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Naming_AlternativeFunctionSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public static function provideAlternativeNames()
    {
        return [
            ['join', 'implode()'],
            ['sizeof', 'count()'],
            ['fputs', 'fwrite()'],
            ['chop', 'rtrim()'],
            ['is_real', 'is_float()'],
            ['strchr', 'strstr()'],
            ['doubleval', 'floatval()'],
            ['key_exists', 'array_key_exists()'],
            ['is_double', 'is_float()'],
            ['ini_alter', 'ini_set()'],
            ['is_long', 'is_int()'],
            ['is_integer', 'is_int()'],
            ['is_real', 'is_float()'],
            ['pos', 'current()'],
            ['sha1', 'hash(\'sha256\', ...)'],
            ['sha1_file', 'hash_file(\'sha256\', ...)'],
            ['md5', 'hash(\'sha256\', ...)'],
            ['md5_file', 'hash_file(\'sha256\', ...)'],
            ['var_dump'],
            ['print_r'],
            ['printf'],
            ['vprintf'],
        ];
   }

    /**
     * @param $method
     * @param $alternative
     * @dataProvider provideAlternativeNames
     */
    public function testFunctionNames($method, $alternative = null)
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);

        $this->assertReportCount(27, 0, $errors, $file);
        $message = $alternative
            ? 'Function "' . $method . '()" is not allowed. Use "' . $alternative . '" instead'
            : 'Function "' . $method . '()" is not allowed. Please remove it';

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            $message,
            'InterNations.Naming.AlternativeFunction.UseAlternative',
            5
        );
    }

    public function testEcho()
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "echo" is not allowed. Please remove it',
            'InterNations.Naming.AlternativeFunction.UseAlternative',
            5
        );
    }

    public function testPrint()
    {
        $file = __DIR__ . '/Fixtures/AlternativeFunction/FunctionNames.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlternativeFunctionSniff'], [$file]);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Statement "print" is not allowed. Please remove it',
            'InterNations.Naming.AlternativeFunction.UseAlternative',
            5
        );
    }
}
