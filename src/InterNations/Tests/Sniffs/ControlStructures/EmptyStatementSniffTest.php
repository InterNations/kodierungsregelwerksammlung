<?php
namespace InterNations\Tests\Sniffs\ControlStructures;

use InterNations\Tests\Sniffs\AbstractTestCase;

class EmptyStatementSniffTest extends AbstractTestCase
{
    public function testEmptyStatement(): void
    {
        $file = __DIR__ . '/Fixtures/EmptyStatements.php';
        $errors = $this->analyze(['InterNations/Sniffs/ControlStructures/EmptyStatementSniff'], [$file]);

        $this->assertReportCount(6, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Empty TRY statement detected'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Empty ELSEIF statement detected'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Empty IF statement detected'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Empty ELSE statement detected'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Empty FOR statement detected'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Empty FOREACH statement detected'
        );
    }

    public function testValidStatement(): void
    {
        $file = __DIR__ . '/Fixtures/ValidStatements.php';
        $errors = $this->analyze(['InterNations/Sniffs/ControlStructures/EmptyStatementSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
