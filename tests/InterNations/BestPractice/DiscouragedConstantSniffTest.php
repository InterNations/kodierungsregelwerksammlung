<?php
namespace InterNations\Sniffs\Tests\BestPractice;

use InterNations\Sniffs\Tests\AbstractTestCase;

class DiscouragedConstantSniffTest extends AbstractTestCase
{
    public function testDoctrineTypesSimpleArrayIsNotEncouraged(): void
    {
        $file = __DIR__ . '/Fixtures/DiscouragedConstant/SomeRepository.php';
        $errors = self::analyze(['InterNations/Sniffs/BestPractice/DiscouragedConstantSniff'], [$file]);

        self::assertReportCount(1, 0, $errors, $file);
        self::assertReportContains(
            $errors,
            $file,
            'errors',
            sprintf(
                'Usage of %s is discouraged. '
                . 'It`s more likely that you want %s or %s instead to escape SQL IN() statements.',
                'Types::SIMPLE_ARRAY',
                '\Doctrine\DBAL\Connection::PARAM_STR_ARRAY',
                '\Doctrine\DBAL\Connection::PARAM_INT_ARRAY',
            )
        );
    }
}
