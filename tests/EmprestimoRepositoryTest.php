<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/EmprestimoRepository.php';

class EmprestimoRepositoryTest extends TestCase
{
    public function testNaoPermiteRegistrarMesmaDevolucaoDuasVezes()
    {
        $conn = $this->createMock(mysqli::class);

        $repository = new EmprestimoRepository($conn);

        $codigoEmprestimo = 1;
        $dataDevolucao = '2026-10-01';

        $primeiraDevolucao = $repository->registrarDevolucao(
            $codigoEmprestimo,
            $dataDevolucao
        );

        $segundaDevolucao = $repository->registrarDevolucao(
            $codigoEmprestimo,
            $dataDevolucao
        );

        $this->assertTrue($primeiraDevolucao);
        $this->assertFalse($segundaDevolucao);
    }
}