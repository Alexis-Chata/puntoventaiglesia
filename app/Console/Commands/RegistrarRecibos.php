<?php

namespace App\Console\Commands;

use App\Models\Configuracion;
use App\Models\Posventa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegistrarRecibos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrar-recibos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registrar las ventas al sistema asignado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $configuracion = Configuracion::find(1);
        if ($configuracion)
        {

            $url = $configuracion->sistema;
            $monto_ventas = Posventa::whereNull('recibo_id')->sum('monto_pago');
            $lista_ventas = Posventa::whereNull('recibo_id')->get();
            $data = [
                'recibo_cliente' => 'Varios',
                'recibo_direccion' => 'Dirección A',
                'recibo_nit' => '123456789',
                'recibo_total' => $monto_ventas,
                'recibo_cajero' => 1,
                'detalle_descripcion' => $configuracion->name,
                'detalle_observacion' => null,
                'detalle_cantidad' => 1,
                'detalle_precio' => $monto_ventas,
                'detalle_importe' => $monto_ventas,
                'detalle_servicio_id' => $configuracion->servicio_id,
            ];

            $response = Http::post($url, $data);

            if ($response)
            {
                foreach ($lista_ventas as $key => $lventa)
                {
                    $lventa->recibo_id = $response;
                    $lventa->save();
                }
            }
        }
    }
}
