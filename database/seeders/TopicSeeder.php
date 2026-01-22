<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'Professional dilemmas',
            'Cardiology',
            'Dermatology',
            'Endocrinology',
            'ENT',
            'Gastroenterology',
            'General surgery',
            'Haematology',
            'Infectious disease',
            'Neurology',
            'Obs & gynae',
            'Ophthalmology',
            'Paediatrics',
            'Pharmacology',
            'Psychiatry',
            'Renal',
            'Respiratory',
            'Rheumatology / MSK',
            'Urology',
        ];

        foreach ($topics as $topic) {
            Topic::firstOrCreate(
                ['slug' => Str::slug($topic)],
                ['name' => $topic]
            );
        }
    }
}
