<div class="mt-3">

    {{-- Mensajes --}}
    @if (session()->has('success'))
        <x-adminlte-alert theme="success" title="Correcto">
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    @if (session()->has('error'))
        <x-adminlte-alert theme="danger" title="Error">
            {{ session('error') }}
        </x-adminlte-alert>
    @endif

    {{-- TARJETA PRINCIPAL --}}
    <x-adminlte-card title="Listado de Productos" theme="info" icon="fas fa-box">

        {{-- BARRA SUPERIOR --}}
        <div class="row mb-3">

            {{-- BUSCADOR --}}
            <div class="col-md-4">
                <x-adminlte-input name="search" wire:model.live="search" label="Buscar" placeholder="Código o nombre">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>

            {{-- BOTÓN NUEVO --}}
            <div class="col-md-8 d-flex justify-content-end align-items-end">
                <x-adminlte-button class="ml-auto" theme="primary" icon="fas fa-plus" label="Nuevo Producto"
                    wire:click="openCreateModal" />
            </div>
        </div>

        {{-- TABLA --}}
        <table class="table table-hover table-striped align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Proveedor Principal</th>
                    <th style="width:150px">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($productos as $p)
                    <tr>
                        <td><strong>{{ $p->codigo }}</strong></td>
                        <td>{{ $p->nombre }}</td>
                        <td>{{ $p->tipo->nombre }}</td>
                        <td>{{ $p->proveedorPrincipal->razon_social ?? '' }}</td>

                        <!--alphine-->
                        <td class="text-center">
                            <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <x-adminlte-button theme="warning" icon="fas fa-edit" class="btn-sm"
                                        wire:click="openEditModal({{ $p->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />

                                    <!-- TOOLTIP ALPINE -->
                                    <div x-show="tooltip"
                                        x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; left: 50%; transform: translateX(-80%); white-space: nowrap; z-index: 9999;">
                                        Editar producto
                                    </div>
                            </div>

                                <!-- BOTÓN ELIMINAR -->
                            <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <x-adminlte-button theme="danger" icon="fas fa-trash" class="btn-sm"
                                        wire:click="delete({{ $p->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />

                                    <!-- TOOLTIP ALPINE -->
                                    <div x-show="tooltip"
                                        x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; left: 50%; transform: translateX(-80%); white-space: nowrap; z-index: 9999;">
                                        Eliminar producto
                                    </div>
                            </div>
                        </td>
                        
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No hay productos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $productos->links() }}

    </x-adminlte-card>

    {{-- ====================================================== --}}
    {{-- ======================== MODAL ======================== --}}
    {{-- ====================================================== --}}

    <x-adminlte-modal id="modalProducto" title="Producto" theme="info" icon="fas fa-box" v-centered static-backdrop
        scrollable wire:ignore.self>

        <div class="row">

            {{-- Tipo --}}
            <div class="col-md-4">
                <x-adminlte-select name="tipo_id" label="Tipo de producto" wire:model.live="tipo_id">
                    <option value="">-- Seleccione --</option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id }}">
                            {{ $tipo->nombre }} ({{ $tipo->prefijo }})
                        </option>
                    @endforeach
                </x-adminlte-select>
                @error('tipo_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Código --}}
            <div class="col-md-4">
                <x-adminlte-input name="codigo" label="Código" wire:model="codigo" readonly />
                @error('codigo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Nombre --}}
            <div class="col-md-4">
                <x-adminlte-input name="nombre" label="Nombre" wire:model="nombre"
                    placeholder="Ej: Guantes de látex" />
                @error('nombre')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Descripción --}}
        <div class="row mt-3">
            <div class="col-md-12">
                <x-adminlte-textarea name="descripcion" label="Descripción" wire:model="descripcion" rows=3 />
                @error('descripcion')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <hr>

        {{-- Proveedor --}}
        <div class="row mt-2">
            <div class="col-md-6">
                <x-adminlte-select name="proveedor_principal_id" label="Proveedor principal"
                    wire:model="proveedor_principal_id">
                    <option value="">-- Seleccione --</option>
                    @foreach ($proveedores as $prov)
                        <option value="{{ $prov->id }}">
                            {{ $prov->razon_social }} ({{ $prov->ruc }})
                        </option>
                    @endforeach
                </x-adminlte-select>
                @error('proveedor_principal_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Proveedores adicionales --}}
            <div class="col-md-6">
                <label for="proveedores_secundarios" class="form-label">Proveedores adicionales</label>
                <select id="proveedores_secundarios" class="form-control" multiple
                    wire:model.live="proveedores_secundarios">

                    @foreach ($proveedores as $prov)
                        <option value="{{ $prov->id }}">
                            {{ $prov->razon_social }} ({{ $prov->ruc }})
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Ctrl o Cmd para seleccionar varios</small>
                @error('proveedores_secundarios')
                    <span class="text-danger d-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <hr>

        {{-- Ambiente --}}
        <div class="row mt-2">
            <div class="col-md-6">
                <x-adminlte-select name="ambiente_id" label="Ambiente inicial" wire:model.live="ambiente_id">
                    <option value="">-- Sin ambiente inicial --</option>
                    @foreach ($ambientes as $amb)
                        <option value="{{ $amb->id }}">
                            {{ $amb->nombre }} - {{ $amb->ubicacion }}
                        </option>
                    @endforeach
                </x-adminlte-select>
                @error('ambiente_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <x-adminlte-input name="cantidad_inicial" label="Cantidad inicial" type="number" min="0"
                    wire:model.live="cantidad_inicial" placeholder="Ej: 100" />
                @error('cantidad_inicial')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- BOTONES DEL MODAL --}}
        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="Cerrar" data-dismiss="modal" />

            @if ($modalMode === 'create')
                <x-adminlte-button theme="success" label="Guardar" wire:click="save" />
            @else
                <x-adminlte-button theme="warning" label="Actualizar" wire:click="update" />
            @endif
        </x-slot>

    </x-adminlte-modal>

</div>

{{-- Scripts JS para abrir/cerrar modal --}}
@push('js')
    <script>
        window.addEventListener('open-producto-modal', () => {
            $('#modalProducto').modal('show');

            // Forzar actualización de selects
            setTimeout(() => {
                $('#modalProducto').find('select').trigger('change');
            }, 200);
        });

        window.addEventListener('close-producto-modal', () => {
            $('#modalProducto').modal('hide');
        });
    </script>
@endpush
