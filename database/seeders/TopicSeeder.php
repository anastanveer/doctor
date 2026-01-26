<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $primaryTopics = [
            'Anatomy',
            'Physiology',
            'Pharmacology',
            'Microbiology',
            'Pathology',
            'Evidence Based Medicine',
            'Past Recalls',
        ];

        $intermediateTopics = [
            'Allergy',
            'Cardiology',
            'Dermatology',
            'Ear, nose and throat',
            'Elderly care / frailty',
            'Endocrinology',
            'Environmental emergencies',
            'Gastroenterology and hepatology',
            'Haematology',
            'Infectious diseases',
            'Maxillofacial / dental',
            'Mental Health',
            'Musculoskeletal (non-traumatic)',
            'Nephrology',
            'Neurology',
            'Obstetrics & Gynaecology',
            'Oncological Emergencies',
            'Ophthalmology',
            'Pharmacology and poisoning',
            'Respiratory',
            'Sexual health',
            'Surgical emergencies',
            'Urology',
            'Vascular',
            'Resuscitation',
            'Palliative and end of life care',
            'Trauma',
            'Including other clinical presentations',
            'Trauma, Pain Management and Procedural Sedation',
            'Neonatal, Safeguarding and Psychosocial emergencies in children',
            'Procedural Skills and Basic Anesthetic Care',
            'Advanced airway management',
            'Chest drain',
            'External pacing',
            'Fracture/ dislocation manipulation',
            'Lumbar puncture',
            'Pain and sedation',
            'POCUS',
            'Vascular access in emergency- IO, femoral vein',
            'Wound management',
            'Complex and Challenging Situations',
            'Legislation and legal framework',
            'Organ/tissue donation',
            'Information governance',
            'Safeguarding',
            'Evidence and guidelines',
        ];

        foreach ($primaryTopics as $topic) {
            Topic::updateOrCreate(
                ['slug' => Str::slug($topic)],
                ['name' => $topic, 'exam_type' => Topic::EXAM_PRIMARY]
            );
        }

        foreach ($intermediateTopics as $topic) {
            Topic::updateOrCreate(
                ['slug' => Str::slug($topic)],
                ['name' => $topic, 'exam_type' => Topic::EXAM_INTERMEDIATE]
            );
        }
    }
}
