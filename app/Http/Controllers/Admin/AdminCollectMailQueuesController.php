<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\CollectMailQueue;

class AdminCollectMailQueuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collect_mail_queues = CollectMailQueue::orderBy('created_at','desc')->Paginate(100);;
        return view('admin.collect_mail_queues.index', compact('collect_mail_queues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.collect_mail_queues.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $topic = $request['topic'];
        $category_id = $request['category_id'];
        $article_id = $request['article_id'];
        $custom_mail_template = $request['custom_mail_template'];
        $run_count = $request['run_count'];
        $last_time_run = $request['last_time_run'];
        $run_cron_job = $request['run_cron_job'];
        $limit_cron_job_attempt = $request['limit_cron_job_attempt'];

        $collect_mail_queue = new CollectMailQueue();
        $collect_mail_queue->topic = $topic;
        $collect_mail_queue->category_id = $category_id;
        $collect_mail_queue->article_id = $article_id;
        $collect_mail_queue->custom_mail_template = $custom_mail_template;
        $collect_mail_queue->run_count = $run_count;
        $collect_mail_queue->last_time_run = $last_time_run;
        $collect_mail_queue->run_cron_job = $run_cron_job;
        $collect_mail_queue->limit_cron_job_attempt = $limit_cron_job_attempt;
        $collect_mail_queue->save();

        $flash_message = 'Created Successfully';

        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_collect_mail_queues.index'));

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
