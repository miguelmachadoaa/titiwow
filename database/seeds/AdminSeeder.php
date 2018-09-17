<?php

use Faker\Factory;
use App\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends DatabaseSeeder {

	public function run()
	{
		DB::table('users')->truncate(); // Using truncate function so all info will be cleared when re-seeding.
		DB::table('roles')->truncate();
		DB::table('role_users')->truncate();
		DB::table('activations')->truncate();

		$admin = Sentinel::registerAndActivate(array(
			'email'       => 'obarrerafranco@gmail.com',
			'password'    => "123456",
			'first_name'  => 'John',
			'last_name'   => 'Doe',
		));

		$masterfile = Sentinel::registerAndActivate(array(
			'email'       => 'crearemosweb@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$adminRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Admin',
			'slug' => 'admin',
			'permissions' => array('admin' => 1),
		]);

		$masterfileRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Masterfile',
			'slug' => 'masterfile',
			'permissions' => array('masterfile' => 1),
		]);

        $userRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Cliente',
			'slug'  => 'cliente',
		]);

		$alpinistaRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Alpinista',
			'slug'  => 'alpinista',
		]);

		$referidoRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Referido',
			'slug'  => 'referido',
		]);

		$corporativoRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Corporativo',
			'slug'  => 'corporativo',
		]);


		$admin->roles()->attach($adminRole);
		$masterfile->roles()->attach($masterfileRole);

		$this->command->info('Admin User created with username admin@admin.com and password admin');
	}

}