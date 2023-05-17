<?php
namespace InterNations\BestPractice\Fixtures\DiscouragedConstant;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Types\Types;
class SomeRepository
{
    private EntityManagerInterface $em;
    public function tryFind(): int
    {
        $sql = 'SELECT 1 FROM foo WHERE bar IN(:inputs)';

        $params = [
            'inputs' => [1,2,3],
        ];

        $types = [
            'inputs' => Types::SIMPLE_ARRAY,
        ];

        return (int) $this->em->getConnection()
            ->executeStatement($sql, $params, $types);
    }
}
