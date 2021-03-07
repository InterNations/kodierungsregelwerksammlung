<?php
namespace InterNations\Sniffs\Tests\Syntax;

use InterNations\Sniffs\Tests\AbstractTestCase;

class ShortListSyntaxSniffTest extends AbstractTestCase
{
    public function testShortListSyntax(): void
    {
        $file = __DIR__ . '/Fixtures/ShortList/InvalidList.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ShortListSyntaxSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
