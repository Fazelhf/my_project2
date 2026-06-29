<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;

class LocalizeFlags extends Command
{
    protected $signature   = 'flags:localize';
    protected $description = 'Update all team flag_urls to local /flags/{Country Name}.png';

    // Maps team name in DB → actual filename in public/flags/
    private array $nameToFile = [
        'USA'                    => 'United States.png',
        'United States'          => 'United States.png',
        'Mexico'                 => 'Mexico.png',
        'Canada'                 => 'Canada.png',
        'Argentina'              => 'Argentina.png',
        'Brazil'                 => 'Brazil.png',
        'France'                 => 'France.png',
        'Germany'                => 'Germany.png',
        'Spain'                  => 'Spain.png',
        'Portugal'               => 'Portugal.png',
        'England'                => 'England.png',
        'Netherlands'            => 'Netherlands.png',
        'Belgium'                => 'Belgium.png',
        'Uruguay'                => 'Uruguay.png',
        'Colombia'               => 'Colombia.png',
        'Ecuador'                => 'Ecuador.png',
        'Paraguay'               => 'Paraguay.png',
        'Panama'                 => 'Panama.png',
        'Haiti'                  => 'Haiti.png',
        'Curaçao'                => 'Curaçao.png',
        'Morocco'                => 'Morocco.png',
        'Senegal'                => 'Senegal.png',
        'Ghana'                  => 'Ghana.png',
        'Egypt'                  => 'Egypt.png',
        'Tunisia'                => 'Tunisia.png',
        'Algeria'                => 'Algeria.png',
        'DR Congo'               => 'DR Congo.png',
        'South Africa'           => 'South Africa.png',
        'Cape Verde'             => 'Cape Verde.png',
        'Japan'                  => 'Japan.png',
        'South Korea'            => 'South Korea.png',
        'Australia'              => 'Australia.png',
        'Saudi Arabia'           => 'Saudi Arabia.png',
        'Iran'                   => 'Iran.png',
        'Qatar'                  => 'Qatar.png',
        'Uzbekistan'             => 'Uzbekistan.png',
        'Jordan'                 => 'Jordan.png',
        'New Zealand'            => 'New Zealand.png',
        'Croatia'                => 'Croatia.png',
        'Switzerland'            => 'Switzerland.png',
        'Norway'                 => 'Norway.png',
        'Sweden'                 => 'Sweden.png',
        'Scotland'               => 'Scotland.png',
        'Austria'                => 'Austria.png',
        'Turkey'                 => 'Turkiye.png',
        'Czech Republic'         => 'Czechia.png',
        'Bosnia and Herzegovina' => 'Bosnia and Herzegovina.png',
    ];

    public function handle(): int
    {
        $updated = 0;
        $skipped = 0;

        Team::all()->each(function (Team $team) use (&$updated, &$skipped) {
            $file = $this->nameToFile[$team->name] ?? null;
            if (! $file) {
                $this->line("  No file mapping for: {$team->name}");
                $skipped++;
                return;
            }

            $localUrl = '/flags/' . rawurlencode($file);
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
