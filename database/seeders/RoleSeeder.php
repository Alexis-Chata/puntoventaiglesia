<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Role::create(['name' => 'Administrador']);
        Role::create(['name' => 'Cajero']);
        Permission::create(['name' =>'admin.configuracion.ajustesistema'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.index'])->syncRoles(['Administrador','Cajero']);
        Permission::create(['name' =>'admin.moneda'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.almacen'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.productos'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.marcas'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.codigo_barra'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.categorias'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.unidades'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'search.buscar_productos'])->syncRoles(['Administrador','cajero']);
        Permission::create(['name' =>'search.buscar_proveedor'])->syncRoles(['Administrador','cajero']);
        Permission::create(['name' =>'search.buscar_marca'])->syncRoles(['Administrador','cajero']);
        Permission::create(['name' =>'search.buscar_categoria'])->syncRoles(['Administrador','cajero']);
        Permission::create(['name' =>'search.buscar_productos_compra'])->syncRoles(['Administrador','cajero']);
        Permission::create(['name' =>'admin.productos.consultar'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.compras'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.clientes'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.proveedor'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.usuario'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.gastos.tgastos'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.gastos.index'])->syncRoles(['Administrador']);
        Permission::create(['name' =>'admin.ventas.pos'])->syncRoles(['Administrador','Cajero']);
        Permission::create(['name' =>'admin.ventas.index'])->syncRoles(['Administrador','Cajero']);
        Permission::create(['name' =>'admin.productos.consultar_barra'])->syncRoles(['Administrador','Cajero']);
        Permission::create(['name' =>'search.buscar_cliente'])->syncRoles(['Administrador','Cajero']);
    }
}
