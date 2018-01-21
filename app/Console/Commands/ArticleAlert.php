<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use Carbon\Carbon;

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
        $last_article = Article::where('status', true)->orderBy('created_at', 'desc')->first();

        $created = new Carbon($last_article->created_at);
        $now = Carbon::now();
        $difference = ($created->diff($now)->days);

        if($difference >= 1){
            $accountId = env('twilioKeyAccountId');
            $token = env('twilioKeySecret');
            $fromNumber = '+16138006902';
            $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
            $test = $twilio->call('+8801670633325', function ($message) {
                $voice_message = "Hi Mashpy. Hope you are fine. I am from online books review. Please publish new article as soon as possible ";
                $voice_message =  $voice_message. 'I again repeat '. $voice_message;
                $message->say($voice_message);
            });
        }

    }
}
