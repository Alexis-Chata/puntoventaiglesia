<?php
use Illuminate\Support\Facades\Route;

Route::view('', 'administrador.index')->name('admin.index');
Route::view('configuracion/ajustes_sistema', 'administrador.configuracion.ajustesistema')->name('admin.configuracion.ajustesistema');
Route::view("moneda","administrador.ajustes.moneda")->name("admin.moneda");
Route::view("almacen","administrador.ajustes.almacen")->name("admin.almacen");
#productos
Route::view("productos","administrador.productos.productos")->name("admin.productos");
Route::view("marcas","administrador.productos.marcas")->name("admin.marcas");
Route::view("codigo_barra","administrador.productos.codigo_barra")->name("admin.codigo_barra");
Route::view("categorias","administrador.productos.categorias")->name("admin.categorias");
Route::view("unidades","administrador.productos.unidades")->name("admin.unidades");
