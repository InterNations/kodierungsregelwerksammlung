<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_BestPractice_ExceptionTestSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testExistingExceptions()
    {
        $file = __DIR__ . '/Fixtures/ExceptionTest/ExceptionTest.php';
        $errors = $this->analyze(['InterNations/Sniffs/BestPractice/ExceptionTestSniff'], [$file]);

        $this->assertReportCount(4, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Annotation @expectedException found. Using annotations in test cases is error-prone because a simple typo '
            . 'can lead to false positives. Use setExpectedException(Exception::class) instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Annotation @expectedException found. Using annotations in test cases is error-prone because a simple typo '
                . 'can lead to false positives. Use setExpectedException(Exception::class, \'Test\', 123) instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Annotation @expectedException found. Using annotations in test cases is error-prone because a simple typo '
                . 'can lead to false positives. Use setExpectedException(Exception::class, , 123) instead'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Annotation @expectedException found. Using annotations in test cases is error-prone because a simple typo '
                . 'can lead to false positives. Use '
                . 'setExpectedExceptionRegExp(Exception::class, \'/(This|That)/\', 123) instead'
        );
    }
}
