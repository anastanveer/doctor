<?php

namespace Database\Seeders;

use App\Models\RevisionNote;
use App\Models\RevisionTopic;
use Illuminate\Database\Seeder;

class RevisionNotesSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Professional Dilemmas',
                'slug' => 'professional-dilemmas',
                'description' => 'Ethics, consent, confidentiality, and safe escalation pathways.',
            ],
            [
                'name' => 'Cardiology',
                'slug' => 'cardiology',
                'description' => 'Core cardiovascular presentations, investigations, and treatments.',
            ],
            [
                'name' => 'Respiratory',
                'slug' => 'respiratory',
                'description' => 'Common respiratory conditions, red flags, and management plans.',
            ],
            [
                'name' => 'Endocrinology',
                'slug' => 'endocrinology',
                'description' => 'Hormone disorders, thyroid disease, and metabolic emergencies.',
            ],
        ];

        $notes = [
            'professional-dilemmas' => [
                [
                    'title' => 'Consent and capacity basics',
                    'slug' => 'consent-and-capacity-basics',
                    'summary' => 'A quick framework for assessing capacity and documenting consent.',
                    'content' => "Start by checking if the patient can understand, retain, weigh, and communicate a decision.\n\nIf capacity is lacking, act in the patient's best interests and involve family or advocates when appropriate. Document the rationale clearly, including who was involved and why the decision was made.\n\nUse local policies for urgent treatment, safeguarding, and escalation to seniors.",
                ],
                [
                    'title' => 'Duty of candour and open disclosure',
                    'slug' => 'duty-of-candour-open-disclosure',
                    'summary' => 'What to do when something goes wrong and how to communicate it.',
                    'content' => "Recognise the incident early and inform a senior or supervisor. Apologise, explain what happened, and outline immediate steps to keep the patient safe.\n\nOffer a plan for follow-up, including who will contact the patient, and record the discussion in the notes. Keep communication clear, calm, and factual.",
                ],
            ],
            'cardiology' => [
                [
                    'title' => 'Hypertension management overview',
                    'slug' => 'hypertension-management-overview',
                    'summary' => 'Diagnosis, lifestyle changes, and first-line treatment options.',
                    'content' => "Confirm the diagnosis using home or ambulatory monitoring. Check for end-organ damage and cardiovascular risk factors.\n\nBegin with lifestyle advice such as salt reduction, exercise, and weight management. First-line drug choices depend on age and ethnicity, with ACE inhibitors or ARBs often used first in younger patients.\n\nReview response and side effects regularly, then step up therapy if targets are not met.",
                ],
                [
                    'title' => 'Acute chest pain approach',
                    'slug' => 'acute-chest-pain-approach',
                    'summary' => 'Rapid assessment and initial management steps.',
                    'content' => "Prioritise ABCs, pain control, ECG, and early blood tests including troponin. Identify red flags such as haemodynamic instability or arrhythmia.\n\nTreat suspected acute coronary syndrome with antiplatelets, anticoagulation where indicated, and rapid cardiology review. Do not miss aortic dissection, pulmonary embolism, or pneumothorax in the differential.",
                ],
            ],
            'respiratory' => [
                [
                    'title' => 'Asthma exacerbation quick guide',
                    'slug' => 'asthma-exacerbation-quick-guide',
                    'summary' => 'Assessment of severity and immediate treatment.',
                    'content' => "Assess severity with peak flow, oxygen saturation, and work of breathing. Provide high-flow oxygen if needed.\n\nGive short-acting bronchodilators, systemic steroids, and consider ipratropium for severe cases. Escalate if there is poor response or signs of exhaustion.",
                ],
                [
                    'title' => 'COPD stable management',
                    'slug' => 'copd-stable-management',
                    'summary' => 'Long-term care plans and monitoring.',
                    'content' => "Encourage smoking cessation, vaccinations, and pulmonary rehabilitation. Use bronchodilators as first-line and add inhaled steroids based on exacerbation history.\n\nReview inhaler technique regularly and monitor for complications such as chronic hypoxia or cor pulmonale.",
                ],
            ],
            'endocrinology' => [
                [
                    'title' => 'Diabetic ketoacidosis essentials',
                    'slug' => 'diabetic-ketoacidosis-essentials',
                    'summary' => 'Key priorities during the first hours of treatment.',
                    'content' => "Start fluid resuscitation immediately and confirm the diagnosis with ketones, glucose, and blood gas. Use a fixed-rate insulin infusion and replace potassium carefully.\n\nMonitor glucose, ketones, and electrolytes closely. Treat precipitating causes such as infection or missed insulin.",
                ],
                [
                    'title' => 'Hypothyroidism assessment',
                    'slug' => 'hypothyroidism-assessment',
                    'summary' => 'Symptoms, tests, and initial management.',
                    'content' => "Check thyroid function tests and look for autoimmune markers if needed. Symptoms can be subtle, including fatigue, weight gain, and cold intolerance.\n\nStart levothyroxine and titrate slowly, especially in older patients or those with cardiac disease.",
                ],
            ],
        ];

        foreach ($topics as $topicData) {
            $topic = RevisionTopic::updateOrCreate(
                ['slug' => $topicData['slug']],
                $topicData
            );

            foreach ($notes[$topicData['slug']] ?? [] as $noteData) {
                RevisionNote::firstOrCreate(
                    ['slug' => $noteData['slug']],
                    array_merge($noteData, ['revision_topic_id' => $topic->id])
                );
            }
        }
    }
}
