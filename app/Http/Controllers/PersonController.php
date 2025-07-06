<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class PersonController extends Controller
{
    public function index()
    {
        $clientes = Person::with('cites.attentions.service')->paginate(5);
        return Inertia::render('Clientes/Index', compact('clientes'));
    }

    public function indexApp(): JsonResponse
    {
        $clientes = Person::with('cites.attentions.service')->get();
        return response()->json($clientes, 200);
    }

    public function create()
    {
        return Inertia::render('Clientes/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'document'   => 'required|unique:person',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|unique:person',
            'phone'      => 'nullable',
            'address'    => 'nullable',
        ]);
        Person::create($data);
        return redirect()->route('clientes.index');
    }

    public function edit(Person $cliente)
    {
        return Inertia::render('Clientes/Edit', ['cliente' => $cliente]);
    }

    public function update(Request $request, Person $cliente)
    {
        $data = $request->validate([
            'document'   => "required|unique:person,document,{$cliente->id}",
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => "required|email|unique:person,email,{$cliente->id}",
            'phone'      => 'nullable',
            'address'    => 'nullable',
        ]);
        $cliente->update($data);
        return redirect()->route('clientes.index');
    }

    public function destroy(Person $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index');
    }
}
