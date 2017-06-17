<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_VarTypeHintsSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testValidVarTypeHints(): void
    {
        $file = __DIR__ . '/Fixtures/VarTypeHints/ValidTypeHints.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/VarTypeHintsSniff'], [$file]);

        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found type "ArrayCollection" for a variable "$testX", "@var ArrayCollection" is forbidden, use "@var Collection|Class[]" instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found type "ArrayCollection|Rating[]" for a variable "$testY", "@var ArrayCollection" is forbidden, use "@var Collection|Class[]" instead'
        );
    }
}
