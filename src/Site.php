<?php

namespace OhDear\ForgeSync;

class Site
{
    /** @var \OhDear\ForgeSync\NginxConfigFile */
    protected $nginxConfigFile;

    public function __construct(string $nginxConfileFileContent)
    {
        $this->nginxConfigFile = new NginxConfigFile($nginxConfileFileContent);
    }

    public function url(): string
    {
        return "{$this->nginxConfigFile->protocol()}://{$this->nginxConfigFile->siteName()}";
    }

    public function shouldBeMonitoredByOhDear(): bool
    {
        return $this->nginxConfigFile->shouldBeMonitoredByOhDear();
    }
}
