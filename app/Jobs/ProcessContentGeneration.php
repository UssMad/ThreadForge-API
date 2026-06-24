<?php

namespace App\Jobs;

use App\Models\RawContent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ProcessContentGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public RawContent $rawContent;

    public function __construct(RawContent $rawContent)
    {
        $this->rawContent = $rawContent;
    }

    public function handle(): void
    {
        // AI generation will be implemented later.
    }
}
