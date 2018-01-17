<?php

namespace OhDear\ForgeSync;

class NginxConfigFile
{
    /** @var string */
    protected $configContent;

    public function __construct(string $configContent)
    {
        $this->configContent = $configContent;
    }

    public function configContent(): string
    {
        return $this->configContent;
    }

    public function siteName(): string
    {
        preg_match('/.?(server_name)\s([a-zA-Z._-]*).?/', $this->configContent, $matches);

        return last($matches);
    }

    public function serverPort(): ?string
    {
        preg_match("/.?(listen)\s([0-9a-zA-Z.]*).?/s", $this->configContent, $ssl_matches);

        return last($ssl_matches);
    }

    public function protocol(): string
    {
        return $this->serverPort() === "443"
            ? 'https'
            : 'http';
    }

    public function shouldBeMonitoredByOhDear(): bool
    {
        return ! str_contains($this->configContent, '#OH-DEAR-DO-NOT-MONITOR');
    }
}