Fluxo E2E: Turmas e Alunos (US03 e US04)

Responsável: Jaine · Arquivo: tests-e2e/TurmaAlunoE2ETest.php · Issue: #46

1. Objetivo e cenários cobertos

Automatizar com Selenium WebDriver (php-webdriver + Chrome no Docker) o fluxo de turmas e alunos, usando os cenários de aceitação de docs/004-lista-users-stories.md:

Cenário	Descrição	Teste
TA04.01	Cadastrar turma	testFluxoCompletoCadastroConsultaEPesquisa
TA04.05	Pesquisar turma pelo curso	idem
TA03.01	Cadastrar aluno vinculado a uma turma	idem
TA03.02	Consultar aluno e ver a turma	idem
TA03.07	Pesquisa de aluno sem resultado	idem
Regra	Código de turma repetido é recusado	testNaoPermiteTurmaComCodigoRepetido
Regra	Matrícula de aluno repetida é recusada	testNaoPermiteAlunoComMatriculaRepetida
Regra	Turma com aluno vinculado não pode ser excluída	testNaoExcluiTurmaComAlunoVinculado
Extra	Inativar aluno	testInativarAluno

Cada teste usa dados únicos (código, curso, matrícula e nome aleatórios), para poder rodar várias vezes sem limpar o banco.

2. Como executar
docker compose exec app vendor/bin/phpunit --testsuite e2e --filter TurmaAlunoE2ETest
3. Ambiente de execução
PHP 8.4, PHPUnit 12.5, php-webdriver, Selenium Grid 4.52.0, Chrome 157 (selenium/standalone-chrome), servidor PHP embutido com PHP_CLI_SERVER_WORKERS=4.
Os testes foram executados na máquina da colega Helena. Na máquina da autora, o Docker enxerga cerca de 1,8 GB de memória e o Chrome levou 2 min 42 s só para iniciar a sessão, o que inviabilizou a execução local. A autora usou o sistema local (sem o Selenium) para reproduzir manualmente os defeitos.
4. Resultados das execuções
Execução	Resultado	Observação
1	5 testes, 5 falhas, 12 asserções	Mensagem de sucesso do cadastro de turma não encontrada
2	5 testes, 5 falhas, 59 asserções	Campos passaram a ser conferidos; o primeiro envio funciona, o seguinte não
3	5 testes, 4 falhas, 69 asserções	Envio por requestSubmit; turma passa, aluno quebra (BUG-01)
4	5 testes, 2 erros e 1 falha, 81 asserções	Após corrigir BUG-01; restam pesquisa e confirmações
5 (final)	5 testes, 88 asserções, OK (0 falhas) em 36 s	Todos os cenários verificados

Problemas do teste e ajustes (execuções 1 a 3).

Campos vazios. O print de falha mostrou o formulário de turma sem alguns valores. Em algumas execuções o Selenium não digitava o texto e a validação required do navegador impedia o envio. O teste passou a conferir o valor de cada campo depois de digitar e, se necessário, a preencher por script.
Formulário não enviado. Com os campos corretos, o formulário continuava sem ser enviado. O log do servidor (docker compose logs app) não registrava nenhum POST após o último GET, e a variável PHP_CLI_SERVER_WORKERS=4 já estava ativa, o que descartou o servidor como causa. O envio passou a ser feito pelo navegador (form.requestSubmit()), em vez de clique ou Enter.

Problemas da pesquisa e das confirmações (execução 4).

Pesquisa sem filtro. O clique no botão "Buscar" também não enviava o formulário, e a página continuava listando todos os alunos. Isso fez a consulta do aluno (TA03.02) passar por acaso, pois a lista sem filtro já continha o aluno. O teste passou a conferir o campo e a enviar a pesquisa pelo navegador, de modo que o filtro realmente acontece.
Janela de confirmação. Excluir turma e inativar aluno exibem uma confirmação do navegador (confirm), e o teste estourava o tempo esperando por ela. O teste passou a aceitar a confirmação por script.

Defeito encontrado no sistema (BUG-01). Nas execuções 3 e 4, todo cadastro de aluno falhava. Reproduzido manualmente, o sistema exibe

Fatal error: Uncaught Error: Class "AlunoValidator" not found in /app/aluno.php on line 62

A classe existe com outro nome: AlunoValidador, em php/AlunoValidador.php, que o aluno.php carrega na linha 9. Nas linhas 62 e 88, porém, o código chama AlunoValidator. Os testes unitários da classe (tests/AlunoValidadorTest.php) usam o nome correto e por isso não detectam o erro; apenas o teste E2E, que percorre a tela real, o encontrou. A correção foi trocar o nome nas duas linhas (commit fix: corrige nome da classe AlunoValidador em aluno.php), e o cadastro de aluno passou a funcionar.

5. Evidências

As evidências estão descritas a seguir; as capturas de tela e o HTML de cada falha ficam em tests-e2e/evidencias/.

Execução 1 (5 falhas). A captura de falha do teste principal mostra a tela "Adicionar nova turma" com os campos preenchidos (código, curso E2E-..., sigla E2E, período 1, série 1, matriz Matriz E2E) e nenhuma mensagem de retorno. Em outra captura da mesma execução, apenas o campo código estava preenchido e curso, sigla, período e série estavam vazios, com o cursor no campo Matriz Curricular. A saída do PHPUnit foi:

FFFFF   5 / 5 (100%)   Time: 01:12.714
There were 5 failures:
1) testFluxoCompletoCadastroConsultaEPesquisa: Texto não encontrado na página: Turma cadastrada com sucesso!
2) testNaoPermiteTurmaComCodigoRepetido: Texto não encontrado na página: Já existe uma turma cadastrada com esse código.
3) testNaoPermiteAlunoComMatriculaRepetida: Texto não encontrado na página: Aluno cadastrado com sucesso!
4) testNaoExcluiTurmaComAlunoVinculado: Texto não encontrado na página: Turma cadastrada com sucesso!
5) testInativarAluno: Texto não encontrado na página: Turma cadastrada com sucesso!
Tests: 5, Assertions: 12, Failures: 5.

Execução 2 (campos conferidos). A saída terminou com Tests: 5, Assertions: 59, Failures: 5: os campos passaram a ficar preenchidos, mas o segundo envio de formulário no mesmo teste continuava sem efeito (por exemplo, Texto não encontrado na página: E2E-61725429 na pesquisa por curso).

Execução 3 (envio via requestSubmit). As capturas de falha mostram o formulário de turma (código, curso, sigla, período, série e matriz preenchidos) e o formulário "Gerenciar alunos" com todos os campos preenchidos (matrícula, nome Aluno Teste ..., data 15/03/2005, endereço Rua dos Testes, 100, e-mail e2e.<matrícula>@exemplo.com, turma selecionada), ambos sem mensagem, e a lista "Alunos ativos" exibindo "Nenhum aluno encontrado.". O log do servidor (docker compose logs app) mostrava apenas requisições GET e [200]/[404] de imagens, CSS e fontes, sem nenhum POST /add_turma.php depois do último GET. O resultado foi F..EE/Tests: 5, Assertions: 69, Failures: 4, com a falha restante no texto Aluno cadastrado com sucesso!.

BUG-01. Ao clicar em "Cadastrar aluno" na tela aluno.php, o navegador exibe a tabela de erro do PHP: Fatal error: Uncaught Error: Class "AlunoValidator" not found in /app/aluno.php on line 62, com a pilha {main}() em .../aluno.php:0. A busca Select-String -Pattern "AlunoValidad" mostrou a classe definida em php/AlunoValidador.php (linha 3, class AlunoValidador), o require_once no aluno.php (linha 9) e as chamadas do teste unitário tests/AlunoValidadorTest.php. O comando git log --all --oneline -S"class AlunoValidator" não retornou nenhum commit. O git diff do conserto mostrou apenas duas linhas alteradas (62 e 88), de AlunoValidator:: para AlunoValidador::.

Execução 4 (BUG-01 corrigido). Tests: 5, Assertions: 81, Errors: 2, Failures: 1: o cadastro de aluno passou e restaram a pesquisa sem resultado (Texto não encontrado na página: Nenhum aluno encontrado.) e duas esperas de confirmação que estouraram o tempo (TimeoutException).

Execução final (execução 5).

docker compose exec app vendor/bin/phpunit tests-e2e/TurmaAlunoE2ETest.php
PHPUnit 12.5.35 by Sebastian Bergmann and contributors.
Runtime:       PHP 8.4.27
Configuration: /app/phpunit.xml

.....                                                       5 / 5 (100%)
Time: 00:36.185, Memory: 20.00 MB
OK (5 tests, 88 assertions)

Teste manual na máquina da autora: com o sistema rodando localmente, o cadastro de turma exibiu "Turma cadastrada com sucesso!".

6. Achados sobre o sistema
BUG-01 (confirmado): aluno.php chama AlunoValidator, mas a classe se chama AlunoValidador; todo cadastro de aluno resultava em Fatal error.
Para alunos existe "Inativar", mas a US03 (TA03.05) fala em "excluir".
A mensagem de sucesso do cadastro de aluno só aparece após redirecionamento (via sessão), e a de turma aparece na própria página; o padrão é diferente entre as telas.
Itens vistos apenas na leitura do código e ainda não confirmados na tela (não tratar como defeito até testar): add_turma.php e aluno.php não chamam exigirAutenticacao() no início (testar em janela anônima, sem login); "Sair" apenas leva a index.html.
7. Limitações
Execução feita na máquina de uma colega por limite de memória da máquina da autora.
Os testes criam registros no banco local; o sistema não exclui alunos, apenas inativa.
O envio dos formulários e a aceitação das confirmações são feitos por script, e não por clique real do usuário, porque o clique do Selenium não funcionou de forma confiável neste ambiente. Os testes, portanto, não verificam a aparência do botão nem o aspecto da janela de confirmação.
A hipótese de que o clique falhava por causa do layout da página (botão parcialmente fora da tela) não foi confirmada.