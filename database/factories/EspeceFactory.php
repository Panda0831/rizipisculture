<?php

namespace Database\Factories;

use App\Models\Espece;
use Illuminate\Database\Eloquent\Factories\Factory;

class EspeceFactory extends Factory
{
    protected $model = Espece::class;

    public function definition(): array
    {
        return [
            'nom_commun' => $this->faker->word(),
            'nom_scientifique' => $this->faker->word(),
            'nom_local' => $this->faker->word(),
            'temperature_min' => $this->faker->randomFloat(1, 0, 30),
            'temperature_max' => $this->faker->randomFloat(1, 30, 40),
            'ph_min' => $this->faker->randomFloat(1, 6, 7),
            'ph_max' => $this->faker->randomFloat(1, 7, 9),
            'description' => $this->faker->paragraph(),
        ];
    }
}
