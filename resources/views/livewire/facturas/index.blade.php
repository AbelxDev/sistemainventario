<div>

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
    <x-adminlte-card title="Facturas de Compra" theme="info" icon="fas fa-file-invoice">

        {{-- BARRA SUPERIOR --}}
        <div class="row mb-3">

            {{-- BUSCADOR --}}
            <div class="col-md-4">
                <x-adminlte-input name="search" wire:model.live="search" label="Buscar"
                    placeholder="Número o proveedor">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>

            {{-- NUEVA FACTURA --}}
            <div class="col-md-8 d-flex justify-content-end align-items-end">
                <button class="btn btn-primary" wire:click="openCreateModal">
                    <i class="fas fa-plus"></i> Nueva Factura
                </button>
            </div>
        </div>

        {{-- TABLA --}}
        <table class="table table-hover table-striped align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Estado</th>
                    <th>PDF</th>
                    <th style="width:150px">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($facturas as $f)
                    <tr>
                        <td><strong>{{ $f->numero }}</strong></td>
                        <td>{{ $f->fecha->format('d/m/Y') }}</td>
                        <td>{{ $f->proveedor->razon_social ?? '' }}</td>
                        <td>
                            @php
                                $badge = match ($f->estado) {
                                    'pendiente' => 'warning',
                                    'procesada' => 'success',
                                    'anulada' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($f->estado) }}</span>
                        </td>
                        <td>
                            @if ($f->pdf_ruta)
                                <a href="{{ asset('storage/' . $f->pdf_ruta) }}" target="_blank"
                                    class="btn btn-xs btn-outline-info">
                                    <i class="fas fa-file-pdf"></i> Ver
                                </a>
                            @else
                                <span class="text-muted">Sin archivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-warning" wire:click="openEditModal({{ $f->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-xs btn-danger"
                                    onclick="confirm('¿Seguro que deseas eliminar esta factura?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $f->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hay facturas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $facturas->links() }}

    </x-adminlte-card>


    {{-- ======================= MODAL BOOTSTRAP NATIVO ======================= --}}
    <div class="modal fade" id="modalFactura" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $modalMode === 'create' ? 'Nueva Factura' : 'Editar Factura' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form wire:key="form-factura-{{ $modalMode }}-{{ $factura_id }}"
                          wire:submit.prevent="{{ $modalMode === 'create' ? 'save' : 'update' }}"
                          enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-md-4">
                                <x-adminlte-input name="numero" label="Número" wire:model="numero" />
                                @error('numero') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <x-adminlte-input name="fecha" label="Fecha" type="date" wire:model="fecha" />
                                @error('fecha') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label>Proveedor</label>
                                <select class="form-control" wire:model="proveedor_id">
                                    <option value="">-- Seleccione --</option>
                                    @foreach ($proveedores as $prov)
                                        <option value="{{ $prov->id }}">
                                            {{ $prov->razon_social }} ({{ $prov->ruc }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('proveedor_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mt-3">

                            <div class="col-md-4">
                                <label>Estado</label>
                                <select class="form-control" wire:model="estado">
                                    <option value="pendiente">Pendiente</option>
                                    <option value="procesada">Procesada</option>
                                    <option value="anulada">Anulada</option>
                                </select>
                                @error('estado') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-8">
                                <label>Archivo PDF (opcional)</label>

                                <input type="file"
                                       class="form-control"
                                       wire:model="pdf_file"
                                       accept="application/pdf">

                                @error('pdf_file') <span class="text-danger">{{ $message }}</span> @enderror

                                @if ($pdf_ruta)
                                    <small class="d-block mt-1">
                                        Archivo actual:
                                        <a href="{{ asset('storage/' . $pdf_ruta) }}" target="_blank">Ver PDF</a>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                            @if ($modalMode === 'create')
                                <button class="btn btn-success" type="submit">Guardar</button>
                            @else
                                <button class="btn btn-warning" type="submit">Actualizar</button>
                            @endif
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>


@push('js')
<script>
    window.addEventListener('open-factura-modal', () => {
        new bootstrap.Modal(document.getElementById('modalFactura')).show();
    });

    window.addEventListener('close-factura-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalFactura'));
        if (modal) modal.hide();
    });
</script>
@endpush
