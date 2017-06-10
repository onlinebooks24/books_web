<?php

use Illuminate\Database\Seeder;
use App\Models\RoleType;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_type_list = [
            [
            'id' => 1,
            'name' => 'admin'
            ],
            [
                'id' => 2,
                'name' => 'editor'
            ],
        ];

        $role_type = DB::table('role_types')->insert($role_type_list);

        $user = User::first();
        $user->role_type_id = '1';
        $user->update();
    }
}
