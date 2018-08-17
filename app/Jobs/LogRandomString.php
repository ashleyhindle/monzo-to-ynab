<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogRandomString implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $randomString = '';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $randomString)
    {
        $this->randomString = $randomString;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        file_put_contents('/tmp/' . base64_encode($this->randomString), $this->randomString);
    }
}
