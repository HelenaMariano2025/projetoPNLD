<?php

use PHPUnit\Framework\TestCase;

class HealthCheckTest extends TestCase
{
    public function testHealthEndpointReturnsValidStructure(): void
    {
        ob_start();
        require __DIR__ . '/../health.php';
        $output = ob_get_clean();

        $data = json_decode($output, true);

        $this->assertIsArray($data, 'O retorno do endpoint /health deve ser um JSON válido');
        $this->assertSame('UP', $data['status']);
        $this->assertSame('Sistema HBL - PNLD', $data['service']);
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertArrayHasKey('php_version', $data);
    }
}
