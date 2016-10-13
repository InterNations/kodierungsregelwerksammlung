<?php
require_once __DIR__ . '/../AbstractTestCase.php';

class InterNations_Tests_Sniffs_BestPractice_CommonDependenciesSniffTest
    extends InterNations_Tests_Sniffs_AbstractTestCase
{
    public function testDoctrineCommonVariableNamesAreEnforced()
    {
        $file = __DIR__ . '/Fixtures/CommonDependencies/Doctrine.php';
        $errors = $this->analyze(['InterNations/Sniffs/BestPractice/CommonDependenciesSniff'], [$file]);

        $this->assertReportCount(10, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$entityManager" ("EntityManager") of method "__construct" must be called "$em"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$objectManager" ("ObjectManager") of method "__construct" must be called "$om"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$objectManagerProperty" assigned from parameter "$objectManager" ("ObjectManager") of method '
            . '"__construct" must be called "$om"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$entityManagerProperty" assigned from parameter "$entityManager" ("EntityManager") of method '
            . '"__construct" must be called "$em"'
        );


        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$entityManager" ("EntityManager") of method "setEntityManagerProperty" must be called "$em"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$entityManagerProperty" assigned from parameter "$entityManager" ("EntityManager") of method '
                . '"setEntityManagerProperty" must be called "$em"'
        );


        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$objectManager" ("ObjectManager") of method "setObjectManagerProperty" must be called "$om"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$objectManagerProperty" assigned from parameter "$objectManager" ("ObjectManager") of method '
                . '"setObjectManagerProperty" must be called "$om"'
        );


        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$entityManager" ("EntityManagerInterface") of method "setAnotherEntityManager" must '
                . 'be called "$em"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$entityManagerProperty" assigned from parameter "$entityManager" ("EntityManagerInterface") '
                . 'of method "setAnotherEntityManager" must be called "$em"'
        );
    }

    public function testStaticVariableNamesAreEnforced()
    {
        $file = __DIR__ . '/Fixtures/CommonDependencies/StaticVariants.php';
        $errors = $this->analyze(['InterNations/Sniffs/BestPractice/CommonDependenciesSniff'], [$file]);

        $this->assertReportCount(4, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$entityManager" ("EntityManager") of method "setEntityManager" must be called "$em"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$entityManager" assigned from parameter "$entityManager" ("EntityManager") of method '
                . '"setEntityManager" must be called "$em"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$entityManager" ("EntityManager") of method "setAnotherEntityManager" must be called "$em"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$entityManager" assigned from parameter "$entityManager" ("EntityManager") of method '
                . '"setAnotherEntityManager" must be called "$em"'
        );
    }
}
