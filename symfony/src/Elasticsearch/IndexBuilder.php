<?php

namespace App\Elasticsearch;

use Elastica\Client;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexBuilder extends AbstractController
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create()
    {
      $index = $this->client->getIndex($this->get_name_db());

      if(!$index->exists()){
        $settings = Yaml::parse(
          file_get_contents(
            __DIR__.'/../../config/elasticsearch_index_book.yaml'
          )
        );

        // We build our index settings and mapping
        $index->create($settings, true);
      }

      return $index;
    }

    public function get_name_db()
    {
      return substr(parse_url($this->getParameter('db'))['path'], 1);
    }
}