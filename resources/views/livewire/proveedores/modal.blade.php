<div class="modal fade @if($modalVisible) show d-block @endif"
     tabindex="-1"
     style="{{ $modalVisible ? 'background: rgba(0,0,0,0.5);' : '' }}">

    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $modo === 'crear' ? 'Nuevo Proveedor' : 'Editar Proveedor' }}
                </h5>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                <div class="row g-3">

                    <div class="col-md-6 col-12">
                        <label class="form-label">Razón Social</label>
                        <input type="text" class="form-control" wire:model="razon_social">
                        @error('razon_social') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">RUC</label>
                        <input type="text" class="form-control" wire:model="ruc">
                        @error('ruc') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" wire:model="telefono">
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control" wire:model="direccion">
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer d-flex justify-content-end">

                <button class="btn btn-secondary" wire:click="cerrarModal">
                    Cancelar
                </button>

                <button class="btn btn-primary"
                        wire:click="{{ $modo === 'crear' ? 'guardar' : 'actualizar' }}">
                    Guardar
                </button>

            </div>
        </div>
    </div>
</div>
