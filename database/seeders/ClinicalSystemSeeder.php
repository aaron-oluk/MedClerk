<?php

namespace Database\Seeders;

use App\Models\ClinicalSign;
use App\Models\ClinicalSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicalSystemSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cardiovascular = ClinicalSystem::updateOrCreate(
            ['name' => 'Cardiovascular System'],
            ['description' => 'Examination and signs relevant to the cardiovascular system.', 'icon' => 'heart', 'color' => '#dc2626'],
        );

        $respiratory = ClinicalSystem::updateOrCreate(
            ['name' => 'Respiratory System'],
            ['description' => 'Examination and signs relevant to the respiratory system.', 'icon' => 'lungs', 'color' => '#0891b2'],
        );

        $gastrointestinal = ClinicalSystem::updateOrCreate(
            ['name' => 'Gastrointestinal System'],
            ['description' => 'Examination and signs relevant to the gastrointestinal system.', 'icon' => 'stomach', 'color' => '#d97706'],
        );

        $neurological = ClinicalSystem::updateOrCreate(
            ['name' => 'Neurological System'],
            ['description' => 'Examination and signs relevant to the neurological system.', 'icon' => 'brain', 'color' => '#7c3aed'],
        );

        $musculoskeletal = ClinicalSystem::updateOrCreate(
            ['name' => 'Musculoskeletal System'],
            ['description' => 'Examination and signs relevant to the musculoskeletal system.', 'icon' => 'bone', 'color' => '#0d9488'],
        );

        $endocrine = ClinicalSystem::updateOrCreate(
            ['name' => 'Endocrine System'],
            ['description' => 'Examination and signs relevant to the endocrine system.', 'icon' => 'gland', 'color' => '#db2777'],
        );

        $renal = ClinicalSystem::updateOrCreate(
            ['name' => 'Renal System'],
            ['description' => 'Examination and signs relevant to the renal system.', 'icon' => 'kidney', 'color' => '#2563eb'],
        );

        $haematology = ClinicalSystem::updateOrCreate(
            ['name' => 'Haematology'],
            ['description' => 'Examination and signs relevant to the blood and lymphatic systems.', 'icon' => 'droplet', 'color' => '#b91c1c'],
        );

        $dermatology = ClinicalSystem::updateOrCreate(
            ['name' => 'Dermatology'],
            ['description' => 'Examination and signs relevant to the skin.', 'icon' => 'skin', 'color' => '#9333ea'],
        );

        $ent = ClinicalSystem::updateOrCreate(
            ['name' => 'ENT and Neck'],
            ['description' => 'Examination and signs relevant to the ear, nose, throat and neck.', 'icon' => 'ear', 'color' => '#65a30d'],
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $cardiovascular->id, 'name' => 'Raised jugular venous pressure'],
            [
                'eponym' => 'JVP',
                'description' => 'Elevation of the venous pulsation in the internal jugular vein above the sternal angle, indicating raised right atrial pressure.',
                'interpretation' => 'A JVP more than 3 cm above the sternal angle, or more than 8 cm above the right atrium in a 45 degree supine patient, is abnormal. Giant v waves suggest tricuspid regurgitation, absent a waves suggest atrial fibrillation.',
                'technique' => 'Position the patient at 45 degrees, head turned 45 degrees away from the examined side. Use tangential light and identify the double pulsation of the right internal jugular vein. Measure the vertical height above the sternal angle.',
                'diagnostic_relevance' => 'Differentiates right heart failure, fluid overload, tricuspid regurgitation, and constrictive pericarditis from left sided causes of oedema.',
                'red_flags' => [
                    'Kussmaul sign (rises on inspiration) suggests constrictive pericarditis',
                    'Cannon a waves indicate complete heart block',
                    'Sudden rise with hypotension may indicate tamponade',
                ],
                'difficulty' => 'intermediate',
                'last_reviewed' => '2026-05-18',
                'media_type' => 'video',
                'media_duration' => '4m 12s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $cardiovascular->id, 'name' => 'Hepatojugular reflux'],
            [
                'eponym' => 'Abdominojugular test',
                'description' => 'Sustained rise in JVP on firm pressure over the right upper quadrant, indicating impaired right heart function.',
                'interpretation' => 'A sustained rise of more than 4 cm for the duration of pressure, with a slow fall on release, is positive. A brief rise that falls while pressure is maintained is normal.',
                'technique' => 'Position the patient at 45 degrees. Apply firm pressure over the right upper quadrant for 30 seconds while observing the JVP. Maintain steady pressure and watch for a sustained rise.',
                'diagnostic_relevance' => 'A positive test supports a diagnosis of right ventricular failure or volume overload, and correlates with elevated pulmonary capillary wedge pressure.',
                'red_flags' => ['Should not be performed in patients with right upper quadrant tenderness or hepatomegaly of unknown cause'],
                'difficulty' => 'advanced',
                'last_reviewed' => '2026-03-30',
                'media_type' => 'video',
                'media_duration' => '3m 05s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $respiratory->id, 'name' => 'Pleural rub'],
            [
                'description' => 'A creaking, grating sound heard on auscultation of the chest, caused by inflamed pleural surfaces rubbing together.',
                'interpretation' => 'Best heard at the lower lateral chest wall during deep inspiration. Does not clear with coughing, unlike crackles. May disappear if a pleural effusion develops.',
                'technique' => 'Auscultate the posterior and lateral chest wall during deep inspiration. Ask the patient to hold breath briefly to confirm the sound is pleural, not pericardial.',
                'diagnostic_relevance' => 'Indicates pleuritis or pleural inflammation, often accompanying pulmonary embolism, pneumonia, or viral pleurisy. Distinguishes pleuritic pain from musculoskeletal chest wall pain.',
                'red_flags' => [
                    'Sudden onset with dyspnoea and pleuritic pain raises concern for pulmonary embolism',
                    'Bilateral rubs may indicate serositis in connective tissue disease',
                ],
                'difficulty' => 'intermediate',
                'last_reviewed' => '2026-04-22',
                'media_type' => 'audio',
                'media_duration' => '1m 38s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $gastrointestinal->id, 'name' => 'Murphy sign'],
            [
                'eponym' => 'John Murphy',
                'description' => 'Inspiratory arrest during palpation of the right subcostal region, suggesting acute cholecystitis.',
                'interpretation' => 'Positive when the patient abruptly stops inspiration as the examiner\'s hand reaches the inflamed gallbladder. Sensitivity is around 65 percent, specificity around 87 percent.',
                'technique' => 'With the patient supine and relaxed, place two fingers under the right costal margin midclavicular line. Ask the patient to breathe in deeply. Watch and feel for the arrest of inspiration.',
                'diagnostic_relevance' => 'A positive Murphy sign has a high positive likelihood ratio for acute cholecystitis. Helps distinguish biliary pain from hepatic, renal, or musculoskeletal causes of right upper quadrant pain.',
                'red_flags' => [
                    'Elderly or diabetic patients may have a false negative due to reduced pain perception',
                    'A very tender abdomen with guarding suggests perforation rather than cholecystitis',
                ],
                'difficulty' => 'core',
                'last_reviewed' => '2026-06-02',
                'media_type' => 'video',
                'media_duration' => '2m 47s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $neurological->id, 'name' => 'Romberg sign'],
            [
                'eponym' => 'Moritz Romberg',
                'description' => 'Loss of balance when standing with feet together and eyes closed, indicating a proprioceptive deficit.',
                'interpretation' => 'A positive sign is a marked increase in unsteadiness or a fall with eyes closed, while the patient can stand steadily with eyes open. Mild sway is normal.',
                'technique' => 'Ask the patient to stand with feet together and arms by the sides. Allow them to stabilise with eyes open, then ask them to close their eyes. Stand close to support the patient for at least 20 seconds.',
                'diagnostic_relevance' => 'Distinguishes sensory ataxia from cerebellar ataxia. Sensory ataxia worsens with eye closure, cerebellar ataxia does not.',
                'red_flags' => [
                    'Bilateral vestibular failure produces a similar pattern',
                    'Falling in any direction suggests cerebellar disease rather than proprioceptive loss',
                ],
                'difficulty' => 'core',
                'last_reviewed' => '2026-05-09',
                'media_type' => 'video',
                'media_duration' => '3m 22s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $neurological->id, 'name' => 'Kernig sign'],
            [
                'eponym' => 'Vladimir Kernig',
                'description' => 'Resistance or pain on extending the knee with the hip flexed, indicating meningeal irritation.',
                'interpretation' => 'Positive when extension of the knee beyond 135 degrees produces resistance or pain in the posterior thigh. Sensitivity is low, so a negative sign does not exclude meningitis.',
                'technique' => 'With the patient supine, flex one hip and knee to 90 degrees. Attempt to extend the knee while maintaining hip flexion. Assess for resistance, pain, or hamstring spasm.',
                'diagnostic_relevance' => 'Supports a diagnosis of meningitis or subarachnoid haemorrhage. Combined with Brudzinski sign, increases clinical suspicion for meningitis.',
                'red_flags' => ['A positive sign in a febrile patient with headache requires urgent assessment for meningitis'],
                'difficulty' => 'core',
                'last_reviewed' => '2026-04-15',
                'media_type' => 'video',
                'media_duration' => '2m 14s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $musculoskeletal->id, 'name' => 'Bone point tenderness'],
            [
                'description' => 'Localised tenderness on palpation of a bony prominence, indicating possible fracture or periostitis.',
                'interpretation' => 'Tenderness over a single bony point with a relevant mechanism is an Ottawa criteria trigger for many extremity injuries and warrants imaging.',
                'technique' => 'Palpate the bony landmarks in a systematic sequence, comparing sides. Use firm but controlled pressure and observe the patient\'s facial response. Note exact location and reproducibility.',
                'diagnostic_relevance' => 'Identifies the site of suspected fracture when combined with mechanism of injury. Distinguishes soft tissue injury from bony injury.',
                'red_flags' => [
                    'Tenderness over the spinous process after trauma may indicate spinal injury and requires immobilisation',
                    'Tenderness over the tibial shaft in a runner may indicate a stress fracture',
                ],
                'difficulty' => 'core',
                'last_reviewed' => '2026-05-25',
                'media_type' => 'image',
                'media_duration' => '2m 30s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $endocrine->id, 'name' => 'Exophthalmos'],
            [
                'description' => 'Anterior protrusion of the eyeball beyond the orbital margin, visible as sclera showing above the iris.',
                'interpretation' => 'Best assessed from above and behind the seated patient, looking down over the forehead at the corneal-scleral relationship.',
                'technique' => 'Stand behind the seated patient and tilt their head back gently. Look down over the forehead to assess how far the cornea protrudes beyond the supraorbital margin.',
                'diagnostic_relevance' => 'Strongly associated with Graves disease. Unilateral protrusion raises concern for a retro-orbital mass.',
                'red_flags' => ['Rapid onset with visual loss or painful eye movements suggests optic nerve compression and requires urgent referral'],
                'difficulty' => 'intermediate',
                'last_reviewed' => '2026-05-01',
                'media_type' => 'image',
                'media_duration' => '1m 50s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $renal->id, 'name' => 'Ballotable kidney'],
            [
                'description' => 'A palpable, ballotable mass in the flank on bimanual palpation, suggesting renal enlargement.',
                'interpretation' => 'A positive balloting sign (the kidney is felt to float upward and tap the anterior hand) suggests hydronephrosis, polycystic kidneys, or a renal mass.',
                'technique' => 'Place one hand in the loin posteriorly and one hand anteriorly below the costal margin. Push the posterior hand forward in short flicks while feeling for the kidney with the anterior hand.',
                'diagnostic_relevance' => 'Bilateral ballotable kidneys suggest polycystic kidney disease. Unilateral enlargement suggests hydronephrosis or a renal tumour.',
                'red_flags' => ['A pulsatile flank mass may represent an aortic aneurysm rather than a renal mass'],
                'difficulty' => 'advanced',
                'last_reviewed' => '2026-04-10',
                'media_type' => 'video',
                'media_duration' => '2m 55s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $haematology->id, 'name' => 'Cervical lymphadenopathy'],
            [
                'description' => 'Palpable enlargement of cervical lymph nodes, classified by site, size, mobility and consistency.',
                'interpretation' => 'Nodes greater than 1 cm, hard, fixed, or non-tender are concerning for malignancy. Tender, mobile nodes suggest reactive or infective causes.',
                'technique' => 'Examine the patient seated, neck slightly flexed. Use the pads of the fingers to palpate all cervical chains in sequence: submental, submandibular, preauricular, postauricular, occipital, anterior and posterior cervical, supraclavicular.',
                'diagnostic_relevance' => 'Pattern of nodal involvement narrows the differential. Localised suggests local infection or malignancy, generalised suggests systemic infection, lymphoma, or HIV.',
                'red_flags' => ['A hard fixed supraclavicular node (Virchow node) in an older patient raises suspicion for abdominal malignancy'],
                'difficulty' => 'core',
                'last_reviewed' => '2026-06-18',
                'media_type' => 'video',
                'media_duration' => '5m 02s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $dermatology->id, 'name' => 'Spider naevi'],
            [
                'description' => 'A central arteriole with radiating fine vessels that blanch on pressure to the central point, found in the distribution of the superior vena cava.',
                'interpretation' => 'Up to a few spider naevi can be normal, particularly in pregnancy. Multiple lesions above the nipple line are more significant.',
                'technique' => 'Inspect the face, neck, and upper chest in good light. Press on the central punctum with a pen tip and release, watching for the radiating vessels to refill from the centre outward.',
                'diagnostic_relevance' => 'Multiple spider naevi suggest chronic liver disease due to reduced oestrogen clearance.',
                'red_flags' => ['New crops of spider naevi with jaundice and ascites suggest decompensated liver disease'],
                'difficulty' => 'core',
                'last_reviewed' => '2026-03-12',
                'media_type' => 'image',
                'media_duration' => '1m 20s',
            ]
        );

        ClinicalSign::updateOrCreate(
            ['clinical_system_id' => $ent->id, 'name' => 'Rinne and Weber tests'],
            [
                'eponym' => 'Heinrich Rinne, Friedrich Weber',
                'description' => 'Tuning fork tests comparing air and bone conduction to distinguish conductive from sensorineural hearing loss.',
                'interpretation' => 'Rinne positive (air conduction louder than bone) is normal. Weber lateralising to the affected ear suggests conductive loss; lateralising to the unaffected ear suggests sensorineural loss.',
                'technique' => 'For Rinne, strike a 512 Hz tuning fork and place the base on the mastoid process, then move it beside the ear canal, asking which is louder. For Weber, place the vibrating fork on the mid-forehead and ask where the sound is heard.',
                'diagnostic_relevance' => 'Distinguishes conductive causes such as wax or otitis media from sensorineural causes such as presbycusis or acoustic neuroma.',
                'red_flags' => ['Sudden unilateral sensorineural loss is an otological emergency requiring urgent assessment'],
                'difficulty' => 'intermediate',
                'last_reviewed' => '2026-02-20',
                'media_type' => 'video',
                'media_duration' => '3m 40s',
            ]
        );
    }
}
