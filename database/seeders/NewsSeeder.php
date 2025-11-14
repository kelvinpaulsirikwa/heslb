<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\Userstable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the first user to use as posted_by
        $user = Userstable::first();
        $postedBy = $user ? $user->id : 1;

        // Ensure news_images directory exists in storage
        Storage::disk('public')->makeDirectory('news_images');

        // Define allowed categories
        $categories = ['general news', 'successful stories'];
        
        $newsData = [
            [
                'title' => 'HESLB YAWASILISHA FEDHA ZA VIFAA VYA MAABARA SHULE YA SEKONDARI HASNUU MAKAME',
                'content' => 'Naibu Katibu Mkuu wa Wizara ya Elimu na Mafunzo ya Amali (WEMA), Dkt. Mwanakhamis Adam Ameir, ameongoza hafla ya makabidhiano ya TZS 10,000,000/= zilizotolewa na Bodi ya Mikopo ya Wanafunzi wa Elimu ya Juu (HESLB) kwa ajili ya ununuzi wa vifaa vya maabara katika Shule ya Sekondari Hasnuu Makame, Mkoa wa Kusini Unguja.

Akizungumza katika hafla hiyo iliyopambwa pia na burudani ya shairi mahususi la tukio hilo, Dkt. Mwanakhamis ameishukuru HESLB kwa msaada huo na kusisitiza umuhimu wa matumizi sahihi ya fedha hizo kwa manufaa ya wanafunzi na walimu.

“Naishukuru HESLB kwa msaada huu ambao utaenda kusaidia kupata vifaa vya maabara katika skuli hii ya Hasnuu Makame. Nasisitiza fedha hizi zitumike kwa uadilifu na umakini mkubwa ili kuhakikisha vifaa muhimu vinapatikana na wanafunzi wanapata fursa ya kujifunza kwa vitendo. Huu ni uwekezaji kwa wanafunzi … na taifa linawaangalia kama viongozi na wataalamu wa kesho,” amesema Dkt. Mwanakhamis.

Dkt. Mwanakhamis ameongeza kuwa Serikali inathamini ushirikiano wa taasisi kama HESLB na jamii katika kukuza elimu. “Tunatoa pongezi na shukrani za dhati kwa msaada huu wenye tija. Tunaomba HESLB iendelee kushirikiana na jamii katika kuhakikisha dhamira ya Serikali ya kuboresha elimu inatekelezwa ipasavyo,” amesema Dkt. Mwanakhamis.

Kwa upande wake, Mkurugenzi Mtendaji wa HESLB, Dkt. Bill Kiwia, amesema msaada huo umetokana na michango ya wadau mbalimbali wa elimu waliyoitoa kwenye maadhimisho ya miaka 20 ya HESLB, mwezi Februari mwaka huu.

“Kupitia michango ya wadau kwenye maadhimisho ya miaka 20 ya HESLB, tumeweza kutenga sehemu ya fedha kusaidia shule hii kwa ajili ya ununuzi wa vifaa vya maabara. Hii ni ishara ya dhamira yetu ya kushirikiana na jamii katika kuboresha elimu ya sekondari nchini,” amesema Dkt. Kiwia.

Naye Mkuu wa Shule ya Sekondari Hasnuu Makame, Mwl. Bakari Mohammed Ali, ameishukuru serikali kupitia HESLB kwa msaada huo ambao amesema utasaidia wanafunzi kupata mafunzo kwa vitendo katika masomo ya Sayansi.

Msaada huo ni sehemu ya utekelezaji wa ahadi ya HESLB iliyotolewa wakati wa maadhimisho ya miaka 20 tangu kuanzishwa kwake, ambapo shule mbili za sekondari, moja kutoka Tanzania Bara na nyingine kutoka Zanzibar zimenufaika.

Hafla hiyo imehudhuriwa na walimu, wanafunzi, viongozi wa Wizara ya Elimu na Mafunzo ya Amali Zanzibar pamoja na wadau wa elimu, ambapo imesisitizwa kuwa msaada huo utakuwa chachu ya kuboresha mazingira ya kujifunza kwa vitendo kwa wanafunzi wa Shule ya Hasnuu Makame.',
                'date_expire' => Carbon::now()->addMonths(6),
                'posted_by' => $postedBy,
                'front_image' => 'news_images/image1.png'
            ],
            [
                'title' => 'MKURUGENZI MTENDAJI HESLB AKABIDHI FEDHA ZA VIFAA VYA MAABARA SHULE YA SEKONDARI TEMEKE',
                'content' => 'Bodi ya Mikopo ya Wanafunzi wa Elimu ya Juu (HESLB) leo imekabidhi kiasi cha Shilingi Milioni kumi (TZS 10,000,000) kwa ajili ya ununuzi wa vifaa vya maabara katika Shule ya Sekondari Temeke, hatua ambayo inalenga kuboresha mazingira ya kujifunzia na kuinua kiwango cha ufaulu katika masomo ya sayansi.

Akizungumza katika hafla ya makabidhiano hayo, Mkurugenzi Mtendaji wa HESLB, Dkt. Bill Kiwia, amesema msaada huo ni sehemu ya utekelezaji wa ahadi iliyotolewa na taasisi hiyo wakati wa maadhimisho ya miaka 20 tangu kuanzishwa kwake ambapo kupitia michango ya wadau, HESLB imeweza kutenga sehemu ya fedha kwa ajili ya kusaidia shule mbili za sekondari, moja ya Tanzania Bara na nyingine ya Zanzibar, kwa kuwapatia msaada wa fedha kwa ajili ya vifaa vya maabara.

“Pamoja na kuwa tunatimiza ahadi tuliyoitoa, msaada huu pia ni sehemu ya jitihada za HESLB katika kuunga mkono maendeleo ya sekta ya elimu, hususan masomo ya Sayansi, Teknolojia, Uhandisi na Hisabati (STEM). Tunatambua kuwa taifa letu haliwezi kufikia uchumi wa kati wa viwanda bila kuwekeza katika elimu ya sayansi na teknolojia kuanzia ngazi za chini,” amesema Dkt. Kiwia.

Kwa upande wake, Mkuu wa Shule ya Sekondari Temeke, Mwalimu Ingia Madika Mtenga, ametoa shukrani kwa Serikali, HESLB na wadau wote wa elimu kwa mchango huo, akieleza kuwa msaada huo utakuwa chachu ya mabadiliko makubwa kwa wanafunzi kwa kuwawezesha kujifunza kwa vitendo kwa ufanisi mkubwa.

“Fedha hizi zitasaidia kufanya maboresho makubwa na kununua vifaa muhimu vya maabara, jambo litakalowasaidia wanafunzi wetu kujifunza kwa vitendo na kuongeza ufaulu katika masomo ya sayansi,” amesema Mwalimu Mtenga.

Akizungumzia vifaa vitakavyoongezwa katika maabara ya shule hiyo, Mwalimu Mtenga ameeleza kuwa wamepanga kununua vifaa vitakavyosaidia katika ufundishaji na kujifunza masomo ya Bailojia (Biology), Kemia (Chemistry) na Fizikia (Physics).

“Tunatambua jitihada za Serikali kupitia Bodi ya Mikopo kwa kuwawezesha wanafunzi katika elimu ya kati na ya juu. Fedha hizi tulizopokea leo zitasaidia kuongeza vifaa kama mashine za kuchuja maji na ‘models’ zinazoonyesha mfumo wa binadamu kwa maabara ya Baiolojia. Tunashukuru na tunaipongeza Serikali kupitia Bodi ya Mikopo, na tunamtakia kila heri Rais wa Jamhuri ya Muungano wa Tanzania kwa jitihada zake katika kuimarisha sekta ya elimu,” ameeleza Mwalimu Mtenga.

Katika hatua nyingine Mkurugenzi Mtendaji wa HESLB, amewasihi wanafunzi kusoma kwa juhudi na kuchangamkia fursa mbalimbali ambazo serikali inatoa kwao hasa kwa upande wa sayansi ikiwemo “Samia Scholarship”, ambayo ni mahsusi kwa ajili ya wanafunzi wenye ufaulu wa juu katika masomo sayansi kwenye mitihani ya kidato cha sita.

Makabidhiano hayo yameelezwa kuwa ni sehemu ya mkakati wa HESLB wa kushirikiana na wadau mbalimbali katika kuendeleza elimu nchini, ikiwa ni pamoja na kuchangia katika maboresho ya mazingira ya kujifunzia kwa wanafunzi wa shule za sekondari.

Wengine walioshuhudia hafla hiyo walikuwa Kaimu Mkurugenzi wa Manispaa ya Temeke, kaimu Mwenyekiti wa Bodi ya Shule, Mtendaji wa Kata ya Sandali ilipo shule hiyo ya Sekondari Temeke, menejimenti ya HESLB, walimu na wanafunzi wa Shule ya Sekondari Temeke.',
                'date_expire' => Carbon::now()->addMonths(4),
                'posted_by' => $postedBy,
                'front_image' => 'news_images/image2.png'
            ],
            [
                'title' => 'BARAZA LA WAFANYAKAZI HESLB LAKUTANA MOROGORO',
                'content' => 'Lajadili maboresho ya utendaji na utatuzi wa changamoto za wafanyakazi

Baraza la Wafanyakazi la Bodi ya Mikopo ya Wanafunzi wa Elimu ya Juu (HESLB) limekutana leo mjini Morogoro kujadili masuala mbalimbali yanayolenga kuboresha utendaji kazi wa taasisi hiyo, huku Menejimenti ikitoa majibu ya hoja mbalimbali zilizotolewa na wajumbe wa baraza hilo.

Akizungumza wakati wa kikao hicho, Mkurugenzi Mtendaji wa HESLB Dkt. Bill Kiwia ambaye ndiye Mwenyekiti wa Baraza hilo, amesema Baraza ni chombo muhimu kinachosaidia kuongeza uwajibikaji na ushirikiano kati ya Menejimenti na watumishi.

“Kupitia Baraza la Wafanyakazi tunapata mrejesho wa moja kwa moja kutoka kwa watumishi wetu. Hii inatupa fursa ya kutatua changamoto zao na kuweka mikakati ya kuongeza ufanisi,” amesema Dkt Kiwia.

Kwa upande wake, mmoja wa wajumbe wa baraza, Bi. Dorah Konga, amesema baraza limekuwa msaada mkubwa katika kuhakikisha sauti za watumishi zinasikika.

“Hii ni nafasi ya kipekee kwa sisi wafanyakazi kueleza changamoto zetu na kutoa mapendekezo. Tunashukuru kwa majibu na hatua zinazochukuliwa na Menejimenti,” amesema Bi. Dorah.

Naye Mwenyekiti wa Chama cha wafanyakazi wa Taasisi za Elimu ya Juu, Sayansi na Teknolojia, Habari, Ufundi Stadi na Utafiti (RAAWU), Tawi la HESLB, Bw. Daud Elisha amebainisha kuwa majadiliano ya kina kwenye Baraza la Wafanyakazi ni muhimu sana kwa kuwa yanagusa maslahi ya watumishi na kuboresha mazingira yao ya kazi.

“Maslahi na mazingira bora ya kazi ni kichocheo cha kuongeza ufanisi katika kazi za taasisi. Tumetoa maoni kadhaa ambayo Menejimenti imeahidi kuyafanyia kazi,” amesema Bw. Elisha.

Baraza la Wafanyakazi ni jukwaa muhimu la kiutendaji linalowawezesha watumishi kushiriki katika maamuzi ya kikazi. Kwa mujibu wa Menejimenti ya HESLB, chombo hicho kimeendelea kuwa nguzo ya mshikamano, uelewano na ari ya kufanya kazi kwa pamoja katika kutimiza majukumu ya kitaasisi.',
                'date_expire' => Carbon::now()->addMonths(8),
                'posted_by' => $postedBy,
                'front_image' => ''
            ],
                 ];

        // Create news entries
        foreach ($newsData as $newsItem) {
            // Handle image copy if specified
            if (!empty($newsItem['front_image'])) {
                $seedImagePath = database_path('seed-files/newsimage/' . basename($newsItem['front_image']));
                
                if (File::exists($seedImagePath)) {
                    // Copy to storage using Laravel Storage
                    $fileName = basename($newsItem['front_image']);
                    $storagePath = 'news_images/' . $fileName;
                    
                    if (!Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->put($storagePath, file_get_contents($seedImagePath));
                    }
                    
                    // Store only the relative path
                    $newsItem['front_image'] = $storagePath;
                } else {
                    $this->command->warn("Image not found: {$seedImagePath}");
                    $newsItem['front_image'] = null;
                }
            }
            
            // Set all news as general news
            $newsItem['category'] = 'general news';
            News::create($newsItem);
        }

        $this->command->info('Successfully created 50 news entries!');
    }
}