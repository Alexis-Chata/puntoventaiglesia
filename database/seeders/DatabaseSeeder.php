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
use App\Models\Devolucion;
use App\Models\Gasto;
use App\Models\Movimiento;
use App\Models\PagoCompra;
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

        /*
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
        */
        $almacen = Almacen::all();
        $pagocompras = PagoCompra::where('created_at','2024-05-01 00:00:00')->get();
        $devoluciones = Devolucion::where('created_at','2024-05-01 00:00:00')->get();
        $gastos = Gasto::where('created_at','2024-05-01 00:00:00')->where('ignorar',0)->get();
        $posventas = Posventa::where('created_at','2024-05-01 00:00:00')->get();

        foreach ($pagocompras as $key => $pagocompra) {
            foreach ($almacen as $key => $alm) {
                if ($alm->id ==  $pagocompra->compra->almacen_id)
                {
                    $balmacen = Almacen::find($pagocompra->compra->almacen_id);
                    $balmacen->monto = $balmacen->monto - $pagocompra->monto_pago;
                    $balmacen->save();
                    $n_movimiento = new Movimiento();
                    $n_movimiento->tipo_movimiento = '-';
                    $n_movimiento->monto = $pagocompra->monto_pago;
                    $n_movimiento->saldo = $balmacen->monto;
                    $n_movimiento->almacen = $pagocompra->compra->almacen_id;
                    $n_movimiento->accion = 'crear';
                    $n_movimiento->movimientoable_id = $pagocompra->id;
                    $n_movimiento->movimientoable_type = 'App\Models\PagoCompra';
                    $n_movimiento->save();
                }
            }
        }

        foreach ($devoluciones as $key => $dev) {
            foreach ($almacen as $key2 => $alm) {
                if ($alm->id ==  $dev->almacen_id)
                {
                    $balmacen = Almacen::find($dev->almacen_id);
                    $balmacen->monto = $balmacen->monto - $dev->monto_pago;
                    $balmacen->save();

                    $n_movimiento = new Movimiento();
                    $n_movimiento->tipo_movimiento = '-';
                    $n_movimiento->monto = $dev->monto_pago;
                    $n_movimiento->saldo = $balmacen->monto;
                    $n_movimiento->almacen = $dev->almacen_id;
                    $n_movimiento->accion = 'crear';
                    $n_movimiento->movimientoable_id = $dev->id;
                    $n_movimiento->movimientoable_type = 'App\Models\Devolucion';
                    $n_movimiento->save();
                }
            }
        }

        foreach ($gastos as $key => $gato) {
            foreach ($almacen as $key2 => $alm) {
                if ($alm->id ==  $gato->almacen_id)
                {
                    $balmacen = Almacen::find($gato->almacen_id);
                    $balmacen->monto = $balmacen->monto - $gato->monto;
                    $balmacen->save();

                    $n_movimiento = new Movimiento();
                    $n_movimiento->tipo_movimiento = '-';
                    $n_movimiento->monto = $gato->monto;
                    $n_movimiento->saldo = $balmacen->monto;
                    $n_movimiento->almacen = $gato->almacen_id;
                    $n_movimiento->accion = 'crear';
                    $n_movimiento->movimientoable_id = $gato->id;
                    $n_movimiento->movimientoable_type = 'App\Models\Gasto';
                    $n_movimiento->save();
                }
            }
        }

        foreach ($posventas as $key => $posventa) {
            foreach ($almacen as $key2 => $alm) {
                if ($alm->id ==  $posventa->almacen_id)
                {
                    $balmacen = Almacen::find($posventa->almacen_id);
                    $balmacen->monto = $balmacen->monto + $posventa->monto_pago;
                    $balmacen->save();

                    $n_movimiento = new Movimiento();
                    $n_movimiento->tipo_movimiento = '+';
                    $n_movimiento->monto = $posventa->monto_pago;
                    $n_movimiento->saldo = $balmacen->monto;
                    $n_movimiento->almacen = $posventa->almacen_id;
                    $n_movimiento->accion = 'crear';
                    $n_movimiento->movimientoable_id = $posventa->id;
                    $n_movimiento->movimientoable_type = 'App\Models\Posventa';
                    $n_movimiento->save();
                }
            }
        }
    }
}
