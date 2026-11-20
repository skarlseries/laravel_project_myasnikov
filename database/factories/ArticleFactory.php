<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->realText(45),
            'description'  => $this->faker->realText(250),
            'image'        => '/images/full.jpg',
            'published_at' => $this->faker->date(),
        ];
    }
}
