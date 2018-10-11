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
        $this->call(AdminSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(FEnvioTableSeeder::class);
        $this->call(FPagoTableSeeder::class);
       // $this->call(MarcasTableSeeder::class);
        $this->call(TDocumentosTableSeeder::class);
        //$this->call(CategoriasTableSeeder::class);
       // $this->call(ProductosTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(ImpuestosTableSeeder::class);
        //$this->call(ProductosCategoriasTableSeeder::class);
        //$this->call(InventarioTableSeeder::class);
       // $this->call(PreciosRolTableSeeder::class);
        $this->call(PAgosEstatusTableSeeder::class);
        $this->call(RolEnvioTableSeeder::class);
        $this->call(RolPagoTableSeeder::class);
        $this->call(OrdenesEstatusTableSeeder::class);
        $this->call(ConfGralTableSeeder::class);
        $this->call(EmpresasTableSeeder::class);
    }
}
