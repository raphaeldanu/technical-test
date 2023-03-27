<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'genre_id' => $this->faker->numberBetween(1, 8),
            'kode_buku' => (string) Str::uuid(),
            'judul_buku' => $this->faker->sentence(6),
            'penulis' => $this->faker->name,
            'penerbit' => $this->faker->company,
            'tahun_terbit' => $this->faker->year,
            'sinopsis' => $this->faker->paragraphs(3, true),
        ];
    }
}
