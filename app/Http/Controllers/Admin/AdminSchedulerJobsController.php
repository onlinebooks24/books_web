<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        if(empty($user_id)){
            $phone_no = $request['phone_no'];
        }

        if(empty($phone_no)){
            $user = User::find($user_id);
            if(!empty($user)){
                $phone_no = $user->phone;
            }
        }


        if(!empty($phone_no)){
            $scheduler_job = new SchedulerJob();
            $scheduler_job->task_description = $task_description;
            $scheduler_job->short_message = $short_message;
            $scheduler_job->task_completed = $task_completed;
            $scheduler_job->notification_interval = $notification_interval;
            $scheduler_job->notification_type_id = $notification_type_id;
            $scheduler_job->deadline = $deadline;
            $scheduler_job->article_id = $article_id;
            $scheduler_job->user_id = $user_id;
            $scheduler_job->phone_no = $phone_no;
            $scheduler_job->save();
            $flash_message = 'Successfully Saved';
        } else {
            $flash_message = 'Something wrong on mobile no';
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
