<?php

namespace OhDear\ForgeSync\Tests;

use OhDear\ForgeSync\Site;

class SiteTest extends TestCase
{
    /** @var \OhDear\ForgeSync\Site */
    protected $httpsSite;

    /** @var \OhDear\ForgeSync\Site */
    protected $httpSite;

    public function setUp()
    {
        parent::setUp();

        $this->httpsSite = new Site($this->getStubContent('nginxConfigHttps.txt'));

        $this->httpSite = new Site($this->getStubContent('nginxConfigHttp.txt'));
    }

    /** @test */
    public function it_can_get_the_url()
    {
        $this->assertEquals('https://example.com', $this->httpsSite->url());

        $this->assertEquals('http://example.com', $this->httpSite->url());
    }
}
