<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Naming_AlwaysUseSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testBasicBehavior()
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

    public function testUnderscoreFunctions()
    {
        $file = __DIR__ . '/Fixtures/AlwaysUse/FunctionalBug.php';
        $errors = $this->analyze(['InterNations/Sniffs/Naming/AlwaysUseSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }
}
