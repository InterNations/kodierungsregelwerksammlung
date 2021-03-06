<?php
namespace InterNations\Sniffs\Tests\Waste;

use InterNations\Sniffs\Tests\AbstractTestCase;

class SuperfluousFormatStringSniffTest extends AbstractTestCase
{
    public function testSuperfluousFormatStrings(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousFormatString/Functions.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousFormatStringSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous printf() call as no parameters are passed. Use plain "echo …;" instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous sprintf() call as no parameters are passed. You can safely remove the function call'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous sprintf() call as no parameters are passed. You can safely remove the function call'
        );
    }

    public function testUseFunction(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousFormatString/UseFunctions.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousFormatStringSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous printf() call as no parameters are passed. Use plain "echo …;" instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous sprintf() call as no parameters are passed. You can safely remove the function call'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous sprintf() call as no parameters are passed. You can safely remove the function call'
        );
    }
}
