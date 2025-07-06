<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Service;
use App\Models\PriceService;
use App\Models\Cite;
use App\Models\Attention;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $nPersons  = env('SEED_PERSONS',    50);
        $nServices = env('SEED_SERVICES',   10);

        $services = Service::factory()
            ->count($nServices)
            ->create()
            ->each(fn($service) =>
                PriceService::factory()
                    ->for($service)
                    ->create()
            );

        $serviceIds = $services->pluck('id')->all();

        Person::factory()
            ->count($nPersons)
            ->create()
            ->each(function($person) {
                Cite::factory()
                    ->count(rand(0, 3))           // 0–3 citas por persona
                    ->create(['cliente_id' => $person->id]);
            });

        $allCites = Cite::all();
        $allCites->each(function($cite) use ($serviceIds) {
            $nAtt = rand(0, 3);                 // 0–3 atenciones por cita
            Attention::factory()
                ->count($nAtt)
                ->create([
                    'cite_id'    => $cite->id,
                    'service_id' => $serviceIds[array_rand($serviceIds)],
                ]);
        });
    }
}
