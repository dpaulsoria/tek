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
        // Lee del .env o usa valores por defecto
        $nPersons    = env('SEED_PERSONS', 50);
        $nServices   = env('SEED_SERVICES', 10);
        $nCites      = env('SEED_CITES', 200);
        $nAttentions = env('SEED_ATTENTIONS', 500);

        // 1) Personas
        Person::factory()->count($nPersons)->create();

        // 2) Servicios y sus precios
        Service::factory()
            ->count($nServices)
            ->create()
            ->each(fn($service) => PriceService::factory()->for($service)->create());

        // 3) Citas (con relación a personas existentes)
        Cite::factory()
            ->count($nCites)
            ->for(Person::inRandomOrder()->first(), 'person')
            ->create();

        // 4) Atenciones (con relación a citas y servicios existentes)
        Attention::factory()
            ->count($nAttentions)
            ->for(Cite::inRandomOrder()->first(), 'cite')
            ->for(Service::inRandomOrder()->first(), 'service')
            ->create();

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
