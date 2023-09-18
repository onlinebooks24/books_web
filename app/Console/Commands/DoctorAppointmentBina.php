<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\SchedulerJob;
use App\User;
use Carbon\Carbon;
use Goutte\Client;

class DoctorAppointmentBina extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctor:appointment_bina';

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
            if(date('Y-m-d H:i:s') == "2022-11-22 00:00:00"){
                $output = $this->hitCresentBina();   
            }
            $ConsoleOutput = new \Symfony\Component\Console\Output\ConsoleOutput();
            $ConsoleOutput->writeln(date('Y-m-d H:i:s'));
            usleep(300 * 1000);
        }
    }
    
    private function hitCresentBina(){
        $client = new Client();
        $params['AgeDay'] = '20';
        $params['AgeMonth'] = '1';
        $params['AgeYear'] = '27';
        $params['Dob'] = '1995-09-09';
        $params['DrCode'] = '0168';
        $params['DrName'] = 'Professor Dr. Munira Ferdausi MBBS, MPH, MS (Obs & Gynae)';
        // $params['DrCode'] = 'S014';
        // $params['DrName'] = 'Prof. Dr. Sangjukta Saha  MBBS, MS (Gynae & Obs)';
        $params['MobileNo'] = '01622761573';
        $params['PatientName'] = 'MAHFUJA AKTER';
        $params['Sex'] = 'Female';
        $params['VisitDate'] = '2022-11-23';
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
