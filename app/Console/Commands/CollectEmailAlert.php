<?php

namespace App\Console\Commands;

use App\Models\EmailSubscriber;
use Illuminate\Console\Command;
use Session;
use Mail;
use Carbon\Carbon;

class CollectEmailAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:collect_email_alert';

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
        $email_subscribers = EmailSubscriber::where(['temporary' => true, 'last_send_email' => null])->get();

        foreach($email_subscribers as $email_subscriber){
            $email_data['email'] = $email_subscriber->email;
            $email_data['title'] = $email_subscriber->collect_mail_queue->article->title;
            $article = $email_subscriber->collect_mail_queue->article;
            $products = $article->products;

            Mail::send(['html'=>'mail_template.collect_email_alert'], ['article' => $article, 'products' => $products, 'email' => $email_data['email'] ], function ($message) use ($email_data)
            {
                $message->subject($email_data['title'] . ' | ' . 'OnlineBooksReview');
                $message->from('info@onlinebooksreview.com', 'OnlineBooksReview');

                $message->to($email_data['email']);
            });

            $email_subscriber->email_count += 1;
            $email_subscriber->last_send_email = Carbon::now();
            $email_subscriber->save();


            sleep(10);
        }

        Mail::send(['html'=>'mail_template.collect_mail_queue'], [], function ($message)
        {
            $message->subject('Collect article is finished for today');
            $message->from('info@onlinebooksreview.com', 'OnlineBooksReview');

            $message->to('mashpysays@gmail.com');
        });

    }

}
