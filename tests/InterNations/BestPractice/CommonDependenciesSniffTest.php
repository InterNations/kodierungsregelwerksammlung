<?php
namespace InterNations\Sniffs\Tests\BestPractice;

use InterNations\Sniffs\Tests\AbstractTestCase;

class CommonDependenciesSniffTest extends AbstractTestCase
{
    public function testDoctrineCommonVariableNamesAreEnforced(): void
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

    public function testStaticVariableNamesAreEnforced(): void
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

    public function testSymfonyVariableNamesAreEnforced(): void
    {
        $file = __DIR__ . '/Fixtures/CommonDependencies/Symfony.php';
        $errors = $this->analyze(['InterNations/Sniffs/BestPractice/CommonDependenciesSniff'], [$file]);

        $this->assertReportCount(4, 0, $errors, $file);
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$eventDispatcher" ("EventDispatcherInterface") of method "__construct" must '
            . 'be called "$dispatcher"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$engine" ("EngineInterface") of method "__construct" must be called "$templating"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Parameter "$ed" ("EventDispatcher") of method "__construct" must be called "$dispatcher"'
        );
        $this->assertReportContains(
            $errors,
            $file,
            'errors',
            'Property "$eventDispatcherProperty" assigned from parameter "$eventDispatcher" '
            . '("EventDispatcherInterface") of method "__construct" must be called "$dispatcher"'
        );
    }
}
