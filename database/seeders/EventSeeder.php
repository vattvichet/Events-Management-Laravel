<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            \App\Models\Event::create([
                'user_id' => $user->id,
                'name' => fake()->sentence(3),
                'description' => fake()->sentence(3),
                'start_date' => fake()->dateTimeBetween('now', '+1 month'),
                'end_date' => fake()->dateTimeBetween('now', '+2 month'),
            ]);
        }
    }
}
