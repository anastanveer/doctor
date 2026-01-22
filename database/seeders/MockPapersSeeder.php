<?php

namespace Database\Seeders;

use App\Models\MockPaper;
use App\Models\MockPaperOption;
use App\Models\MockPaperQuestion;
use Illuminate\Database\Seeder;

class MockPapersSeeder extends Seeder
{
    public function run(): void
    {
        $papers = [
            [
                'title' => 'Mock paper 1 - Clinical problem solving',
                'slug' => 'mock-paper-1',
                'description' => 'CPS focused mock paper with common clinical scenarios.',
                'order' => 1,
            ],
            [
                'title' => 'Mock paper 2 - Clinical problem solving',
                'slug' => 'mock-paper-2',
                'description' => 'A second CPS paper to test core clinical decision making.',
                'order' => 2,
            ],
            [
                'title' => 'Mock paper 3 - Clinical problem solving',
                'slug' => 'mock-paper-3',
                'description' => 'CPS practice focused on acute presentations.',
                'order' => 3,
            ],
            [
                'title' => 'Mock paper 4 - Clinical problem solving',
                'slug' => 'mock-paper-4',
                'description' => 'CPS questions with high yield red flags.',
                'order' => 4,
            ],
            [
                'title' => 'Mock paper 5 - Clinical problem solving',
                'slug' => 'mock-paper-5',
                'description' => 'CPS practice for common MSRA topics.',
                'order' => 5,
            ],
            [
                'title' => 'Mock paper 6 - Clinical problem solving',
                'slug' => 'mock-paper-6',
                'description' => 'Final CPS paper with mixed medical topics.',
                'order' => 6,
            ],
        ];

        $questions = [
            'mock-paper-1' => [
                [
                    'topic' => 'Cardiology',
                    'stem' => 'A 62-year-old with atrial fibrillation has a CHA2DS2-VASc score of 2. What is the best long-term stroke prevention?',
                    'explanation' => 'A score of 2 or more warrants long-term anticoagulation, usually with a DOAC.',
                    'options' => [
                        'No anticoagulation',
                        'Aspirin only',
                        'Start a DOAC',
                        'Dabigatran for 4 weeks then stop',
                    ],
                    'correct' => 2,
                ],
                [
                    'topic' => 'Respiratory',
                    'stem' => 'An adult with acute asthma has a PEFR of 45 percent predicted. What is the initial management?',
                    'explanation' => 'Start with oxygen and short acting bronchodilators. IV magnesium is for severe or refractory cases.',
                    'options' => [
                        'High flow oxygen and nebulised SABA',
                        'Oral antibiotics only',
                        'Discharge with inhaler',
                        'IV magnesium first line for all',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Endocrinology',
                    'stem' => 'In diabetic ketoacidosis, the first priority after diagnosis is to:',
                    'explanation' => 'Fluid resuscitation is the first priority, then fixed rate insulin and electrolyte monitoring.',
                    'options' => [
                        'Start IV fluids',
                        'Give subcutaneous insulin only',
                        'Give bicarbonate immediately',
                        'Start oral intake',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Professional Dilemmas',
                    'stem' => 'A capacitated patient refuses a blood transfusion. What is the best response?',
                    'explanation' => 'A capacitated refusal must be respected. Document the discussion and offer alternatives.',
                    'options' => [
                        'Respect the decision and document it',
                        'Override and transfuse',
                        'Sedate and transfuse',
                        'Call police',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Endocrinology',
                    'stem' => 'A patient with hypothyroidism remains symptomatic on levothyroxine. The next step is to:',
                    'explanation' => 'Check adherence, timing, and repeat TFTs before dose adjustments.',
                    'options' => [
                        'Review adherence and repeat TFTs',
                        'Stop levothyroxine immediately',
                        'Add carbimazole',
                        'Start insulin therapy',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Respiratory',
                    'stem' => 'Which is the most appropriate first-line management for stable COPD?',
                    'explanation' => 'Smoking cessation and bronchodilators are first-line in stable COPD.',
                    'options' => [
                        'Smoking cessation and inhaled bronchodilator',
                        'Long-term oral steroids for all',
                        'IV antibiotics only',
                        'Nebulised adrenaline',
                    ],
                    'correct' => 0,
                ],
            ],
            'mock-paper-2' => [
                [
                    'topic' => 'Gastroenterology',
                    'stem' => 'A patient with haematemesis is hypotensive. What should you do first?',
                    'explanation' => 'Airway, breathing, circulation and resuscitation come before definitive endoscopy.',
                    'options' => [
                        'Assess ABC and start resuscitation',
                        'Arrange endoscopy immediately',
                        'Give oral PPI only',
                        'Discharge with safety net',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Renal',
                    'stem' => 'Hyperkalaemia with ECG changes is best treated initially with:',
                    'explanation' => 'IV calcium stabilises the myocardium while other measures lower potassium.',
                    'options' => [
                        'IV calcium gluconate',
                        'Oral potassium binder only',
                        'Loop diuretic only',
                        'High dose insulin without glucose',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Psychiatry',
                    'stem' => 'When starting an SSRI for depression, the most appropriate counselling is:',
                    'explanation' => 'SSRIs take weeks to improve mood, and patients need clear safety netting.',
                    'options' => [
                        'Benefits may take 2 to 4 weeks and safety net',
                        'Stop if not better in 2 days',
                        'Double the dose immediately',
                        'Never combine with therapy',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Obs and Gynae',
                    'stem' => 'A stable patient with suspected ectopic pregnancy should have:',
                    'explanation' => 'Prompt assessment is needed to confirm diagnosis and manage risk.',
                    'options' => [
                        'Urgent ultrasound and early pregnancy referral',
                        'Routine outpatient review in 6 weeks',
                        'Immediate hysterectomy',
                        'No imaging if urine test positive',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Gastroenterology',
                    'stem' => 'A patient with acute pancreatitis should receive:',
                    'explanation' => 'Early IV fluids and analgesia are key in pancreatitis.',
                    'options' => [
                        'IV fluids and adequate analgesia',
                        'Immediate ERCP for all',
                        'Oral antibiotics only',
                        'No imaging or labs',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Renal',
                    'stem' => 'In acute kidney injury, the most appropriate initial step is:',
                    'explanation' => 'Assess volume status, stop nephrotoxins, and correct reversible causes.',
                    'options' => [
                        'Assess volume status and stop nephrotoxins',
                        'Give contrast CT urgently',
                        'Start dialysis immediately for all',
                        'Ignore urine output',
                    ],
                    'correct' => 0,
                ],
            ],
            'mock-paper-3' => [
                [
                    'topic' => 'Infectious Disease',
                    'stem' => 'In suspected sepsis, the most time critical action is:',
                    'explanation' => 'Prompt IV antibiotics improve outcomes and should not be delayed.',
                    'options' => [
                        'Give IV broad spectrum antibiotics within 1 hour',
                        'Wait for cultures before treatment',
                        'Give oral antibiotics only',
                        'Stop all fluids',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Paediatrics',
                    'stem' => 'A child with non blanching rash and fever in the community should receive:',
                    'explanation' => 'Immediate antibiotics and urgent transfer are required for suspected meningococcal disease.',
                    'options' => [
                        'IM benzylpenicillin and urgent transfer',
                        'Oral antihistamines only',
                        'Reassurance and discharge',
                        'Delayed GP review',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Neurology',
                    'stem' => 'A patient arrives within 4.5 hours of ischaemic stroke onset and has no contraindications. Best next step?',
                    'explanation' => 'Eligible patients should be evaluated urgently for thrombolysis.',
                    'options' => [
                        'Consider thrombolysis',
                        'Start oral aspirin only',
                        'Delay imaging',
                        'Observe overnight',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Haematology',
                    'stem' => 'High Wells score for DVT. The most appropriate next step is:',
                    'explanation' => 'High probability warrants immediate anticoagulation while awaiting imaging.',
                    'options' => [
                        'Start anticoagulation and arrange imaging',
                        'D dimer only',
                        'Discharge without follow up',
                        'Compression stockings only',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Paediatrics',
                    'stem' => 'A child with croup and stridor at rest should receive:',
                    'explanation' => 'Nebulised adrenaline is indicated in moderate to severe croup.',
                    'options' => [
                        'Nebulised adrenaline and steroids',
                        'Oral antibiotics only',
                        'Immediate discharge',
                        'Surgery',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Neurology',
                    'stem' => 'A transient unilateral weakness lasting 10 minutes is most consistent with:',
                    'explanation' => 'Short-lived focal deficits suggest a TIA.',
                    'options' => [
                        'Transient ischaemic attack',
                        'Subarachnoid haemorrhage',
                        'Meningitis',
                        'Migraine aura only',
                    ],
                    'correct' => 0,
                ],
            ],
            'mock-paper-4' => [
                [
                    'topic' => 'General Surgery',
                    'stem' => 'A patient has right iliac fossa pain with guarding. Best initial step?',
                    'explanation' => 'Guarding suggests peritonism, so urgent surgical assessment is required.',
                    'options' => [
                        'Urgent surgical review and keep nil by mouth',
                        'Send home with analgesia',
                        'Schedule routine clinic review',
                        'Start oral laxatives',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Dermatology',
                    'stem' => 'Which feature is most concerning for melanoma?',
                    'explanation' => 'ABCDE features are red flags, especially asymmetry and colour change.',
                    'options' => [
                        'Asymmetry and colour change',
                        'Small uniform mole',
                        'Symmetric freckle',
                        'Itch without any skin change',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Ophthalmology',
                    'stem' => 'Acute painful red eye with a mid dilated pupil suggests:',
                    'explanation' => 'This is an emergency requiring urgent treatment and referral.',
                    'options' => [
                        'Acute angle closure glaucoma',
                        'Dry eye syndrome',
                        'Allergic conjunctivitis',
                        'Blepharitis',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Urology',
                    'stem' => 'Testicular torsion is suspected. The most appropriate action is:',
                    'explanation' => 'Time critical condition requiring urgent surgery.',
                    'options' => [
                        'Immediate surgical exploration',
                        'Await ultrasound next day',
                        'Give antibiotics only',
                        'Reassure and discharge',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'General Surgery',
                    'stem' => 'For suspected appendicitis in a stable adult, the best first-line imaging is:',
                    'explanation' => 'CT is commonly used in adults to confirm appendicitis.',
                    'options' => [
                        'CT abdomen and pelvis',
                        'MRI brain',
                        'Plain abdominal x-ray only',
                        'No imaging required',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Dermatology',
                    'stem' => 'A new rapidly expanding, painful erythematous rash with fever suggests:',
                    'explanation' => 'Systemic symptoms with spreading erythema suggest cellulitis.',
                    'options' => [
                        'Cellulitis',
                        'Seborrhoeic dermatitis',
                        'Vitiligo',
                        'Psoriasis only',
                    ],
                    'correct' => 0,
                ],
            ],
            'mock-paper-5' => [
                [
                    'topic' => 'Rheumatology',
                    'stem' => 'First line disease modifying therapy for new rheumatoid arthritis is:',
                    'explanation' => 'Methotrexate is typically first line DMARD unless contraindicated.',
                    'options' => [
                        'Methotrexate',
                        'High dose opioids',
                        'Paracetamol only',
                        'Azithromycin',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'ENT',
                    'stem' => 'Acute otitis media with persistent fever and otorrhoea is best managed with:',
                    'explanation' => 'Persistent symptoms warrant antibiotics and clear safety netting.',
                    'options' => [
                        'Oral antibiotics and safety net',
                        'No treatment at all',
                        'Immediate surgery',
                        'Topical steroids only',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Pharmacology',
                    'stem' => 'Warfarin associated major bleeding with high INR should be treated with:',
                    'explanation' => 'Major bleeding requires rapid reversal with PCC and vitamin K.',
                    'options' => [
                        'PCC plus IV vitamin K',
                        'Stop warfarin only',
                        'Aspirin and observe',
                        'Oral vitamin K only',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Cardiology',
                    'stem' => 'Initial management for NSTEMI typically includes:',
                    'explanation' => 'NSTEMI management uses antiplatelets and anticoagulation with early cardiology input.',
                    'options' => [
                        'Dual antiplatelet and anticoagulation',
                        'Thrombolysis immediately',
                        'No ECG monitoring',
                        'Discharge without follow up',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'ENT',
                    'stem' => 'Initial management of anterior epistaxis in a stable patient is:',
                    'explanation' => 'Simple first aid with pressure and topical vasoconstrictors is first-line.',
                    'options' => [
                        'Pinch the nose and lean forward',
                        'Immediate nasal surgery',
                        'Start IV antibiotics only',
                        'Head back and swallow',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Pharmacology',
                    'stem' => 'Which drug class is first-line for neuropathic pain?',
                    'explanation' => 'First-line options include amitriptyline, duloxetine, or gabapentinoids.',
                    'options' => [
                        'Amitriptyline',
                        'Paracetamol only',
                        'IV morphine for all',
                        'Topical antibiotics',
                    ],
                    'correct' => 0,
                ],
            ],
            'mock-paper-6' => [
                [
                    'topic' => 'Respiratory',
                    'stem' => 'Suspected pulmonary embolism with high Wells score. Best next step?',
                    'explanation' => 'High probability warrants anticoagulation while arranging imaging.',
                    'options' => [
                        'Start anticoagulation and arrange CTPA',
                        'D dimer only',
                        'Discharge with reassurance',
                        'Give antibiotics first',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Endocrinology',
                    'stem' => 'Thyroid storm management should include:',
                    'explanation' => 'Treat with beta blockers and antithyroid therapy, then iodine once blocked.',
                    'options' => [
                        'Beta blocker, antithyroid drug, then iodine',
                        'Stop all medications',
                        'Only analgesia',
                        'Oral fluids and discharge',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Professional Dilemmas',
                    'stem' => 'A patient threatens serious harm to another person. You should:',
                    'explanation' => 'Confidentiality can be breached to prevent serious harm, sharing the minimum required.',
                    'options' => [
                        'Share minimum necessary information to protect others',
                        'Keep everything confidential',
                        'Post on social media',
                        'Ignore the threat',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Paediatrics',
                    'stem' => 'Bronchiolitis in an infant is primarily managed with:',
                    'explanation' => 'Supportive care is the mainstay; antibiotics are not routine.',
                    'options' => [
                        'Supportive care and oxygen if needed',
                        'Routine antibiotics',
                        'High dose steroids for all',
                        'Nebulised antibiotics',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Cardiology',
                    'stem' => 'For atrial fibrillation with fast ventricular rate, the first-line rate control is:',
                    'explanation' => 'Beta blockers or rate-limiting calcium channel blockers are first-line.',
                    'options' => [
                        'Beta blocker or diltiazem',
                        'Immediate cardioversion for all',
                        'Long-term digoxin only',
                        'No treatment required',
                    ],
                    'correct' => 0,
                ],
                [
                    'topic' => 'Professional Dilemmas',
                    'stem' => 'A colleague appears impaired at work. You should:',
                    'explanation' => 'Patient safety comes first; escalate concerns appropriately.',
                    'options' => [
                        'Escalate through appropriate clinical channels',
                        'Ignore to avoid conflict',
                        'Publicly shame them',
                        'Cover without informing anyone',
                    ],
                    'correct' => 0,
                ],
            ],
        ];

        foreach ($papers as $paperData) {
            $paper = MockPaper::updateOrCreate(
                ['slug' => $paperData['slug']],
                array_merge($paperData, [
                    'duration_minutes' => 75,
                    'is_active' => true,
                ])
            );

            $paperQuestions = $questions[$paperData['slug']] ?? [];
            foreach ($paperQuestions as $order => $questionData) {
                $question = MockPaperQuestion::updateOrCreate(
                    [
                        'mock_paper_id' => $paper->id,
                        'order' => $order + 1,
                    ],
                    [
                        'topic' => $questionData['topic'],
                        'stem' => $questionData['stem'],
                        'explanation' => $questionData['explanation'],
                    ]
                );

                $question->options()->delete();
                foreach ($questionData['options'] as $idx => $optionText) {
                    MockPaperOption::create([
                        'mock_paper_question_id' => $question->id,
                        'text' => $optionText,
                        'is_correct' => $idx === $questionData['correct'],
                        'order' => $idx + 1,
                    ]);
                }
            }
        }
    }
}
