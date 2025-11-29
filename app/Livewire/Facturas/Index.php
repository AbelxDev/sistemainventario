<?php

namespace App\Livewire\Facturas;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Factura;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $modalMode = 'create';

    public $factura_id;
    public $numero;
    public $fecha;
    public $proveedor_id;
    public $estado = 'pendiente';
    public $pdf_file;   // Archivo subido temporal
    public $pdf_ruta;   // Ruta final del PDF guardado

    public $proveedores = [];

    public function mount()
    {
        $this->proveedores = Proveedor::orderBy('razon_social')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        return [
            'numero' => 'required|string|max:50|unique:facturas,numero,' . $this->factura_id,
            'fecha' => 'required|date',
            'proveedor_id' => 'required|exists:proveedors,id',
            'estado' => 'required|string|in:pendiente,procesada,anulada',
            'pdf_file' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    private function resetForm()
    {
        $this->reset([
            'factura_id',
            'numero',
            'fecha',
            'proveedor_id',
            'estado',
            'pdf_file',
            'pdf_ruta',
        ]);

        $this->estado = 'pendiente';
        $this->proveedores = Proveedor::orderBy('razon_social')->get();
    }

    // ================= MODAL CREAR =================
    public function openCreateModal()
    {
        $this->modalMode = 'create';
        $this->resetForm();
        $this->pdf_ruta = null;
        $this->dispatch('open-factura-modal');
    }

    public function save()
    {

        $this->validate();

        $rutaPdf = null;

        if ($this->pdf_file) {

            // Ruta temporal del archivo subido por Livewire
            $tmpPath = $this->pdf_file->getRealPath();

            // Obtener extension real
            $extension = $this->pdf_file->getClientOriginalExtension() ?: 'pdf';

            // Nombre final seguro
            $nombreFinal = 'facturas/' . sha1(uniqid() . microtime()) . '.' . $extension;

            // Copiar desde el temporal hacia storage/app/public/facturas
            Storage::disk('public')->put($nombreFinal, file_get_contents($tmpPath));

            $rutaPdf = $nombreFinal;
        }

        Factura::create([
            'numero' => $this->numero,
            'fecha' => $this->fecha,
            'proveedor_id' => $this->proveedor_id,
            'estado' => $this->estado,
            'pdf_ruta' => $rutaPdf,
        ]);

        session()->flash('success', 'Factura registrada correctamente.');

        $this->dispatch('close-factura-modal');
        $this->resetForm();
    }

    // ================= MODAL EDITAR =================
    public function openEditModal($id)
    {
        $this->modalMode = 'edit';

        $f = Factura::findOrFail($id);

        $this->factura_id = $f->id;
        $this->numero = $f->numero;
        $this->fecha = $f->fecha?->format('Y-m-d');
        $this->proveedor_id = $f->proveedor_id;
        $this->estado = $f->estado;
        $this->pdf_ruta = $f->pdf_ruta;

        $this->pdf_file = null;

        $this->dispatch('open-factura-modal');
    }

    public function updatedPdfFile()
    {
        dd("LLEGO OK", $this->pdf_file);
    }


    public function update()
    {
        $this->validate();

        $f = Factura::findOrFail($this->factura_id);

        if ($this->pdf_file) {

            // Borrar PDF anterior
            if ($f->pdf_ruta && Storage::disk('public')->exists($f->pdf_ruta)) {
                Storage::disk('public')->delete($f->pdf_ruta);
            }

            // Copiar nuevo archivo
            $tmpPath = $this->pdf_file->getRealPath();
            $extension = $this->pdf_file->getClientOriginalExtension() ?: 'pdf';
            $nombreFinal = 'facturas/' . sha1(uniqid() . microtime()) . '.' . $extension;

            Storage::disk('public')->put($nombreFinal, file_get_contents($tmpPath));

            $this->pdf_ruta = $nombreFinal;
        }

        $f->update([
            'numero' => $this->numero,
            'fecha' => $this->fecha,
            'proveedor_id' => $this->proveedor_id,
            'estado' => $this->estado,
            'pdf_ruta' => $this->pdf_ruta,
        ]);

        session()->flash('success', 'Factura actualizada correctamente.');

        $this->dispatch('close-factura-modal');
        $this->resetForm();
    }

    // ================= ELIMINAR =================
    public function delete($id)
    {
        $f = Factura::findOrFail($id);

        if (Schema::hasTable('factura_detalles')) {
            if ($f->detalles()->count() > 0) {
                session()->flash('error', 'No se puede eliminar la factura porque tiene detalles registrados.');
                return;
            }
        }

        if ($f->pdf_ruta && Storage::disk('public')->exists($f->pdf_ruta)) {
            Storage::disk('public')->delete($f->pdf_ruta);
        }

        $f->delete();

        session()->flash('success', 'Factura eliminada correctamente.');
    }

    public function render()
    {
        $facturas = Factura::with('proveedor')
            ->where(function ($q) {
                $q->where('numero', 'like', "%{$this->search}%")
                    ->orWhereHas('proveedor', function ($q2) {
                        $q2->where('razon_social', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.facturas.index', compact('facturas'));
    }
}
