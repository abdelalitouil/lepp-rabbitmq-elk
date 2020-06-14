<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Elastica\Query;
use Elastica\Query\QueryString;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use App\Elasticsearch\IndexBuilder;

/**
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    private $router;
    private $indexBuilder;

    public function __construct(UrlGeneratorInterface $router, IndexBuilder $indexBuilder)
    {
        $this->router = $router;
        $this->indexBuilder = $indexBuilder;
    }

    /**
     * @Route("/docrine", name="search_doctrine", methods={"GET"})
     */
    public function searchDoctrine(): Response
    {
        return $this->render('search/search_doctrine.html.twig');
    }

    /**
     * @Route("/elasticsearch", name="search_elasticsearch", methods={"GET"})
     */
    public function searchElasticsearch(): Response
    {
        return $this->render('search/search_elasticsearch.html.twig');
    }

    /**
     * @Route("/searchDoctrineJson", name="search_doctrine_json", methods={"GET"})
     */
    public function searchDoctrineJson(Request $request, BookRepository $bookRepository): Response
    {
        $term       = $request->query->get('term');
        $foundBooks = $bookRepository->findByTerm($term);

        $results    = [];
        foreach ($foundBooks as $book) {
            $results[] = [
                'id'            => $book->getId(),
                'title'         => $book->getTitle(),
                'summary'       => $book->getSummary(),
                'author'        => $book->getAuthor(),
                'language'      => $book->getLanguage(),
                'indexation'    => $book->getIndexation(),

                // Not indexed but needed for display
                'url'           => $this->router->generate('book_show', ['id' => $book->getId()], UrlGeneratorInterface::ABSOLUTE_PATH),
                'date'          => $book->getPublishDate()->format('M d, Y'),
            ];
        }

        return $this->json($results);
    }

    /**
     * @Route("/searchElasticsearchJson", name="search_elasticsearch_json", methods={"GET"})
     */
    public function searchElasticsearchJson(Request $request): Response
    {
        $term       = $request->query->get('term');

        $elasticaQuery = new QueryString($term);
        $query      = new Query($elasticaQuery);

        $foundBooks = $this->indexBuilder->create()->search($query->setSize(4000));

        $results    = [];
        foreach ($foundBooks as $book) {
            $results[] = [
                'id'            => $book->getId(),
                'title'         => $book->getSource()['title'],
                'summary'       => $book->getSource()['summary'],
                'author'        => $book->getSource()['author'],
                'language'      => $book->getSource()['language'],
                'indexation'    => $book->getSource()['indexation'],

                // Not indexed but needed for display
                'url'           => $this->router->generate('book_show', ['id' => $book->getId()], UrlGeneratorInterface::ABSOLUTE_PATH),
                'date'          => $book->getSource()['date'],
            ];
        }

        return $this->json($results);
    }
}