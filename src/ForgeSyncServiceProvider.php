<?php

namespace OhDear\ForgeSync;

use Illuminate\Support\ServiceProvider;

class ForgeSyncServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/forge-sync.php' => config_path('forge-sync.php'),
            ], 'config');

            $this->commands([
                SyncSitesCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/forge-sync.php', 'forge-sync');
    }
}
