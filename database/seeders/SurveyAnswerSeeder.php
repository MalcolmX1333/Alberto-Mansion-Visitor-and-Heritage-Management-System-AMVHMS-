<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class SurveyAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $visitorSurvey = Survey::where('name', 'Visitor Information')->first();
        $feedbackSurvey = Survey::where('name', 'Visitor Feedback')->first();

        $uniqueIdentifiers = collect();

        for ($i = 0; $i < 30; $i++) { // Updated to 30
            $uniqueIdentifier = Str::uuid()->toString();
            $uniqueIdentifiers->push($uniqueIdentifier);

            // Create entry for Visitor Information survey
            $visitorEntry = Entry::create([
                'survey_id' => $visitorSurvey->id,
                'device_identifier' => $uniqueIdentifier,
            ]);

            $visitorEntry->answers()->createMany([
                ['question_id' => 1, 'value' => 'Bus ' . rand(1, 100)],
                ['question_id' => 2, 'value' => 'John Doe ' . $i],
                ['question_id' => 3, 'value' => 'Address ' . $i],
                ['question_id' => 4, 'value' => 'Nationality ' . $i],
                ['question_id' => 5, 'value' => rand(0, 1) ? 'Male' : 'Female'],
                ['question_id' => 6, 'value' => rand(1, 50)],
                ['question_id' => 7, 'value' => rand(1, 50)],
                ['question_id' => 8, 'value' => rand(1, 50)],
                ['question_id' => 9, 'value' => rand(0, 1) ? 'Yes' : 'No'],
                ['question_id' => 10, 'value' => rand(1, 50)],
                ['question_id' => 11, 'value' => rand(1, 50)],
                ['question_id' => 12, 'value' => rand(1, 50)],
                ['question_id' => 13, 'value' => rand(1, 50)],
            ]);

            // Create entry for Visitor Feedback survey
            $feedbackEntry = Entry::create([
                'survey_id' => $feedbackSurvey->id,
                'device_identifier' => $uniqueIdentifier,
            ]);

            $feedbackEntry->answers()->createMany([
                ['question_id' => 14, 'value' => rand(1, 5)],
                ['question_id' => 15, 'value' => 'Feedback ' . $i],
                ['question_id' => 16, 'value' => rand(1, 5)],
                ['question_id' => 17, 'value' => rand(1, 5)],
                ['question_id' => 18, 'value' => rand(1, 5)],
                ['question_id' => 19, 'value' => rand(1, 5)],
                ['question_id' => 20, 'value' => 'Improvement suggestion ' . $i],
                ['question_id' => 21, 'value' => rand(1, 5)],
                ['question_id' => 22, 'value' => rand(1, 5)],
                ['question_id' => 23, 'value' => rand(1, 5)],
                ['question_id' => 24, 'value' => rand(1, 5)],
            ]);
        }
    }
}
