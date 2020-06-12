<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Elasticsearch\BookIndexer;
use App\Elasticsearch\IndexBuilder;

class ElasticReindexCommand extends Command
{
    protected static $defaultName = 'elastic:reindex';

    private $indexBuilder;
    private $bookIndexer;

    public function __construct(IndexBuilder $indexBuilder, BookIndexer $bookIndexer)
    {
        $this->indexBuilder = $indexBuilder;
        $this->bookIndexer = $bookIndexer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Rebuild the Index and populate it.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $index = $this->indexBuilder->create();

        $this->bookIndexer->indexAllDocuments($index->getName());

        $io->success('Index populated and ready!');

        return 0;
    }
}
