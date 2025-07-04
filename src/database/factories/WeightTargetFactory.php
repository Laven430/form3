<?php

namespace Database\Factories;

use App\Models\WeightTarget;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeightTargetFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = WeightTarget::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'target_weight' => $this->faker->randomFloat(1, 50, 70),
        ];
    }
}