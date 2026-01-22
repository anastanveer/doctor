<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $topics = Topic::pluck('id', 'slug');
        $topicModels = Topic::orderBy('name')->get();

        $questions = [
            [
                'topic_slug' => 'cardiology',
                'type' => 'single',
                'difficulty' => 'Advanced',
                'stem' => 'Which valve replacement option gives the longest durability for a 54-year-old with aortic stenosis?',
                'explanation' => 'Mechanical valves last longest but require lifelong anticoagulation.',
                'options' => [
                    ['text' => 'Mechanical valve + Warfarin', 'is_correct' => true],
                    ['text' => 'Bioprosthetic valve', 'is_correct' => false],
                    ['text' => 'Ross procedure', 'is_correct' => false],
                    ['text' => 'Transcatheter AVR', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'rheumatology-msk',
                'type' => 'single',
                'difficulty' => 'Recall',
                'stem' => 'A 40-year-old on methotrexate develops a persistent dry cough. What is the next best step?',
                'explanation' => 'Suspicion of methotrexate pneumonitis requires stopping the drug and imaging.',
                'options' => [
                    ['text' => 'Increase methotrexate dose', 'is_correct' => false],
                    ['text' => 'Hold methotrexate and arrange CXR', 'is_correct' => true],
                    ['text' => 'Add azathioprine', 'is_correct' => false],
                    ['text' => 'Start oral prednisolone', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'pharmacology',
                'type' => 'single',
                'difficulty' => 'Precision',
                'stem' => 'Which antibiotic must be avoided in acute porphyria due to neurotoxic risk?',
                'explanation' => 'Avoid porphyrinogenic antibiotics; doxycycline is safer than others.',
                'options' => [
                    ['text' => 'Doxycycline', 'is_correct' => false],
                    ['text' => 'Ceftriaxone', 'is_correct' => false],
                    ['text' => 'Amoxicillin', 'is_correct' => false],
                    ['text' => 'Co-amoxiclav', 'is_correct' => true],
                ],
            ],
            [
                'topic_slug' => 'respiratory',
                'type' => 'single',
                'difficulty' => 'Core attaching',
                'stem' => 'In an acute asthma attack, which is the first-line bronchodilator?',
                'explanation' => 'High-dose inhaled short-acting beta-agonist is first line.',
                'options' => [
                    ['text' => 'Inhaled salbutamol', 'is_correct' => true],
                    ['text' => 'Oral steroids only', 'is_correct' => false],
                    ['text' => 'Theophylline', 'is_correct' => false],
                    ['text' => 'Antibiotics', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'professional-dilemmas',
                'type' => 'multiple',
                'difficulty' => 'High yield',
                'stem' => 'Select TWO actions that best align with GMC guidance when receiving a complaint.',
                'explanation' => 'Acknowledge, reflect, and maintain professional communication.',
                'options' => [
                    ['text' => 'Acknowledge the complaint promptly', 'is_correct' => true],
                    ['text' => 'Ignore to avoid escalation', 'is_correct' => false],
                    ['text' => 'Reflect and seek feedback', 'is_correct' => true],
                    ['text' => 'Discuss confidential details publicly', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'neurology',
                'type' => 'multiple',
                'difficulty' => 'Recall',
                'stem' => 'Which TWO features suggest temporal arteritis?',
                'explanation' => 'Jaw claudication and visual symptoms are classical.',
                'options' => [
                    ['text' => 'Jaw claudication', 'is_correct' => true],
                    ['text' => 'Visual loss', 'is_correct' => true],
                    ['text' => 'Ankle swelling', 'is_correct' => false],
                    ['text' => 'Haemoptysis', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'endocrinology',
                'type' => 'true_false',
                'difficulty' => 'Quick check',
                'stem' => 'True or False: Diabetic ketoacidosis requires immediate insulin infusion before fluids.',
                'explanation' => 'IV fluids are prioritised before insulin in many protocols.',
                'options' => [
                    ['text' => 'True', 'is_correct' => false],
                    ['text' => 'False', 'is_correct' => true],
                ],
            ],
            [
                'topic_slug' => 'gastroenterology',
                'type' => 'ordering',
                'difficulty' => 'Workflow',
                'stem' => 'Order the steps for managing an upper GI bleed in the emergency department.',
                'explanation' => 'Resuscitate, assess airway, and arrange urgent endoscopy.',
                'options' => [
                    ['text' => 'Assess airway and breathing', 'correct_order' => 1],
                    ['text' => 'Insert large-bore IV access', 'correct_order' => 2],
                    ['text' => 'Resuscitate with fluids', 'correct_order' => 3],
                    ['text' => 'Arrange urgent endoscopy', 'correct_order' => 4],
                ],
            ],
            [
                'topic_slug' => 'infectious-disease',
                'type' => 'match',
                'difficulty' => 'Matching',
                'stem' => 'Match the organism to the typical antibiotic of choice.',
                'explanation' => 'Pair each organism with the first-line agent.',
                'options' => [
                    ['text' => 'Strep pneumoniae', 'match_key' => 'Penicillin'],
                    ['text' => 'MRSA', 'match_key' => 'Vancomycin'],
                    ['text' => 'C. difficile', 'match_key' => 'Vancomycin (oral)'],
                    ['text' => 'H. pylori', 'match_key' => 'Triple therapy'],
                ],
            ],
            [
                'topic_slug' => 'renal',
                'type' => 'short_answer',
                'difficulty' => 'Recall',
                'stem' => 'What is the most common cause of nephrotic syndrome in adults?',
                'explanation' => 'Membranous nephropathy is common in adults.',
                'answer_text' => 'Membranous nephropathy',
                'options' => [],
            ],
            [
                'topic_slug' => 'cardiology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'A 62-year-old has crushing chest pain and ST elevation in V2-V5. What is the next best step?',
                'explanation' => 'Primary PCI is the preferred reperfusion strategy for STEMI.',
                'options' => [
                    ['text' => 'Immediate primary PCI', 'is_correct' => true],
                    ['text' => 'Thrombolysis next day', 'is_correct' => false],
                    ['text' => 'Outpatient stress test', 'is_correct' => false],
                    ['text' => 'Oral aspirin only', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'respiratory',
                'type' => 'single',
                'difficulty' => 'Applied',
                'stem' => 'A COPD patient has pH 7.25 and PaCO2 8.5 kPa despite controlled oxygen. Best next step?',
                'explanation' => 'Acute hypercapnic respiratory failure responds to non-invasive ventilation.',
                'options' => [
                    ['text' => 'Start non-invasive ventilation', 'is_correct' => true],
                    ['text' => 'Increase oxygen to 100%', 'is_correct' => false],
                    ['text' => 'Give furosemide only', 'is_correct' => false],
                    ['text' => 'Discharge with antibiotics', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'neurology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'A 45-year-old has sudden thunderclap headache. CT head is normal after 12 hours. Next investigation?',
                'explanation' => 'Lumbar puncture is required to assess for xanthochromia.',
                'options' => [
                    ['text' => 'Lumbar puncture for xanthochromia', 'is_correct' => true],
                    ['text' => 'MRI spine', 'is_correct' => false],
                    ['text' => 'Carotid Doppler', 'is_correct' => false],
                    ['text' => 'EEG', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'gastroenterology',
                'type' => 'single',
                'difficulty' => 'Applied',
                'stem' => 'Cirrhotic patient with haematemesis and hypotension. After ABC resuscitation, what drug should be started urgently?',
                'explanation' => 'Terlipressin reduces portal pressure in suspected variceal bleed.',
                'options' => [
                    ['text' => 'IV terlipressin', 'is_correct' => true],
                    ['text' => 'IV omeprazole only', 'is_correct' => false],
                    ['text' => 'Oral propranolol', 'is_correct' => false],
                    ['text' => 'IV metoclopramide', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'endocrinology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Pregnant woman with hypothyroidism has rising TSH in first trimester. What change is needed?',
                'explanation' => 'Levothyroxine dose should be increased early in pregnancy.',
                'options' => [
                    ['text' => 'Increase levothyroxine dose', 'is_correct' => true],
                    ['text' => 'Stop levothyroxine', 'is_correct' => false],
                    ['text' => 'Switch to carbimazole', 'is_correct' => false],
                    ['text' => 'No change', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'renal',
                'type' => 'single',
                'difficulty' => 'Applied',
                'stem' => 'Elderly patient on ACE inhibitor and NSAID develops AKI after gastroenteritis. Best initial management?',
                'explanation' => 'Stop nephrotoxic drugs and give IV fluids to restore perfusion.',
                'options' => [
                    ['text' => 'Stop nephrotoxic drugs and give IV fluids', 'is_correct' => true],
                    ['text' => 'Start diuretics', 'is_correct' => false],
                    ['text' => 'Start ACE inhibitor', 'is_correct' => false],
                    ['text' => 'Restrict fluids', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'obs-gynae',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Postpartum haemorrhage with a boggy uterus. First-line treatment?',
                'explanation' => 'Uterine atony responds to massage and oxytocin.',
                'options' => [
                    ['text' => 'Bimanual uterine massage and oxytocin', 'is_correct' => true],
                    ['text' => 'Hysterectomy immediately', 'is_correct' => false],
                    ['text' => 'Give tranexamic acid only', 'is_correct' => false],
                    ['text' => 'Wait and observe', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'paediatrics',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Child with barking cough, stridor, and no respiratory distress. Best treatment?',
                'explanation' => 'Single-dose oral dexamethasone improves croup symptoms.',
                'options' => [
                    ['text' => 'Oral dexamethasone', 'is_correct' => true],
                    ['text' => 'Immediate intubation', 'is_correct' => false],
                    ['text' => 'Nebulised antibiotics', 'is_correct' => false],
                    ['text' => 'Oral salbutamol', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'dermatology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'A 52-year-old has a changing pigmented lesion with asymmetry and irregular border. Best action?',
                'explanation' => 'Suspicious pigmented lesions need urgent cancer referral.',
                'options' => [
                    ['text' => 'Urgent suspected cancer referral', 'is_correct' => true],
                    ['text' => 'Reassure and review in 6 months', 'is_correct' => false],
                    ['text' => 'Topical steroid', 'is_correct' => false],
                    ['text' => 'Cryotherapy in clinic', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'ent',
                'type' => 'single',
                'difficulty' => 'Applied',
                'stem' => 'Adult with drooling, severe sore throat, fever, and tripod posture. Best immediate management?',
                'explanation' => 'Epiglottitis is an airway emergency; secure airway and give IV antibiotics.',
                'options' => [
                    ['text' => 'Secure airway and give IV antibiotics', 'is_correct' => true],
                    ['text' => 'Send home with oral antibiotics', 'is_correct' => false],
                    ['text' => 'Throat swab only', 'is_correct' => false],
                    ['text' => 'Nebulised salbutamol', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'psychiatry',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Acute mania with agitation and reduced need for sleep. First-line drug?',
                'explanation' => 'An antipsychotic such as olanzapine is first-line for acute mania.',
                'options' => [
                    ['text' => 'Antipsychotic such as olanzapine', 'is_correct' => true],
                    ['text' => 'SSRI', 'is_correct' => false],
                    ['text' => 'Benzodiazepine only long-term', 'is_correct' => false],
                    ['text' => 'Stop all medication', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'haematology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Provoked DVT after surgery. How long to anticoagulate?',
                'explanation' => 'Three months is standard for a provoked DVT.',
                'options' => [
                    ['text' => '3 months', 'is_correct' => true],
                    ['text' => 'Indefinitely', 'is_correct' => false],
                    ['text' => '1 week', 'is_correct' => false],
                    ['text' => 'No anticoagulation', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'infectious-disease',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Young adult with meningococcal sepsis. Immediate antibiotic?',
                'explanation' => 'Ceftriaxone is first-line for suspected meningococcal sepsis.',
                'options' => [
                    ['text' => 'IV ceftriaxone', 'is_correct' => true],
                    ['text' => 'Oral amoxicillin', 'is_correct' => false],
                    ['text' => 'IV vancomycin only', 'is_correct' => false],
                    ['text' => 'Oral doxycycline', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'pharmacology',
                'type' => 'single',
                'difficulty' => 'Recall',
                'stem' => 'Patient on warfarin starts erythromycin. What happens to INR?',
                'explanation' => 'Macrolides inhibit warfarin metabolism, increasing INR.',
                'options' => [
                    ['text' => 'INR increases', 'is_correct' => true],
                    ['text' => 'INR decreases', 'is_correct' => false],
                    ['text' => 'No change', 'is_correct' => false],
                    ['text' => 'Warfarin becomes inactive', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'general-surgery',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Right iliac fossa pain, fever, raised WBC. Most appropriate management?',
                'explanation' => 'Classic appendicitis is managed surgically.',
                'options' => [
                    ['text' => 'Urgent appendectomy', 'is_correct' => true],
                    ['text' => 'High-fibre diet', 'is_correct' => false],
                    ['text' => 'Discharge with laxatives', 'is_correct' => false],
                    ['text' => 'Observe for 4 weeks', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'urology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Painless visible haematuria in a 68-year-old smoker. Next step?',
                'explanation' => 'Visible haematuria needs urgent urology assessment and cystoscopy.',
                'options' => [
                    ['text' => 'Urgent cystoscopy referral', 'is_correct' => true],
                    ['text' => 'Trial antibiotics only', 'is_correct' => false],
                    ['text' => 'Reassure', 'is_correct' => false],
                    ['text' => 'MRI brain', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'ophthalmology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Painful red eye with mid-dilated pupil and high IOP. Immediate treatment?',
                'explanation' => 'Acute angle-closure glaucoma needs urgent IOP lowering.',
                'options' => [
                    ['text' => 'IV acetazolamide and topical IOP-lowering drops', 'is_correct' => true],
                    ['text' => 'Topical steroid only', 'is_correct' => false],
                    ['text' => 'Oral antibiotics', 'is_correct' => false],
                    ['text' => 'Eye patch', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'professional-dilemmas',
                'type' => 'single',
                'difficulty' => 'Professional',
                'stem' => 'You smell alcohol on a colleague before a clinic. Best action?',
                'explanation' => 'Patient safety comes first; remove from duty and escalate appropriately.',
                'options' => [
                    ['text' => 'Remove from duty and escalate via senior', 'is_correct' => true],
                    ['text' => 'Ignore to avoid conflict', 'is_correct' => false],
                    ['text' => 'Confront in front of patients', 'is_correct' => false],
                    ['text' => 'Cover for them without reporting', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'rheumatology-msk',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Acute gout in first MTP joint with no contraindications. First-line treatment?',
                'explanation' => 'NSAIDs are first-line in acute gout if no contraindications.',
                'options' => [
                    ['text' => 'NSAID', 'is_correct' => true],
                    ['text' => 'Allopurinol immediately', 'is_correct' => false],
                    ['text' => 'Long-term steroids only', 'is_correct' => false],
                    ['text' => 'Opioids only', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'cardiology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Atrial fibrillation with hypotension and chest pain. Best next step?',
                'explanation' => 'Unstable AF requires immediate synchronized cardioversion.',
                'options' => [
                    ['text' => 'Immediate synchronized DC cardioversion', 'is_correct' => true],
                    ['text' => 'Start oral beta blocker', 'is_correct' => false],
                    ['text' => 'Give aspirin and review', 'is_correct' => false],
                    ['text' => 'Observe only', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'respiratory',
                'type' => 'single',
                'difficulty' => 'Applied',
                'stem' => 'Suspected PE with high Wells score. Most appropriate initial action?',
                'explanation' => 'Start anticoagulation and arrange definitive imaging.',
                'options' => [
                    ['text' => 'Start LMWH and arrange CTPA', 'is_correct' => true],
                    ['text' => 'D-dimer only and discharge', 'is_correct' => false],
                    ['text' => 'Chest physiotherapy', 'is_correct' => false],
                    ['text' => 'Oral antibiotics', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'neurology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'TIA within 12 hours with no contraindications. Best management?',
                'explanation' => 'Start antiplatelet therapy and arrange urgent TIA assessment.',
                'options' => [
                    ['text' => 'Give aspirin and urgent TIA clinic assessment', 'is_correct' => true],
                    ['text' => 'Delay treatment for 1 week', 'is_correct' => false],
                    ['text' => 'Start warfarin immediately', 'is_correct' => false],
                    ['text' => 'No action', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'gastroenterology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Acute pancreatitis with severe epigastric pain and raised lipase. Initial management?',
                'explanation' => 'Early IV fluids and analgesia are key in acute pancreatitis.',
                'options' => [
                    ['text' => 'Aggressive IV fluids and analgesia', 'is_correct' => true],
                    ['text' => 'Immediate ERCP for all', 'is_correct' => false],
                    ['text' => 'Start steroids', 'is_correct' => false],
                    ['text' => 'Keep NPO and discharge', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'endocrinology',
                'type' => 'single',
                'difficulty' => 'Applied',
                'stem' => 'Thyroid storm with fever and tachycardia. Best initial drug combination?',
                'explanation' => 'Beta blockade plus PTU reduces peripheral effects and hormone synthesis.',
                'options' => [
                    ['text' => 'Beta blocker and propylthiouracil', 'is_correct' => true],
                    ['text' => 'Levothyroxine and lithium', 'is_correct' => false],
                    ['text' => 'Only iodine', 'is_correct' => false],
                    ['text' => 'Only antibiotics', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'renal',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Hyperkalaemia 7.2 mmol/L with peaked T waves. First step?',
                'explanation' => 'IV calcium gluconate stabilizes the myocardium.',
                'options' => [
                    ['text' => 'IV calcium gluconate', 'is_correct' => true],
                    ['text' => 'Oral sodium bicarbonate only', 'is_correct' => false],
                    ['text' => 'Insulin only without monitoring', 'is_correct' => false],
                    ['text' => 'Wait for repeat bloods', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'obs-gynae',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Stable ectopic pregnancy with small adnexal mass and low hCG. Management?',
                'explanation' => 'Medical management with methotrexate is appropriate when stable.',
                'options' => [
                    ['text' => 'Methotrexate therapy', 'is_correct' => true],
                    ['text' => 'Immediate laparotomy', 'is_correct' => false],
                    ['text' => 'Routine antenatal care', 'is_correct' => false],
                    ['text' => 'No follow-up', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'paediatrics',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Bronchiolitis in a 6-month-old with mild symptoms. Best treatment?',
                'explanation' => 'Supportive care is recommended for mild bronchiolitis.',
                'options' => [
                    ['text' => 'Supportive care only', 'is_correct' => true],
                    ['text' => 'Antibiotics', 'is_correct' => false],
                    ['text' => 'Oral steroids', 'is_correct' => false],
                    ['text' => 'Nebulised salbutamol routinely', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'dermatology',
                'type' => 'single',
                'difficulty' => 'Core',
                'stem' => 'Mild cellulitis on leg with no systemic signs. Best antibiotic?',
                'explanation' => 'Flucloxacillin is first-line for uncomplicated cellulitis.',
                'options' => [
                    ['text' => 'Oral flucloxacillin', 'is_correct' => true],
                    ['text' => 'Topical antifungal', 'is_correct' => false],
                    ['text' => 'No treatment', 'is_correct' => false],
                    ['text' => 'Oral acyclovir', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'ent',
                'type' => 'single',
                'difficulty' => 'Applied',
                'stem' => 'Sudden sensorineural hearing loss within 24 hours. Best management?',
                'explanation' => 'Urgent steroids improve recovery; refer to ENT immediately.',
                'options' => [
                    ['text' => 'Urgent high-dose steroids and ENT referral', 'is_correct' => true],
                    ['text' => 'Reassure and review in 6 weeks', 'is_correct' => false],
                    ['text' => 'Oral antibiotics', 'is_correct' => false],
                    ['text' => 'Ear syringing', 'is_correct' => false],
                ],
            ],
            [
                'topic_slug' => 'psychiatry',
                'type' => 'single',
                'difficulty' => 'Professional',
                'stem' => 'Patient expresses active suicidal intent with a plan. Best next step?',
                'explanation' => 'Immediate risk assessment and safety planning are required.',
                'options' => [
                    ['text' => 'Urgent mental health assessment and ensure safety', 'is_correct' => true],
                    ['text' => 'Offer routine GP appointment', 'is_correct' => false],
                    ['text' => 'Send home alone', 'is_correct' => false],
                    ['text' => 'Ignore to respect privacy', 'is_correct' => false],
                ],
            ],
        ];

        $existingStems = Question::pluck('stem')->all();

        foreach ($questions as $payload) {
            $payload['topic_id'] = $topics[$payload['topic_slug']] ?? null;
            $this->storeQuestion($payload, $existingStems);
        }

        $this->seedTopicMinimums($topicModels, $existingStems, 40);
    }

    private function seedTopicMinimums($topicModels, array &$existingStems, int $target): void
    {
        foreach ($topicModels as $topic) {
            $current = Question::where('topic_id', $topic->id)->count();
            $seed = $current + 1;
            while ($current < $target) {
                $payload = $this->generateAutoQuestion($topic, $seed);
                if ($this->storeQuestion($payload, $existingStems)) {
                    $current++;
                }
                $seed++;
            }
        }
    }

    private function generateAutoQuestion(Topic $topic, int $sequence): array
    {
        $types = ['single', 'multiple', 'true_false', 'ordering', 'match', 'short_answer', 'single', 'multiple'];
        $type = $types[$sequence % count($types)];
        $difficulties = ['Advanced', 'Core', 'Precision', 'Applied', 'Recall'];
        $difficulty = $difficulties[$sequence % count($difficulties)];
        $stem = $this->buildStem($topic->name, $sequence);
        $meta = $this->imageMeta($topic->name, $sequence);
        $explanation = 'This reflects standard exam-style reasoning for the scenario.';

        if ($type === 'true_false') {
            $statement = $this->buildTrueFalseStatement($topic->name, $sequence);
            $isTrue = ($sequence % 2) === 0;
            $stem = $statement;
            $options = [
                ['text' => 'True', 'is_correct' => $isTrue],
                ['text' => 'False', 'is_correct' => !$isTrue],
            ];
        } elseif ($type === 'ordering') {
            $steps = $this->orderingSteps($topic->name);
            $options = [];
            foreach ($steps as $index => $text) {
                $options[] = [
                    'text' => $text,
                    'correct_order' => $index + 1,
                ];
            }
        } elseif ($type === 'match') {
            $pairs = $this->matchingPairs($topic->name, $sequence);
            $options = [];
            foreach ($pairs as $pair) {
                $options[] = [
                    'text' => $pair['left'],
                    'match_key' => $pair['right'],
                ];
            }
        } elseif ($type === 'short_answer') {
            $answer = $this->shortAnswer($topic->name, $sequence);
            $options = [];
        } elseif ($type === 'multiple') {
            $options = $this->multipleChoiceOptions($topic->name, $sequence, 2);
        } else {
            $options = $this->multipleChoiceOptions($topic->name, $sequence, 1);
        }

        return [
            'topic_id' => $topic->id,
            'type' => $type,
            'difficulty' => $difficulty,
            'stem' => $stem,
            'explanation' => $explanation,
            'answer_text' => $answer ?? null,
            'options' => $options ?? [],
            'meta' => $meta,
        ];
    }

    private function buildStem(string $topicName, int $sequence): string
    {
        $ages = [28, 34, 41, 52, 63, 71];
        $settings = ['in the emergency department', 'in clinic', 'on the ward', 'in general practice'];
        $focus = [
            'most appropriate next step',
            'most appropriate investigation',
            'first-line management',
            'most likely diagnosis',
            'key red flag to recognise',
        ];
        $age = $ages[$sequence % count($ages)];
        $setting = $settings[$sequence % count($settings)];
        $goal = $focus[$sequence % count($focus)];

        if (stripos($topicName, 'Professional') !== false) {
            return "Professional dilemmas case {$sequence}: You are {$setting} and a concern is raised about safety. What is the {$goal}?";
        }

        return "{$topicName} case {$sequence}: A {$age}-year-old {$setting} presents with an acute {$topicName} scenario. What is the {$goal}?";
    }

    private function buildTrueFalseStatement(string $topicName, int $sequence): string
    {
        $statements = [
            "In {$topicName}, immediate assessment and documentation of red flags is essential.",
            "For {$topicName} presentations, delaying review for several weeks is always safe.",
            "In {$topicName} emergencies, early senior escalation improves outcomes.",
            "In {$topicName}, a focused history and examination are required before treatment decisions.",
        ];

        $statement = $statements[$sequence % count($statements)];
        return "{$statement} (Case {$sequence})";
    }

    private function orderingSteps(string $topicName): array
    {
        if (stripos($topicName, 'Professional') !== false) {
            return [
                'Ensure immediate patient safety',
                'Inform a senior or responsible lead',
                'Document the incident and facts clearly',
                'Arrange follow-up and learning actions',
            ];
        }

        return [
            "Initial assessment of {$topicName} presentation",
            'Immediate stabilisation and safety actions',
            'Targeted investigations to confirm diagnosis',
            'Definitive management and follow-up plan',
        ];
    }

    private function matchingPairs(string $topicName, int $sequence): array
    {
        $pairs = [];
        for ($i = 1; $i <= 4; $i++) {
            $pairs[] = [
                'left' => "{$topicName} scenario {$sequence}.{$i}",
                'right' => "{$topicName} action {$sequence}.{$i}",
            ];
        }

        return $pairs;
    }

    private function shortAnswer(string $topicName, int $sequence): string
    {
        $answers = [
            'Urgent specialist review',
            'Immediate escalation to senior',
            'Start first-line therapy',
            'Arrange definitive imaging',
            'Provide safety net and follow-up',
        ];

        return $answers[$sequence % count($answers)];
    }

    private function multipleChoiceOptions(string $topicName, int $sequence, int $correctCount): array
    {
        $correctPool = [
            'Escalate to senior and start targeted care',
            'Order urgent imaging and monitor closely',
            'Start first-line therapy and reassess',
            'Arrange specialist review within 24 hours',
        ];
        $wrongPool = [
            'Discharge without follow-up',
            'Delay assessment for several weeks',
            'Provide reassurance only',
            'Start contraindicated treatment',
            'Ignore red flags and continue routine care',
        ];

        $correct = [];
        for ($i = 0; $i < $correctCount; $i++) {
            $correct[] = $correctPool[($sequence + $i) % count($correctPool)];
        }
        $wrong = [];
        for ($i = 0; $i < (4 - $correctCount); $i++) {
            $wrong[] = $wrongPool[($sequence + $i) % count($wrongPool)];
        }

        $options = [];
        foreach ($correct as $text) {
            $options[] = ['text' => $text, 'is_correct' => true];
        }
        foreach ($wrong as $text) {
            $options[] = ['text' => $text, 'is_correct' => false];
        }

        return $options;
    }

    private function imageMeta(string $topicName, int $sequence): ?array
    {
        if ($sequence % 7 !== 0) {
            return null;
        }

        $images = ['/images/1.png', '/images/2.png', '/images/222.png'];
        $image = $images[$sequence % count($images)];

        return [
            'image' => $image,
            'alt' => "{$topicName} clinical image",
        ];
    }

    private function storeQuestion(array $payload, array &$existingStems): bool
    {
        if (in_array($payload['stem'], $existingStems, true)) {
            return false;
        }

        $question = Question::create([
            'topic_id' => $payload['topic_id'] ?? null,
            'type' => $payload['type'],
            'difficulty' => $payload['difficulty'],
            'stem' => $payload['stem'],
            'explanation' => $payload['explanation'] ?? null,
            'answer_text' => $payload['answer_text'] ?? null,
            'meta' => $payload['meta'] ?? null,
        ]);

        foreach ($payload['options'] as $index => $option) {
            QuestionOption::create([
                'question_id' => $question->id,
                'text' => $option['text'],
                'is_correct' => $option['is_correct'] ?? false,
                'order' => $index + 1,
                'match_key' => $option['match_key'] ?? null,
                'correct_order' => $option['correct_order'] ?? null,
            ]);
        }

        $existingStems[] = $payload['stem'];

        return true;
    }
}
