<?php
namespace OhDear\ForgeSync\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getStubContent(string $fileName): string
    {
        $path = __DIR__ . "/stubs/{$fileName}";

        return file_get_contents($path);
    }
}