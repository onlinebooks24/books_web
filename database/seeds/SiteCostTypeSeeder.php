<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\SiteCostType;

class SiteCostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteCostType::truncate();
        $date_time = Carbon::now()->toDateTimeString();
        SiteCostType::insert([
            [
                'id'       => '1',
                'name'       => 'article',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '2',
                'name'       => 'seo',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '3',
                'name'       => 'social',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '4',
                'name'       => 'domain',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '5',
                'name'       => 'hosting',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '6',
                'name'       => 'email',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '7',
                'name'       => 'development',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ],
            [
                'id'       => '8',
                'name'       => 'others',
                'created_at' => $date_time,
                'updated_at' => $date_time
            ]
        ]);
    }
}
