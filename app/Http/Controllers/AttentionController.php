<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use App\Models\Cite;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttentionController extends Controller
{
    public function index()
    {
        $atenciones = Attention::with('cite.person','service')
            ->paginate(10);
        $citas = Cite::get();
        $servicios = Service::get();
        return Inertia::render('Atenciones/Index', compact('atenciones', 'citas', 'servicios'));
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
            'service_id'    => 'required|exists:service,id',
            'price_service' => 'required|numeric',
        ]);
        Attention::create($data);
        return redirect()->route('atenciones.index');
    }

    public function edit(Attention $atencion)
    {
        $atencion->load('cite.person','service');
        $citas     = Cite::with('person')->get();
        $servicios = Service::orderBy('name')->get();
        return Inertia::render('Atenciones/Edit', compact('atencion','citas','servicios'));
    }

    public function update(Request $request, Attention $atencion)
    {
        $data = $request->validate([
            'date'          => 'required|date',
            'cite_id'       => 'required|exists:cite,id',
            'service_id'    => 'required|exists:service,id',
            'price_service' => 'required|numeric',
        ]);

        $wasDirty = $atencion->isDirty();          // antes de fill
        $atencion->fill($data);
        $nowDirty = $atencion->isDirty();          // después de fill
        $updated = $atencion->save();

        return redirect()->route('atenciones.index');
    }

    public function destroy(Attention $atencion)
    {
        $atencion->delete();
        return redirect()->route('atenciones.index');
    }
}
