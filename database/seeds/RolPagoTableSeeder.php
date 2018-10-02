<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolPagoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alp_rol_pago')->insert([
            ['id_rol' => 1,'id_forma_pago' => 1, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 1,'id_forma_pago' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 1,'id_forma_pago' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 2,'id_forma_pago' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 2,'id_forma_pago' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 3,'id_forma_pago' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 4,'id_forma_pago' => 1, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 4,'id_forma_pago' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 4,'id_forma_pago' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 5,'id_forma_pago' => 2, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 5,'id_forma_pago' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['id_rol' => 6,'id_forma_pago' => 3, 'id_user' => 1,'created_at' => Carbon::now()->format('Y-m-d H:i:s')]
        ]);
    }
}
