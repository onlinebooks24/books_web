<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\NotificationType;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\SchedulerJob;
use App\User;

class AdminSchedulerJobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $scheduler_jobs = SchedulerJob::orderBy('created_at', 'desc')->paginate(50);
        return view('admin.scheduler_jobs.index', compact('scheduler_jobs'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notification_types = NotificationType::orderBy('created_at', 'desc')->get();
        $users = User::where('role_type_id', '2')
                        ->orwhere('role_type_id', '1')->get();
        return view('admin.scheduler_jobs.create', compact('notification_types', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task_description = $request['task_description'];
        $short_message = $request['short_message'];
        $task_completed = $request['task_completed'];
        $notification_interval = $request['notification_interval'];
        $notification_type_id = $request['notification_type_id'];
        $deadline = $request['deadline'];
        $article_id = $request['article_id'];
        $user_id = $request['user_id'];
        $phone_no = $request['phone_no'];
        $article = null;
        $username = 'Dear,';
        $send_call = null;

        if(empty($phone_no)){
            $user = User::find($user_id);
            if(!empty($user)){
                $phone_no = $user->phone;
                $username = $user->name;
            }
        }

        if(!empty($article_id)){
            $article = Article::find($article_id);
            $voice_message = ",.. Hi, $username. Hope you are fine. I am from online books review. "
                . " You task was to work on ". $article->title
                . ". Please try to complete this article as soon as possible and inform to Mashpy. Bye Bye. Take care.";
        } //if you update here voice message, also update on SchedulerJobAlert.php command.

        if(!empty($short_message)){
            $voice_message = $short_message;
        }

        if(!empty($phone_no) && !empty($voice_message)){
            if(empty($deadline)){
                $accountId = env('twilioKeyAccountId');

                $token = env('twilioKeySecret');
                $fromNumber = config('constants.twilio_from_number');

                $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
                $send_call = $twilio->call($phone_no, function ($message) use ($voice_message) {
                    $message->say(str_repeat($voice_message, 2), ['voice' => 'woman', 'language' => 'en']);
                });

                //Please remove it later.
                $admin_number = config('constants.admin_number');

                if ($phone_no != $admin_number || $phone_no != '+8801823387518'){
                    $voice_message = 'Copy call to you.'. $voice_message;
                    $call_to_admin = $twilio->call($admin_number, function ($message) use ($voice_message) {
                        $message->say(str_repeat($voice_message, 2), ['voice' => 'woman', 'language' => 'en']);
                    });
                }
                //Please remove it later.

            } else {
                $task_completed = false;
            }

            $scheduler_job = new SchedulerJob();
            $scheduler_job->task_description = $task_description;
            $scheduler_job->short_message = $short_message;
            $scheduler_job->task_completed = $task_completed;
            $scheduler_job->notification_interval = $notification_interval;
            $scheduler_job->notification_type_id = $notification_type_id;
            $scheduler_job->last_notification = Carbon::now();
            $scheduler_job->deadline = $deadline;
            $scheduler_job->article_id = $article_id;
            $scheduler_job->user_id = $user_id;
            $scheduler_job->transaction_no = $send_call->sid;
            $scheduler_job->count += 1;
            $scheduler_job->phone_no = $request['phone_no'];
            $scheduler_job->save();
            $flash_message = 'Successfully Saved';
        } else {
            $flash_message = 'Something wrong on mobile no or voice message';
        }

        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_scheduler_jobs.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

}
