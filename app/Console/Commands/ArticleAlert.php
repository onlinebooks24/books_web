<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Plivo\RestClient;

class ArticleAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:alert';

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
        $voice_message = "I am from online books review. Please complete javascript article as soon as possible.";
        $voice_message =  $voice_message. 'I again repeat '. $voice_message;

        $voice_message_url = route('voice_call.call_template', $voice_message);

        $client = new RestClient("MAMDY4ZJNJYTQ0MZJKMZ", "ZjFiNDNjMDNkOTEzNmJjMmVjYjJiZTc2OTViMmFi");
        $call_made = $client->calls->create(
            '+14154847489',
            ['+8801670633325'],
            $voice_message_url,
            'GET'
        );
    }
}
