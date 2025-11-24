<div class="mt-3">

    {{-- ============================= --}}
    {{-- BARRA SUPERIOR --}}
    {{-- ============================= --}}
    <x-adminlte-card title="Gestión de Usuarios" theme="info" icon="fas fa-users">

        <div class="row mb-3">

            <div class="col-md-9">
                <x-adminlte-input name="search" wire:model.live="search" placeholder="Buscar por nombre, correo o rol"
                    label="Buscar usuario:" igroup-size="sm">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>

            <div class="col-md-3 d-flex align-items-end justify-content-end">
                <x-adminlte-button theme="primary" icon="fas fa-user-plus" label="Nuevo Usuario"
                    wire:click="openCreateModal" />
            </div>

        </div>


        {{-- ============================= --}}
        {{-- TABLA DE USUARIOS --}}
        {{-- ============================= --}}
        <div>
            <table class="table table-hover table-striped align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>N°</th>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-envelope"></i> Correo</th>
                        <th><i class="fas fa-user-tag"></i> Rol</th>
                        <th class="text-center"><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            {{-- Numeración correcta con paginación --}}
                            <td>{{ $users->firstItem() + $loop->index }}</td>

                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                                @if ($user->roles->first())
                                    <span class="badge bg-primary">
                                        {{ $user->roles->first()->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Sin rol</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <x-adminlte-button theme="warning" icon="fas fa-edit" label="Editar" class="btn-sm"
                                    wire:click="openEditModal({{ $user->id }})" />

                                <x-adminlte-button theme="danger" icon="fas fa-trash" label="Eliminar" class="btn-sm"
                                    wire:click="confirmDelete({{ $user->id }})" />
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle"></i> No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINACIÓN DE LIVEWIRE --}}
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>


    </x-adminlte-card>

    {{-- ============================= --}}
    {{-- MODAL CREAR / EDITAR --}}
    {{-- ============================= --}}
    <div class="modal fade" id="modalUserForm" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalUserFormLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header {{ $editMode ? 'bg-warning' : 'bg-primary' }} text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalUserFormLabel">
                        <i class="fas {{ $editMode ? 'fa-edit' : 'fa-user-plus' }} mr-2"></i>
                        {{ $editMode ? 'Editar Usuario' : 'Crear Usuario' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- FORM -->
                <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}">
                    <div class="modal-body">

                        <div class="row">

                            <!-- Nombre -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nombre completo</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Juan Pérez" wire:model="name">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>

                                    @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Correo electrónico</label>
                                <div class="input-group">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="correo@ejemplo.com" wire:model="email">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>

                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="********" wire:model="password">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>

                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                @if ($editMode)
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Dejar vacío si no deseas cambiar la contraseña
                                    </small>
                                @endif
                            </div>

                            <!-- Rol -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Rol del usuario</label>
                                <div class="input-group">
                                    <select class="form-control @error('role_id') is-invalid @enderror"
                                        wire:model="role_id">
                                        <option value="">-- Seleccione un rol --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user-tag"></span>
                                        </div>
                                    </div>

                                    @error('role_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>


                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-1"></i> Cancelar
                        </button>

                        <button type="submit" class="btn {{ $editMode ? 'btn-success' : 'btn-primary' }}">
                            <i class="fas {{ $editMode ? 'fa-save' : 'fa-plus-circle' }} mr-1"></i>
                            {{ $editMode ? 'Actualizar' : 'Crear' }} Usuario
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    {{-- ============================= --}}
    {{-- MODAL ELIMINACIÓN --}}
    {{-- ============================= --}}
    <div class="modal fade" id="modalDeleteUser" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalDeleteUserLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalDeleteUserLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Eliminar Usuario
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body text-center">

                    <i class="fas fa-user-times fa-3x text-danger mb-3"></i>

                    <h5 class="font-weight-bold">
                        ¿Seguro que deseas eliminar este usuario?
                    </h5>

                    <p class="text-muted">
                        Esta acción es permanente y no se puede deshacer.
                    </p>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i>
                        Cancelar
                    </button>

                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- JS PARA MANEJO DE MODALES --}}
    {{-- ============================= --}}
    @push('js')
        <script>
            document.addEventListener('livewire:init', () => {

                let modalForm = null;
                let modalDelete = null;

                function initModals() {

                    // destruir instancias previas
                    if (modalForm) modalForm.dispose();
                    if (modalDelete) modalDelete.dispose();

                    modalForm = new bootstrap.Modal(document.getElementById('modalUserForm'));
                    modalDelete = new bootstrap.Modal(document.getElementById('modalDeleteUser'));
                }

                // Limpieza total del modal en el DOM
                function resetBootstrapState() {
                    document.body.classList.remove('modal-open');

                    // eliminar backdrop si quedó pegado
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(b => b.remove());
                }

                // Inicializar
                initModals();

                // Cuando Livewire re-renderiza la vista
                Livewire.hook('message.processed', () => {
                    initModals();
                });

                // ================================
                // EVENTOS PERSONALIZADOS
                // ================================

                Livewire.on('open-form-modal', () => {
                    resetBootstrapState();
                    modalDelete.hide();
                    modalForm.show();
                });

                Livewire.on('close-form-modal', () => {
                    modalForm.hide();
                    resetBootstrapState();
                    Livewire.dispatch('resetForm'); // opción para limpiar el lado Livewire
                });

                Livewire.on('open-delete-modal', () => {
                    resetBootstrapState();
                    modalForm.hide();
                    modalDelete.show();
                });

                Livewire.on('close-delete-modal', () => {
                    modalDelete.hide();
                    resetBootstrapState();
                });

                window.addEventListener('success', event => {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: event.detail.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                });


                // ================================
                // SI EL USUARIO CIERRA MANUALMENTE (botón X)
                // ================================
                document.getElementById('modalUserForm')
                    .addEventListener('hidden.bs.modal', function() {
                        resetBootstrapState();
                        Livewire.dispatch('resetForm'); // Limpia campos y errores
                    });

                document.getElementById('modalDeleteUser')
                    .addEventListener('hidden.bs.modal', function() {
                        resetBootstrapState();
                    });

            });
        </script>
    @endpush

</div>
