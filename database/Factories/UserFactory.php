<?php
namespace Database\Factories;

use App\Models\Users;
use Core\Lib\Database\Factory;

class UserFactory extends Factory {
    //protected $modelName = Users::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->saveEmail(),
            'acl' => json_encode([""]),
            'password' => $this->faker->password(),
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