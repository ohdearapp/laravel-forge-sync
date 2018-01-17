<?php

namespace OhDear\ForgeSync;

use OhDear\PhpSdk\OhDear;
use Themsaid\Forge\Forge;
use Illuminate\Support\Collection;
use Themsaid\Forge\Resources\Server;
use OhDear\PhpSdk\Resources\Site as OhDearSite;
use Themsaid\Forge\Resources\Site as ForgeSite;

class ForgeSync
{
    /** @var \Themsaid\Forge\Forge */
    protected $forge;

    /** @var \OhDear\PhpSdk\OhDear */
    protected $ohDear;

    /** @var int */
    protected $ohDearTeamId;

    public function __construct(int $ohDearTeamId, string $ohDearApiToken = null, string $forgeApiToken = null)
    {
        $this->ohDearTeamId = $ohDearTeamId;

        $this->ohDear = new OhDear($ohDearApiToken ?? config('forge-sync.ohdear_api_token'));

        $this->forge = new Forge($forgeApiToken ?? config('forge-sync.forge_api_token'));
    }

    public function sites(): Collection
    {
        $ohDearSites = collect($this->ohDear->sites())->map(function (OhDearSite $ohDearSite) {
            return $ohDearSite->url;
        })->toArray();

        return collect($this->forge->servers())->flatMap(function (Server $server) {
            return collect($this->forge->sites($server->id))->filter(function (ForgeSite $forgeSite) {
                return $forgeSite->name !== 'default' && $forgeSite->name !== '';
            })->map(function ($forgeSite) {
                return new Site($this->forge->siteNginxFile($forgeSite->serverId, $forgeSite->id));
            });
        })
        ->filter(function (Site $site) {
            return $site->shouldBeMonitoredByOhDear();
        })
        ->filter(function (Site $site) use ($ohDearSites) {
            return in_array($site->url(), $ohDearSites) == false;
        });
    }

    public function addToOhDear(Site $site)
    {
        return $this->ohDear->createSite(['url' => $site->url(), 'team_id' => $this->ohDearTeamId]);
    }
}
