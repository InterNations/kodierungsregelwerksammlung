<?php
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class Klass
{
    private $eventDispatcherProperty;

    public function __construct(EventDispatcherInterface $eventDispatcher, EventDispatcher $ed, EngineInterface $engine)
    {
        $this->eventDispatcherProperty = $eventDispatcher;
    }
}
