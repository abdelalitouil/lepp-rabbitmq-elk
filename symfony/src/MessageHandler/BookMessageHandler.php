<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\BookMessage;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookMessageHandler implements MessageHandlerInterface
{
    private $bookRepository;
    private $em;

    public function __construct(BookRepository $bookRepository, EntityManagerInterface $em)
    {
        $this->bookRepository = $bookRepository;
        $this->em = $em;
    }

    public function __invoke(BookMessage $message)
    {
        $book = $this->bookRepository->find($message->getBookId());
        $book->setIndexation('Indexed');
        $this->em->persist($book);
        $this->em->flush();
        // Index book
        // ...
    }
}