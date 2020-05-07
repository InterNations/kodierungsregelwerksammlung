<?php
namespace InterNations\Sniffs\Tests\Syntax;

use InterNations\Sniffs\Tests\AbstractTestCase;

class VarTypeHintsSniffTest extends AbstractTestCase
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
            'Found type "ArrayCollection" for property "$testX", "@var ArrayCollection" is forbidden, use "@var Collection|Class[]" instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found type "ArrayCollection|Rating[]" for property "$testY", "@var ArrayCollection" is forbidden, use "@var Collection|Class[]" instead'
        );
    }
}
