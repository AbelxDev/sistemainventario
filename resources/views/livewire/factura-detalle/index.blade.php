<div>
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
            <input type="number" class="form-control" disabled value="{{ $faltantes }}">
        </div>

        <div class="col-md-2">
            <label>&nbsp;</label><br>
            <button class="btn btn-primary" wire:click="agregarDetalle">
                <i class="fas fa-plus-circle"></i> Agregar
            </button>
        </div>
    </div>

</div>
