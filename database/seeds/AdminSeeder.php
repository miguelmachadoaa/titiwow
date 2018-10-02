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
			'email'       => 'admin@gmail.com',
			'password'    => "123456",
			'first_name'  => 'John',
			'last_name'   => 'Doe',
		));

		$shopmanager = Sentinel::registerAndActivate(array(
			'email'       => 'shopmanager@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$shopmanagercorp = Sentinel::registerAndActivate(array(
			'email'       => 'shopmanagercorp@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$masterfile = Sentinel::registerAndActivate(array(
			'email'       => 'masterfile@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$sac = Sentinel::registerAndActivate(array(
			'email'       => 'sac@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$cedi = Sentinel::registerAndActivate(array(
			'email'       => 'cedi@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$logistica = Sentinel::registerAndActivate(array(
			'email'       => 'logistica@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$finanzas = Sentinel::registerAndActivate(array(
			'email'       => 'finanzas@gmail.com',
			'password'    => "123456",
			'first_name'  => 'Orangel',
			'last_name'   => 'Barrera',
		));

		$adminRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Admin',
			'slug' => 'admin',
			'tipo' => 1,
			'permissions' => array('productos.index'=>true,'productos.create'=>true,'productos.store'=>true,'productos.show'=>true,'productos.edit'=>true,'productos.update'=>true,'productos.destroy'=>true,'categorias.index'=>true,'categorias.create'=>true,'categorias.store'=>true,'categorias.show'=>true,'categorias.edit'=>true,'categorias.update'=>true,'categorias.destroy'=>true,'marcas.index'=>true,'marcas.create'=>true,'marcas.store'=>true,'marcas.show'=>true,'marcas.edit'=>true,'marcas.update'=>true,'marcas.destroy'=>true,'ordenes.index'=>true,'ordenes.create'=>true,'ordenes.store'=>true,'ordenes.show'=>true,'ordenes.edit'=>true,'ordenes.update'=>true,'ordenes.destroy'=>true,'formaspago.index'=>true,'formaspago.create'=>true,'formaspago.store'=>true,'formaspago.show'=>true,'formaspago.edit'=>true,'formaspago.update'=>true,'formaspago.destroy'=>true,'configuracion.index'=>true,'configuracion.create'=>true,'configuracion.store'=>true,'configuracion.show'=>true,'configuracion.edit'=>true,'configuracion.update'=>true,'configuracion.destroy'=>true,'estatus.index'=>true,'estatus.create'=>true,'estatus.store'=>true,'estatus.show'=>true,'estatus.edit'=>true,'estatus.update'=>true,'estatus.destroy'=>true,'formasenvio.index'=>true,'formasenvio.create'=>true,'formasenvio.store'=>true,'formasenvio.show'=>true,'formasenvio.edit'=>true,'formasenvio.update'=>true,'formasenvio.destroy'=>true,'rolenvios.index'=>true,'rolenvios.create'=>true,'rolenvios.store'=>true,'rolenvios.show'=>true,'rolenvios.edit'=>true,'rolenvios.update'=>true,'rolenvios.destroy'=>true,'rolpagos.index'=>true,'rolpagos.create'=>true,'rolpagos.store'=>true,'rolpagos.show'=>true,'rolpagos.edit'=>true,'rolpagos.update'=>true,'rolpagos.destroy'=>true,'rolconfiguracion.index'=>true,'rolconfiguracion.create'=>true,'rolconfiguracion.store'=>true,'rolconfiguracion.show'=>true,'rolconfiguracion.edit'=>true,'rolconfiguracion.update'=>true,'rolconfiguracion.destroy'=>true,'impuestos.index'=>true,'impuestos.create'=>true,'impuestos.store'=>true,'impuestos.show'=>true,'impuestos.edit'=>true,'impuestos.update'=>true,'impuestos.destroy'=>true,'menus.index'=>true,'menus.create'=>true,'menus.store'=>true,'menus.show'=>true,'menus.edit'=>true,'menus.update'=>true,'menus.destroy'=>true,'transportistas.index'=>true,'transportistas.create'=>true,'transportistas.store'=>true,'transportistas.show'=>true,'transportistas.edit'=>true,'transportistas.update'=>true,'transportistas.destroy'=>true,'sedes.index'=>true,'sedes.create'=>true,'sedes.store'=>true,'sedes.show'=>true,'sedes.edit'=>true,'sedes.update'=>true,'sedes.destroy'=>true,'admin'=>1),
		]);
		$shopmanagerRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Shop Manager',
			'slug' => 'shopmanager',
			'tipo' => 1,
			'permissions' => array('shopmanager' => 1),
		]);
		$shopmanagercorpRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Shop Manager Corporativo',
			'slug' => 'shopmanagercorp',
			'tipo' => 1,
			'permissions' => array('shopmanagercorp' => 1),
		]);

		$masterfileRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Masterfile',
			'slug' => 'masterfile',
			'tipo' => 1,
			'permissions' => array('masterfile' => 1),
		]);

		$sacRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'SAC',
			'slug' => 'sac',
			'tipo' => 1,
			'permissions' => array('sac' => 1),
		]);

		$cediRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'CEDI',
			'slug' => 'cedi',
			'tipo' => 1,
			'permissions' => array('cedi' => 1),
		]);

		$logisticaRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Logística',
			'slug' => 'logistica',
			'tipo' => 1,
			'permissions' => array('logistica' => 1),
		]);

		$finanzasRole = Sentinel::getRoleRepository()->createModel()->create([
			'name' => 'Finanzas',
			'slug' => 'finanzas',
			'tipo' => 1,
			'permissions' => array('finanzas' => 1),
		]);

        $userRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Cliente',
			'slug'  => 'cliente',
			'tipo' => 2,
		]);

		$embajadorRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Embajador',
			'slug'  => 'embajador',
			'tipo' => 2,
		]);

		$amigosRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Amigo Alpina',
			'slug'  => 'amigoalpina',
			'tipo' => 2,
		]);

		$corporativoRole = Sentinel::getRoleRepository()->createModel()->create([
			'name'  => 'Corporativo',
			'slug'  => 'corporativo',
			'tipo' => 2,
		]);


		$admin->roles()->attach($adminRole);
		$shopmanager->roles()->attach($shopmanagerRole);
		$shopmanagercorp->roles()->attach($shopmanagercorpRole);
		$masterfile->roles()->attach($masterfileRole);
		$sac->roles()->attach($sacRole);
		$cedi->roles()->attach($cediRole);
		$logistica->roles()->attach($logisticaRole);
		$finanzas->roles()->attach($finanzasRole);

		$this->command->info('Admin User created with username admin@admin.com and password admin');
	}

}