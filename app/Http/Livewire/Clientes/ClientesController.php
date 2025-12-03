<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use App\Models\Customers;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ClientesController extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $pageTitle, $componentName;
    public $customers;
    private $pagination = 5;
    public $nombre;
    public $apellido;
    public $apellido2;
    public $correo;
    public $password;
    public $telefono;
    public $direccion;
    public $ciudad;
    public $estado;
    public $codigo_postal;
    public $pais = 'México';
    public $rfc;
    public $tipo_cliente = 'minorista';
    public $estatus = 'activo';
    public $limite_credito = 0;
    public $descuento_preferencial = 0;
    public $notas;

    public $selected_id = 0;
    public $search;
    public $profile;
    public $roles;
    public function buscar()
    {
        $this->resetPage();
        $this->customers = Customers::where('correo', 'like', '%' . $this->search . '%')
            ->orWhere('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('apellido', 'like', '%' . $this->search . '%')
            ->orWhere('telefono', 'like', '%' . $this->search . '%')
            ->orWhere('rfc', 'like', '%' . $this->search . '%')
            ->paginate($this->pagination);
    }



    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Clientes';
    }
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        return view('livewire.clientes.clientes-controller', [
            'data' => $this->customers ?? Customers::paginate($this->pagination),
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function resetUI()
    {
        $this->nombre = '';
        $this->apellido = '';
        $this->apellido2 = '';
        $this->correo = '';
        $this->telefono = '';
        $this->direccion = '';
        $this->ciudad = '';
        $this->estado = '';
        $this->codigo_postal = '';
        $this->pais = 'México';
        $this->rfc = '';
        $this->tipo_cliente = 'minorista';
        $this->estatus = 'activo';
        $this->limite_credito = 0;
        $this->descuento_preferencial = 0;
        $this->notas = '';

        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
    }
    public function edit(Customers $user)
    {
        $this->selected_id = $user->id;
        $this->nombre = $user->nombre;
        $this->apellido = $user->apellido;
        $this->apellido2 = $user->apellido2;
        $this->correo = $user->correo;
        $this->telefono = $user->telefono;
        $this->direccion = $user->direccion;
        $this->ciudad = $user->ciudad;
        $this->estado = $user->estado;
        $this->codigo_postal = $user->codigo_postal;
        $this->pais = $user->pais;
        $this->rfc = $user->rfc;
        $this->tipo_cliente = $user->tipo_cliente;
        $this->estatus = $user->estatus;
        $this->limite_credito = $user->limite_credito;
        $this->descuento_preferencial = $user->descuento_preferencial;
        $this->notas = $user->notas;
        $this->emit('show-modal', 'open!');
    }
    public function Store()
    {
        $rules = [
            'nombre' => 'required|min:3',
            'apellido' => 'required|min:3',
            'correo' => 'required|unique:customers|email',
            'telefono' => 'nullable|min:10',
            'tipo_cliente' => 'required|in:minorista,mayorista,distribuidor',
            'estatus' => 'required|in:activo,inactivo,suspendido',
            'password' => 'required|min:6'
        ];

        $messages = [
            'nombre.required' => 'Ingresa el nombre',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'apellido.required' => 'Ingresa el apellido',
            'apellido.min' => 'El apellido debe tener al menos 3 caracteres',
            'correo.required' => 'Ingresa el correo',
            'correo.email' => 'Ingresa un correo válido',
            'correo.unique' => 'El email ya existe en el sistema',
            'telefono.min' => 'El teléfono debe tener al menos 10 caracteres',
            'tipo_cliente.required' => 'Selecciona el tipo de cliente',
            'estatus.required' => 'Selecciona el estatus',
            'password.required' => 'Ingresa la contraseña',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres'
        ];

        $this->validate($rules, $messages);

        $user = Customers::create([
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'apellido2' => $this->apellido2,
            'correo' => $this->correo,
            'password' => bcrypt($this->password),
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'estado' => $this->estado,
            'codigo_postal' => $this->codigo_postal,
            'pais' => $this->pais,
            'rfc' => $this->rfc,
            'tipo_cliente' => $this->tipo_cliente,
            'estatus' => $this->estatus,
            'limite_credito' => $this->limite_credito ?? 0,
            'descuento_preferencial' => $this->descuento_preferencial ?? 0,
            'notas' => $this->notas,
        ]);

        $this->resetUI();
        $this->emit('user-added', 'Cliente Registrado');
    }

    public function Update()
    {
        try {
            $user = Customers::find($this->selected_id);

            $user->update([
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'apellido2' => $this->apellido2,
                'correo' => $this->correo,
                'password' => strlen($this->password) > 0 ? bcrypt($this->password) : $user->password,
                'telefono' => $this->telefono,
                'direccion' => $this->direccion,
                'ciudad' => $this->ciudad,
                'estado' => $this->estado,
                'codigo_postal' => $this->codigo_postal,
                'pais' => $this->pais,
                'rfc' => $this->rfc,
                'tipo_cliente' => $this->tipo_cliente,
                'estatus' => $this->estatus,
                'limite_credito' => $this->limite_credito ?? 0,
                'descuento_preferencial' => $this->descuento_preferencial ?? 0,
                'notas' => $this->notas,
            ]);

            $this->resetUI();
            $this->emit('global-msg', 'Cliente Actualizado');
            $this->emit('user-added', 'Cliente Actualizado');
        } catch (\Exception $e) {
            $this->emit('global-msg', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'

    ];

    public function destroy(Customers $user)
    {
        try {
            $user->estatus = 'inactivo';
            $user->save();

            $this->resetUI();
            $this->emit('global-msg', 'Cliente desactivado con éxito');
        } catch (\Exception $e) {
            $this->emit('global-msg', 'Error al desactivar el cliente: ' . $e->getMessage());
        }
    }
}
