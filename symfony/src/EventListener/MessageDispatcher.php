<?php
namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\Events;
use App\Entity\Book;
use App\Message\BookMessage;

class MessageDispatcher implements EventSubscriber
{
    private $bus;

    public function __construct(MessageBusInterface $bus) {
        $this->bus = $bus;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args){}

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->dispatch($args);
    }

    public function dispatch(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Book) {
            $message = new BookMessage($entity->getId());
            $this->bus->dispatch($message);
        }
    }
}