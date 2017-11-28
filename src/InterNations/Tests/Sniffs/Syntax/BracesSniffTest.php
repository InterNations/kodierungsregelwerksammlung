<?php
namespace InterNations\Tests\Sniffs\Syntax;

use InterNations\Tests\Sniffs\AbstractTestCase;

class BracesTest extends AbstractTestCase
{
    public function testInvalidDocBlocks(): void
    {
        $file = __DIR__ . '/Fixtures/Braces/InvalidBraces.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/BracesSniff'], [$file]);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Opening bracket of "class SameLine" must be in the next line',
            'InterNations.Syntax.Braces.MissingNewlineClassBrace',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Exactly a single newline must follow after the declaration of "class TooManyNewlines"',
            'InterNations.Syntax.Braces.TooManyNewlinesClassBrace',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Opening bracket of "interface SameLineInterface" must be in the next line',
            'InterNations.Syntax.Braces.MissingNewlineInterfaceBrace',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Exactly a single newline must follow after the declaration of "interface TooManyNewlinesInterface"',
            'InterNations.Syntax.Braces.TooManyNewlinesInterfaceBrace',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Opening bracket of "trait SameLineTrait" must be in the next line',
            'InterNations.Syntax.Braces.MissingNewlineTraitBrace',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Exactly a single newline must follow after the declaration of "trait TooManyNewlinesTrait"',
            'InterNations.Syntax.Braces.TooManyNewlinesTraitBrace',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Opening bracket of "function createFunction" must be in the next line',
            'InterNations.Syntax.Braces.MissingNewlineFunctionBrace',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Exactly a single newline must follow after the declaration of "function createFunction"',
            'InterNations.Syntax.Braces.TooManyNewlinesFunctionBrace',
            5
        );

        $this->assertReportCount(8, 0, $errors, $file);
    }
}
