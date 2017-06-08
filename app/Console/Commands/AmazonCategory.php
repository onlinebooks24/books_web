<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Helper;


class AmazonCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amazon:category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $search_query = [
            'Operation' => 'BrowseNodeLookup',
            'BrowseNodeId' => '549726',
        ];

        $amazon_response = Helper::amazonAdAPI($search_query);

    }
}
