<?php

declare(strict_types=1);

namespace Tests\E2E;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Exception\TimeoutException;
use Throwable;

abstract class NavegadorTestCase extends TestCase
{
    /** Administrador criado por sql/bancoPNLD.sql */
    protected const ADMIN_MATRICULA = '1234567890';
    protected const ADMIN_SENHA = '1234567890';

    protected static ?RemoteWebDriver $driver = null;

    protected function setUp(): void
    {
        // Cada teste usa um navegador novo, sem cookies nem sessão anterior
        self::encerrarNavegador();
        $this->iniciarNavegador();
    }

    public static function tearDownAfterClass(): void
    {
        self::encerrarNavegador();
    }

    protected function iniciarNavegador(): void
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
            getenv('SELENIUM_URL') ?: 'http://localhost:4444',
            $capacidades
        );
    }

    /** Descarta o navegador atual e abre outro, como um visitante novo. */
    protected function reiniciarNavegador(): void
    {
        self::encerrarNavegador();
        $this->iniciarNavegador();
    }

    protected static function encerrarNavegador(): void
    {
        if (self::$driver !== null) {
            try {
                self::$driver->quit();
            } catch (Throwable) {
                // navegador já encerrado
            }
            self::$driver = null;
        }
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
        return rtrim(getenv('E2E_BASE_URL') ?: 'http://localhost:8000', '/') . $caminho;
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
        try {
            self::$driver->wait($segundos)->until(
                WebDriverExpectedCondition::textToBePresentInElement(
                    WebDriverBy::tagName('body'),
                    $texto
                )
            );
        } catch (TimeoutException) {
            $this->fail("Texto não encontrado na página: {$texto}");
        }

        $this->assertStringContainsString(
            $texto,
            self::$driver->findElement(WebDriverBy::tagName('body'))->getText()
        );
    }

    protected function esperarUrlContendo(string $trecho, int $segundos = 8): void
    {
        try {
            self::$driver->wait($segundos)->until(
                WebDriverExpectedCondition::urlContains($trecho)
            );
        } catch (TimeoutException) {
            $this->fail("A URL não contém '{$trecho}'. URL atual: " . self::$driver->getCurrentURL());
        }

        $this->assertStringContainsString($trecho, self::$driver->getCurrentURL());
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

    /** Salva screenshot e HTML em tests-e2e/evidencias/ (evidência para o relatório). */
    protected function capturar(string $nome): void
    {
        $pasta = __DIR__ . '/evidencias';
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $arquivo = preg_replace('/[^A-Za-z0-9_-]+/', '_', $nome);
        self::$driver->takeScreenshot("{$pasta}/{$arquivo}.png");
        file_put_contents(
            "{$pasta}/{$arquivo}.html",
            '<!-- URL: ' . self::$driver->getCurrentURL() . " -->\n" . self::$driver->getPageSource()
        );
    }
}