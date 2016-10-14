<?php
use Doctrine\ORM\EntityManager as Foo;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Klass
{
    private $entityManagerProperty;

    private $objectManagerProperty;

    public function __construct(EntityManager $entityManager, ObjectManager $objectManager, Something $something)
    {
        $this->entityManagerProperty = $entityManager;
        $this->objectManagerProperty = $objectManager;
    }

    public function setEntityManagerProperty(EntityManager $entityManager)
    {
        $this->entityManagerProperty = $entityManager;
    }

    public function setObjectManagerProperty(ObjectManager $objectManager)
    {
        $this->objectManagerProperty = $objectManager;
    }

    public function setAnotherEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManagerProperty = $entityManager;
    }

    public function getSomething()
    {
    }
}
