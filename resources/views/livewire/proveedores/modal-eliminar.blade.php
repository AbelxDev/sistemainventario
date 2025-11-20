<div class="modal fade" id="modalEliminarProveedor" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fa fa-exclamation-triangle"></i> Confirmar eliminación
                </h5>
            </div>

            <div class="modal-body">
                <p>
                    ¿Deseas eliminar al proveedor
                    <strong class="text-danger">"{{ $nombreAEliminar }}"</strong>?<br>
                    Esta acción no se puede deshacer.
                </p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                </button>

                <button class="btn btn-danger" wire:click="eliminar">
                    Eliminar
                </button>
            </div>

        </div>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('livewire:init', () => {

        let modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminarProveedor'));

        Livewire.on('open-delete-modal', () => {
            modalEliminar.show();
        });

        Livewire.on('close-delete-modal', () => {
            modalEliminar.hide();
        });

    });
</script>
@endpush

