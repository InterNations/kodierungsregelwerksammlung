<?php
namespace InterNations\Tests\Sniffs\Naming;

use InterNations\Tests\Sniffs\AbstractTestCase;

class AlwaysUseSniffTest extends AbstractTestCase
{
    public function testBasicBehavior(): void
    {
        $file = __DIR__ . '/Fixtures/AlwaysUse/Basic.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlwaysUseSniff'], [$file]);

        $this->assertReportCount(7, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Legacy namespaces are prohibited (Legacy_Extend). Introduce a "use"-statement and alias properly'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Legacy namespaces are prohibited (Legacy_Implements). Introduce a "use"-statement and alias properly'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Legacy namespaces are prohibited (Legacy_TypeHint). Introduce a "use"-statement and alias properly'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Legacy namespaces are prohibited (Legacy_New). Introduce a "use"-statement and alias properly'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Legacy namespaces are prohibited (Legacy_InstanceOf). Introduce a "use"-statement and alias properly'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Legacy namespaces are prohibited (Legacy_TypeHintClosure). Introduce a "use"-statement and alias properly'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Legacy namespaces are prohibited (Legacy_Call). Introduce a "use"-statement and alias properly'
        );
    }

    public function testUnderscoreFunctions(): void
    {
        $file = __DIR__ . '/Fixtures/AlwaysUse/FunctionalBug.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlwaysUseSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
