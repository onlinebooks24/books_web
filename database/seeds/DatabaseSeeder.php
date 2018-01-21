<?php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->command->info('----- Running Role Type Data Seeder -----');
        $this->call(RoleTypeSeeder::class);
        $this->call(SiteCostTypeSeeder::class);
        $this->call(NotificationTypeSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('----- Seeding Completed -----');

        Model::reguard();
    }
}
