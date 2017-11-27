<?php
namespace InterNations\Tests\Sniffs\Formatting;

use InterNations\Tests\Sniffs\AbstractTestCase;

class EmptyLineBeforeControlStructureFormattingSniffTest extends AbstractTestCase
{
    public function testSimpleControlStructures(): void
    {
        $file = __DIR__ . '/Fixtures/SimpleControlStructures.php';
        $errors = $this->analyze(
            ['InterNations/Sniffs/Formatting/EmptyLineBeforeControlStructureFormattingSniff'],
            [$file]
        );

        $this->assertReportCount(7, 0, $errors, $file);

        $this->assertReportContains($errors, $file, 'errors', 'Missing blank line before if statement');
        $this->assertReportContains($errors, $file, 'errors', 'Missing blank line before while statement');
        $this->assertReportContains($errors, $file, 'errors', 'Missing blank line before foreach statement');
        $this->assertReportContains($errors, $file, 'errors', 'Missing blank line before switch statement');
        $this->assertReportContains($errors, $file, 'errors', 'Missing blank line before for statement');
        $this->assertReportContains($errors, $file, 'errors', 'Missing blank line before do statement');
        $this->assertReportContains($errors, $file, 'errors', 'Missing blank line before return statement');
    }
}
