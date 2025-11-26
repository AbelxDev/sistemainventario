<div>
    <x-adminlte-card title="Proveedores" theme="info" icon="fas fa-truck">
        <div class="row mb-3">

            <div class="col-md-9">
                <x-adminlte-input name="search" wire:model.live="search"
                    label="Buscar proveedor:"
                    placeholder="Buscar por Razón Social, RUC, Teléfono o Dirección"
                    igroup-size="sm">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="dark" icon="fas fa-search" />
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="col-md-3 d-flex align-items-end justify-content-end">
                <x-adminlte-button theme="primary" icon="fa fa-plus" label="Nuevo Proveedor"
                    wire:click="crear" />
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" style="min-width: 900px">
                <thead>
                <tr>
                    <th>N°</th>
                    <th> <i class="fas fa-building"></i> Razón Social</th>
                    <th><i class="fas fa-id-card"></i> RUC</th>
                    <th><i class="fas fa-phone"></i> Teléfono</th>
                    <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
                </thead>

                <tbody>
                @foreach($proveedores as $index => $item)
                    <tr>
                        <td>{{ $proveedores->firstItem() + $index }}</td>
                        <td>{{ $item->razon_social }}</td>
                        <td>{{ $item->ruc }}</td>
                        <td>{{ $item->telefono }}</td>
                        <td>{{ $item->direccion }}</td>
                        <td>
                            <div class="d-flex align-items-center" style="gap: 12px">
                                <button class="btn btn-warning btn-sm"
                                        wire:click="editar({{ $item->id }})">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm"
                                        wire:click="confirmarEliminacion({{ $item->id }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $proveedores->links() }}
        </div>

    </x-adminlte-card>
    
    @include('livewire.proveedores.modal')
    @include('livewire.proveedores.modal-eliminar')
</div>

@push('js')
<script>
    // --- ALERTA SWEETALERT2 ---
    document.addEventListener('success', (event) => {
        Swal.fire({
            icon: "success",
            title: event.detail.message,
            showConfirmButton: false,
            timer: 1500,
            position: "center"
        });
    });
</script>
@endpush
@push('css')
<style>
    @media (min-width: 992px) { /* pantallas grandes */
        .custom-modal {
            margin-top: -120px !important;
        }
    }

        @media (max-width: 991px) { /* tablets y móviles */
            .custom-modal {
                margin-top: 0 !important;
        }
    }

    /* Tamaño normal (desktop) */
    thead th {
        font-size: 16px;
    }
    thead th i {
        font-size: 16px;
        margin-right: 4px;
    }

    /* Tablets (pantallas medianas) */
    @media (max-width: 991px) {
        thead th {
            font-size: 14px;
        }
        thead th i {
            font-size: 14px;
        }
    }

    /* Celulares (pantallas pequeñas) */
    @media (max-width: 768px) {
        thead th {
            font-size: 12px;
        }
        thead th i {
            font-size: 12px;
        }
    }

    /* Celulares muy pequeños */
    @media (max-width: 576px) {
        thead th {
            font-size: 11px;
        }
        thead th i {
            font-size: 11px;
        }
    }

    @media (max-width: 768px) {
        table td, table th {
            padding: 6px 8px !important;
        }
    }

    /* Fix para SweetAlert centrado en AdminLTE */
    .swal2-container {
        top: 0 !important;
        left: 0 !important;
        bottom: 0 !important;
        right: 0 !important;
        transform: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .swal2-popup {
        margin: auto !important;
    }

</style>
@endpush('css')

