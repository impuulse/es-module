<?php

namespace App\Services\Search\Commands;

use App\Services\Search\Repositories\ProductSetSearchRepository;
use Illuminate\Console\Command;

class ProductSetsReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:product-sets-reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all product sets to ElasticSearch';

    private ProductSetSearchRepository $productSetSearchRepository;

    /**
     * Create a new command instance.
     *
     * @param ProductSetSearchRepository $productSetSearchRepository
     */
    public function __construct(ProductSetSearchRepository $productSetSearchRepository)
    {
        parent::__construct();

        $this->productSetSearchRepository = $productSetSearchRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Индексация подборок товаров началась, это может занять некоторое время...');

        $message = $this->productSetSearchRepository->reindex();

        $this->info("\n$message");
    }
}
