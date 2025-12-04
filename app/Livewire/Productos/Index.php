<?php

namespace App\Livewire\Productos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use App\Models\TipoProducto;
use App\Models\Proveedor;
use App\Models\Ambiente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    // ============ LISTADO ============
    public $search = '';

    // ============ MODALES ============
    public $modalMode = 'create'; // create | edit

    // ============ CAMPOS FORMULARIO ============
    public $producto_id;
    public $tipos = [];
    public $proveedores = [];
    public $ambientes = [];

    public $tipo_id;
    public $codigo;
    public $nombre;
    public $descripcion;
    public $proveedor_principal_id;
    public $proveedores_secundarios = [];
    public $ambiente_id;
    public $cantidad_inicial;

    public function mount()
    {
        $this->tipos = TipoProducto::orderBy('nombre')->get();
        $this->proveedores = Proveedor::orderBy('razon_social')->get();
        $this->ambientes = Ambiente::orderBy('nombre')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // ============ VALIDACIONES ============
    protected $messages = [
        'tipo_id.required' => 'El tipo de producto es obligatorio.',
        'tipo_id.exists'   => 'El tipo seleccionado no es válido.',

        'codigo.required'  => 'El código es obligatorio.',
        'codigo.max'       => 'El código no puede exceder 50 caracteres.',
        'codigo.unique'    => 'Este código ya está registrado.',

        'nombre.required'  => 'El nombre del producto es obligatorio.',
        'nombre.max'       => 'El nombre no puede exceder 255 caracteres.',

        'proveedor_principal_id.required' => 'El proveedor principal es obligatorio.',
        'proveedor_principal_id.exists'   => 'El proveedor principal no es válido.',

        'proveedores_secundarios.array'   => 'El formato de proveedores secundarios no es válido.',
        'proveedores_secundarios.*.exists' => 'Uno de los proveedores secundarios no es válido.',

        'ambiente_id.exists' => 'El ambiente seleccionado no es válido.',

        'cantidad_inicial.integer' => 'La cantidad inicial debe ser un número.',
        'cantidad_inicial.min'     => 'La cantidad inicial no puede ser negativa.',
        'cantidad_inicial.required' => 'La cantidad inicial es obligatoria.',
        'cantidad_inicial.integer'  => 'La cantidad inicial debe ser un número entero.',
        'cantidad_inicial.min'      => 'La cantidad inicial no puede ser negativa.',

    ];

    protected function rules()
    {
        return [
            'tipo_id' => 'required|exists:tipo_productos,id',
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $this->producto_id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'proveedor_principal_id' => 'required|exists:proveedors,id',
            'proveedores_secundarios' => 'array',
            'proveedores_secundarios.*' => 'exists:proveedors,id',
            'ambiente_id' => 'nullable|exists:ambientes,id',
            'cantidad_inicial' => 'nullable|integer|min:0',
        ];
    }

    // ============ CÓDIGO AUTOMÁTICO ============
    public function updatedTipoId()
    {
        $this->generarCodigo();
    }

    protected function generarCodigo()
    {
        if (!$this->tipo_id) {
            $this->codigo = null;
            return;
        }

        $tipo = TipoProducto::find($this->tipo_id);
        if (!$tipo)
            return;

        $prefijo = strtoupper($tipo->prefijo);

        $codigo = DB::transaction(function () use ($prefijo) {
            $ultimo = Producto::where('codigo', 'like', $prefijo . '-%')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $next = 1;

            if ($ultimo && preg_match('/^' . preg_quote($prefijo, '/') . '\-(\d+)$/', $ultimo->codigo, $m)) {
                $next = (int) $m[1] + 1;
            }

            return sprintf('%s-%04d', $prefijo, $next);
        });

        $this->codigo = $codigo;
    }

    // ============ MODAL CREAR ============
    public function openCreateModal()
    {
        $this->modalMode = 'create';
        $this->resetForm();

        $this->resetErrorBag();
        $this->dispatch('open-producto-modal');
    }

    public function save()
    {
        $this->cantidad_inicial = (int) $this->cantidad_inicial;


        $this->validate();

        DB::transaction(function () {
            $producto = Producto::create([
                'tipo_id' => $this->tipo_id,
                'codigo' => $this->codigo,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'proveedor_principal_id' => $this->proveedor_principal_id,
            ]);

            $producto->proveedores()->sync(
                collect($this->proveedores_secundarios)
                    ->diff([$this->proveedor_principal_id])
                    ->values()
                    ->all()
            );

            if (!empty($this->ambiente_id) && $this->cantidad_inicial !== null && $this->cantidad_inicial >= 0) {
                $producto->ambientes()->attach($this->ambiente_id, [
                    'cantidad' => $this->cantidad_inicial
                ]);
            }
        });

        $this->dispatch('success', message: 'Producto creado correctamente!');

        $this->dispatch('close-producto-modal');

        $this->resetPage();

        $this->resetForm();

    }

    // ============ MODAL EDITAR ============
    public function openEditModal($id)
    {
        $this->modalMode = 'edit';

        $p = Producto::with(['proveedores', 'ambientes'])->findOrFail($id);

        $this->producto_id = $p->id;
        $this->tipo_id = $p->tipo_id;
        $this->codigo = $p->codigo;
        $this->nombre = $p->nombre;
        $this->descripcion = $p->descripcion;
        $this->proveedor_principal_id = $p->proveedor_principal_id;
        $this->proveedores_secundarios = $p->proveedores->pluck('id')->toArray();

        // Manejar ambiente SAFE
        $ambiente = $p->ambientes->first();

        if ($ambiente) {
            $this->ambiente_id = $ambiente->id;
            $this->cantidad_inicial = $ambiente->pivot->cantidad;
        } else {
            $this->ambiente_id = null;
            $this->cantidad_inicial = 0;
        }
         
        $this->resetErrorBag();
        $this->dispatch('open-producto-modal');
    }


    public function update()
    {
        $this->validate();

        $p = Producto::findOrFail($this->producto_id);

        $p->update([
            'tipo_id' => $this->tipo_id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'proveedor_principal_id' => $this->proveedor_principal_id,
        ]);

        $p->proveedores()->sync(
            collect($this->proveedores_secundarios)
                ->diff([$this->proveedor_principal_id])
                ->values()
                ->all()
        );

        if ($this->ambiente_id) {
            $p->ambientes()->sync([
                $this->ambiente_id => ['cantidad' => $this->cantidad_inicial],
            ]);
        }

        $this->dispatch('success', message: 'Producto actualizado correctamente!');

        $this->dispatch('close-producto-modal');
        $this->resetForm();
    }

    // ============ ELIMINAR ============
    public function delete($id)
    {
        $p = Producto::findOrFail($id);

        // ---- Validar LOTES ----
        if (Schema::hasTable('lotes')) {
            if ($p->lotes()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el producto porque tiene lotes registrados.');
                return;
            }
        }

        // ---- Validar MOVIMIENTOS ----
        if (Schema::hasTable('movimientos')) {
            if ($p->movimientos()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el producto porque tiene movimientos registrados.');
                return;
            }
        }

        // ---- Validar FACTURA DETALLE ----
        if (Schema::hasTable('factura_detalles')) {
            if ($p->facturasDetalle()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el producto porque aparece en facturas.');
                return;
            }
        }

        // ---- Validar REQUERIMIENTOS ----
        if (Schema::hasTable('requerimientos')) {
            if ($p->requerimientos()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el producto porque tiene requerimientos asociados.');
                return;
            }
        }

        // ---- Si no tiene ninguna relación ----
        $p->delete();

        $this->dispatch('cerrarModalEliminar');
        $this->dispatch('success', message: 'Producto eliminado correctamente!');
    }

    // ============ RESET ============
    private function resetForm()
    {
        $this->reset([
            'producto_id',
            'tipo_id',
            'codigo',
            'nombre',
            'descripcion',
            'proveedor_principal_id',
            'proveedores_secundarios',
            'ambiente_id',
            'cantidad_inicial'
        ]);
        $this->tipos = TipoProducto::all();
        $this->proveedores = Proveedor::all();
        $this->ambientes = Ambiente::all();
    }

    public function render()
    {
        $productos = Producto::with('tipo')
            ->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                    ->orWhere('codigo', 'like', "%{$this->search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(2);


        return view('livewire.productos.index', compact('productos'));
    }
    
    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->dispatch('abrirModalEliminar');
    }

    public $deleteId;

     public function confirmDelete()
    {
        $producto = Producto::find($this->deleteId);

        if (!$producto) {
            $this->dispatch('error', message: 'Producto no encontrado.');
            return;
        }

        try {
            $producto->delete();

            $this->dispatch('success', message: 'Producto eliminado correctamente.' );
            $this->dispatch('cerrarModalEliminar');

        } catch (\Exception $e) {

            $this->dispatch('error', message: 'Error al eliminar el producto.');
        }
    }

    
}
