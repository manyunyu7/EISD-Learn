<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Set the locale to Indonesian

        foreach (range(1, 10) as $index) {
            $question = $faker->sentence;
            $choices = json_encode([
                ['text' => 'Pilihan 1', 'score' => $faker->numberBetween(0, 20)],
                ['text' => 'Pilihan 2', 'score' => $faker->numberBetween(0, 20)],
                ['text' => 'Pilihan 3', 'score' => $faker->numberBetween(0, 20)],
                ['text' => 'Pilihan 4', 'score' => $faker->numberBetween(0, 20)],
                ['text' => 'Pilihan 5', 'score' => $faker->numberBetween(0, 20)],
            ]);

            DB::table('questions')->insert([
                'question' => $question,
                'image' => null, // Add your image path here if needed
                'correct_answer' => 'Pilihan ' . $faker->numberBetween(1, 5),
                'choices' => $choices,
                'exam_id' => $faker->numberBetween(1, 5), // Adjust the range as needed
                'created_by' => $faker->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
