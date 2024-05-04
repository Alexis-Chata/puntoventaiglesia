<div>
    <div class="card">
        <div class="row m-2 mt-4">
            <div class="">
                <a class="btn btn-success" href="{{route('admin.productos')}}" >
                    <i class="fas fa-undo"></i> Volver a Productos
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 px-4 pt-4">
                @php
                    echo DNS1D::getBarcodeHTML($producto->codigo, $producto->simbologia);
                @endphp
                {{$producto->codigo}}
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-8 p-4">
                <table class="table table-bordered" >
                    <tr>
                        <td>Tipo</td>
                        <td>{{$producto->tipo}}</td>
                    </tr>
                    <tr>
                        <td>Código de producto</td>
                        <td>{{$producto->codigo}}</td>
                    </tr>
                    <tr>
                        <td>nombre del producto</td>
                        <td>{{$producto->designacion}}</td>
                    </tr>
                    <tr>
                        <td>Categoría</td>
                        <td>{{$producto->categoria->name}}</td>
                    </tr>
                    <tr>
                        <td>Marca</td>
                        <td>
                            @if (isset($producto->marca))
                            {{$producto->marca->name}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Costo</td>
                        <td>{{$configuracion->moneda->simbolo.$producto->costo}}</td>
                    </tr>
                    <tr>
                        <td>Precio</td>
                        <td>{{$configuracion->moneda->simbolo.$producto->precio}}</td>
                    </tr>
                    <tr>
                        <td>Unidad</td>
                        <td>{{$producto->cunitario->name_cor}}</td>
                    </tr>
                    <tr>
                        <td>Impuesto</td>
                        <td>{{$producto->impuesto_orden}} %</td>
                    </tr>
                    <tr>
                        <td>Alerta de stock</td>
                        <td><span class="badge text-bg-warning">{{$producto->alerta_stock}}</span> </td>
                    </tr>
                </table>

                <table class="table table-bordered" >
                    <thead>
                        <tr>
                            <th><b>Almacén</b></th>
                            <th><b>Cantidad</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producto->almacenes as $alm)
                        <tr>
                            <td>{{$alm->almacen->nombre}}</td>
                            <td>{{$alm->stock}}  {{$producto->cunitario->name_cor}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-sm-4 p-4">
                <img id='imagen_prodcuto' src="{{ asset($producto->imagen) }}" class="img-thumbnail" alt="">
            </div>
        </div>
    </div>
</div>
