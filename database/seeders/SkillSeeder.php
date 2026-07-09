<?php

namespace Database\Seeders;

use App\Models\ClinicalSystem;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cardiovascular = ClinicalSystem::where('name', 'Cardiovascular System')->firstOrFail();
        $respiratory = ClinicalSystem::where('name', 'Respiratory System')->firstOrFail();
        $gastrointestinal = ClinicalSystem::where('name', 'Gastrointestinal System')->firstOrFail();
        $neurological = ClinicalSystem::where('name', 'Neurological System')->firstOrFail();

        Skill::updateOrCreate(
            ['clinical_system_id' => $cardiovascular->id, 'name' => 'Cardiovascular examination'],
            [
                'description' => 'Structured approach to examining the cardiovascular system.',
                'est_minutes' => 14,
                'equipment' => ['Stethoscope', 'Penlight', 'Sphygmomanometer'],
                'competency_codes' => ['1.7.A'],
                'procedure_steps' => [
                    ['title' => 'Introduce and consent', 'detail' => 'Greet the patient by name, confirm identity, explain the examination and obtain verbal consent. Position the patient at 45 degrees with the chest exposed.', 'caution' => 'Maintain dignity with a gown or drape.'],
                    ['title' => 'General inspection', 'detail' => 'Observe from the end of the bed for breathlessness, cyanosis, facial features of syndromic disease, and obvious scars or devices.'],
                    ['title' => 'Peripheral hands and arms', 'detail' => 'Inspect the hands for clubbing, splinter haemorrhages, Osler nodes and Janeway lesions. Assess the radial pulse, then brachial, assessing rate, rhythm, character and volume.'],
                    ['title' => 'Facial and neck assessment', 'detail' => 'Examine the conjunctivae for pallor, the mouth for central cyanosis, and assess the JVP at 45 degrees. Feel the carotid pulse for character.'],
                    ['title' => 'Anterior chest inspection and palpation', 'detail' => 'Inspect for scars, visible impulses and deformities. Palpate the apex beat and any thrills in the five key areas.', 'caution' => 'Identify displacements and parasternal heave.'],
                    ['title' => 'Auscultation', 'detail' => 'Listen with the diaphragm then the bell in the aortic, pulmonary, tricuspid and mitral areas. Time each sound to the carotid pulse. Listen in expiration for murmurs and roll the patient left lateral for the mitral area.'],
                    ['title' => 'Back and legs', 'detail' => 'Listen to the lung bases for crackles. Examine the sacrum and ankles for pitting oedema. Check peripheral pulses including popliteal, posterior tibial and dorsalis pedis.'],
                    ['title' => 'Complete the examination', 'detail' => 'Thank the patient, allow them to dress, and summarise. State you would check blood pressure, perform an ECG, dipstick the urine and request relevant imaging.', 'caution' => 'Never forget to mention blood pressure measurement.'],
                ],
            ]
        );

        Skill::updateOrCreate(
            ['clinical_system_id' => $respiratory->id, 'name' => 'Respiratory examination'],
            [
                'description' => 'Structured approach to examining the respiratory system.',
                'est_minutes' => 12,
                'equipment' => ['Stethoscope'],
                'competency_codes' => ['1.6.A'],
                'procedure_steps' => [
                    ['title' => 'Introduce and consent', 'detail' => 'Greet, confirm identity, explain and consent. Position the patient at 45 degrees with the chest exposed to the waist.'],
                    ['title' => 'End of bed inspection', 'detail' => 'Look for breathlessness, use of accessory muscles, cyanosis, cachexia, and adjuncts such as oxygen or inhalers.'],
                    ['title' => 'Hands and face', 'detail' => 'Inspect for clubbing, tar staining, peripheral cyanosis. Assess the pulse and respiratory rate over 60 seconds. Examine the conjunctivae and oral cavity.'],
                    ['title' => 'Neck', 'detail' => 'Examine the JVP for elevation and palpate the cervical and supraclavicular lymph nodes.'],
                    ['title' => 'Anterior chest inspection', 'detail' => 'Inspect for scars, symmetrical movement, deformities such as pectus excavatum, and work of breathing.'],
                    ['title' => 'Chest expansion and palpation', 'detail' => 'Assess symmetrical chest expansion. Feel for tactile vocal fremitus or vocal resonance.'],
                    ['title' => 'Percussion and auscultation', 'detail' => 'Percuss side to side, comparing zones. Auscultate with the diaphragm in the same sequence, assessing breath sounds, added sounds and vocal resonance.'],
                    ['title' => 'Posterior chest', 'detail' => 'Sit the patient forward. Repeat inspection, expansion, percussion and auscultation on the back. Examine the sacrum for oedema.'],
                    ['title' => 'Complete the examination', 'detail' => 'Thank the patient, help them dress, and state you would check oxygen saturation, perform peak flow, and request a chest X-ray.', 'caution' => 'Always state oxygen saturation as a bedside observation.'],
                ],
            ]
        );

        Skill::updateOrCreate(
            ['clinical_system_id' => $gastrointestinal->id, 'name' => 'Abdominal examination'],
            [
                'description' => 'Structured approach to examining the abdomen.',
                'est_minutes' => 13,
                'equipment' => ['Stethoscope'],
                'competency_codes' => ['1.8.A'],
                'procedure_steps' => [
                    ['title' => 'Introduce and consent', 'detail' => 'Greet, confirm identity, explain and consent. Position the patient flat with one pillow under the head and arms by the sides, exposed from xiphisternum to symphysis pubis.'],
                    ['title' => 'General inspection', 'detail' => 'Look from the end of the bed for cachexia, distension, scars, visible masses, and stoma sites.'],
                    ['title' => 'Hands and face', 'detail' => 'Examine the hands for clubbing, palmar erythema, Dupuytren contracture, and liver flap. Inspect the eyes for scleral icterus and the mouth for ulcers or angular stomatitis.'],
                    ['title' => 'Neck and chest', 'detail' => 'Examine cervical lymph nodes. In men check for gynaecomastia, in both sexes check for spider naevi on the upper chest.', 'caution' => 'Spider naevi above the nipple line suggest chronic liver disease.'],
                    ['title' => 'Superficial palpation', 'detail' => 'Kneel beside the patient. Palpate all nine regions gently, watching the patient\'s face. Note tenderness, guarding and rigidity.', 'caution' => 'Examine any tender area last.'],
                    ['title' => 'Deep palpation', 'detail' => 'Palpate more deeply for masses and organomegaly. Feel for the liver edge, spleen, and kidneys using bimanual technique.'],
                    ['title' => 'Percussion and auscultation', 'detail' => 'Percuss the liver span, spleen and any masses. Auscultate bowel sounds in the right iliac fossa and listen for bruits over the aorta and renal arteries.'],
                    ['title' => 'Complete the examination', 'detail' => 'Thank the patient, help them dress. State you would examine the hernial orifices, genitalia, perform a rectal examination, and check the urine.', 'caution' => 'Always mention examining hernial orifices and performing a rectal examination as completion steps.'],
                ],
            ]
        );

        Skill::updateOrCreate(
            ['clinical_system_id' => $neurological->id, 'name' => 'Cranial nerve examination'],
            [
                'description' => 'Examines all twelve cranial nerves accurately and efficiently.',
                'est_minutes' => 18,
                'equipment' => ['Penlight', 'Snellen chart', 'Tuning fork 512 Hz', 'Cotton wool', 'Tongue depressor'],
                'competency_codes' => ['1.5.A'],
                'procedure_steps' => [
                    ['title' => 'Introduce and consent', 'detail' => 'Greet, confirm identity, explain and consent. Position the patient seated, facing you at eye level.'],
                    ['title' => 'Olfactory (I)', 'detail' => 'Test each nostril separately with a non-irritant smell such as coffee or clove oil, asking the patient to identify the smell.'],
                    ['title' => 'Optic (II)', 'detail' => 'Test visual acuity with a Snellen chart, fields by confrontation, and examine the fundi with an ophthalmoscope.'],
                    ['title' => 'Oculomotor, trochlear, abducens (III, IV, VI)', 'detail' => 'Inspect for ptosis, test the pupillary light and accommodation reflexes, and assess eye movements in the six cardinal directions.', 'caution' => 'Ask about diplopia at each position.'],
                    ['title' => 'Trigeminal (V)', 'detail' => 'Test facial sensation in all three divisions, the corneal reflex, and the muscles of mastication by asking the patient to clench the jaw.'],
                    ['title' => 'Facial (VII)', 'detail' => 'Inspect for facial asymmetry at rest. Test forehead wrinkling, eye closure, cheek puffing, and smiling.', 'caution' => 'Lower motor neuron lesions affect the whole hemiface, upper motor neuron lesions spare the forehead.'],
                    ['title' => 'Vestibulocochlear (VIII)', 'detail' => 'Test hearing in each ear using a whispered voice. Perform Rinne and Weber tests with a 512 Hz tuning fork to distinguish conductive from sensorineural loss.'],
                    ['title' => 'Glossopharyngeal and vagus (IX, X)', 'detail' => 'Listen to the voice for hoarseness. Ask the patient to say aah, and inspect the uvula for deviation. Test the gag reflex if indicated.'],
                    ['title' => 'Accessory (XI)', 'detail' => 'Test sternocleidomastoid by asking the patient to turn the head against resistance, and trapezius by shoulder shrug.'],
                    ['title' => 'Hypoglossal (XII)', 'detail' => 'Inspect the tongue for wasting and fasciculations at rest in the mouth. Ask the patient to protrude the tongue and look for deviation.', 'caution' => 'The tongue deviates towards the side of a lower motor neuron lesion.'],
                ],
            ]
        );
    }
}
