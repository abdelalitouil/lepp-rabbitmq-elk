<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\BookMessage;
use App\Repository\BookRepository;
use App\Elasticsearch\IndexBuilder;
use App\Elasticsearch\BookIndexer;
use App\Entity\Book;
use Psr\Log\LoggerInterface;

class BookMessageHandler implements MessageHandlerInterface
{
    private $bookRepository;
    private $em;
    private $indexBuilder;
    private $bookIndexer;
    private $logger;

    public function __construct(BookRepository $bookRepository, EntityManagerInterface $em, IndexBuilder $indexBuilder, BookIndexer $bookIndexer, LoggerInterface $logger)
    {
        $this->bookRepository = $bookRepository;
        $this->em = $em;
        $this->indexBuilder = $indexBuilder;
        $this->bookIndexer = $bookIndexer;
        $this->logger = $logger;
    }

    public function __invoke(BookMessage $message)
    {
        // get index of the book
        $index = $this->indexBuilder->create();
        switch ($message->getOperation()) {
            case 'postPersist':
                $book = $this->bookRepository->find($message->getBookId());
                $documents = array($this->bookIndexer->buildDocument($book));
                $this->indexingBook($book);
                $index->addDocuments($documents);
                break;
                
            case 'postUpdate':
                $book = $this->bookRepository->find($message->getBookId());
                $documents = array($this->bookIndexer->buildDocument($book));
                $this->indexingBook($book);
                $index->updateDocuments($documents);
                break;
            
            case 'preRemove':
                $documents = array($this->bookIndexer->buildDocumentByIdOnly($message->getBookId()));
                $index->deleteDocuments($documents);
                break;
        }
        $index->refresh();
    }

    public function indexingBook(Book $book)
    {
        $book->setIndexation('Indexed_' . date('d-m-Y H:i:s'));
        $this->em->persist($book);
        $this->em->flush();
    }
}