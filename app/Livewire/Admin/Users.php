<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    public $name, $email, $password, $role_id;
    public $editMode = false;
    public $deleteId = null;
    public $userId = null;
    public $roles = [];
    public $search = '';

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.min' => 'Debe tener al menos 3 caracteres.',
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'Formato inválido.',
        'email.unique' => 'Correo ya registrado.',
        'password.min' => 'Mínimo 8 caracteres.',
        'password.regex' => 'Debe incluir mayúscula, minúscula y número.',
        'password.required' => 'La contraseña es obligatoria.',
        'role_id.required' => 'Seleccione un rol.',
    ];

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => [
            'required',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ],
        'role_id' => 'required'
    ];

    public function mount()
    {
        $this->roles = Role::all();
    }

    /* ----------------------------- */
    /*   MODAL CREAR                 */
    /* ----------------------------- */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->dispatch('open-form-modal');
    }

    /* ----------------------------- */
    /*   MODAL EDITAR                */
    /* ----------------------------- */
    public function openEditModal($id)
    {
        $this->resetForm();
        $this->editMode = true;

        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = null;
        $this->role_id = $user->roles->first()?->id;

        $this->dispatch('open-form-modal');
    }

    /* ----------------------------- */
    /*     CREAR USUARIO             */
    /* ----------------------------- */
    public function store()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $role = Role::find($this->role_id);
        $user->assignRole($role->name);

        $this->resetForm();
        $this->dispatch('close-form-modal');

        $this->dispatch('success', message: 'Usuario creado correctamente.');

    }

    /* ----------------------------- */
    /*     ACTUALIZAR USUARIO        */
    /* ----------------------------- */
    public function update()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => "required|email|unique:users,email,{$this->userId}",
            'role_id' => 'required',
            'password' => ['nullable', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], $this->messages);

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $role = Role::find($this->role_id);
        $user->syncRoles([$role->name]);

        $this->resetForm();
        $this->dispatch('close-form-modal');

        $this->dispatch('success', message: 'Usuario actualizado correctamente.');
    }

    /* ----------------------------- */
    /*   CONFIRMAR ELIMINACIÓN       */
    /* ----------------------------- */
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    /* ----------------------------- */
    /*   ELIMINAR                    */
    /* ----------------------------- */
    public function delete()
    {
        $user = User::find($this->deleteId);
        if (!$user)
            return;

        if (auth()->id() === $user->id) {
            session()->flash('error', 'No puedes eliminarte.');
            $this->dispatch('open-delete-modal');
            return;
        }

        $user->syncRoles([]);
        $user->delete();

        $this->deleteId = null;
        $this->dispatch('close-delete-modal');

        $this->dispatch('success', message: 'Usuario eliminado correctamente.');
    }

    /* ----------------------------- */
    /*   RESET FORMULARIO            */
    /* ----------------------------- */
    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'role_id', 'userId']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /* ----------------------------- */
    /*   RENDER                      */
    /* ----------------------------- */
    public function render()
    {
        $users = User::with('roles')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas(
                        'roles',
                        fn($q) =>
                        $q->where('name', 'like', '%' . $this->search . '%')
                    );
            })
            ->orderBy('name')
            ->get();

        return view('livewire.admin.users', compact('users'));
    }
}
