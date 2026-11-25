<?php

use PHPUnit\Framework\TestCase;

class HealthCheckTest extends TestCase
{
    /** @var array<string, string|false> */
    private array $envOriginal = [];

    protected function setUp(): void
    {
        $this->envOriginal = [
            'DB_HOST' => getenv('DB_HOST'),
            'DB_USER' => getenv('DB_USER'),
            'DB_PASSWORD' => getenv('DB_PASSWORD'),
            'DB_NAME' => getenv('DB_NAME'),
        ];
    }

    protected function tearDown(): void
    {
        foreach ($this->envOriginal as $key => $value) {
            if ($value === false) {
                putenv($key);
            } else {
                putenv("{$key}={$value}");
            }
        }
    }

    public function testHealthEndpointRetornaHealthyComBancoConectado(): void
    {
        ob_start();
        require __DIR__ . '/../health.php';
        $output = (string) ob_get_clean();

        $data = json_decode($output, true);

        $this->assertIsArray($data, 'O retorno do endpoint /health deve ser um JSON válido');
        $this->assertSame('healthy', $data['status']);
        $this->assertSame('connected', $data['database']);
        $this->assertSame('Sistema HBL - PNLD', $data['service']);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $data['timestamp']);
    }

    public function testHealthEndpointRetornaUnhealthySemConfiguracaoDeBanco(): void
    {
        putenv('DB_HOST');
        putenv('DB_USER');
        putenv('DB_NAME');

        ob_start();
        require __DIR__ . '/../health.php';
        $output = (string) ob_get_clean();

        $data = json_decode($output, true);

        $this->assertIsArray($data);
        $this->assertSame('unhealthy', $data['status']);
        $this->assertSame('not_configured', $data['database']);
    }

    public function testHealthEndpointRetornaUnhealthyComBancoDesconectado(): void
    {
        putenv('DB_HOST=127.0.0.1');
        putenv('DB_USER=usuario_invalido');
        putenv('DB_PASSWORD=senha_invalida');
        putenv('DB_NAME=banco_inexistente');

        ob_start();
        require __DIR__ . '/../health.php';
        $output = (string) ob_get_clean();

        $data = json_decode($output, true);

        $this->assertIsArray($data);
        $this->assertSame('unhealthy', $data['status']);
        $this->assertSame('disconnected', $data['database']);
    }
}
