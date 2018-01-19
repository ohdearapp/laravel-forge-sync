<?php

namespace OhDear\ForgeSync;

use Exception;
use OhDear\PhpSdk\OhDear;
use Illuminate\Console\Command;

class SyncSitesCommand extends Command
{
    /** @var \OhDear\ForgeSync\ForgeSync */
    protected $sync;

    /** @var \Illuminate\Support\Collection */
    protected $availableTeams;

    /** @var \Illuminate\Support\Collection */
    protected $syncableSites;

    protected $signature = 'ohdear:forge-sync {--ohDearKey=} {--forgeKey=} {--dry-run}';

    protected $description = 'Sync existing Laravel Forge Sites to Oh Dear!';

    public function handle()
    {
        $ohDearTeamId = $this->askOhDearTeamId();

        $this->info('Scan your Forge sites... (this could need a little bit of time).');

        $this->sync = new ForgeSync($ohDearTeamId, $this->option('ohDearKey'), $this->option('forgeKey'));

        $this->syncableSites = $this->sync->sites();

        if ($this->syncableSites->count() === 0) {
            $this->warn("You don't have any sites that can be synced!");

            return;
        }
        if ($this->option('dry-run')) {
            $this->warn("Running in dry-mode: we make any changes to your Oh Dear! account.");
        }

        $choice = $this->choice('Which Forge sites should be synced with Oh Dear?', $this->siteChoices());

        $this->syncSites($choice);

        $this->info('All done');
    }

    protected function askOhDearTeamId(): int
    {
        $choice = $this->choice(
            'Please select the Oh Dear! team that should be synced with your forge account.',
            $this->teamChoices()
        );

        return $this->availableTeams->filter(function ($team) use ($choice) {
            return "<comment>{$team['name']}</comment>" === $choice;
        })->first()['id'];
    }

    protected function syncSites(string $siteChoice)
    {
        if (str_is('<comment>All Sites</comment>', $siteChoice)) {
            $this->info('Syncing all sites...');

            $this->syncableSites
                ->filter(function (Site $site) use ($siteChoice) {
                    if ($siteChoice === '<comment>All Sites</comment>') {
                        return true;
                    }

                    $url = str_replace(['<comment>', '</comment>'], '', $siteChoice);

                    return $site->url() === $url;
                })
                ->each(function (Site $site) {
                    if ($this->option('dry-run')) {
                        $this->comment("Would have added site `{$site->url()}` if not running in dry-mode.");

                        return;
                    }

                    try {
                        $this->sync->addToOhDear($site);

                        $this->comment("Added site `{$site->url()}`");
                    } catch (Exception $exception) {
                        $this->error("Could not add site `{$site->url()}` because {$exception->getMessage()}");
                    }
                });
        }
    }

    protected function teamChoices(): array
    {
        $ohDear = new OhDear($this->option('ohDearKey') ?? config('forge-sync.ohdear_api_token'));

        $this->availableTeams = collect($ohDear->me()->teams['data']->attributes);

        return $this->availableTeams->map(function ($team) {
            return "<comment>{$team['name']}</comment>";
        })->toArray();
    }

    protected function siteChoices(): array
    {
        return $this->syncableSites->map(function (Site $site) {
            return "<comment>{$site->url()}</comment>";
        })->merge(['<comment>All Sites</comment>'])->toArray();
    }
}
