<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OAuthClientTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		\DB::table('oauth_clients')->insert(array('id' => 1, 
				'secret' => 'secret', 
				'name' => 'App', 
				'created_at' => '2015-11-29 23:42:00',
				'updated_at' => '2015-11-29 23:42:00'));
	}

}
