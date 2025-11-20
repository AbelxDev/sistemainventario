<div class="mt-3">

    {{-- Mensajes --}}
    @if (session()->has('success'))
        <x-adminlte-alert theme="success" title="Correcto">
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <x-adminlte-card title="Tipos de Producto" theme="info" icon="fas fa-tags">

        {{-- BUSCADOR --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <x-adminlte-input name="search" label="Buscar:" placeholder="Nombre o prefijo" wire:model.live="search">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>

            <div class="col-md-8 d-flex justify-content-end align-items-end">
                <x-adminlte-button theme="primary" icon="fas fa-plus" label="Nuevo Tipo" wire:click="openCreateModal" />
            </div>
        </div>

        {{-- TABLA --}}
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Nombre</th>
                    <th>Prefijo</th>
                    <th>Descripción</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($tipos as $tipo)
                    <tr>
                        <td>{{ $tipos->firstItem() + $loop->index }}</td>
                        <td>{{ $tipo->nombre }}</td>
                        <td><strong>{{ $tipo->prefijo }}</strong></td>
                        <td>{{ $tipo->descripcion }}</td>

                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-warning" wire:click="openEditModal({{ $tipo->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-xs btn-danger"
                                    onclick="confirm('¿Eliminar?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $tipo->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay registros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $tipos->links() }}
        </div>

    </x-adminlte-card>

    {{-- MODAL --}}
    <x-adminlte-modal id="modalTipo" title="Tipo de Producto" theme="info" icon="fas fa-tag" v-centered
        static-backdrop scrollable wire:ignore.self>

        <div class="row">
            <div class="col-md-6">
                <x-adminlte-input name="nombre" label="Nombre" wire:model="nombre" />
                @error('nombre')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <x-adminlte-input name="prefijo" label="Prefijo" wire:model="prefijo" placeholder="Ej: MED"
                    :disabled="$canEditPrefijo === false" />
                @if (!$canEditPrefijo)
                    <small class="text-muted">
                        Este prefijo no puede ser modificado porque ya existen productos asociados.
                    </small>
                @endif

                @error('prefijo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-12 mt-2">
                <x-adminlte-textarea name="descripcion" label="Descripción" wire:model="descripcion" rows="3" />
                @error('descripcion')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cancelar" data-dismiss="modal" />
            @if ($modalMode === 'create')
                <x-adminlte-button theme="success" label="Guardar" wire:click="save" />
            @else
                <x-adminlte-button theme="warning" label="Actualizar" wire:click="update" />
            @endif
        </x-slot>
    </x-adminlte-modal>

</div>

{{-- JS para los modales --}}
@push('js')
    <script>
        window.addEventListener('open-modal', () => $('#modalTipo').modal('show'));
        window.addEventListener('close-modal', () => $('#modalTipo').modal('hide'));
    </script>
@endpush
