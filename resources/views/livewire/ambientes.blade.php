<div>

    {{-- Campo de búsqueda --}}
    <div class="mb-3">
        <input type="text" wire:model.live="search" class="form-control" placeholder="Buscar...">
    </div>
    <button class="btn btn-primary mb-3" wire:click="crear">
        <i class="fas fa-plus"></i> Nuevo Ambiente
    </button>


    {{-- Tabla --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th style="width: 120px;">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($ambientes as $ambiente)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ambiente->nombre }}</td>
                    <td>{{ $ambiente->ubicacion }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" wire:click="editar({{ $ambiente->id }})">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-danger btn-sm" wire:click="confirmarEliminar({{ $ambiente->id }})">
                            <i class="fas fa-trash"></i>
                        </button>



                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No hay resultados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal para crear/editar --}}
    @if ($modal)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,.5);">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">{{ $modo === 'crear' ? 'Crear Ambiente' : 'Editar Ambiente' }}</h5>
                        <button type="button" class="btn" wire:click="cerrarModal"> <i class="fas fa-times"></i> </button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" wire:model="nombre" class="form-control">
                            
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Ubicación</label>
                            <input type="text" wire:model="ubicacion" class="form-control">

                            @error('ubicacion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="cerrarModal">Cancelar</button>
                        <button class="btn btn-primary" wire:click="guardar">Guardar</button>
                    </div>

                </div>
            </div>
        </div>
    @endif
    @if ($confirmarEliminacion)
    <div class="modal fade show d-block" style="background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn text-white" wire:click="cancelarEliminar">
                    <i class="fas fa-times"></i>
                </button>

                </div>

                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este ambiente?</p>
                </div>

                <div class="modal-footer">
                    
                    <button class="btn btn-secondary" wire:click="cancelarEliminar">Cancelar</button>
                    <button class="btn btn-danger" wire:click="eliminarDefinitivo">Eliminar</button>
                </div>

            </div>
        </div>
    </div>
@endif


</div>
