<?php
namespace InterNations\Sniffs\Tests\Waste;

use InterNations\Sniffs\Tests\AbstractTestCase;

class SuperfluousUseStatementsSniffTest extends AbstractTestCase
{
    public function testDocBlocks(): void
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
            'Superfluous use-statement found for symbol "ClassName28", but no further reference'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "Prefix", but no further reference'
        );
    }

    public function testFunctions(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/Functions.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "Functional\select", but no further reference'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "Functional", but no further reference'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "Functional\true", but no further reference'
        );
    }

    public function testTrait(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/Traits.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);

        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "InvalidSymbol", but no further reference'
        );
    }

    public function testAnnotations(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/Annotations.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);
        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "ClassName5", but no further reference'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "ClassName6", but no further reference'
        );
    }

    public function testConstants(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/Constants.php';
        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);
        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "NamespaceName\\ClassName::CONSTANT2", but no further reference'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "NamespaceName\\CONSTANT4", but no further reference'
        );
    }

    public function testUseStatementSameNamespace(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/SameNs.php';

        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "Foo\Bar\Superfluous", but no use statement needed as namespaces match'
        );
    }

    public function testUseStatementReturnTypes(): void
    {
        $file = __DIR__ . '/Fixtures/SuperfluousUseStatements/ReturnTypes.php';

        $errors = $this->analyze(['InterNations/Sniffs/Waste/SuperfluousUseStatementsSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous use-statement found for symbol "UnusedSymbol", but no further reference'
        );
    }
}
