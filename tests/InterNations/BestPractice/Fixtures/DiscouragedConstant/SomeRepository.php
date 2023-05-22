<?php
namespace InterNations\BestPractice\Fixtures\DiscouragedConstant;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Types\Type;
class SomeRepository
{
    private EntityManagerInterface $em;
    public function tryFind(): int
    {
        $sql = 'SELECT 1 FROM foo WHERE bar IN(:inputs) OR foobar NOT IN (:exclude)';

        $params = [
            'inputs' => [1,2,3],
            'exclude' => ['a', 'b']
        ];

        $types = [
            'inputs' => Types::SIMPLE_ARRAY,
            'exclude' => Type::SIMPLE_ARRAY,
        ];

        return (int) $this->em->getConnection()
            ->executeStatement($sql, $params, $types);
    }
}
