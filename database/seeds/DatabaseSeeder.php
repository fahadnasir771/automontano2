<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // insert sample user as the system admin
        $user = DB::table('users')->insert([
            'name' => 'Fahad',
            'title' => 'Admin',
            'email' => 'fahad@admin.com',
            'password' => bcrypt("123456"),
            'role' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
         ]);

         /**
          * Role 1 => Admin
          * Role 2 => Acceptor
          * Role 3 => Operator
          * Role 4 => Customer
          */
    }
}
