<div class="mt-3">

    <x-adminlte-card title="Tipos de Producto" theme="info" icon="fas fa-tags">

        {{-- BUSCADOR + NUEVO --}}
        <div class="row mb-3">
            {{-- BUSCADOR --}}
            <div class="col-md-9">
                <x-adminlte-input name="search" wire:model.live="search" placeholder="Buscar por nombre o prefijo" label="Buscar tipo de producto:" igroup-size="sm">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>

            {{-- BOTÓN NUEVO --}}
            <div class="col-md-3 d-flex align-items-end justify-content-end">
                <x-adminlte-button label="Nuevo Producto" theme="primary" icon="fas fa-plus"
                    wire:click="openCreateModal"/>
            </div>
        </div>

        {{-- TABLA --}}
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle" style="min-width: 900px">
                <thead class="bg-light">
                    <tr>
                        <th>N°</th>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-tag"></i> Prefijo</th>
                        <th><i class="fas fa-file-alt"></i> Descripción</th>
                        <th class="text-center" style="width: 120px;"><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tipos as $tipo)
                        <tr>
                            <td>{{ $tipos->firstItem() + $loop->index }}</td>
                            <td>{{ $tipo->nombre }}</td>
                            <td><strong>{{ $tipo->prefijo }}</strong></td>
                            <td>{{ $tipo->descripcion }}</td>

                            <td class="text-center">
                                {{-- EDITAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <x-adminlte-button theme="warning" icon="fas fa-edit" class="btn-sm"
                                        wire:click="openEditModal({{ $tipo->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />

                                    <div x-show="tooltip"
                                        x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%; white-space: nowrap; z-index: 2000;">
                                        Editar producto
                                    </div>
                                </div>

                                {{-- ELIMINAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <x-adminlte-button theme="danger" icon="fas fa-trash" class="btn-sm"
                                        wire:click="confirmarEliminar({{ $tipo->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />

                                    <div x-show="tooltip"
                                        x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%; white-space: nowrap; z-index: 2000;">
                                        Eliminar producto
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle"></i> No hay resultados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINACIÓN --}}
            <div class="mt-3">
                {{ $tipos->links() }}
            </div>
        </div>

    </x-adminlte-card>

    {{-- MODAL CREAR / EDITAR --}}
    <div class="modal fade" id="modalTipo" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalTipoLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                {{-- HEADER --}}
                <div class="modal-header {{ $modalMode === 'create' ? 'bg-info' : 'bg-warning' }} text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalTipoLabel">
                        <i class="fas {{ $modalMode === 'create' ? 'fas fa-tags' : 'fa-edit' }} mr-2"></i>
                        {{ $modalMode === 'create' ? 'Crear Tipo de Producto' : 'Editar Tipo de Producto' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- BODY --}}
                <form wire:submit.prevent="{{ $modalMode === 'create' ? 'save' : 'update' }}">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nombre</label>
                                <input type="text" wire:model.defer="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    placeholder="Nombre del tipo de producto">
                                @error('nombre')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Prefijo</label>
                                <input type="text" wire:model.defer="prefijo"
                                    class="form-control @error('prefijo') is-invalid @enderror"
                                    placeholder="Ej: MED" @if(!$canEditPrefijo) disabled @endif>
                                @error('prefijo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">Descripción</label>
                                <textarea wire:model.defer="descripcion" rows="3" class="form-control"></textarea>
                                @error('descripcion')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn {{ $modalMode === 'create' ? 'btn-primary' : 'btn-success' }}">
                            <i class="fas {{ $modalMode === 'create' ? 'fa-plus-circle' : 'fa-save' }} mr-1"></i>
                            {{ $modalMode === 'create' ? 'Crear producto' : 'Actualizar' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- MODAL ELIMINAR --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalDeleteLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalDeleteLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Eliminar Tipo de Producto
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center">
                    
                    <i class="fas fa-tags fa-3x text-danger mb-3 me-2"></i>
                    <i class="fas fa-times fa-3x text-danger mb-3"></i>

                    <h5 class="font-weight-bold">
                        ¿Seguro que deseas eliminar este tipo de producto?</h5>

                    <p class="text-muted">
                        Esta acción es permanente y no se puede deshacer.</p>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="cancelarEliminar" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i>
                        Cancelar</button>
                    <button type="button" class="btn btn-danger" wire:click="eliminarDefinitivo">
                        <i class="fas fa-trash-alt mr-1"></i> 
                        Eliminar</button>
                </div>

            </div>
        </div>
    </div>

</div>

@push('js')
<script>
    document.addEventListener('livewire:init', () => {

        Livewire.on('open-modal', () => $('#modalTipo').modal('show'));
        Livewire.on('close-modal', () => $('#modalTipo').modal('hide'));

        Livewire.on('abrirModalEliminar', () => $('#modalDelete').modal('show'));
        Livewire.on('cerrarModalEliminar', () => $('#modalDelete').modal('hide'));

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

    });
</script>
@endpush
