<?php

namespace Database\Factories;

use App\Enums\OrderStatusType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'total' => $this->faker->randomFloat(2, 0, 1000),
            'status' => $this->faker->randomElement(["Pending" , "In_Progress" , "Cancelled"]),
            'user_id' => 1,
            // 'product_id' => 1,
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->randomElement(["cairo" , "alex"]),
            'postal_code' => "11735",
            "created_at" => $this->faker->dateTimeBetween("-1 years")
        ];
    }
}
