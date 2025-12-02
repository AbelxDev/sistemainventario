<div class="mt-3">

    {{-- ============================= --}}
    {{-- HEADER Y BUSCADOR --}}
    {{-- ============================= --}}
    <x-adminlte-card title="Gestión de Facturas" theme="info" icon="fas fa-file-invoice">

        <div class="row mb-3">

            {{-- BUSCADOR --}}
            <div class="col-md-9">
                <x-adminlte-input 
                    name="search" 
                    wire:model.live="search"
                    placeholder="Buscar por número o proveedor..."
                >
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" disable-feedback />
                    </x-slot>
                </x-adminlte-input>
            </div>

            {{-- BOTÓN CREAR --}}
            <div class="col-md-3 text-right">
                <x-adminlte-button 
                    label="Nueva Factura" 
                    theme="primary" 
                    icon="fas fa-plus" 
                    wire:click="openCreateModal"
                />
            </div>
        </div>

        {{-- ============================= --}}
        {{-- TABLA --}}
        {{-- ============================= --}}
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>N°</th>
                        <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                        <th><i class="fas fa-truck"></i> Proveedor</th>
                        <th><i class="fas fa-info-circle"></i> Estado</th>
                        <th><i class="fas fa-file-pdf"></i> PDF</th>
                        <th class="text-center" style="width: 120px;"><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($facturas as $f)
                        <tr>
                            <td>{{ $f->numero }}</td>
                            <td>{{ $f->fecha->format('d/m/Y') }}</td>
                            <td>{{ $f->proveedor?->razon_social }}</td>

                            <td>
                                <span class="badge badge-{{ 
                                    $f->estado === 'pendiente' ? 'warning' : 
                                    ($f->estado === 'procesada' ? 'success' : 'danger') 
                                }}">
                                    {{ ucfirst($f->estado) }}
                                </span>
                            </td>

                            <td >

                                @if ($f->pdf_ruta)
                                    <div x-data="{ tooltip: false }" class="d-inline-block position-relative">
                                        <button 
                                            type="button"
                                            class="btn btn-info btn-sm"
                                            @click="openCenteredPdf('{{ asset('storage/' . $f->pdf_ruta) }}')"
                                            @mouseenter="tooltip = true"
                                            @mouseleave="tooltip = false">

                                            <i class="fas fa-eye"></i>
                                        </button>


                                        <div 
                                            x-show="tooltip"
                                            x-transition
                                            class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                            style="bottom: 120%; right: 50%; white-space: nowrap; z-index: 9999;">
                                            Ver PDF
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif

                            </td>


                            <!-- mensajes con alphine -->
                            <td class="text-center">

                                {{-- BOTÓN EDITAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <button 
                                        class="btn btn-warning btn-sm"
                                        wire:click="openEditModal({{ $f->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- TOOLTIP -->
                                    <div 
                                        x-show="tooltip"
                                        x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%; white-space: nowrap; z-index: 9999;"
                                    >
                                        Editar factura
                                    </div>
                                </div>

                                {{-- BOTÓN ELIMINAR --}}
                                <div x-data="{ tooltip: false }" class="d-inline-block position-relative mx-1">
                                    <button 
                                        class="btn btn-danger btn-sm"
                                        wire:click="confirmarEliminar({{ $f->id }})"
                                        @mouseenter="tooltip = true"
                                        @mouseleave="tooltip = false"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <!-- TOOLTIP -->
                                    <div 
                                        x-show="tooltip"
                                        x-transition
                                        class="position-absolute bg-dark text-white px-2 py-1 rounded shadow"
                                        style="bottom: 120%; right: 50%; white-space: nowrap; z-index: 9999;"
                                    >
                                        Eliminar factura
                                    </div>
                                </div>

                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No se encontraron facturas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            {{ $facturas->links() }}
        </div>

    </x-adminlte-card>

    {{-- ========================================= --}}
    {{-- MODAL CREAR / EDITAR --}}
    {{-- ========================================= --}}
    <div wire:ignore.self class="modal fade" id="modalFactura" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas {{ $modalMode === 'create' ? 'fas fa-file-invoice' : 'fa-edit' }}"></i>
                        {{ $modalMode === 'create' ? ' Nueva Factura' : 'Editar Factura' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">
                    <div class="row">
                        {{-- Número --}}
                        <div class="col-md-6 mb-3">
                            <label>Número</label>
                            <input type="text" class="form-control" wire:model="numero">
                            @error('numero') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Fecha --}}
                        <div class="col-md-6">
                            <label>Fecha</label>
                            <input type="date" class="form-control" wire:model="fecha">
                            @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        {{-- Proveedor --}}
                        <div class="col-md-6">
                            <label>Proveedor</label>
                            <select class="form-control" wire:model="proveedor_id">
                                <option value="">Seleccione...</option>
                                @foreach ($proveedores as $p)
                                    <option value="{{ $p->id }}">{{ $p->razon_social }}</option>
                                @endforeach
                            </select>
                            @error('proveedor_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="col-md-6">
                            <label>Estado</label>
                            <select class="form-control" wire:model="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="procesada">Procesada</option>
                                <option value="anulada">Anulada</option>
                            </select>
                            @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    
                    {{-- PDF --}}
                    <div 
                        class="mt-4"
                        x-data="pdfUploader"
                        @reset-pdf.window="reset()"
                        x-cloak
                    >
                        <label class="font-weight-bold mb-2">Archivo PDF</label>

                        {{-- Zona de arrastrar / seleccionar --}}
                        <div
                            style="cursor: pointer";
                            class="border rounded p-4 text-center bg-light "
                            :class="{ 
                                'border-primary shadow': fileName !== '', 
                                'border-danger': isDragging 
                            }"
                            @click="openFileDialog()"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)"
                        >
                            <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>

                            <div class="mt-2">
                                <strong x-text="fileName || 'Haz clic o arrastra un PDF aquí'"></strong>
                            </div>

                            <input 
                                type="file" 
                                accept="application/pdf"
                                wire:model="pdf_file"
                                class="d-none"
                                x-ref="pdfInput"
                                @change="updateFileName($event)"
                            >
                        </div>

                        {{-- Mostrar errores --}}
                        @error('pdf_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        {{-- Vista previa PDF --}}
                        @if ($pdf_preview_path)
                            <iframe 
                                src="{{ asset('storage/' . $pdf_preview_path) }}" 
                                class="mt-3"
                                style="width:100%; height:500px; border:1px solid #ccc;">
                            </iframe>
                        @endif
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i> Cancelar
                    </button>

                    <button type="submit"
                        class="btn {{ $modalMode === 'create' ? 'btn-primary' : 'btn-success' }}"
                        wire:click="{{ $modalMode === 'create' ? 'save' : 'update' }}"
                    >
                        <i class="fas {{ $modalMode === 'create' ? 'fa-plus-circle' : 'fa-save' }} mr-1"></i>
                        {{ $modalMode === 'create' ? 'Crear Factura' : 'Actualizar Factura' }}
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- MODAL ELIMINACIÓN --}}
    {{-- ========================================= --}}
    <div wire:ignore.self class="modal fade" id="modalEliminarFactura" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-trash"></i> Confirmar Eliminación</h5>
                    <button class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body text-center">
                    <!-- conseguir otro documento de color negro  - hecho -->
                    <i class="fas fa-file-alt fa-3x text-danger"></i>
                    <i class="fas fa-times text-danger fa-4x"></i>

                    <h5 class="font-weight-blod">
                        ¿Seguro que deseas eliminar esta factura?
                    </h5>
                    <p class="text-muted">
                        Esta acción es permanente y no se puede deshacer.
                    </p>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i>
                        Cancelar
                    </button>

                    <button class="btn btn-danger" wire:click="eliminarDefinitivo">
                        <i class="fas fa-trash-alt mr-1"></i>
                        Eliminar
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
{{-- ======================================================== --}}
{{-- SCRIPTS LIMPIOS Y OPTIMIZADOS --}}
{{-- ======================================================== --}}
<script>
document.addEventListener('livewire:init', () => {

    // Abrir / cerrar modal principal
    Livewire.on('abrirModalForm', () => $('#modalFactura').modal('show'));
    Livewire.on('cerrarModalForm', () => $('#modalFactura').modal('hide'));

    // Abrir / cerrar modal eliminar
    Livewire.on('abrirModalEliminar', () => $('#modalEliminarFactura').modal('show'));
    Livewire.on('cerrarModalEliminar', () => $('#modalEliminarFactura').modal('hide'));

    // Alerts de éxito
    Livewire.on('success', (data) => {
        Swal.fire({
            icon: 'success',
            title: data.message,
            timer: 2000,
            showConfirmButton: false
        });
    });
});

document.addEventListener('alpine:init', () => {

    Alpine.data('pdfUploader', () => ({
        fileName: '',
        isDragging: false,

        openFileDialog() {
            this.$refs.pdfInput.click();
        },

        updateFileName(event) {
            const file = event.target.files[0];
            if (file) this.fileName = file.name;
        },

        handleDrop(event) {
            this.isDragging = false;

            const file = event.dataTransfer.files[0];
            if (!file) return;

            if (file.type !== "application/pdf") {
                this.fileName = "Archivo no válido";
                return;
            }

            // Cargar archivo en el input real
            this.$refs.pdfInput.files = event.dataTransfer.files;

            // Disparar evento para Livewire
            this.$refs.pdfInput.dispatchEvent(new Event('change', { bubbles: true }));

            this.fileName = file.name;
        },

        reset() {
            this.fileName = '';
            this.isDragging = false;
            this.$refs.pdfInput.value = null;
        },
    }));

});

document.addEventListener('DOMContentLoaded', function () {

    // Reset al cerrar modal (X, cancelar, clic fuera, ESC)
    $('#modalFactura').on('hidden.bs.modal', function () {

        // Reset Livewire
        Livewire.dispatch('resetModalFactura');

        // Reset Alpine (file input)
        window.dispatchEvent(new CustomEvent('reset-pdf'));
    });

});

function openCenteredPdf(url) {
    // Tamaño de la ventana
    const width = 900;
    const height = 700;

    // Calcular posición centrada
    const left = (window.screen.width / 2) - (width / 2);
    const top = (window.screen.height / 2) - (height / 2);

    // Abrir ventana centrada
    window.open(
        url,
        "VisorPDF",
        `
            width=${width},
            height=${height},
            top=${top},
            left=${left},
            resizable=yes,
            scrollbars=yes
        `
    );
}
</script>

