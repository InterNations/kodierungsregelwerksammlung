<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Klass
{
    private static $entityManager;

    public static function setEntityManager(EntityManager $entityManager)
    {
        static::$entityManager = $entityManager;
    }

    public static function setAnotherEntityManager(EntityManager $entityManager)
    {
        self::$entityManager = $entityManager;
    }
}
