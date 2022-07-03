<?php

namespace App\Services\Search\Commands;

use App\Services\Search\Repositories\CategorySearchRepository;
use Illuminate\Console\Command;

class CategoriesReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:categories-reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all categories to ElasticSearch';

    private CategorySearchRepository $categorySearchRepository;

    /**
     * Create a new command instance.
     *
     * @param CategorySearchRepository $categorySearchRepository
     */
    public function __construct(CategorySearchRepository $categorySearchRepository)
    {
        parent::__construct();

        $this->categorySearchRepository = $categorySearchRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Индексация категорий началась, это может занять некоторое время...');

        $message = $this->categorySearchRepository->reindex();

        $this->info("\n$message");
    }
}
