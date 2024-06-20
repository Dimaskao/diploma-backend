<?php

namespace Database\Factories;

use App\Models\RegularUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'role_id' => Role::inRandomOrder()->first()->id, // Assuming roles are already created
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // or Hash::make('password')
            'avatar_url' => $this->faker->imageUrl(),
            'user_id' => RegularUser::factory(), // Create a related RegularUser
            'company_id' => null, // You can set this to null or a valid UUID if needed
        ];
    }
}
