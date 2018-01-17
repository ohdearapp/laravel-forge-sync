<?php

namespace OhDear\ForgeSync;

use Exception;
use Illuminate\Support\Collection;
use OhDear\PhpSdk\OhDear;
use OhDear\PhpSdk\Resources\Site as OhDearSite;
use Themsaid\Forge\Forge;
use Themsaid\Forge\Resources\Server;
use OhDear\ForgeSync\Model\Site;
use Themsaid\Forge\Resources\Site as ForgeSite;

class ForgeSync
{
    /** @var \Themsaid\Forge\Forge */
    protected $forge;

    /** @var \OhDear\PhpSdk\OhDear */
    protected $ohDear;

    /** @var integer */
    protected $ohDearTeamId;

    public function __construct(int $ohDearTeamId, string $ohDearApiToken = null, string $forgeApiToken = null)
    {
        $this->ohDearTeamId = $ohDearTeamId;

        $this->ohDear = new OhDear($ohDearApiToken ?? config('forge-sync.ohdear_api_token'));

        $this->forge = new Forge($forgeApiToken ?? config('forge-sync.forge_api_token'));
    }

    public function getSyncableSites(): Collection
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

    /**
     * @param string $url
     *
     * @return bool|string
     */
    public function registerSiteAtOhDear(string $url)
    {
        try {
            $this->ohDear->createSite(['url' => $url, 'team_id' => $this->ohDearTeamId]);

            return true;
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }


}
