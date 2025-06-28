<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use App\Models\Cite;
use App\Models\Service;    // asegúrate de tener este modelo/migración
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttentionController extends Controller
{
    public function index()
    {
        $atenciones = Attention::with('cite.person','service')
            ->orderBy('date')
            ->paginate(10);
        return Inertia::render('Atenciones/Index', compact('atenciones'));
    }

    public function create()
    {
        $citas     = Cite::with('person')->get();
        $servicios = Service::orderBy('name')->get();
        return Inertia::render('Atenciones/Create', compact('citas','servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'          => 'required|date',
            'cite_id'       => 'required|exists:cite,id',
            'service_id'    => 'required|exists:services,id',
            'price_service' => 'required|numeric',
        ]);
        Attention::create($data);
        return redirect()->route('atenciones.index');
    }

    public function edit(Attention $atencion)
    {
        $citas     = Cite::with('person')->get();
        $servicios = Service::orderBy('name')->get();
        return Inertia::render('Atenciones/Edit', [
          'atencion'  => $atencion,
          'citas'     => $citas,
          'servicios' => $servicios,
        ]);
    }

    public function update(Request $request, Attention $atencion)
    {
        $data = $request->validate([
            'date'          => 'required|date',
            'cite_id'       => 'required|exists:cite,id',
            'service_id'    => 'required|exists:services,id',
            'price_service' => 'required|numeric',
        ]);
        $atencion->update($data);
        return redirect()->route('atenciones.index');
    }

    public function destroy(Attention $atencion)
    {
        $atencion->delete();
        return redirect()->route('atenciones.index');
    }
}
