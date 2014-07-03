<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Waste_SuperfluousUseStatementsSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testDocBlocks()
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/DocBlocks.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "ClassName27", but no further reference'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "Unused", but no further reference'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "Prefix", but no further reference'
        );
    }

    public function testAnnotations()
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/Annotations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);
        $this->assertReportCount(2, 0, $errors, $file);
    }
}
