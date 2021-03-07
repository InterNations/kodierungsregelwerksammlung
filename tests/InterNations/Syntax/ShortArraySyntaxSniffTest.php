<?php
namespace InterNations\Sniffs\Tests\Syntax;

use InterNations\Sniffs\Tests\AbstractTestCase;

class ShortArraySyntaxSniffTest extends AbstractTestCase
{
    public function testOneLineOk(): void
    {
        $file = __DIR__ . '/Fixtures/ShortArray/Invalid.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ShortArraySyntaxSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
