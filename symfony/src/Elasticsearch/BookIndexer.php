<?php

namespace App\Elasticsearch;

use App\Entity\Book;
use App\Repository\BookRepository;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BookIndexer
{
    private $client;
    private $bookRepository;
    private $router;

    public function __construct(Client $client, BookRepository $bookRepository, UrlGeneratorInterface $router)
    {
        $this->client = $client;
        $this->bookRepository = $bookRepository;
        $this->router = $router;
    }

    public function buildDocument(Book $book)
    {
        return new Document(
            $book->getId(), // Manually defined ID
            [
                'title'         => $book->getTitle(),
                'summary'       => $book->getSummary(),
                'author'        => $book->getAuthor(),
                'language'      => $book->getLanguage(),
                'indexation'    => $book->getIndexation(),

                // Not indexed but needed for display
                'url'           => $this->router->generate('book_show', ['id' => $book->getId()], UrlGeneratorInterface::ABSOLUTE_PATH),
                'date'          => $book->getPublishDate()->format('M d, Y'),
            ],
            "book" // Types are deprecated, to be removed in Elastic 7
        );
    }

    public function buildDocumentByIdOnly(int $id)
    {
        return new Document(
            $id,
            [],
            "book"
        );
    }

    public function indexAllDocuments($indexName)
    {
        $allBooks = $this->bookRepository->findAll();
        $index = $this->client->getIndex($indexName);

        $documents = [];
        foreach ($allBooks as $book) {
            $documents[] = $this->buildDocument($book);
        }

        $index->addDocuments($documents);
        $index->refresh();
    }
}