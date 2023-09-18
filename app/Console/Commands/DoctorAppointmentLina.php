<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\SchedulerJob;
use App\User;
use Carbon\Carbon;
use Goutte\Client;

class DoctorAppointmentLina extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctor:appointment_lina';

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
        $default_msg = "\"You wouldn\u0027t make Advance appiontment of this Doctor.\"";
        $output = $default_msg;
        while($output == $default_msg ){
           $output = $this->hitCresentLina();
        }
    }
    
     private function hitCresentLina(){
        $client = new Client();
        $params['AgeDay'] = '17';
        $params['AgeMonth'] = '1';
        $params['AgeYear'] = '28';
        $params['Dob'] = '1994-08-30';
        $params['DrCode'] = 'S014';
        $params['DrName'] = 'Prof. Dr. Sangjukta Saha  MBBS, MS (Gynae & Obs)';
        $params['MobileNo'] = '01631567446';
        $params['PatientName'] = 'MAHMUDA AKTER';
        $params['Sex'] = 'Female';
        $params['VisitDate'] = '2022-10-19';
        $params['VisitType'] = '';
        
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://202.191.127.170:8020/Appointment/Save', [
            'form_params' => $params
        ]);
        
        $content = $response->getBody()->getContents();
        $output_msg = $content . ' ' .date('Y-m-d H:i:s');
        $ConsoleOutput = new \Symfony\Component\Console\Output\ConsoleOutput();
        $ConsoleOutput->writeln($output_msg);
        $myfile = file_put_contents('storage/logs/doctor_logs.txt', $output_msg.PHP_EOL , FILE_APPEND | LOCK_EX);
        return $content;
    }
}
