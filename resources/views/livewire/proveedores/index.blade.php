<div>
    <x-adminlte-card title="Proveedores" theme="info" icon="fas fa-truck">
        <div class="row mb-3">

            <div class="col-md-9">
                <x-adminlte-input name="search" wire:model.live="search"
                    label="Buscar proveedor:"
                    placeholder="Razón Social, RUC, Teléfono o Dirección"
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
                    <th>Razón Social</th>
                    <th>RUC</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
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
document.addEventListener('success', event => {
    Swal.fire({
        position: "top-end",
        icon: "success",
        title: event.detail.message,
        showConfirmButton: false,
        timer: 1500
    });
});
</script>
@endpush


