<?php

namespace InterNations\Sniffs\Tests\Syntax;

use InterNations\Sniffs\Tests\AbstractTestCase;

class ConstantVisibilitySniffTest extends AbstractTestCase
{
    public function testValidConstantVisibility(): void
    {
        $file = __DIR__ . '/Fixtures/ConstantVisibility/ValidConstants.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ConstantVisibilitySniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testInvalidConstantVisibility(): void
    {
        $file = __DIR__ . '/Fixtures/ConstantVisibility/InvalidConstants.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ConstantVisibilitySniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }

    public function testConstantOutsideClass(): void
    {
        $file = __DIR__ . '/Fixtures/ConstantVisibility/Constant.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/ConstantVisibilitySniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
    }
}
