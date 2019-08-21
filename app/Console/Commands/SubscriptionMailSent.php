<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\EmailSubscriber;
use Mail;
use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionMailSent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $date = Carbon::today()->subDays(7);
        $articles = Article::where('created_at', '>=', $date)->get();


//      $subscribers = DB::table('email_subscribers')->get();
        $email = ['tonypcworld@gmail.com'];


            Mail::send('mail_template.subscription_mail_send', ['article'=>$articles], function ($message) use ($email)
            {
                $message->subject('newsletter');
                $message->from('tonykhan658@gmail.com', 'OnlineBooksReview');
                $message->to($email);
            });


    }
}
