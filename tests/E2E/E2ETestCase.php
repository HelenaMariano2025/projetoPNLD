<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;

abstract class E2ETestCase extends TestCase
{
    protected RemoteWebDriver $driver;

    protected string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $seleniumUrl = getenv('SELENIUM_URL') ?: 'http://localhost:4444';

        $this->baseUrl = getenv('BASE_URL') ?: 'http://localhost:8000';

        $capabilities = DesiredCapabilities::chrome();

        $capabilities->setCapability('goog:chromeOptions', [
            'args' => [
                '--no-sandbox',
                '--disable-dev-shm-usage',
                '--window-size=1366,768',
            ],
        ]);

        $this->driver = RemoteWebDriver::create(
            $seleniumUrl,
            $capabilities,
            10000,
            120000
        );
    }

    protected function tearDown(): void
    {
        if (isset($this->driver)) {
            $this->driver->quit();
        }

        parent::tearDown();
    }
}