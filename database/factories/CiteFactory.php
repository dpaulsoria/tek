<?php
namespace Database\Factories;

use App\Models\Cite;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class CiteFactory extends Factory
{
    protected $model = Cite::class;

    public function definition()
    {
        return [
            'date'             => $this->faker->dateTimeBetween('-1 year','now')->format('Y-m-d'),
            'time_arrival'     => $this->faker->time('H:i:s'),
            'cliente_id'       => Person::factory(),
            'amount_attention' => $this->faker->numberBetween(1,5),
            'total_service'    => $this->faker->randomFloat(2, 20, 500),
            'status'           => $this->faker->randomElement(['PENDIENTE', 'CONFIRMADA', 'CANCELADA']),
        ];
    }
}
