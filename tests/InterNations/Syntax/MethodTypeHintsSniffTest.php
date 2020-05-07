<?php
namespace InterNations\Sniffs\Tests\Syntax;

use InterNations\Sniffs\Tests\AbstractTestCase;

class MethodTypeHintsSniffTest extends AbstractTestCase
{
    public function testClosureTypeHints(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/ClosureTypeHints.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);

        $this->assertReportCount(2, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected Type hint for the parameter "$someVar" in closure'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'PHP 7 style return type hint is required for closure'
        );
    }

    public function testValidMethodTypeHints(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/ValidTypeHints.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testNoArgumentFound(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/missingArgument.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected at least one argument for magic method "missingArgument::__isset" found none'
        );
    }

    public function testWrongArgumentForMethod(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/WrongArgumentForMethod.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected no arguments for this magic method "WrongArgumentForMethod::__clone" found "$request"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected type hint "?string" a for a method "WrongArgumentForMethod::test", found "string"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected type hint "?float" a for a method "WrongArgumentForMethod::test", found "float"'
        );
    }

    public function testInvalidParamTypeHints(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/MissingParameterTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected Type hint for the parameter "$context" in method "MissingParameterTypeHint::postAction"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected Type hint for the parameter "$entityType" in method "MissingParameterTypeHint::postAction"'
        );
    }

    public function testWrongTypeHints(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/WrongTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(4, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected type hint "string" for parameter "$request" found "Request" for the magic method "WrongTypeHint::__call"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected Type hint for the parameter "$test" in method "WrongTypeHint::__call"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Return type hint for a method "WrongTypeHint::__call" must be documented to specify their exact type, Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers and so on...'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Found return type "ArrayCollection" a for a method "WrongTypeHint::forbidTypeHint", return type "ArrayCollection" and "PersistentCollection" is forbidden, use Collection::toArray() instead'
        );
    }

    public function testSuperfluousParamDoc(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/SuperfluousParamDoc.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(5, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous parameter comment doc: Activity $activity'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous parameter comment doc: User $attendee'
        );
    }

    public function testNoReturnTypeHint(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/NoReturnTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected no return type for the method "NoReturnTypeHint::__clone" found "array"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous return type doc'
        );
    }

    public function testReturnTypeHint(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/ReturnTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(4, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'PHP 7 style return type hint is required for method "ReturnTypeHint::testNoReturnTypeHint"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'No leading space allowed before colon, exactly one space after colon, expected return type formatting to be "): Attendance" got ") : Attendance"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Return type hint for a method "ReturnTypeHint::testMissingDocForArrayReturnTypeHint" must be documented to specify their exact type, Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers and so on...'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Return type hint for a method "ReturnTypeHint::testMissingDocForArrayReturnTypeHint" must be documented to specify their exact type, Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers and so on...'
        );
    }

    public function testControllers(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/TestController.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testSelfTypeHints(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/SelfTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected return type "self" a for a method "SelfTypeHint::Y", found "SelfTypeHint"'
        );
    }

    public function testMissingParamDoc(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/missingParamDoc.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Array type hint for the parameter "$a" in method "missingParamDoc::x" must be documented to specify the exact type. Use "@param Class[] $a" for a list of objects of type "Class", use "@param integer[] $a" for a list of integers and so on...'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Iterable type hint for the parameter "$a" in method "missingParamDoc::y" must be documented to specify the exact type. Use "@param Class[] $a" for a list of objects of type "Class", use "@param integer[] $a" for a list of integers and so on...'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Collection type hint for the parameter "$b" in method "missingParamDoc::z" must be documented to to specify the exact type. Use Collection|Class[]'
        );
    }

    public function testMissingReturnTypeHintDoc(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/missingDocForReturnTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Return type hint for a method "missingDocForReturnTypeHint::x" must be documented to specify their exact type, Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers and so on...'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Return type hint for a method "missingDocForReturnTypeHint::y" must be documented to specify their exact type, Use "@return Class[]" for a list of classes, use "@return integer[]" for a list of integers and so on...'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Return type hint for a method "missingDocForReturnTypeHint::z" must be documented to specify their exact type, use Collection::toArray() instead'
        );
    }

    public function testMixedTypeHint(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/MixedTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testUnionTypeHint(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/UnionTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(3, 0, $errors, $file);
    }

    public function testTrait(): void
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/ValidTypeHintsTrait.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(0, 0, $errors, $file);
    }
}
