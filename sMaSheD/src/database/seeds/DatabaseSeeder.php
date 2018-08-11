<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // DB::table('users')->insert([
        //     'name' => 'smashed',
        //     'email' => 'smashed@example.com',
        //     'password' => Hash::make('smashed_pass'),
        // ]);
        DB::table('users')->insert([
            'name' => 'excel2018',
            'email' => 'excel2018@fit.vutbr.cz',
            'password' => Hash::make('excel2018_pass'),
        ]);
    }
}
