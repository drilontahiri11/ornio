<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Falmur',
            'email' => 'flamur.mavraj@ornio.no',
            'password' => bcrypt('Ornio2018'),
            'role_id'=>\App\User::ROLE_ADIMN,
            'created_at'=>\Carbon\Carbon::now(),
            'updated_at'=>\Carbon\Carbon::now(),
        ]);
    }
}
