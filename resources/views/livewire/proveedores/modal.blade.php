<!-- MODAL BOOTSTRAP REAL -->
<div wire:ignore.self class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $modo === 'crear' ? 'Nuevo Proveedor' : 'Editar Proveedor' }}
                </h5>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div class="row g-3">

                    <div class="col-md-6 col-12">
                        <label class="form-label">Razón Social</label>
                        <input type="text" class="form-control" wire:model="razon_social" maxlength='255'>
                        @error('razon_social') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">RUC</label>
                        <input type="text" class="form-control"
                            wire:model="ruc"
                            maxlength="20">
                        @error('ruc') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control"
                            wire:model="telefono"
                            maxlength="20"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="col-md-6 col-12">
                        <label class="form-label">Dirección</label>
                        <input type="text" class="form-control"
                         wire:model="direccion"
                         maxlength="255">
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">
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

<script>
    let modalProveedor = null;

    window.addEventListener('open-modal', () => {
        if (!modalProveedor) {
            modalProveedor = new bootstrap.Modal(document.getElementById('modalProveedor'));
        }
        modalProveedor.show();
    });

    window.addEventListener('close-modal', () => {
        if (modalProveedor) {
            modalProveedor.hide();
        }
    });
</script>
