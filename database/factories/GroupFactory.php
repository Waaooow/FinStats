<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Keluarga', 'Teman Sekelas', 'Kantor', 'Liburan Bersama']),
            'code' => 'KAS-' . strtoupper($this->faker->bothify('????')),
        ];
    }
}
