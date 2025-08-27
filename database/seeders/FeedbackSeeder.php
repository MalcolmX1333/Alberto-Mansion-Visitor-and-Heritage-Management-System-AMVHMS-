<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use MattDaneshvar\Survey\Models\Survey;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a new survey
        $survey = Survey::create(['name' => 'Visitor Feedback', 'settings' =>  ['accept-guest-entries' => true]]);

        // Existing questions
        $survey->questions()->create([
            'content' => 'How is your Visit?',
            'type' => 'rating',
            'options' => [1, 2, 3, 4, 5], // Representing 1 to 5 stars
            'rules' => ['required']
        ]);

        $survey->questions()->create([
            'content' => 'Feedback',
            'rules' => ['required']
        ]);

        // Section for MUSEUM
        $museumSection = $survey->sections()->create(['name' => 'MUSEUM']);

        $museumSection->questions()->create([
            'content' => 'The office was willing to help, assist, and provide prompt service.',
            'type' => 'rating',
            'options' => [1, 2, 3, 4, 5],
            'rules' => ['required']
        ]);

        $museumSection->questions()->create([
            'content' => 'I am generally satisfied with the service I availed.',
            'type' => 'rating',
            'options' => [1, 2, 3, 4, 5],
            'rules' => ['required']
        ]);

        $museumSection->questions()->create([
            'content' => 'The staff was capable and knowledgeable to perform their duties.',
            'type' => 'rating',
            'options' => [1, 2, 3, 4, 5],
            'rules' => ['required']
        ]);

        $museumSection->questions()->create([
            'content' => 'The responses were clear and easily understood.',
            'type' => 'rating',
            'options' => [1, 2, 3, 4, 5],
            'rules' => ['required']
        ]);
    }
}
