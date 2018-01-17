<?php

namespace OhDear\ForgeSync\Tests;

use OhDear\ForgeSync\NginxConfigFile;

class NginxConfigFileTest extends TestCase
{

    /** @var \OhDear\ForgeSync\NginxConfigFile */
    protected $nginxConfigFile;

    public function setUp()
    {
        parent::setUp();

        $this->nginxConfigFile = new NginxConfigFile($this->getStubContent('nginxConfigHttps.txt'));
    }

    /** @test */
    public function it_can_get_the_server_name()
    {
        $this->assertEquals('example.com', $this->nginxConfigFile->siteName());
    }

    /** @test */
    public function it_can_get_the_server_port()
    {
        $this->assertEquals('443', $this->nginxConfigFile->serverPort());
    }

    /** @test */
    public function if_can_detect_if_the_site_should_be_synced_with_ohdear()
    {
        $this->assertTrue($this->nginxConfigFile->shouldBeMonitoredByOhDear());

        $nginxConfileFile = new NginxConfigFile($this->getStubContent('nginxConfigDoNotMonitor.txt'));

        $this->assertFalse($nginxConfileFile->shouldBeMonitoredByOhDear());
    }


}
