<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_Whitespace_OverlongParameterSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testOverlongParameterDeclaration()
    {
        $file = __DIR__ . '/Fixtures/Parameter/OverlongParameterDeclaration.php';
        $errors = $this->analyze(['InterNations/Sniffs/Whitespace/OverlongParameterSniff'], [$file]);

        $this->assertReportCount(7, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'All arguments of sameLine() should be in the same line',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationSameLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$arg3" of variousLines() should be in a distinct line. Expected line 13, got 12',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$arg4" of variousLines() should be in a distinct line. Expected line 14, got 13',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$arg5" of variousLines() should be in a distinct line. Expected line 15, got 13',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$arg6" of variousLines() should be in a distinct line. Expected line 16, got 13',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$arg7" of variousLines() should be in a distinct line. Expected line 17, got 14',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$arg8" of variousLines() should be in a distinct line. Expected line 18, got 15',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
    }

    public function testOverlongParameterInvocation()
    {
        $file = __DIR__ . '/Fixtures/Parameter/OverlongInvocation.php';
        $errors = $this->analyze(['InterNations/Sniffs/Whitespace/OverlongParameterSniff'], [$file]);

        $this->assertReportCount(14, 0, $errors, $file);
        /** Method invocation */
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of $this->call() should be in a distinct line. Expected line 63, got 62',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        /** Method invocation from a variable */
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of $this->$method() should be in a distinct line. Expected line 76, got 75',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of $this->$methodName() should be in a distinct line. Expected line 84, got 83',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );

        /** Static methods */
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of static::call() should be in a distinct line. Expected line 96, got 95',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of self::call() should be in a distinct line. Expected line 108, got 107',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of OverlongInvocation::call() should be in a distinct line. Expected line 120, got 119',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );


        /** Static methods dynamic invocation */
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of static::$method() should be in a distinct line. Expected line 133, got 132',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of self::$method() should be in a distinct line. Expected line 141, got 140',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of OverlongInvocation::$method() should be in a distinct line. Expected line 149, got 148',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );

        /** Static methods dynamic invocation with curly braces */
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of OverlongInvocation::$methodName() should be in a distinct line. Expected line 157, got 156',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of static::$methodName() should be in a distinct line. Expected line 165, got 164',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of self::$methodName() should be in a distinct line. Expected line 173, got 172',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );

        /** Functions */
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of func() should be in a distinct line. Expected line 185, got 184',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$overlyLongArgumentName7" of $functionName() should be in a distinct line. Expected line 194, got 193',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
    }

    public function testNestedOverlongInvocation()
    {
        $file = __DIR__ . '/Fixtures/Parameter/OverlongInvocationNested.php';
        $errors = $this->analyze(['InterNations/Sniffs/Whitespace/OverlongParameterSniff'], [$file]);

        $this->assertReportCount(4, 0, $errors, $file);

        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$baz" of func1() should be in a distinct line. Expected line 7, got 6',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$bar" of func5() should be in a distinct line. Expected line 20, got 19',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "function" of func12() should be in a distinct line. Expected line 43, got 44',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$foo" of ClassName() should be in a distinct line. Expected line 54, got 55',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
    }

    public function testComplexOverlongInvocation()
    {
        $file = __DIR__ . '/Fixtures/Parameter/OverlongInvocationComplex.php';
        $errors = $this->analyze(['InterNations/Sniffs/Whitespace/OverlongParameterSniff'], [$file]);

        $this->assertReportCount(3, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$baz" of func4() should be in a distinct line. Expected line 23, got 22',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$baz" of func5() should be in a distinct line. Expected line 30, got 29',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Argument "$baz" of func6() should be in a distinct line. Expected line 37, got 38',
            'InterNations.Whitespace.OverlongParameter.ArgumentDeclarationDistinctLine',
            5
        );
    }
}
