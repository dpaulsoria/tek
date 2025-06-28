<?php
// app/Http/Controllers/ServiceController.php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        // 1) Paginamos los servicios
        $servicios = Service::orderBy('name')
            ->paginate(10);

        return Inertia::render('Servicios/Index', compact('servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:service,name',
            'slug' => 'required|string|unique:service,slug',
        ]);

        Service::create($data);

        return redirect()->route('servicios.index');
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => "required|string|unique:service,name,{$service->id}",
            'slug' => "required|string|unique:service,slug,{$service->id}",
        ]);

        $service->update($data);

        return redirect()->route('servicios.index');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('servicios.index');
    }
}
