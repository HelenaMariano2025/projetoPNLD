## 10. Prática 1 — Automação de testes de sistema com Selenium 

### 10.1. Ambiente e configuração

| Item | Valor |
|---|---|
| Ferramenta | Selenium WebDriver, com a biblioteca `php-webdriver/webdriver` (versão resolvida pelo Composer, ver `composer.lock`) |
| Framework de testes | PHPUnit 12.5.35 |
| PHP | 8.4.27 |
| Servidor Selenium | Selenium Standalone 4.52.0, imagem `selenium/standalone-chrome` |
| Navegador | Chrome 157.0.8103.38, em modo *headless* |
| Data da execução | 08/11/2026 |

**Instalação da biblioteca** (substitui a simulação `--dry-run` da seção 6.2):

```bash
docker compose up --build -d
docker compose exec app composer require --dev php-webdriver/webdriver
docker compose exec app composer dump-autoload
```

**Serviço Selenium no `compose.yaml`.** O contêiner compartilha a rede do `app`, de modo que o navegador acessa a aplicação em `http://localhost:8000`:

```yaml
  selenium:
    image: selenium/standalone-chrome:latest
    shm_size: "2gb"
    network_mode: "service:app"
    depends_on:
      - app
```

**Suíte `e2e` no `phpunit.xml`**, separada da suíte padrão para que a cobertura e o Sonar não tentem executá-la:

```xml
<testsuite name="e2e">
    <directory suffix="Test.php">tests-e2e</directory>
</testsuite>
```

**Execução:**

```bash
docker compose up -d
docker compose exec app vendor/bin/phpunit --testsuite e2e --filter AutenticacaoE2ETest
```

**Arquivos:**

| Arquivo | Função |
|---|---|
| `tests-e2e/NavegadorTestCase.php` | Classe base: abre o navegador, preenche campos, aguarda textos e URLs, captura tela em caso de falha |
| `tests-e2e/AutenticacaoE2ETest.php` | Testes do fluxo de cadastro e autenticação |

### 10.2. Problemas encontrados durante a configuração

| # | Sintoma | Ação adotada |
|---|---|---|
| 1 | O Chrome do contêiner `selenium` não abria `http://app:8000` (`net::ERR_CONNECTION_CLOSED`); a conexão com o servidor Selenium estava correta | O serviço Selenium passou a usar `network_mode: "service:app"`, e a aplicação é acessada por `http://localhost:8000` |
| 2 | Após o primeiro login, os campos do formulário permaneciam vazios nos logins seguintes dentro do mesmo navegador, e os testes falhavam por tempo esgotado | Cada teste passou a abrir um navegador novo (método `setUp`). A causa exata não foi identificada. O comportamento foi observado por um script de diagnóstico, e a mudança eliminou o problema |

Uma hipótese levantada e descartada: o servidor embutido do PHP atender uma requisição por vez. O ajuste testado não alterou o resultado.

### 10.3. Fluxo implementado (Cadastro e Login)

Fluxo: **cadastro e login** (US01 e US02). Total de **18 testes** (os 8 do TA02.06 vêm de um *data provider*):

| Teste | Cenário (User Stories) | Resultado esperado |
|---|---|---|
| `testTA0101...` | TA01.01 + TA02.01: cadastrar administrador e autenticar com a nova conta | Mensagem de sucesso; login redireciona para `funcoes.php` |
| `testCadastroExigeCamposObrigatorios` (3 casos) | TA01.02, TA01.03, TA01.04: campo obrigatório ausente | Mensagem de campo obrigatório |
| `testTA0201...` | TA02.01: credenciais válidas | Redireciona para `funcoes.php` |
| `testTA0202...` | TA02.02: senha incorreta | "Matrícula ou senha inválida." |
| `testTA0203...` | TA02.03: matrícula não cadastrada | "Matrícula ou senha inválida." |
| `testTA0204...` | TA02.04: campos vazios | "Matrícula e senha são obrigatórias." |
| `testTA0205...` | TA02.05: administrador autenticado acessa as telas | Nenhum redirecionamento ao login |
| `testTA0206...` (8 rotas) | TA02.06: acesso direto sem autenticação | Redireciona para `login.php` |
| `testLogoutEncerraASessao` | Encerramento de sessão | Após o logout, páginas restritas redirecionam ao login |

Rotas do TA02.06: `/turmas.php`, `/aluno.php`, `/livros.php`, `/emprestimosDevolucoes.php`, `/historico.php`, `/add_turma.php`, `/addEmprestimo.php` e `/cadastro.php`. A rota `/devolverLivro.php` ficou de fora do teste porque altera dados mesmo por requisição GET.

### 10.4. Execução ANTES da correção

A suíte, ainda sem o teste de logout (17 testes), revelou **4 falhas**, todas do cenário TA02.06:

```text
docker compose exec app vendor/bin/phpunit --testsuite e2e --filter AutenticacaoE2ETest
PHPUnit 12.5.35 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.4.27
Configuration: /app/phpunit.xml

..........F...FFF                                                 17 / 17 (100%)

Time: 01:40.959, Memory: 20.00 MB

There were 4 failures:

1) Tests\E2E\AutenticacaoE2ETest::testTA0206AcessoDiretoSemAutenticacaoRedirecionaAoLogin#1 with data ('/aluno.php')
A URL não contém 'login.php'. URL atual: http://localhost:8000/aluno.php

/app/tests-e2e/NavegadorTestCase.php:135
/app/tests-e2e/AutenticacaoE2ETest.php:131

2) Tests\E2E\AutenticacaoE2ETest::testTA0206AcessoDiretoSemAutenticacaoRedirecionaAoLogin#5 with data ('/add_turma.php')
A URL não contém 'login.php'. URL atual: http://localhost:8000/add_turma.php

/app/tests-e2e/NavegadorTestCase.php:135
/app/tests-e2e/AutenticacaoE2ETest.php:131

3) Tests\E2E\AutenticacaoE2ETest::testTA0206AcessoDiretoSemAutenticacaoRedirecionaAoLogin#6 with data ('/addEmprestimo.php')
A URL não contém 'login.php'. URL atual: http://localhost:8000/addEmprestimo.php

/app/tests-e2e/NavegadorTestCase.php:135
/app/tests-e2e/AutenticacaoE2ETest.php:131

4) Tests\E2E\AutenticacaoE2ETest::testTA0206AcessoDiretoSemAutenticacaoRedirecionaAoLogin#7 with data ('/cadastro.php')
A URL não contém 'login.php'. URL atual: http://localhost:8000/cadastro.php

/app/tests-e2e/NavegadorTestCase.php:135
/app/tests-e2e/AutenticacaoE2ETest.php:131

FAILURES!
Tests: 17, Assertions: 33, Failures: 4.
```

### 10.5. Defeitos encontrados e correção

Os testes de sistema encontraram **defeitos reais de controle de acesso** na aplicação:

| Página | Defeito (antes) | Correção (depois) |
|---|---|---|
| `aluno.php` | Aberta sem login: qualquer visitante podia cadastrar, editar e inativar alunos | `exigirAutenticacao()` no início |
| `add_turma.php` | Aberta sem login | `exigirAutenticacao()` após o início da sessão |
| `addEmprestimo.php` | Formulário aberto sem login (só o envio era validado) | `exigirAutenticacao()` após o autoload |
| `devolverLivro.php` | Alterava dados por GET sem verificar login | `exigirAutenticacao()` após o autoload |
| `cadastro.php` | Público: qualquer pessoa criava um administrador e já saía logada como ele | Restrito a administrador autenticado; a sessão do novo administrador deixa de ser criada no cadastro |
| Sessão | Não existia logout, e o ID da sessão não mudava no login | Novo `logout.php`; `session_regenerate_id` no login (`regenerarSessao()`) |
| Navegação | Links públicos para o cadastro em `index.html` e `login.php` | Removidos; o menu do administrador passou a ter "Cadastrar administrador" |

Pull Request: [fix/#46-restringir-acesso-admin](https://github.com/HelenaMariano2025/projetoPNLD/pull/48).

**Regressão (PHPUnit):** após as alterações, a suíte existente permaneceu verde.

```text
docker compose exec app vendor/bin/phpunit
Tests: 191, Assertions: 576, Deprecations: 1.
OK, but there were issues!
```

As mensagens de erro do MySQL exibidas durante a execução vêm de testes que provocam essas falhas de propósito.

### 10.6. Execução DEPOIS da correção

Execução intermediária, já com o teste de logout (18 testes). As páginas `/aluno.php`, `/add_turma.php` e `/cadastro.php` já redirecionavam, e restava uma falha:

```text
PHPUnit 12.5.35 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.4.27
Configuration: /app/phpunit.xml

................F.                                                18 / 18 (100%)

Time: 01:13.100, Memory: 20.00 MB

There was 1 failure:

1) Tests\E2E\AutenticacaoE2ETest::testTA0206AcessoDiretoSemAutenticacaoRedirecionaAoLogin#6 with data ('/addEmprestimo.php')
A URL não contém 'login.php'. URL atual: http://localhost:8000/addEmprestimo.php

/app/tests-e2e/NavegadorTestCase.php:135
/app/tests-e2e/AutenticacaoE2ETest.php:142

FAILURES!
Tests: 18, Assertions: 39, Failures: 1.
```

A falha ocorreu porque a chamada de exigirAutenticacao() foi posicionada incorretamente após session_start(), quando deveria estar após o require_once __DIR__ . '/vendor/autoload.php'. Após identificar o erro, a chamada foi reposicionada corretamente, permitindo a execução dos testes E2E sem falhas.

Execução final, após corrigir `addEmprestimo.php`:

```text
PHPUnit 12.5.35 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.4.27
Configuration: /app/phpunit.xml

..................                                                18 / 18 (100%)

Time: 01:28.859, Memory: 20.00 MB

OK (18 tests, 40 assertions).
```

### 10.7. Comparativo antes × depois

| Cenário | Antes | Depois |
|---|---|---|
| Rotas restritas redirecionam ao login (TA02.06) | 4 de 8 falhavam | 8 de 8 aprovados |
| Logout encerra a sessão | Funcionalidade inexistente | Funcionalidade implementada e aprovada |
| Total | 13 aprovados, 4 falhas (17 testes) | 18 aprovados, 0 falhas (18 testes) |

**Evidência visual:**

![Execução final dos testes E2E](image.png)

### 10.8. Limitações

- A suíte E2E depende de três serviços ativos (aplicação, banco e Selenium) e fica fora da suíte padrão do PHPUnit.
- Cada teste abre um navegador novo, o que torna a execução mais lenta: 18 testes em cerca de 1 minuto e 13 segundos.
- A execução em integração contínua (GitHub Actions) ainda não foi implementada.
- A causa exata da perda de digitação após o primeiro login (item 2 da seção 10.2) não foi identificada.
- Os testes cobrem o fluxo de cadastro e login. Os fluxos de alunos e turmas e de livros e empréstimos são cobertos pelos outros integrantes.
