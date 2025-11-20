<div>
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" wire:click="crear">
                <i class="fa fa-plus"></i> Nuevo Proveedor
            </button>
        </div>

        <div class="card-body">

            {{-- SCROLL RESPONSIVE --}}
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
                                <div class="d-flex align-items-center" style= "gap: 12px">
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

            {{ $proveedores->links() }}
        </div>
    </div>

    @include('livewire.proveedores.modal')
    @include('livewire.proveedores.modal-eliminar')
</div>
