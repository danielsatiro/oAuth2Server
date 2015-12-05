<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		User::create([
			'name' => 'Daniel Satiro', 
			'email' => 'danielsatiro2003@yahoo.com.br',
			'password' => '$2y$10$rhnCD6Pc./qhHqnGdLr.qeCVttT6ALSNTN.4wL8sL7rUtboy6Farq']);
		User::create([
			'name' => 'Daniel Satiro 2', 
			'email' => 'danielsatiro2004@hotmail.com.br',
			'password' => '$2y$10$rhnCD6Pc./qhHqnGdLr.qeCVttT6ALSNTN.4wL8sL7rUtboy6Farq']);
	}

}
