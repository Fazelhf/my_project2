<?php

use App\Models\Team;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const FLAG_MAP = [
        'Qatar'               => 'qa',
        'Ecuador'             => 'ec',
        'Senegal'             => 'sn',
        'Netherlands'         => 'nl',
        'England'             => 'gb-eng',
        'Iran'                => 'ir',
        'USA'                 => 'us',
        'United States'       => 'us',
        'Wales'               => 'gb-wls',
        'Argentina'           => 'ar',
        'Saudi Arabia'        => 'sa',
        'Mexico'              => 'mx',
        'Poland'              => 'pl',
        'France'              => 'fr',
        'Australia'           => 'au',
        'Denmark'             => 'dk',
        'Tunisia'             => 'tn',
        'Spain'               => 'es',
        'Costa Rica'          => 'cr',
        'Germany'             => 'de',
        'Japan'               => 'jp',
        'Belgium'             => 'be',
        'Canada'              => 'ca',
        'Morocco'             => 'ma',
        'Croatia'             => 'hr',
        'Brazil'              => 'br',
        'Serbia'              => 'rs',
        'Switzerland'         => 'ch',
        'Cameroon'            => 'cm',
        'Portugal'            => 'pt',
        'Ghana'               => 'gh',
        'Uruguay'             => 'uy',
        'South Korea'         => 'kr',
        'South Africa'        => 'za',
        'Czech Republic'      => 'cz',
        'Czechia'             => 'cz',
        'Bosnia and Herzegovina' => 'ba',
        'Slovakia'            => 'sk',
        'Hungary'             => 'hu',
        'Scotland'            => 'gb-sct',
        'Turkey'              => 'tr',
        'Colombia'            => 'co',
        'Chile'               => 'cl',
        'Peru'                => 'pe',
        'Venezuela'           => 've',
        'Paraguay'            => 'py',
        'Bolivia'             => 'bo',
        'Panama'              => 'pa',
        'Honduras'            => 'hn',
        'Jamaica'             => 'jm',
        'Trinidad and Tobago' => 'tt',
        'Nigeria'             => 'ng',
        'Ivory Coast'         => 'ci',
        'DR Congo'            => 'cd',
        'Algeria'             => 'dz',
        'Egypt'               => 'eg',
        'Namibia'             => 'na',
        'Tanzania'            => 'tz',
        'Mozambique'          => 'mz',
        'Zambia'              => 'zm',
        'Angola'              => 'ao',
        'Rwanda'              => 'rw',
        'Comoros'             => 'km',
        'Benin'               => 'bj',
        'Ukraine'             => 'ua',
        'Austria'             => 'at',
        'Greece'              => 'gr',
        'Romania'             => 'ro',
        'Albania'             => 'al',
        'Georgia'             => 'ge',
        'Slovenia'            => 'si',
        'North Macedonia'     => 'mk',
        'Kosovo'              => 'xk',
        'New Zealand'         => 'nz',
        'Cape Verde'          => 'cv',
        'Iraq'                => 'iq',
        'Jordan'              => 'jo',
        'Uzbekistan'          => 'uz',
        'China'               => 'cn',
        'Indonesia'           => 'id',
        'Philippines'         => 'ph',
        'Thailand'            => 'th',
        'Vietnam'             => 'vn',
        'Myanmar'             => 'mm',
        'Kyrgyzstan'          => 'kg',
        'Tajikistan'          => 'tj',
        'Kuwait'              => 'kw',
        'Bahrain'             => 'bh',
        'Oman'                => 'om',
        'UAE'                 => 'ae',
        'United Arab Emirates' => 'ae',
        'Lebanon'             => 'lb',
        'Syria'               => 'sy',
        'Pakistan'            => 'pk',
        'India'               => 'in',
        'Israel'              => 'il',
        'Palestine'           => 'ps',
    ];

    public function up(): void
    {
        foreach (Team::all() as $team) {
            $iso = self::FLAG_MAP[$team->name] ?? null;
            if ($iso) {
                $team->update([
                    'flag_url' => "https://flagcdn.com/w40/{$iso}.png",
                ]);
            }
        }
    }

    public function down(): void
    {
        Team::query()->update(['flag_url' => null]);
    }
};
