<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#modalTgasto">
    Modal Gastos
</button>

<!-- Modal -->
<div class="modal fade" id="modalTgasto" tabindex="-1" aria-labelledby="modalTgastoLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalTgastoLabel">{{ $titlemodal }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cerrar_modal_tgasto_x"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="guardar" id="formularioGasto">
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <label for="tgasto_nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="tgasto_nombre" placeholder="Ingrese Nombre Corto" wire:model="tgastoform.name">
                            @error('tgastoform.name')<div class="p-1" style="color:red;"> {{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <label for="tgasto_descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="tgasto_descripcion" cols="30" rows="10" wire:model="tgastoform.descripcion"></textarea>
                            @error('tgastoform.descripcion')<div class="p-1" style="color:red;"> {{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <input type="checkbox" id="tgastoform_ignorar"   wire:model='s_ignorar'>
                            <label class="form-check-label" for="tgastoform_ignorar">Ignorar</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formularioGasto" class="btn btn-primary">{{ $titlemodal }}</button>
            </div>
        </div>
    </div>
</div>
