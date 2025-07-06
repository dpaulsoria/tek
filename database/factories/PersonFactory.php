<?php
namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition()
    {
        return [
            'document'   => $this->faker->unique()->numerify('##########'),
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'address'    => $this->faker->address(),
            'phone'      => $this->faker->phoneNumber(),
            'email'      => $this->faker->unique()->safeEmail(),
        ];
    }
}
