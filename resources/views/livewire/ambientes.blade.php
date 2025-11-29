<div class="mt-3">

    <x-adminlte-card title="Gestión de Usuarios" theme="info" icon="fas fa-users">

        {{-- ============================= --}}
        {{-- BUSCADOR + BOTÓN CREAR --}}
        {{-- ============================= --}}
        <div class="row mb-3">

            {{-- BUSCADOR --}}
            <div class="col-md-9">
                <x-adminlte-input name="search" wire:model.live="search" placeholder="Buscar..." label="Buscar ambiente:" igroup-size="sm">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>

            {{-- BOTÓN NUEVO --}}
            <div class="col-md-3 d-flex align-items-end justify-content-end">
                <x-adminlte-button label="Nuevo Ambiente" theme="primary" icon="fas fa-plus"
                    wire:click="crear"/>
            </div>

        </div> {{-- /row --}}

        {{-- ============================= --}}
        {{-- TABLA --}}
        {{-- ============================= --}}
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle" style="min-width: 900px">
                <thead class="bg-light">
                    <tr>
                        <th>N°</th>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-map-marker-alt"></i> Ubicación</th>
                        <th class="text-center" style="width: 120px;"><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($ambientes as $ambiente)
                        <tr>
                            <td>{{ $ambientes->firstItem() + $loop->index }}</td>

                            <td>{{ $ambiente->nombre }}</td>
                            <td>{{ $ambiente->ubicacion }}</td>

                            <!-- mensajes con alphine -->
                            <td class="text-center">
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                        <x-adminlte-button theme="warning" icon="fas fa-edit" class="btn-sm"
                                            wire:click="editar({{ $ambiente->id }})"
                                            @mouseenter="tooltip = true"
                                            @mouseleave="tooltip = false" />

                                        <!-- TOOLTIP ALPINE -->
                                        <div x-show="tooltip"
                                            x-transition
                                            class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                            style="bottom: 120%; left: 50%; transform: translateX(-80%); white-space: nowrap; z-index: 9999;">
                                            Editar ambiente
                                        </div>
                                </div>

                                    <!-- BOTÓN ELIMINAR -->
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                        <x-adminlte-button theme="danger" icon="fas fa-trash" class="btn-sm"
                                            wire:click="confirmarEliminar({{ $ambiente->id }})"
                                            @mouseenter="tooltip = true"
                                            @mouseleave="tooltip = false" />

                                        <!-- TOOLTIP ALPINE -->
                                        <div x-show="tooltip"
                                            x-transition
                                            class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                            style="bottom: 120%; left: 50%; transform: translateX(-80%); white-space: nowrap; z-index: 9999;">
                                            Eliminar ambiente
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
                {{ $ambientes->links() }}
            </div>

        </div>

    </x-adminlte-card>


    {{-- ============================= --}}
    {{-- MODAL CREAR / EDITAR --}}
    {{-- ============================= --}}
    <div class="modal fade" id="modalForm" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalFormLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                {{-- HEADER --}}
                <div class="modal-header {{ $modo === 'crear' ? 'bg-primary' : 'bg-warning' }} text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalFormLabel">
                        <i class="fas {{ $modo === 'crear' ? 'fa-layer-group' : 'fa-edit' }} mr-2"></i>
                        {{ $modo === 'crear' ? 'Crear Ambiente' : 'Editar Ambiente' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- FORM --}}
                <form wire:submit.prevent="guardar">
                    <div class="modal-body">

                        <div class="row">

                            {{-- NOMBRE --}}
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nombre</label>

                                <div class="input-group">
                                    <input type="text" wire:model="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        placeholder="Nombre del ambiente">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-tag"></span>
                                        </div>
                                    </div>

                                    @error('nombre')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- UBICACIÓN --}}
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Ubicación</label>

                                <div class="input-group">
                                    <input type="text" wire:model="ubicacion"
                                        class="form-control @error('ubicacion') is-invalid @enderror"
                                        placeholder="Ej: Segundo piso, aula 203">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-map-marker-alt"></span>
                                        </div>
                                    </div>

                                    @error('ubicacion')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="cerrarModal">
                            <i class="fas fa-times-circle mr-1"></i> Cancelar
                        </button>

                        <button type="submit"
                            class="btn {{ $modo === 'crear' ? 'btn-primary' : 'btn-success' }}">
                            <i class="fas {{ $modo === 'crear' ? 'fa-plus-circle' : 'fa-save' }} mr-1"></i>
                            {{ $modo === 'crear' ? 'Crear Ambiente' : 'Actualizar Ambiente' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    {{-- ============================= --}}
    {{-- MODAL ELIMINAR AMBIENTE --}}
    {{-- ============================= --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalDeleteLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalDeleteLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Eliminar Ambiente
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body text-center">

                    <i class="fas fa-user-times fa-3x text-danger mb-3"></i>

                    <h5 class="font-weight-bold">
                        ¿Seguro que deseas eliminar este ambiente?
                    </h5>

                    <p class="text-muted">
                        Esta acción es permanente y no se puede deshacer.
                    </p>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" wire:click="cancelarEliminar"
                        data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i>
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" wire:click="eliminarDefinitivo">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>


</div>


{{-- ============================= --}}
{{-- SCRIPTS DE MODALES Y ALERTAS --}}
{{-- ============================= --}}
@push('js')
<script>
    document.addEventListener('livewire:init', () => {

        Livewire.on('abrirModalForm', () => $('#modalForm').modal('show'));
        Livewire.on('cerrarModalForm', () => $('#modalForm').modal('hide'));

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
