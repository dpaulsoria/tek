<?php
namespace Database\Factories;

use App\Models\Attention;
use App\Models\Cite;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttentionFactory extends Factory
{
    protected $model = Attention::class;

    public function definition()
    {
        // asume que PriceService.value ya coincide con Service
        return [
            'date'          => $this->faker->date(),
            'cite_id'       => Cite::factory(),
            'service_id'    => Service::factory(),
            'price_service' => $this->faker->randomFloat(2, 5, 200),
        ];
    }
}
