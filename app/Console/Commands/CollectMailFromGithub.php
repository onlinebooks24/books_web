<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Session;
use Mail;
use App\Http\Controllers\Admin\AdminTemporaryEmailController;

class CollectMailFromGithub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:collect_mail_from_github';

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
        AdminTemporaryEmailController::index();
    }
}
