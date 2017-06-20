<?php

use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<5;$i++){
            DB::table('contacts')->insert([
                'first_name' => str_random(10),
                'last_name' => str_random(10),
                'email' => str_random(10).'@gmail.com',
                'phone' => mt_rand(1000000000, 9999999999),
            ]);
        }
    }
}
