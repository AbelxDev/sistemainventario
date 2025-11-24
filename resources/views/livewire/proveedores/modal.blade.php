<!-- MODAL BOOTSTRAP REAL -->
<div wire:ignore.self class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable custom-modal">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header {{ $modo === 'crear' ? 'bg-primary' : 'bg-warning' }} text-white">
                <h5 class="modal-title d-flex align-items-center">
                    <i class="fas {{ $modo === 'crear' ? 'fa-user-plus' : 'fa-edit' }} mr-2"></i>
                    {{ $modo === 'crear' ? 'Nuevo Proveedor' : 'Editar Proveedor' }}
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div class="row g-3">
                    <div class="col-md-6 col-12 mb-3">
                        <label class="form-label font-weight-bold">Razón Social</label>

                        <div class="input-group">
                            <input type="text" 
                                class="form-control @error('razon_social') is-invalid @enderror"
                                wire:model="razon_social"
                                maxlength="40">

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-building"></span>
                                </div>
                            </div>
                        </div>

                        @error('razon_social')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                        <label class="form-label font-weight-bold">RUC</label>

                        <div class="input-group">
                            <input type="text" 
                                class="form-control @error('ruc') is-invalid @enderror"
                                wire:model="ruc"
                                maxlength="20">

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                        </div>

                        @error('ruc')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                        <label class="form-label font-weight-bold">Teléfono</label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                wire:model="telefono"
                                maxlength="20"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <label class="form-label font-weight-bold">Dirección</label>
                        <div class="input-group">
                            <input
                                type="text"
                                id="direccionInput"
                                class="form-control @error('direccion') is-invalid @enderror"
                                wire:model="direccion"
                                maxlength="60"
                                aria-describedby="direccionCounter"
                            >

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-map-marker-alt"></span>
                                </div>
                            </div>
                        </div>

                        <small id="direccionCounter" class="text-muted" aria-live="polite">
                            {{ strlen($direccion ?? '') }}/60
                        </small>
                        @error('direccion')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
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

        // sincronizar el contador al abrir (por si Livewire puso un valor)
        setTimeout(() => {
            const input = document.getElementById('direccionInput');
            const counter = document.getElementById('direccionCounter');
            if (input && counter) {
                counter.textContent = `${input.value.length}/60`;
            }
            // opcional: focus al input
            if (input) input.focus();
        }, 50);
    });

    window.addEventListener('close-modal', () => {
        if (modalProveedor) {
            modalProveedor.hide();
        }
    });

    // Delegación de eventos: un único listener en el modal que captura 'input' del campo
    document.addEventListener('DOMContentLoaded', () => {
        const modalEl = document.getElementById('modalProveedor');
        if (!modalEl) return;

        modalEl.addEventListener('input', (e) => {
            // si el input que generó el evento es nuestro campo direccion
            if (e.target && e.target.id === 'direccionInput') {
                const counter = document.getElementById('direccionCounter');
                if (counter) {
                    const max = parseInt(e.target.getAttribute('maxlength') || 100, 10);
                    const len = e.target.value.length;
                    counter.textContent = `${len}/${max}`;

                    // clases visuales opcionales
                    counter.classList.remove('text-danger','text-warning','text-muted');
                    if (len >= max) {
                        counter.classList.add('text-danger');
                    } else if (len >= Math.floor(max * 0.8)) {
                        counter.classList.add('text-warning');
                    } else {
                        counter.classList.add('text-muted');
                    }
                }
            }
        });

        // Además: si Livewire actualiza el DOM fuera del modal, aún queremos sincronizar
        // al terminar cualquier mensaje processed, actualizamos el contador desde el DOM
        if (window.Livewire) {
            Livewire.hook('message.processed', () => {
                const input = document.getElementById('direccionInput');
                const counter = document.getElementById('direccionCounter');
                if (input && counter) {
                    const max = parseInt(input.getAttribute('maxlength') || 100, 10);
                    counter.textContent = `${input.value.length}/${max}`;
                }
            });
        }
    });
</script>

