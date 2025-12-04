<div class="mt-3">

    {{-- ============================= --}}
    {{-- MENSAJES --}}
    {{-- ============================= --}}
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


    {{-- ============================= --}}
    {{-- TARJETA PRINCIPAL --}}
    {{-- ============================= --}}
    <x-adminlte-card title="Gestión de Productos" theme="info" icon="fas fa-box">

        {{-- ============================= --}}
        {{-- BUSCADOR + BOTÓN CREAR --}}
        {{-- ============================= --}}
        <div class="row mb-3">

            {{-- BUSCADOR --}}
            <div class="col-md-9">
                <x-adminlte-input name="search" wire:model.live="search"
                    placeholder="Buscar por código o nombre" label="Buscar producto:"
                    igroup-size="sm">

                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>

                </x-adminlte-input>
            </div>

            {{-- BOTÓN NUEVO --}}
            <div class="col-md-3 d-flex align-items-end justify-content-end">
                <x-adminlte-button label="Nuevo Producto" theme="primary" icon="fas fa-plus"
                    wire:click="openCreateModal" />
            </div>

        </div> {{-- /row --}}

        {{-- ============================= --}}
        {{-- TABLA --}}
        {{-- ============================= --}}
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle" style="min-width: 950px">
                <thead class="bg-light">
                    <tr>
                        <th>N°</th>
                        <th><i class="fas fa-barcode"></i> Código</th>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-tags"></i> Tipo</th>
                        <th><i class="fas fa-truck"></i> Proveedor Principal</th>
                        <th class="text-center" style="width: 140px;">
                            <i class="fas fa-cogs"></i> Acciones
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($productos as $p)
                        <tr>
                            <td>{{ $productos->firstItem() + $loop->index }}</td>

                            <td><strong>{{ $p->codigo }}</strong></td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->tipo->nombre }}</td>
                            <td>{{ $p->proveedorPrincipal->razon_social ?? '' }}</td>

                            <td class="text-center">

                                {{-- BOTÓN EDITAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">

                                    <x-adminlte-button theme="warning" icon="fas fa-edit" class="btn-sm"
                                        wire:click="openEditModal({{ $p->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />

                                    <div x-show="tooltip" x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%;
                                        white-space: nowrap; z-index: 2000;">
                                        Editar producto
                                    </div>

                                </div>

                                {{-- BOTÓN ELIMINAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">

                                    <x-adminlte-button theme="danger" icon="fas fa-trash" class="btn-sm"
                                        wire:click="openDeleteModal({{ $p->id }})"

                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false" />

                                    <div x-show="tooltip" x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%;
                                        white-space: nowrap; z-index: 2000;">
                                        Eliminar producto
                                    </div>

                                </div>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle"></i> No hay productos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINACIÓN --}}
            <div class="mt-3">
                {{ $productos->links() }}
            </div>
        </div>

    </x-adminlte-card>


    {{-- ============================= --}}
    {{-- MODAL CREAR / EDITAR --}}
    {{-- ============================= --}}
    <div class="modal fade" id="modalProducto" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalProductoLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">

                {{-- HEADER DINÁMICO --}}
                <div class="modal-header {{ $modalMode === 'create' ? 'bg-primary' : 'bg-warning' }} text-white">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="fas {{ $modalMode === 'create' ? 'fa-box' : 'fa-edit' }} mr-2"></i>
                        {{ $modalMode === 'create' ? 'Crear Producto' : 'Editar Producto' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                {{-- ============================= --}}
                {{-- FORMULARIO --}}
                {{-- ============================= --}}
                <div class="modal-body">

                    <div class="row">

                        {{-- TIPO --}}
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold">Tipo de producto</label>

                            <select class="form-control" wire:model.live="tipo_id">
                                <option value="">-- Seleccione --</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }} ({{ $tipo->prefijo }})</option>
                                @endforeach
                            </select>

                            @error('tipo_id')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- CÓDIGO --}}
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold">Código</label>

                            <div class="input-group">
                                <input type="text" class="form-control" wire:model="codigo" readonly>
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fas fa-barcode"></i></div>
                                </div>
                            </div>

                            @error('codigo')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- NOMBRE --}}
                        <div class="col-md-4 mb-3">
                            <label class="font-weight-bold">Nombre</label>

                            <div class="input-group">
                                <input type="text" class="form-control" wire:model="nombre"
                                    placeholder="Ej: Guantes de látex">
                                <div class="input-group-append">
                                    <div class="input-group-text"><i class="fas fa-tag"></i></div>
                                </div>
                            </div>

                            @error('nombre')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                    {{-- DESCRIPCIÓN --}}
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label class="font-weight-bold">Descripción</label>
                            <textarea class="form-control" rows="3" wire:model="descripcion"></textarea>
                            @error('descripcion')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    {{-- PROVEEDORES --}}
                    <div class="row mt-2">

                        {{-- ============================== --}}
                        {{-- PROVEEDOR PRINCIPAL (NORMAL) --}}
                        {{-- ============================== --}}
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Proveedor principal</label>

                            <select class="form-control" wire:model="proveedor_principal_id">
                                <option value="">-- Seleccione --</option>
                                @foreach ($proveedores as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->razon_social }} ({{ $prov->ruc }})</option>
                                @endforeach
                            </select>

                            @error('proveedor_principal_id')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- ============================== --}}
                        {{-- PROVEEDORES ADICIONALES (MULTI) --}}
                        {{-- ============================== --}}
                        <div class="col-md-6 mb-3"
                            x-data="multiSelect({
                                proveedores: @js($proveedores),
                                selected: @entangle('proveedores_secundarios'),
                                proveedorPrincipal: @entangle('proveedor_principal_id')
                            })"
                            x-init="init()"
                            x-effect="sincronizarPrincipal()">



                            <label class="font-weight-bold mb-1">Proveedores adicionales</label>

                            <!-- INPUT PRINCIPAL -->
                             <div class="form-control d-flex flex-wrap align-items-start"
                                style="cursor:pointer; min-height: 42px; height:auto;"

                                :class="!proveedorPrincipalActivo ? 'bg-light text-muted border-secondary' : ''"
                                @click="if (proveedorPrincipalActivo) open = !open">

                                <!-- TAGS -->
                                <template x-for="item in selectedOptions.filter(o => o.id !== Number(proveedorPrincipal))" :key="item.id">

                                    <span class="badge badge-info mr-1 mb-1 d-flex align-items-center">
                                        <span x-text="item.razon_social"></span>
                                        <i class="fas fa-times ml-2"
                                        style="cursor:pointer"
                                        @click.stop="toggle(item.id)"
                                        x-show="proveedorPrincipalActivo"></i>
                                    </span>
                                </template>

                                <span x-show="selectedOptions.length === 0" class="text-muted">
                                    Seleccione uno o varios proveedores...
                                </span>

                                <i class="fas fa-chevron-down ml-auto"
                                x-show="proveedorPrincipalActivo"></i>
                            </div>

                            <!-- DROPDOWN -->
                            <div class="border mt-1 bg-white rounded shadow-sm"
                                x-show="open && proveedorPrincipalActivo"
                                @click.outside="open = false"
                                x-transition
                                style="position:absolute; z-index:9999; width:100%; max-height:200px; overflow-y:auto;">

                                <template x-for="prov in proveedores.filter(p => p.id !== Number(proveedorPrincipal))" :key="prov.id">
                                    <label class="px-2 py-1 d-flex align-items-center" style="cursor:pointer;">

                                        <input type="checkbox" class="mr-2"
                                            :disabled="!proveedorPrincipalActivo"
                                            @change="toggle(prov.id)"
                                            :checked="selected.includes(prov.id)">

                                        <span x-text="prov.razon_social + ' (' + prov.ruc + ')'"></span>
                                    </label>
                                </template>


                            </div>


                            @error('proveedores_secundarios')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <hr>

                    {{-- AMBIENTE + CANTIDAD --}}
                    <div class="row mt-2">

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Ambiente inicial</label>
                            <select class="form-control" wire:model.live="ambiente_id">
                                <option value="">-- Sin ambiente inicial --</option>
                                @foreach ($ambientes as $amb)
                                    <option value="{{ $amb->id }}">{{ $amb->nombre }} - {{ $amb->ubicacion }}</option>
                                @endforeach
                            </select>
                            @error('ambiente_id')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Cantidad inicial</label>

                            <input type="number" min="0" class="form-control" wire:model.live="cantidad_inicial"
                                placeholder="Ej: 100">

                            @error('cantidad_inicial')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                </div> {{-- /modal-body --}}


                {{-- ============================= --}}
                {{-- FOOTER --}}
                {{-- ============================= --}}
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i> Cancelar
                    </button>

                    @if ($modalMode === 'create')
                        <button class="btn btn-primary" wire:click="save">
                            <i class="fas fa-save mr-1"></i> Crear Factura
                        </button>
                    @else
                        <button class="btn btn-success" wire:click="update">
                            <i class="fas fa-edit mr-1"></i> Actualizar
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
    {{-- ============================= --}}
    {{-- MODAL ELIMINAR PRODUCTO --}}
    {{-- ============================= --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalDeleteLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-md">
            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title d-flex align-items-center" id="modalDeleteLabel">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Eliminar Producto
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body text-center">

                    <i class="fas fa-box fa-3x text-danger mb-3"></i>
                    <i class="fas fa-times fa-4x text-danger mb-3"></i>

                    <h5 class="font-weight-bold">
                        ¿Seguro que deseas eliminar este producto?
                    </h5>

                    <p class="text-muted">
                        Esta acción es permanente y no se puede deshacer.
                    </p>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i> Cancelar
                    </button>

                    <button class="btn btn-danger" wire:click="confirmDelete">
                        <i class="fas fa-trash mr-1"></i> Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>





{{-- ============================= --}}
{{-- SCRIPTS DE MODAL --}}
{{-- ============================= --}}
@push('js')
<script>
document.addEventListener('livewire:init', () => {

    Livewire.on('abrirModalForm', () => $('#modalForm').modal('show'));
    Livewire.on('cerrarModalForm', () => $('#modalForm').modal('hide'));

    Livewire.on('abrirModalEliminar', () => $('#modalDelete').modal('show'));
    Livewire.on('cerrarModalEliminar', () => $('#modalDelete').modal('hide'));

    Livewire.on('open-producto-modal', () => $('#modalProducto').modal('show'));
    Livewire.on('close-producto-modal', () => $('#modalProducto').modal('hide'));

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



function multiSelect({ proveedores, selected, proveedorPrincipal }) {
    return {
        open: false,
        proveedores,
        selected,
        proveedorPrincipal,

        init() {
            if (!Array.isArray(this.selected)) {
                this.selected = [];
            }

            this.$watch("proveedorPrincipal", (nuevo) => {
                nuevo = Number(nuevo);

                this.selected = this.selected.filter(id => id !== nuevo);
                this.open = false;
            });
        },

        sincronizarPrincipal() {
            if (!this.proveedorPrincipal) return;

            this.selected = this.selected.filter(
                id => id !== Number(this.proveedorPrincipal)
            );
        },

        get proveedorPrincipalActivo() {
            return this.proveedorPrincipal !== null && this.proveedorPrincipal !== "";
        },

        toggle(id) {
            if (!this.proveedorPrincipalActivo) return;
            if (id === Number(this.proveedorPrincipal)) return;

            if (this.selected.includes(id)) {
                this.selected = this.selected.filter(i => i !== id);
            } else {
                this.selected.push(id);
            }
        },

        get selectedOptions() {
            return this.proveedores.filter(p => this.selected.includes(p.id));
        }
    }
}


</script>
@endpush
