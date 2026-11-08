<?php

declare(strict_types=1);

namespace Tests\E2E;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class NavegadorTestCase extends TestCase
{
    /** Administrador criado por sql/bancoPNLD.sql */
    protected const ADMIN_MATRICULA = '1234567890';
    protected const ADMIN_SENHA = '1234567890';

    protected static RemoteWebDriver $driver;

    public static function setUpBeforeClass(): void
    {
        $opcoes = new ChromeOptions();
        $opcoes->addArguments([
            '--headless=new',
            '--no-sandbox',
            '--window-size=1366,900',
            '--lang=pt-BR',
        ]);

        $capacidades = DesiredCapabilities::chrome();
        $capacidades->setCapability('goog:chromeOptions', $opcoes);

        self::$driver = RemoteWebDriver::create(
            getenv('SELENIUM_URL') ?: 'http://selenium:4444',
            $capacidades
        );
    }

    public static function tearDownAfterClass(): void
    {
        self::$driver->quit();
    }

    protected function setUp(): void
    {
        // Cada teste começa sem sessão
        self::$driver->get($this->url('/login.php'));
        self::$driver->manage()->deleteAllCookies();
    }

    protected function onNotSuccessfulTest(Throwable $t): never
    {
        try {
            $this->capturar('FALHA_' . $this->name());
        } catch (Throwable) {
            // não esconder a falha original
        }

        throw $t;
    }

    protected function url(string $caminho): string
    {
        return rtrim(getenv('E2E_BASE_URL') ?: 'http://app:8000', '/') . $caminho;
    }

    protected function abrir(string $caminho): void
    {
        self::$driver->get($this->url($caminho));
    }

    protected function preencher(string $campo, string $valor): void
    {
        $elemento = self::$driver->findElement(WebDriverBy::name($campo));
        $elemento->clear();
        $elemento->sendKeys($valor);
    }

    protected function clicarEnviar(): void
    {
        self::$driver
            ->findElement(WebDriverBy::cssSelector('form button[type=submit]'))
            ->click();
    }

    protected function esperarTexto(string $texto, int $segundos = 8): void
    {
        self::$driver->wait($segundos)->until(
            WebDriverExpectedCondition::textToBePresentInElement(
                WebDriverBy::tagName('body'),
                $texto
            ),
            "Texto não encontrado na página: {$texto}"
        );
    }

    protected function esperarUrlContendo(string $trecho, int $segundos = 8): void
    {
        self::$driver->wait($segundos)->until(
            WebDriverExpectedCondition::urlContains($trecho),
            "A URL não contém: {$trecho}"
        );
    }

    protected function autenticar(
        string $matricula = self::ADMIN_MATRICULA,
        string $senha = self::ADMIN_SENHA
    ): void {
        $this->abrir('/login.php');
        $this->preencher('matricula', $matricula);
        $this->preencher('senha', $senha);
        $this->clicarEnviar();
        $this->esperarUrlContendo('funcoes.php');
    }

    /** Salva screenshot em tests-e2e/evidencias/ para usar no relatório. */
    protected function capturar(string $nome): void
    {
        $pasta = __DIR__ . '/evidencias';
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $arquivo = preg_replace('/[^A-Za-z0-9_-]+/', '_', $nome);
        self::$driver->takeScreenshot("{$pasta}/{$arquivo}.png");
    }
}