<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\NotificationType;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotificationType::truncate();
        $date_time = Carbon::now()->toDateTimeString();
        NotificationType::insert([
            [
                'id'       => '1',
                'name'       => 'voice_twilio',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '2',
                'name'       => 'sms_twilio',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ]
        ]);
    }
}
