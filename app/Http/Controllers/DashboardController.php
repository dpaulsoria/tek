<?php

namespace App\Http\Controllers;

use App\Models\Attention;
use App\Models\Cite;
use App\Models\Service;
use App\Models\Person;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'counts' => [
                'clientes'   => Person::count(),
                'citas'      => Cite::count(),
                'atenciones' => Attention::count(),
                'servicios'  => Service::count(),
            ],
        ]);
    }
}
