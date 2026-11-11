<?php

declare(strict_types=1);

namespace Tests\E2E;

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Fluxo E2E: cadastro de administrador e autenticação.
 * Rastreia: US01 (TA01.01 a TA01.04) e US02 (TA02.01 a TA02.06).
 */
final class AutenticacaoE2ETest extends NavegadorTestCase
{
    // ---------- US01 — Cadastrar administrador ----------

    public function testTA0101CadastraAdministradorEAutenticaComEle(): void
    {
        $this->autenticar();                 // o cadastro é feito por um administrador logado
        $matricula = (string) random_int(100000000, 999999999);

        $this->abrir('/cadastro.php');
        $this->preencher('matricula', $matricula);
        $this->preencher('nome', 'Admin E2E');
        $this->preencher('senha', 'senha123');
        $this->clicarEnviar();

        $this->esperarTexto('Administrador cadastrado com sucesso.');
        $this->capturar('TA01_01_cadastro_sucesso');

        // Nova sessão: autentica com a conta recém-criada
        $this->reiniciarNavegador();
        $this->autenticar($matricula, 'senha123');
    }

    public static function camposObrigatorios(): array
    {
        return [
            'TA01.02 sem matrícula' => ['', 'Fulano', 'abc', 'A matrícula é obrigatória.'],
            'TA01.03 sem nome'      => ['111', '', 'abc', 'O nome é obrigatório.'],
            'TA01.04 sem senha'     => ['111', 'Fulano', '', 'A senha é obrigatória.'],
        ];
    }

    #[DataProvider('camposObrigatorios')]
    public function testCadastroExigeCamposObrigatorios(
        string $matricula,
        string $nome,
        string $senha,
        string $mensagem
    ): void {
        $this->autenticar();
        $this->abrir('/cadastro.php');
        $this->preencher('matricula', $matricula);
        $this->preencher('nome', $nome);
        $this->preencher('senha', $senha);
        $this->clicarEnviar();

        $this->esperarTexto($mensagem);
    }

    // ---------- US02 — Autenticar administrador ----------

    public function testTA0201AutenticaComCredenciaisValidas(): void
    {
        $this->autenticar();

        $this->assertStringContainsString('funcoes.php', self::$driver->getCurrentURL());
        $this->capturar('TA02_01_login_sucesso');
    }

    public function testTA0202SenhaIncorretaNegaAcesso(): void
    {
        $this->abrir('/login.php');
        $this->preencher('matricula', self::ADMIN_MATRICULA);
        $this->preencher('senha', 'senha-errada');
        $this->clicarEnviar();

        $this->esperarTexto('Matrícula ou senha inválida.');
        $this->assertStringNotContainsString('funcoes.php', self::$driver->getCurrentURL());
    }

    public function testTA0203MatriculaNaoCadastradaNegaAcesso(): void
    {
        $this->abrir('/login.php');
        $this->preencher('matricula', '99999999999');
        $this->preencher('senha', 'qualquer');
        $this->clicarEnviar();

        $this->esperarTexto('Matrícula ou senha inválida.');
    }

    public function testTA0204CamposVaziosSaoRejeitados(): void
    {
        $this->abrir('/login.php');
        $this->clicarEnviar();

        $this->esperarTexto('Matrícula e senha são obrigatórias.');
    }

    public function testTA0205AdministradorAutenticadoAcessaTelasAdministrativas(): void
    {
        $this->autenticar();

        foreach (['/turmas.php', '/aluno.php', '/livros.php', '/emprestimosDevolucoes.php', '/historico.php'] as $pagina) {
            $this->abrir($pagina);
            $this->assertStringNotContainsString(
                'login.php',
                self::$driver->getCurrentURL(),
                "{$pagina} redirecionou para o login mesmo com sessão ativa"
            );
        }
    }

    public function testLogoutEncerraASessao(): void
    {
        $this->autenticar();

        $this->abrir('/logout.php');
        $this->esperarUrlContendo('login.php');

        $this->abrir('/turmas.php');
        $this->esperarUrlContendo('login.php');
    }

    public static function paginasRestritas(): array
    {
        // Apenas páginas que não alteram dados ao serem abertas por GET.
        // (devolverLivro.php fica de fora de propósito: ele altera dados via GET.)
        return [
            ['/turmas.php'], ['/aluno.php'], ['/livros.php'],
            ['/emprestimosDevolucoes.php'], ['/historico.php'],
            ['/add_turma.php'], ['/addEmprestimo.php'], ['/cadastro.php'],
        ];
    }

    #[DataProvider('paginasRestritas')]
    public function testTA0206AcessoDiretoSemAutenticacaoRedirecionaAoLogin(string $pagina): void
    {
        $this->abrir($pagina);

        $this->esperarUrlContendo('login.php');
        $this->assertStringContainsString('login.php', self::$driver->getCurrentURL());
    }
}
