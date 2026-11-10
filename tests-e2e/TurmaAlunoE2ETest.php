<?php
 
declare(strict_types=1);
 
namespace Tests\E2E;
 
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;
 
/**
 * Fluxo E2E de Turmas e Alunos (US03 e US04).
 *
 * Cenários de aceitação cobertos (docs/004-lista-users-stories.md):
 *  - TA04.01 cadastrar turma, TA04.05 pesquisar turma pelo curso
 *  - TA03.01 cadastrar aluno vinculado a uma turma, TA03.02 consultar aluno,
 *    TA03.07 pesquisa sem resultado
 *  - Regras de negócio: código de turma repetido, matrícula de aluno repetida
 *    e bloqueio da exclusão de turma com aluno vinculado.
 *
 * Observações:
 *  - Cada teste cria dados com valores únicos (código, curso, matrícula, nome),
 *    para poder rodar várias vezes sem limpar o banco.
 *  - O sistema não exclui alunos, apenas inativa. Os registros criados pelos
 *    testes continuam no banco local.
 *  - testInativarAluno parte de uma hipótese (a pesquisa só lista alunos
 *    ativos); se falhar, confirme o comportamento na tela antes de concluir
 *    que há defeito.
 */
final class TurmaAlunoE2ETest extends NavegadorTestCase
{
    private string $codigoTurma = '';
    private string $curso = '';
    private string $matricula = '';
    private string $nome = '';
 
    protected function setUp(): void
    {
        parent::setUp();
 
        // curso aceita no máximo 20 caracteres (VARCHAR(20))
        $this->codigoTurma = (string) random_int(100000, 999999999);
        $this->curso = 'E2E-' . random_int(10000000, 99999999);
        $this->matricula = (string) random_int(1000000000, 9999999999);
        $this->nome = 'Aluno Teste ' . self::letras(8);
    }
 
    public function testFluxoCompletoCadastroConsultaEPesquisa(): void
    {
        $this->autenticar();
 
        // TA04.01 - cadastrar turma
        $this->cadastrarTurma();
        $this->esperarTexto('Turma cadastrada com sucesso!');
        $this->capturar('TA04_01_turma_cadastrada');
 
        // TA04.05 - pesquisar turma pelo curso
        $this->abrir('/turmas.php');
        $this->preencher('curso', $this->curso);
        $this->clicarEnviar();
        $this->esperarTexto($this->curso);
        $this->capturar('TA04_05_turma_pesquisada');
 
        // TA03.01 - cadastrar aluno vinculado à turma
        $this->cadastrarAluno();
        $this->esperarTexto('Aluno cadastrado com sucesso!');
        $this->capturar('TA03_01_aluno_cadastrado');
 
        // TA03.02 - consultar o aluno e ver a turma associada
        $this->pesquisarAluno($this->nome);
        $this->esperarTexto($this->matricula);
        $this->esperarTexto($this->nome);
        $this->esperarTexto($this->curso);
        $this->capturar('TA03_02_aluno_consultado');
 
        // TA03.07 - pesquisa sem correspondência
        $this->pesquisarAluno('Inexistente ' . self::letras(10));
        $this->esperarTexto('Nenhum aluno encontrado.');
        $this->capturar('TA03_07_sem_resultado');
    }
 
    public function testNaoPermiteTurmaComCodigoRepetido(): void
    {
        $this->autenticar();
 
        $this->cadastrarTurma();
        $this->esperarTexto('Turma cadastrada com sucesso!');
 
        $this->cadastrarTurma();
        $this->esperarTexto('Já existe uma turma cadastrada com esse código.');
        $this->capturar('ERRO_turma_codigo_repetido');
    }
 
    public function testNaoPermiteAlunoComMatriculaRepetida(): void
    {
        $this->autenticar();
 
        $this->cadastrarTurma();
        $this->esperarTexto('Turma cadastrada com sucesso!');
 
        $this->cadastrarAluno();
        $this->esperarTexto('Aluno cadastrado com sucesso!');
 
        $this->cadastrarAluno();
        $this->esperarTexto('Já existe um aluno com essa matrícula.');
        $this->capturar('ERRO_aluno_matricula_repetida');
    }
 
    public function testNaoExcluiTurmaComAlunoVinculado(): void
    {
        $this->autenticar();
 
        $this->cadastrarTurma();
        $this->esperarTexto('Turma cadastrada com sucesso!');
 
        $this->cadastrarAluno();
        $this->esperarTexto('Aluno cadastrado com sucesso!');
 
        $this->abrir('/turmas.php');
        $this->preencher('curso', $this->curso);
        $this->clicarEnviar();
        $this->esperarTexto($this->curso);
 
        self::$driver->findElement(WebDriverBy::cssSelector(
            "a[href='excluir_turma.php?codigo={$this->codigoTurma}']"
        ))->click();
        $this->aceitarConfirmacao();
 
        $this->esperarTexto(
            'Não é possível excluir esta turma porque existem alunos vinculados.'
        );
        $this->capturar('REGRA_exclusao_bloqueada');
    }
 
    public function testInativarAluno(): void
    {
        $this->autenticar();
 
        $this->cadastrarTurma();
        $this->esperarTexto('Turma cadastrada com sucesso!');
 
        $this->cadastrarAluno();
        $this->esperarTexto('Aluno cadastrado com sucesso!');
 
        $this->pesquisarAluno($this->nome);
        $this->esperarTexto($this->matricula);
 
        self::$driver->findElement(WebDriverBy::xpath(
            "//form[.//input[@name='matricula' and @value='{$this->matricula}']]"
            . "//button[@type='submit']"
        ))->click();
        $this->aceitarConfirmacao();
 
        $this->esperarTexto('Aluno inativado com sucesso!');
        $this->capturar('ALUNO_inativado');
 
        // Hipótese: a pesquisa lista apenas alunos ativos
        $this->pesquisarAluno($this->nome);
        $this->esperarTexto('Nenhum aluno encontrado.');
    }
 
    private function cadastrarTurma(): void
    {
        $this->abrir('/add_turma.php');
        $this->preencher('codigo', $this->codigoTurma);
        $this->preencher('curso', $this->curso);
        $this->preencher('siglaCurso', 'E2E');
        $this->preencher('periodo', '1');
        $this->preencher('serie', '1');
        $this->preencher('matrizCurricular', 'Matriz E2E');
        $this->clicarEnviar();
    }
 
    private function cadastrarAluno(): void
    {
        $this->abrir('/aluno.php');
 
        // Na página há outro campo "nome" (pesquisa) mais abaixo; o primeiro
        // encontrado pelo atributo name é o do formulário de cadastro.
        $this->preencher('matricula', $this->matricula);
        $this->preencher('nome', $this->nome);
        $this->preencher('endereco', 'Rua dos Testes, 100');
        $this->preencher('email', "e2e.{$this->matricula}@exemplo.com");
 
        // Campo type=date: define o valor direto, sem depender do idioma do navegador
        self::$driver->executeScript(
            'arguments[0].value = arguments[1];',
            [self::$driver->findElement(WebDriverBy::id('datnasc')), '2005-03-15']
        );
 
        (new WebDriverSelect(
            self::$driver->findElement(WebDriverBy::id('codigoTurma'))
        ))->selectByValue($this->codigoTurma);
 
        // O primeiro botão de envio da página é o do formulário de cadastro
        $this->clicarEnviar();
    }
 
    private function pesquisarAluno(string $nome): void
    {
        $this->abrir('/aluno.php');
 
        $campo = self::$driver->findElement(
            WebDriverBy::cssSelector('form.pesquisa input[name=nome]')
        );
        $campo->clear();
        $campo->sendKeys($nome);
 
        self::$driver->findElement(
            WebDriverBy::cssSelector('form.pesquisa button[type=submit]')
        )->click();
    }
 
    private function aceitarConfirmacao(): void
    {
        self::$driver->wait(5)->until(WebDriverExpectedCondition::alertIsPresent());
        self::$driver->switchTo()->alert()->accept();
    }
 
    private static function letras(int $quantidade): string
    {
        $alfabeto = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $texto = '';
 
        for ($i = 0; $i < $quantidade; $i++) {
            $texto .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
        }
 
        return $texto;
    }
}
 