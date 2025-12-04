<div class="mt-3">

    <x-adminlte-card title="Proveedores" theme="info" icon="fas fa-truck">

        {{-- BUSCADOR + BOTÓN NUEVO --}}
        <div class="row mb-3">
            <div class="col-md-9">
                <x-adminlte-input name="search" wire:model.live="search"
                    placeholder="Buscar por Razón Social, RUC, Teléfono o Dirección"
                    label="Buscar proveedor:" igroup-size="sm">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>

            <div class="col-md-3 d-flex align-items-end justify-content-end">
                <x-adminlte-button label="Nuevo Proveedor" theme="primary" icon="fas fa-plus"
                    wire:click="crear"/>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle" style="min-width: 900px">
                <thead class="bg-light">
                    <tr>
                        <th>N°</th>
                        <th><i class="fas fa-building"></i> Razón Social</th>
                        <th><i class="fas fa-id-card"></i> RUC</th>
                        <th><i class="fas fa-phone"></i> Teléfono</th>
                        <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                        <th class="text-center"><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $index => $item)
                        <tr>
                            <td>{{ $proveedores->firstItem() + $index }}</td>
                            <td>{{ $item->razon_social }}</td>
                            <td>{{ $item->ruc }}</td>
                            <td>{{ $item->telefono }}</td>
                            <td>{{ $item->direccion }}</td>

                            {{-- BOTONES --}}
                            <td class="text-center">
                                {{-- EDITAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <x-adminlte-button theme="warning" icon="fas fa-edit" class="btn-sm"
                                        wire:click="editar({{ $item->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />
                                    <div x-show="tooltip" x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%; white-space: nowrap; z-index: 2000;">
                                        Editar proveedor
                                    </div>
                                </div>

                                {{-- ELIMINAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <x-adminlte-button theme="danger" icon="fas fa-trash" class="btn-sm"
                                        wire:click="confirmarEliminacion({{ $item->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />
                                    <div x-show="tooltip" x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%; white-space: nowrap; z-index: 2000;">
                                        Eliminar proveedor
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle"></i> No hay resultados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINACIÓN --}}
            <div class="mt-3">
                {{ $proveedores->links() }}
            </div>
        </div>

    </x-adminlte-card>

    {{-- MODAL CREAR / EDITAR --}}
    @include('livewire.proveedores.modal')

    {{-- MODAL ELIMINAR --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalDeleteLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalDeleteLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Eliminar Proveedor
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body text-center">
                    <i class="fas fa-truck fa-3x text-danger mb-3 me-2"></i>
                    <i class="fas fa-times fa-3x text-danger mb-3"></i>

                    <h5 class="font-weight-bold">
                        ¿Seguro que deseas eliminar el proveedor?
                    </h5>
                    <p class="text-muted">
                        Esta acción es permanente y no se puede deshacer.
                    </p>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="cerrarModalEliminar"
                        data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i>
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" wire:click="eliminar">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- SCRIPTS MODALES Y ALERTAS --}}
@push('js')
<script>
document.addEventListener('livewire:init', () => {

    Livewire.on('open-modal', () => $('#modalForm').modal('show'));
    Livewire.on('close-modal', () => $('#modalForm').modal('hide'));

    Livewire.on('open-delete-modal', () => $('#modalDelete').modal('show'));
    Livewire.on('close-delete-modal', () => $('#modalDelete').modal('hide'));

    window.addEventListener('success', event => {
        Swal.fire({
            position: "center",
            icon: "success",
            title: event.detail.message,
            width: "30rem",
            showConfirmButton: false,
            timer: 1500
        });
    });

    window.addEventListener('edited', event => {
        Swal.fire({
            position: "center",
            icon: "info",
            title: event.detail.message,
            width: "30rem",
            showConfirmButton: false,
            timer: 1500
        });
    });
    window.addEventListener('error', event => {
        Swal.fire({
            icon: "error",
            title: event.detail.message,
            position: "center",
            width: "30rem",
            showConfirmButton: false,
            timer: 4000
        });
    });


});
</script>
@endpush
