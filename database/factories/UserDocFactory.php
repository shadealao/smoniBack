<?php

namespace Database\Factories;

use App\Models\UserDoc;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserDocFactory extends Factory
{
    protected $model = UserDoc::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word . '.pdf',
            'file' => 'documents/' . $this->faker->uuid . '.pdf',
            'file_type' => 'application/pdf',
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}