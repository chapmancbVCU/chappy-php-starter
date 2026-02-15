<?php
namespace Database\Factories;

use App\Models\Users;
use Core\Lib\Database\Factory;

/**
 * Factory for creating new user table records.
 */
class UserFactory extends Factory {
    protected $modelName = Users::class;

    public function definition(): array
    {
        $tempPassword = $this->faker->password();
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->safeEmail(),
            'acl' => json_encode([""]),
            'password' => $tempPassword,
            'confirm' => $tempPassword,
            'fname' => $this->faker->firstName(),
            'lname' => $this->faker->lastName(),
            'description' => $this->faker->sentence(3),
            'inactive' => 0,
            'reset_password' => 0,
            'login_attempts' => 0,
            'deleted' => 0
        ];
    }
}