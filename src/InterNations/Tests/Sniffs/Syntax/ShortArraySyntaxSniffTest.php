<?php
namespace InterNations\Tests\Sniffs\Syntax;

use InterNations\Tests\Sniffs\AbstractTestCase;

class ShortArraySniffTest extends AbstractTestCase
{
    public function testOneLineOk(): void
    {
        $file = __DIR__ . '/Fixtures/ShortArray/Invalid.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ShortArraySyntaxSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
