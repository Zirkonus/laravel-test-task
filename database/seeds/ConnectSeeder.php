<?php

use Illuminate\Database\Seeder;

class ConnectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('connect')->insert([
			'app' => 'zohoapi',
			'code' => 'dEx5dTeVwpVS',
			'token' => 'd95f56cf99c1ccb609f77aaeeaf4118d'
		]);
    }
}
