<?php
namespace InterNations\Sniffs\Tests\Files;

use InterNations\Sniffs\Tests\AbstractTestCase;

class LineLengthSniffTest extends AbstractTestCase
{
    public function testLineLengthCheck(): void
    {
        $file = __DIR__ . '/Fixtures/LineLength/LineLength.php';
        $errors = $this->analyze(['InterNations/Sniffs/Files/LineLengthSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
