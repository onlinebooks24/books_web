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
        $role_type = RoleType::create(array(
                        'name' => 'admin'
                    ));

        $user = User::first();
        $user->role_type_id = $role_type->id;
        $user->update();
    }
}
