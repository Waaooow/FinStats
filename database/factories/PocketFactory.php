<?php

namespace Database\Factories;

use App\Models\Pocket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PocketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pocket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement([
                'Tabungan Liburan',
                'Saku Harian',
                'Darurat',
                'Tabungan Belanja',
                'Pocket Pendidikan',
            ]),
            'target' => $this->faker->optional()->randomElement([1000000, 5000000, 10000000]),
            'balance' => $this->faker->randomNumber(5) * 1000,
        ];
    }
}
