<?php

namespace Modules\Area\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Area\Entities\City;

class SeedCitiesTableSeeder extends Seeder
{
    private $records = [
        'HA' => ['en' => 'Hawalli', 'ar' => 'حولي',
            'states' =>
                [
                    ['ar' => 'أنجفة', 'en' => 'Anjafa'],
                    ['ar' => 'بيان', 'en' => 'Bayān'],
                    ['ar' => 'البدع', 'en' => 'Bi\'da'],
                    ['ar' => 'حولي', 'en' => 'Hawally'],
                    ['ar' => 'حطين', 'en' => 'Hittin'],
                    ['ar' => 'الجابرية', 'en' => 'Jabriya'],
                    ['ar' => 'ميدان حولي', 'en' => 'Maidan Hawalli'],
                    ['ar' => 'مشرف', 'en' => 'Mishrif'],
                    ['ar' => 'مبارك الجابر', 'en' => 'Mubarak Al-Jabir'],
                    ['ar' => 'النقرة', 'en' => 'Nigra'],
                    ['ar' => 'الرميثية', 'en' => 'Rumaithiya'],
                    ['ar' => 'سلام', 'en' => 'Salam'],
                    ['ar' => 'السالمية', 'en' => 'Salmiya'],
                    ['ar' => 'سلوى', 'en' => 'Salwa'],
                    ['ar' => 'الشعب', 'en' => 'Sha\'ab'],
                    ['ar' => 'الشهداء', 'en' => 'Shuhada'],
                    ['ar' => 'الصديق', 'en' => 'Siddiq'],
                    ['ar' => 'جنوب السرة', 'en' => 'South Surra'],
                    ['ar' => 'الزهراء', 'en' => 'Zahra'],
                ]
        ],
       'JA' => ['en' => 'Al Jahra', 'ar' => 'الجهراء',
            'states' =>
                [
                    ['ar' => 'العبدلي', 'en' => 'Abdali'],
                    ['ar' => 'النهضة / شرق الصليبخات', 'en' => 'Al Nahda / East Sulaibikhat'],
                    ['ar' => 'أمغرة', 'en' => 'Amghara'],
                    ['ar' => 'بر الجهراء', 'en' => 'Bar Jahra'],
                    ['ar' => 'الجهراء', 'en' => 'Jahra'],
                    ['ar' => 'الجهراء المنطقة الصناعية', 'en' => 'Jahra Industrial Area'],
                    ['ar' => 'كبد', 'en' => 'Kabad'],
                    ['ar' => 'النعيم', 'en' => 'Naeem'],
                    ['ar' => 'النسيم', 'en' => 'Nasseem'],
                    ['ar' => 'العيون', 'en' => 'Oyoun'],
                    ['ar' => 'القصر', 'en' => 'Qasr'],
                    ['ar' => 'مدينة سعد العبدالله', 'en' => 'Saad Al Abdullah City'],
                    ['ar' => 'السالمي', 'en' => 'Salmi'],
                    ['ar' => 'السكراب', 'en' => 'Sikrab'],
                    ['ar' => 'جنوب الدوحة / القيروان', 'en' => 'South Doha / Qairawān'],
                    ['ar' => 'الصبية', 'en' => 'Subiya'],
                    ['ar' => 'الصليبية', 'en' => 'Sulaibiya'],
                    ['ar' => 'الصليبية المنطقة الزراعية', 'en' => 'Sulaibiya Agricultural Area'],
                    ['ar' => 'تيماء', 'en' => 'Taima'],
                    ['ar' => 'الواحة', 'en' => 'Waha'],
                ]
        ],
       'AH' => ['en' => 'Al Ahmadi', 'ar' => 'الأحمدي',
            'states' =>
                [
                    ['ar' => 'أبو حليفة', 'en' => 'Abu Halifa'],
                    ['ar' => 'ميناء عبد الله', 'en' => 'Abdullah Port'],
                    ['ar' => 'الأحمدي', 'en' => 'Ahmadi'],
                    ['ar' => 'علي صباح السالم', 'en' => 'Ali As-Salim'],
                    ['ar' => 'العقيلة', 'en' => 'Aqila'],
                    ['ar' => 'بر الأحمدي', 'en' => 'Bar Al Ahmadi	'],
                    ['ar' => 'بنيدر', 'en' => 'Bneidar'],
                    ['ar' => 'بر الأحمدي', 'en' => 'Dhaher'],
                    ['ar' => 'الفحيحيل', 'en' => 'Fahaheel'],
                    ['ar' => 'فهد الأحمد', 'en' => 'Fahad Al-Ahmad'],
                    ['ar' => 'هدية', 'en' => 'Hadiya'],
                    ['ar' => 'جابر العلي', 'en' => 'Jaber Al-Ali'],
                    ['ar' => 'جواخير الوفرة', 'en' => 'Jawaher Al Wafra'],
                    ['ar' => 'الجليعة', 'en' => 'Jilei\'a'],
                    ['ar' => 'الخيران', 'en' => 'Khairan'],
                    ['ar' => 'المهبولة', 'en' => 'Mahbula'],
                    ['ar' => 'المنقف', 'en' => 'Mangaf'],
                    ['ar' => 'المقوع', 'en' => 'Miqwa'],
                    ['ar' => 'مدينة الخيران الجديدة', 'en' => 'New Khairan City'],
                    ['ar' => 'الوفرة الجديدة', 'en' => 'New Wafra'],
                    ['ar' => 'النويصيب', 'en' => 'Nuwaiseeb'],
                    ['ar' => 'الرقة', 'en' => 'Riqqa'],
                    ['ar' => 'مدينة صباح الأحمد', 'en' => 'Sabah Al-Ahmad City'],
                    ['ar' => 'مدينة صباح الأحمد البحرية', 'en' => 'Sabah Al Ahmad Sea City'],
                    ['ar' => 'الصباحية', 'en' => 'Sabahiya'],
                    ['ar' => 'الشعيبة', 'en' => 'Shu\'aiba (North & South)'],
                    ['ar' => 'جنوب الصباحية', 'en' => 'South Sabahiya'],
                    ['ar' => 'الوفرة', 'en' => 'Wafra'],
                    ['ar' => 'الزور', 'en' => 'Zoor'],
                    ['ar' => 'الظهر', 'en' => 'Zuhar'],
                ]
        ],
        'KU' => ['en' => 'Al Asimah', 'ar' => 'العاصمة',
            'states' =>
                [
                    ['ar' => 'ضاحية عبد الله السالم', 'en' => 'Abdulla Al-Salem'],
                    ['ar' => 'العديلية', 'en' => 'Adailiya'],
                    ['ar' => 'بنيد القار', 'en' => 'Bnaid Al-Qar'],
                    ['ar' => 'الدعية', 'en' => 'Daʿiya'],
                    ['ar' => 'الدسمة', 'en' => 'Dasma'],
                    ['ar' => 'الدوحة', 'en' => 'Doha'],
                    ['ar' => 'ميناء الدوحة', 'en' => 'Doha Port'],
                    ['ar' => 'الفيحاء', 'en' => 'Faiha'],
                    ['ar' => 'فيلكا', 'en' => 'Failaka'],
                    ['ar' => 'غرناطة', 'en' => 'Ghornata'],
                    ['ar' => 'مدينة جابر الأحمد', 'en' => 'Jaber Al-Ahmad City'],
                    ['ar' => 'جِبْلَة', 'en' => 'Jibla'],
                    ['ar' => 'كيفان', 'en' => 'Kaifan'],
                    ['ar' => 'الخالدية', 'en' => 'Khaldiya'],
                    ['ar' => 'المنصورية', 'en' => 'Mansūriya'],
                    ['ar' => 'المرقاب', 'en' => 'Mirgab'],
                    ['ar' => 'النهضة', 'en' => 'Nahdha'],
                    ['ar' => 'شمال غرب الصليبيخات', 'en' => 'North West Sulaibikhat'],
                    ['ar' => 'النزهة', 'en' => 'Nuzha'],
                    ['ar' => 'القادسية', 'en' => 'Qadsiya'],
                    ['ar' => 'قرطبة', 'en' => 'Qurtuba'],
                    ['ar' => 'الروضة', 'en' => 'Rawda'],
                    ['ar' => 'الشامية', 'en' => 'Shamiya'],
                    ['ar' => 'شرق', 'en' => 'Sharq'],
                    ['ar' => 'الشويخ', 'en' => 'Shuwaikh'],
                    ['ar' => 'الشويخ الصناعية', 'en' => 'Shuwaikh Industrial Area'],
                    ['ar' => 'ميناء الشويخ', 'en' => 'Shuwaikh Port'],
                    ['ar' => 'الصليبخات', 'en' => 'Sulaibikhat'],
                    ['ar' => 'السرة', 'en' => 'Surra'],
                    ['ar' => 'جزيرة ام النمل', 'en' => 'Umm an Namil Island'],
                    ['ar' => 'اليرموك', 'en' => 'Yarmouk'],
                ]
        ],
        'FA' => ['en' => 'Al Farwaniyah', 'ar' => 'الفروانية',
            'states' =>
                [
                    ['ar' => 'عبدالله المبارك', 'en' => 'Abdullah Al-Mubarak	'],
                    ['ar' => 'منطقة المطار', 'en' => 'Airport District'],
                    ['ar' => 'الأندلس', 'en' => 'Andalous'],
                    ['ar' => 'العارضية', 'en' => 'Ardiya'],
                    ['ar' => 'العارضية حرفية', 'en' => 'Ardiya Herafiya'],
                    ['ar' => 'العارضية المنطقة الصناعية', 'en' => 'Ardiya Industrial Area'],
                    ['ar' => 'اشبيلية', 'en' => 'Ashbelya'],
                    ['ar' => 'الضجيج', 'en' => 'Dhajeej'],
                    ['ar' => 'الفروانية', 'en' => 'Farwaniya'],
                    ['ar' => 'الفردوس', 'en' => 'Fordous'],
                    ['ar' => 'جليب الشيوخ', 'en' => 'Jleeb Al-Shuyoukh'],
                    ['ar' => 'خيطان', 'en' => 'Khaitan'],
                    ['ar' => 'العمرية', 'en' => 'Omariya'],
                    ['ar' => 'الرابية', 'en' => 'Rabiya'],
                    ['ar' => 'الري', 'en' => 'Rai'],
                    ['ar' => 'الرقعي', 'en' => 'Al-Riggae'],
                    ['ar' => 'الرحاب', 'en' => 'Rihab'],
                    ['ar' => 'صباح الناصر', 'en' => 'Sabah Al-Nasser'],
                    ['ar' => 'سباق الهجن', 'en' => 'Sabaq Al Hajan'],
                ]
        ],
        'MU' => ['en' => 'Mubarak Al-Kabeer', 'ar' => 'مبارك الكبير',
            'states' =>
                [
                    ['ar' => 'أبو الحصانية', 'en' => 'Abu Al Hasaniya'],
                    ['ar' => 'أبو فطيرة', 'en' => 'Abu Futaira'],
                    ['ar' => 'العدان', 'en' => 'Adān'],
                    ['ar' => 'القرين', 'en' => 'Al Qurain'],
                    ['ar' => 'القصور', 'en' => 'Al-Qusour'],
                    ['ar' => 'الفنطاس', 'en' => 'Fintās'],
                    ['ar' => 'الفنيطيس', 'en' => 'Funaitīs'],
                    ['ar' => 'المسيلة', 'en' => 'Misīla'],
                    ['ar' => 'مبارك الكبير', 'en' => 'Mubarak Al-Kabeer'],
                    ['ar' => 'السالم', 'en' => 'Sabah Al-Salem'],
                    ['ar' => 'صبحان', 'en' => 'Sabhān'],
                    ['ar' => 'جنوب وسطي', 'en' => 'South Wista'],
                    ['ar' => 'وسطي', 'en' => 'Wista'],
                ]
        ],
    ];

    /**
     * @param  int  $country_id
     */
    public function run($country_id = 1)
    {
        foreach ($this->records as $record) {
            $city = City::create(['title' => ['ar' => $record['ar'],'en' => $record['en']], 'status' => 1, 'country_id' => $country_id]);


            foreach ($record['states'] as $state) {
                (new SeedStatesTableSeeder())->run($state , $city);
            }
        }
    }
}
