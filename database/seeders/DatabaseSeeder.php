<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Posventa;
use App\Models\PosventaDetalle;
use App\Models\Proveedor;
use App\Models\Tgasto;
use App\Models\User;
use App\Models\ProductoAlmacen;
use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();


        // llamar al sembrador de datos en Moneda

       /*
       $this->call(RoleSeeder::class);
        $this->call(MonedaSeeder::class);
        $this->call(AlmacenSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(UnidadSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(CompraSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(TgastoSeeder::class);
        $this->call(TmovimientoCajaSeeder::class);
        $this->call(AjustesSistemaSeed::class);

        $user = User::create([
            'name' => 'Administrador',
            'lastname' => 'Rodriguez',
            'telefono' => '+51 934 665 704',
            'email' => 'test@example.com',
            'username' => 'usuario',
            'email_verified_at' => '2024-03-25 21:16:12',
            'password' => bcrypt('123456789'),
        ]);

        $user->assignRole('Administrador');

        */

        /*
        $detalles = PosventaDetalle::all();
        foreach ($detalles as $key => $det)
        {   $bproducto = Producto::where('codigo',$det->producto_codigo)->first();
            if ($bproducto) {
            $det->producto_compra = $bproducto->costo;
            $det->producto_costo_compra = $bproducto->costo*$det->producto_cantidad;
            $det->save();
            }
        }
        */


        $productos = Producto::where('tipo','compuesto')->get();
        foreach ($productos as $key => $pro)
        {
            $almacenes = Almacen::all();
            foreach ($almacenes as $tey => $alm) {
                $bproducto_almacen = ProductoAlmacen::where('producto_id',$pro->id)->where('almacen_id',$alm->id)->first();
                if($bproducto_almacen == false)
                {
                    $ne_pro_alm = new ProductoAlmacen();
                    $ne_pro_alm->almacen_id  = $alm->id;
                    $ne_pro_alm->producto_id = $pro->id;
                    $ne_pro_alm->stock = 0;
                    $ne_pro_alm->save();
                }
            }
        }
    }
}
