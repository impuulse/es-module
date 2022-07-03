<?php

namespace App\Services\Search\Commands;

use App\Services\Search\Repositories\OfferSearchRepository;
use Illuminate\Console\Command;

class OffersReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:offers-reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all offers to ElasticSearch';

    private OfferSearchRepository $offerSearchRepository;

    /**
     * Create a new command instance.
     *
     * @param OfferSearchRepository $offerSearchRepository
     */
    public function __construct(OfferSearchRepository $offerSearchRepository)
    {
        parent::__construct();

        $this->offerSearchRepository = $offerSearchRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Индексация вариантов товаров началась, это может занять некоторое время...');

        $message = $this->offerSearchRepository->reindex();

        $this->info("\n$message");
    }
}
