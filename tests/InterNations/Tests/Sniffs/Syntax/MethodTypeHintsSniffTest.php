<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Syntax_MethodTypeHintsSniffTest extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testValidMethodTypeHints()
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/ValidTypeHints.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);

        $this->assertReportCount(0, 0, $errors, $file);
    }

    public function testNoArgumentFound()
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/NoArgumentFound.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected argument for this magic method "NoArgumentFound::__construct" found nothing...'
        );
    }

    public function testWrongArgumentForMethod()
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/WrongArgumentForMethod.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(1, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected no arguments for this magic method "WrongArgumentForMethod::__clone" found "$request"'
        );
    }

    public function testInvalidParamTypeHints()
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/MissingParameters.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected Type hint for the parameter "$context" in method "MissingParameters::postAction"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Expected Type hint for the parameter "$entityType" in method "MissingParameters::postAction"'
        );
    }

    public function testWrongTypeHints()
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/WrongTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(3, 0, $errors, $file);
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
            'Return type hint for a method "WrongTypeHint::__call" must be documented to specify
                    their exact type, Use "@return Class[]" for a list of classes, use "@return integer[]" for a list
                    of integers and so on...'
        );
    }

    public function testSuperfluousParamDoc()
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/SuperfluousParamDoc.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(2, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous parameter comment doc'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Superfluous parameter comment doc'
        );
    }

    public function testNoReturnTypeHint()
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

    public function testReturnTypeHint()
    {
        $file = __DIR__ . '/Fixtures/MethodTypeHints/ReturnTypeHint.php';
        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
        $this->assertReportCount(3, 0, $errors, $file);
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
            'No space should come before colon, exactly one space after colon, expected return type formatting to be "): Attendance" got ") : Attendance"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Return type hint for a method "ReturnTypeHint::testMissingDocForArrayReturnTypeHing" must be documented to specify
                    their exact type, Use "@return Class[]" for a list of classes, use "@return integer[]" for a list
                    of integers and so on...'
        );
    }


//    public function testInvalidParamTypeHints()
//    {
//        $file = __DIR__ . '/Fixtures/MethodTypeHints/InvalidParamTypeHints.php';
//        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
//        $this->assertReportCount(1, 0, $errors, $file);
//        $this->assertReportContains(
//            $errors,
//            $file,
//            'errors',
//            'Type hint is required for parameter "$x" in method "InvalidParamTypeHints::createFunction"'
//        );
//    }
//
//    public function testInvalidReturnTypeHints()
//    {
//        $file = __DIR__ . '/Fixtures/MethodTypeHints/InvalidReturnTypeHints.php';
//        $errors = $this->analyze(['InterNations/Sniffs/Syntax/MethodTypeHintsSniff'], [$file]);
//        $this->assertReportCount(1, 0, $errors, $file);
//        $this->assertReportContains(
//            $errors,
//            $file,
//            'errors',
//            'Php 7 style return Type hint is required for the method "InvalidReturnTypeHints::createFunction"'
//        );
//    }
}
