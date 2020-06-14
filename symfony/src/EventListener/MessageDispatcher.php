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
            Events::preRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->dispatch($args, __FUNCTION__);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->dispatch($args, __FUNCTION__);
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->dispatch($args, __FUNCTION__);
    }

    private function dispatch(LifecycleEventArgs $args, string $operation)
    {
        $entity = $args->getObject();
        if ($entity instanceof Book) {
            $message = new BookMessage($entity->getId(), $operation);
            $this->bus->dispatch($message);
        }
    }
}