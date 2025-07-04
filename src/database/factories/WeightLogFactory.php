<?php

namespace Database\Factories;

use App\Models\WeightLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class WeightLogFactory extends Factory
{
    protected $model = WeightLog::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->unique()->dateTimeBetween('-35 days', 'now')->format('Y-m-d'),
            'weight' => $this->faker->randomFloat(1, 60, 90),
            'calories_intake' => $this->faker->numberBetween(1500, 3000),
            'exercise_time' => Carbon::createFromTime($this->faker->numberBetween(0, 2), $this->faker->numberBetween(0, 59))->format('H:i:s'),
            'exercise_content' => $this->faker->sentence(5, true),
        ];
    }

    public function sequenceDateForUser(int $userId, int $daysAgo)
    {
        return $this->state(function (array $attributes) use ($userId, $daysAgo) {
            $date = Carbon::now()->subDays($daysAgo)->format('Y-m-d');
            return [
                'user_id' => $userId,
                'date' => $date,
            ];
        });
    }
}