<?php

namespace App\Http\Controllers;

use App\Models\Cite;
use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CiteController extends Controller
{
    public function index()
    {
        $citas = Cite::with('person')->orderBy('date', 'DESC')->paginate(10);
        return Inertia::render('Citas/Index', compact('citas'));
    }

    public function create()
    {
        $clientes = Person::orderBy('last_name')->get();
        return Inertia::render('Citas/Create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'             => 'required|date',
            'time_arrival'     => 'required',
            'cliente_id'       => 'required|exists:person,id',
            'amount_attention' => 'nullable|integer',
            'total_service'    => 'nullable|numeric',
            'status'           => 'required|string',
        ]);
        Cite::create($data);
        return redirect()->route('citas.index');
    }

    public function edit(Cite $cita)
    {
        $clientes = Person::orderBy('last_name')->get();
        return Inertia::render('Citas/Edit', compact('cita','clientes'));
    }

    public function update(Request $request, Cite $cita)
    {
        $data = $request->validate([
            'date'             => 'required|date',
            'time_arrival'     => 'required',
            'cliente_id'       => 'required|exists:person,id',
            'amount_attention' => 'nullable|integer',
            'total_service'    => 'nullable|numeric',
            'status'           => 'required|string',
        ]);
        $cita->update($data);
        return redirect()->route('citas.index');
    }

    public function destroy(Cite $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index');
    }
}
