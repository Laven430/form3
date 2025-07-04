<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeightTarget;
use App\Models\WeightLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('ja_JP');
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'current_weight' => 70.5,
            'target_weight' => 65.0,
        ]);

        WeightTarget::factory()->create([
            'user_id' => $user->id,
            'target_weight' => $user->target_weight,
        ]);

        for ($i = 0; $i < 35; $i++) {
            $date = Carbon::now()->subDays($i);
            WeightLog::factory()->create([
                'user_id' => $user->id,
                'date' => $date->format('Y-m-d'),
                'weight' => $user->current_weight + ($i * 0.1 - 1.5) + (mt_rand(-5, 5) / 10),
                'calories_intake' => $faker->numberBetween(1800, 2500),
                'exercise_time' => Carbon::createFromTime($faker->numberBetween(0, 1), $faker->numberBetween(0, 59))->format('H:i:s'),
                'exercise_content' => $faker->randomElement(['ランニング', '筋トレ', 'ウォーキング', 'ヨガ', null]),
            ]);
        }

        $latestLog = WeightLog::where('user_id', $user->id)->orderBy('date', 'desc')->first();
        if ($latestLog) {
            $user->current_weight = $latestLog->weight;
            $user->save();
        }
    }
}