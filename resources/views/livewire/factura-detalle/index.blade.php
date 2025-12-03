<div>
    {{-- ============================= --}}
    {{-- FORMULARIO SUPERIOR --}}
    {{-- ============================= --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    <label>Producto</label>
                    <select class="form-control" wire:model="producto_id">
                        <option value="">Seleccione...</option>
                        @foreach ($productos as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Cantidad</label>
                    <input type="number" class="form-control" wire:model="cantidad">
                </div>

                <div class="col-md-2">
                    <label>Recibidos</label>
                    <input type="number" class="form-control" wire:model="recibidos">
                </div>

                <div class="col-md-2">
                    <label>Faltantes</label>
                    <input type="number" class="form-control bg-light" disabled wire:model="faltantes">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary" wire:click="agregarDetalle">
                        <i class="fas fa-plus-circle"></i> Agregar
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- TABLA PROFORMA (solo si hay detalles) --}}
    {{-- ============================= --}}
    @if (count($detalles) > 0)
        <table class="table table-bordered mt-3">
            <thead class="bg-info text-white">
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Recibidos</th>
                    <th>Faltantes</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['producto_nombre'] }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>{{ $item['recibidos'] }}</td>
                        <td>{{ $item['faltantes'] }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm"
                                wire:click="eliminarDetalle({{ $index }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
