<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Hash;

class HashCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash:make 
                            {text : the value to be consulted} 
                            {--requests= : number of requests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a hash of the input text';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $requests = $this->option('requests');
        $textHash = $this->argument('text');

        $counter = 1;

        while ($counter <= $requests) {
            $response = Http::post(
                env('APP_URL') . '/api/v1/hashes/store',
                ['text' => $textHash]
            );

            if ($response->failed()) {
                $this->error($response->json()['data'] ?? $response->json()['error']['message']);
                break;
            }

            $textHash = $response->json()['data']['hash'];
            $this->info("Request {$counter} of {$requests}: OK");

            $counter++;
        }

        $this->table(
            ['Batch', 'Num. Block', 'Input string', 'Key', 'Hash', 'Attempts'],
            Hash::all(['batch', 'id', 'input', 'key', 'hash', 'attempts'])
        );
    }
}
