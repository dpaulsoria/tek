<?php
namespace Database\Factories;

use App\Models\PriceService;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceServiceFactory extends Factory
{
    protected $model = PriceService::class;

    public function definition()
    {
        return [
            // asume que primero generas Services y luego PriceServices
            'service_id' => Service::factory(),
            'value'      => $this->faker->randomFloat(2, 5, 200),
            'status'     => $this->faker->randomElement(['ACTIVE','INACTIVE']),
        ];
    }
}
