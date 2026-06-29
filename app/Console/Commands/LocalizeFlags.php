<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;

class LocalizeFlags extends Command
{
    protected $signature   = 'flags:localize';
    protected $description = 'Update all team flag_urls from CDN to local /flags/{iso}.png';

    private array $nameToIso = [
        'USA'                    => 'us',
        'United States'          => 'us',
        'Mexico'                 => 'mx',
        'Canada'                 => 'ca',
        'Argentina'              => 'ar',
        'Brazil'                 => 'br',
        'France'                 => 'fr',
        'Germany'                => 'de',
        'Spain'                  => 'es',
        'Portugal'               => 'pt',
        'England'                => 'gb-eng',
        'Netherlands'            => 'nl',
        'Belgium'                => 'be',
        'Uruguay'                => 'uy',
        'Colombia'               => 'co',
        'Ecuador'                => 'ec',
        'Peru'                   => 'pe',
        'Chile'                  => 'cl',
        'Venezuela'              => 've',
        'Bolivia'                => 'bo',
        'Paraguay'               => 'py',
        'Panama'                 => 'pa',
        'Costa Rica'             => 'cr',
        'Honduras'               => 'hn',
        'El Salvador'            => 'sv',
        'Jamaica'                => 'jm',
        'Haiti'                  => 'ht',
        'Trinidad and Tobago'    => 'tt',
        'Curaçao'                => 'cw',
        'Guatemala'              => 'gt',
        'Morocco'                => 'ma',
        'Senegal'                => 'sn',
        'Nigeria'                => 'ng',
        'Cameroon'               => 'cm',
        'Ghana'                  => 'gh',
        'Egypt'                  => 'eg',
        'Tunisia'                => 'tn',
        'Algeria'                => 'dz',
        'DR Congo'               => 'cd',
        'South Africa'           => 'za',
        'Cape Verde'             => 'cv',
        'Mali'                   => 'ml',
        'Japan'                  => 'jp',
        'South Korea'            => 'kr',
        'Australia'              => 'au',
        'Saudi Arabia'           => 'sa',
        'Iran'                   => 'ir',
        'Qatar'                  => 'qa',
        'China'                  => 'cn',
        'Indonesia'              => 'id',
        'Uzbekistan'             => 'uz',
        'Jordan'                 => 'jo',
        'New Zealand'            => 'nz',
        'Serbia'                 => 'rs',
        'Croatia'                => 'hr',
        'Poland'                 => 'pl',
        'Switzerland'            => 'ch',
        'Denmark'                => 'dk',
        'Austria'                => 'at',
        'Sweden'                 => 'se',
        'Norway'                 => 'no',
        'Scotland'               => 'gb-sct',
        'Albania'                => 'al',
        'Ukraine'                => 'ua',
        'Romania'                => 'ro',
        'Hungary'                => 'hu',
        'Slovakia'               => 'sk',
        'Slovenia'               => 'si',
        'Greece'                 => 'gr',
        'Turkey'                 => 'tr',
        'Czech Republic'         => 'cz',
        'Bosnia and Herzegovina' => 'ba',
        'North Macedonia'        => 'mk',
        'Montenegro'             => 'me',
        'Kosovo'                 => 'xk',
        'Israel'                 => 'il',
    ];

    public function handle(): int
    {
        $updated = 0;
        $skipped = 0;

        Team::all()->each(function (Team $team) use (&$updated, &$skipped) {
            $iso = $this->nameToIso[$team->name] ?? null;
            if (! $iso) {
                $this->line("  No ISO mapping for: {$team->name}");
                $skipped++;
                return;
            }

            $localUrl = "/flags/{$iso}.png";
            if ($team->flag_url === $localUrl) {
                $skipped++;
                return;
            }

            $team->update(['flag_url' => $localUrl]);
            $this->line("  Updated: {$team->name} → {$localUrl}");
            $updated++;
        });

        $this->info("Done. Updated: {$updated}, Skipped: {$skipped}");
        return 0;
    }
}
