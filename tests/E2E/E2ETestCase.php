<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
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

    protected function aceitarCookies(): void
    {
        $this->driver->get($this->baseUrl . '/login.php');

        $this->driver->wait(5)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::id('cookieConsent')
            )
        );

        $banner = $this->driver->findElement(
            WebDriverBy::id('cookieConsent')
        );

        if ($banner->isDisplayed()) {
            $this->driver->findElement(
                WebDriverBy::id('acceptCookies')
            )->click();
        }
    }

    protected function loginAdministrador(): void
    {
        $this->aceitarCookies();

        $this->driver->findElement(
            WebDriverBy::name('matricula')
        )->sendKeys('1234567890');

        $this->driver->findElement(
            WebDriverBy::name('senha')
        )->sendKeys('1234567890');

        $this->driver->findElement(
            WebDriverBy::cssSelector('button[type="submit"]')
        )->click();
    }

    protected function tearDown(): void
    {
        if (isset($this->driver)) {
            $this->driver->quit();
        }

        parent::tearDown();
    }
}
