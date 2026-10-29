<?php

namespace Database\Factories;

use App\Models\Pocket;
use App\Models\PocketTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PocketTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PocketTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['top_up', 'withdraw', 'transfer_in', 'transfer_out']);

        return [
            'pocket_id' => Pocket::factory(),
            'type' => $type,
            'amount' => $this->faker->randomNumber(4) * 1000,
            'description' => $this->faker->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'related_pocket_id' => null,
            'related_group_id' => null,
        ];
    }
}
