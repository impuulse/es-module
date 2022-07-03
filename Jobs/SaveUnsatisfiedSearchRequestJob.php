<?php

namespace App\Services\Search\Jobs;

use App\Services\Search\Repositories\UnsatisfiedSearchRequestsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class SaveUnsatisfiedSearchRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected string $term;

    /**
     * Create a new job instance.
     *
     * @param string $term
     */
    public function __construct(string $term)
    {
        $this->term = $term;
    }

    private function getUnsatisfiedSearchRequestsRepository(): UnsatisfiedSearchRequestsRepository
    {
        return app(UnsatisfiedSearchRequestsRepository::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->getUnsatisfiedSearchRequestsRepository()->create(['term' => $this->term]);
        } catch (Throwable $exception) {
            $message = __('errors.can_not_create_model') . PHP_EOL;
            $message .= $exception->getMessage();
            Log::error($message);
        }
    }
}
