<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/regras.php';

class RegrasTest extends TestCase
{
    public function testCampoObrigatorioPreenchido()
    {
        $this->assertTrue(validarCampoObrigatorio('João'));
    }

    public function testCampoObrigatorioVazio()
    {
        $this->assertFalse(validarCampoObrigatorio(''));
    }

    public function testLivroDisponivelPodeSerEmprestado()
    {
        $this->assertTrue(podeEmprestar(5));
    }

    public function testLivroSemDisponibilidadeNaoPodeSerEmprestado()
    {
        $this->assertFalse(podeEmprestar(0));
    }

    public function testDataDevolucao()
    {
        $resultado = calcularDataDevolucao('2026-09-17');

        $this->assertEquals('2026-10-07', $resultado);
    }
}