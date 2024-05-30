<?php

namespace App\Livewire\Forms;

use App\Models\Compra;
use App\Models\CompraPago;
use App\Models\PagoCompra;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PagoCompraForm extends Form
{
    public ?PagoCompra $pagocompra;
    public ?Compra $compra;
    public $compra_id;
    public $fecha_pago;
    public $opcion_pago;
    public $cantidad_recibida;
    public $monto_pago;
    public $cambio;
    public $nota = null;
    private AlmacenForm $almacenform;
    private MovimientoForm $movimientoform;

    public $rules =
    [
        'compra_id' => 'required',
        'fecha_pago' => 'required',
        'opcion_pago' => 'required',
        'cantidad_recibida' => 'required',
        'monto_pago' => 'required',
        'cambio' => 'required',
    ];

    public function set(PagoCompra $pagocompra){
        $this->pagocompra = $pagocompra;
        $this->compra = $pagocompra->compra;
        $this->compra_id = $pagocompra->compra_id;
        $this->fecha_pago = $pagocompra->fecha_pago;
        $this->opcion_pago = $pagocompra->opcion_pago;
        $this->monto_pago = $pagocompra->monto_pago;
        $this->cambio = $pagocompra->cambio;
        $this->nota = $pagocompra->nota;
    }

    public function calcular_campos()
    {
        $pago_adicional = 0;
        if ($this->compra) {$pago_adicional = $this->compra->pagocompras->sum('monto_pago');}

        $this->monto_pago =  $this->monto_pago == null ? 0 : $this->monto_pago;
        $this->monto_pago =  $this->monto_pago > ($this->compra->total-$pago_adicional) ? $this->compra->total-$pago_adicional : $this->monto_pago;
        $this->cantidad_recibida = $this->cantidad_recibida < $this->monto_pago ? $this->monto_pago : $this->cantidad_recibida;
        $this->cambio = $this->cantidad_recibida-$this->monto_pago;
    }

    public function update(){
        $this->validate($this->rules);
        $bcompra = PagoCompra::find($this->pagocompra->id);
        ##########################
        $saldo = $this->almacenform->agregar_descontar_monto_almacen($bcompra->compra->almacen_id,$bcompra->monto_pago,'+');
        $this->movimientoform->agregar_movimiento($bcompra->id,$bcompra->compra->almacen_id,$bcompra->monto_pago,$saldo,'+','App\Models\PagoCompra','editar');
        $this->pagocompra->update($this->all());
        $saldoa = $this->almacenform->agregar_descontar_monto_almacen($this->pagocompra->compra->almacen_id,$this->monto_pago,'-');
        $this->movimientoform->agregar_movimiento($this->pagocompra->id,$this->pagocompra->compra->almacen_id,$this->monto_pago,$saldoa,'-','App\Models\PagoCompra','editar');
    }

    public function store()
    {

        $this->compra_id = $this->compra->id;
        $this->validate($this->rules);
        $this->pagocompra = PagoCompra::create($this->all());
        $this->actualizar_compra($this->pagocompra->compra_id);

        $this->almacenform = new AlmacenForm();
        $this->movimientoform = new MovimientoForm();
        $saldo = $this->almacenform->agregar_descontar_monto_almacen($this->pagocompra->compra->almacen_id,$this->monto_pago,'-');
        $this->movimientoform->agregar_movimiento($this->pagocompra->id,$this->pagocompra->compra->almacen_id,$this->monto_pago,$saldo,'-','App\Models\PagoCompra','crear');
    }

    public function eliminar_pago_compra(PagoCompra $pagocompra)
    {
        $this->almacenform = new AlmacenForm();
        $this->movimientoform = new MovimientoForm();
        $saldo = $this->almacenform->agregar_descontar_monto_almacen($pagocompra->compra->almacen_id,$pagocompra->monto_pago,'+');
        $this->movimientoform->agregar_movimiento($pagocompra->id,$pagocompra->compra->almacen_id,$pagocompra->monto_pago,$saldo,'+','App\Models\PagoCompra','Eliminar');
        $pagocompra->delete();
    }

    public function actualizar_compra($compra_id){
        $bcompra = Compra::find($compra_id);
        if ($bcompra->pagocompras->count() == 0) {
            $bcompra->estado_pago = 1;
        }
        else {
            $bcompra->estado_pago = $bcompra->pagocompras->sum('monto_pago') == $bcompra->total ? 2 : 3;
        }
        $bcompra->save();
    }
}
