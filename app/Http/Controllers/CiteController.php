<?php

namespace App\Http\Controllers;

use App\Models\Cite;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class CiteController extends Controller
{
    public function index()
    {
        $citas = Cite::with(['person', 'attentions.service'])->paginate(10);
        $clientes = Person::select(['id','document','first_name','last_name'])->get();
        return Inertia::render('Citas/Index', compact('citas', 'clientes'));
    }

    public function create()
    {
        $clientes = Person::get();
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

    public function storeApp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date'             => 'required|date',
            'time_arrival'     => 'required',
            'cliente_id'       => 'required|exists:person,id',
            'amount_attention' => 'nullable|integer',
            'total_service'    => 'nullable|numeric',
            'status'           => 'required|string',
        ]);
        $created = Cite::create($data);
        return response()->json($created, 200);
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
