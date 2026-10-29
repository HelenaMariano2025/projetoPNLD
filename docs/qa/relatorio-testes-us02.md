# Relatório de Testes e Garantia de Qualidade — US02

## 1. Identificação

**Atividade:** Tarefa 02 — Garantia de Qualidade e Testes
**Iteração:** Iteração 1
**História de Usuário:** US02 — Autenticar administrador e acessar funcionalidades
**Desenvolvedora da US02:** Jaine
**QA Engineer / Testadora:** Helena Mariano
**Repositório:** projetoPNLD

## 2. Objetivo da atividade

Esta atividade prática individual simula o fluxo de desenvolvimento com garantia de qualidade (QA) contínua da Iteração 1. Nesse processo, cada discente atua tanto como **Desenvolvedor** quanto como **QA Engineer / Testador** de uma implementação realizada por um colega de equipe.

Nesta etapa, atuei como **QA Engineer / Testadora da US02**, implementada pela colega Jaine. O objetivo foi revisar a implementação, executar testes automatizados, verificar a integração com o banco de dados e realizar testes de aceitação diretamente sobre o sistema, comparando os resultados obtidos com os critérios de aceitação definidos para a história de usuário.

A revisão foi realizada sobre a branch da implementação da US02, `task/467`, utilizando uma branch própria de revisão.

Além da atuação como testadora nesta etapa, a atividade faz parte do fluxo em que também exerço o papel de **desenvolvedora**, sendo responsável por implementar funcionalidades em outras histórias de usuário e, posteriormente, contribuir para a validação das implementações dos colegas.

---

## 3. História de Usuário avaliada

**US02 — Autenticar administrador e acessar funcionalidades**

A funcionalidade permite que um administrador informe sua matrícula e senha para acessar as funcionalidades administrativas do sistema.

Foram considerados os seguintes critérios de aceitação:

* **TA02.01:** informar matrícula e senha válidas; o sistema deve autenticar o administrador e encaminhá-lo para a Página do Administrador.
* **TA02.02:** informar matrícula cadastrada e senha incorreta; o sistema deve negar o acesso e apresentar mensagem de credenciais inválidas.
* **TA02.03:** informar matrícula não cadastrada; o sistema deve negar o acesso e apresentar mensagem de credenciais inválidas.
* **TA02.04:** tentar autenticar sem preencher matrícula ou senha; o sistema deve impedir a autenticação e indicar os campos obrigatórios.

---

## 4. Ambiente de testes

Os testes foram executados no ambiente local utilizando:

* PHP 8.4.8
* PHPUnit 12.5.35
* Xdebug 3.4.2
* MySQL
* Banco de dados `SistemaHBL`
* Servidor PHP local em `localhost:8000`
* Sistema operacional Linux

O banco possuía um administrador cadastrado para a realização dos testes funcionais:

* Matrícula: `1234567890`
* Nome: Ronaldo
* Senha: `1234567890`

---

## 5. Revisão da implementação

Durante a revisão da US02, foram analisados os principais componentes envolvidos no processo de autenticação:

* `login.php`
* `php/regras.php`
* `php/AdministradorRepository.php`
* `tests/LoginTest.php`
* `tests/AdministradorRepositoryIntegrationTest.php`

A implementação utiliza o `AdministradorRepository` para consultar o administrador pela matrícula. A função `autenticarAdministrador()` realiza as validações necessárias e compara a senha informada com a senha armazenada.

Em caso de autenticação válida, o sistema cria a sessão do administrador e redireciona o usuário para `funcoes.php`, correspondente à área de funcionalidades administrativas.

Em caso de credenciais inválidas, o sistema permanece na tela de autenticação e apresenta mensagem informando que a matrícula ou senha é inválida.

---

## 6. Testes unitários

Foram executados os testes unitários existentes em `tests/LoginTest.php`.

Os testes verificam individualmente as principais regras da autenticação:

### 6.1 Campos obrigatórios

O teste `testNaoPermiteLoginComCamposVazios()` verifica se matrícula e senha vazias são rejeitadas pela função `validarCampoObrigatorio()`.

**Resultado:** passou.

### 6.2 Autenticação com credenciais válidas

O teste `testAutenticaAdministradorComCredenciaisValidas()` utiliza um mock de `AdministradorRepository` para simular a existência de um administrador cadastrado e verifica se a função `autenticarAdministrador()` retorna os dados do administrador quando matrícula e senha são válidas.

**Resultado:** passou.

### 6.3 Senha inválida

O teste `testNaoAutenticaComSenhaInvalida()` simula um administrador existente, mas utiliza uma senha incorreta. O resultado esperado é `null`, indicando que a autenticação deve ser recusada.

**Resultado:** passou.

### 6.4 Matrícula não cadastrada

O teste `testNaoAutenticaMatriculaNaoCadastrada()` simula uma consulta que não encontra administrador para a matrícula informada. O resultado esperado também é `null`.

**Resultado:** passou.

### Resultado dos testes unitários

Os quatro testes relacionados diretamente às regras de autenticação apresentaram comportamento esperado.

**Resultado:** 4 testes unitários passaram.

Esses testes verificam principalmente a lógica interna da autenticação e utilizam mock para isolar o `AdministradorRepository`.

---

## 7. Teste de integração

Também foi analisado e executado o teste:

`tests/AdministradorRepositoryIntegrationTest.php`

O objetivo desse teste é verificar a integração entre o `AdministradorRepository` e o banco de dados real, utilizando uma conexão MySQL e realizando uma consulta pela matrícula.

O teste executado foi:

```text
testBuscaAdministradorCadastradoNoBanco
```

A execução apresentou:

```text
Failed asserting that null is not null.
```

A falha ocorreu porque o teste procura especificamente pela matrícula `20250101`, porém esse administrador não estava cadastrado no banco de dados utilizado durante a execução.

Foi verificado diretamente no banco que o administrador disponível para os testes era:

```text
1234567890 | Ronaldo | 1234567890
```

Portanto, a falha observada está relacionada à **dependência de dados previamente existentes no ambiente de teste**, e não foi considerada, isoladamente, uma falha comprovada da lógica de autenticação.

Mesmo apresentando falha de configuração/dados, o teste caracteriza uma tentativa de **teste de integração funcional**, pois utiliza o `AdministradorRepository` em conjunto com uma conexão real ao banco de dados.

---

## 8. Execução conjunta dos testes automatizados

Foram executados os testes da autenticação e do repositório de administrador utilizando as credenciais do banco local.

Comando utilizado:

```bash
DB_HOST=localhost DB_USER=pnld DB_PASSWORD=123456 DB_NAME=SistemaHBL ./vendor/bin/phpunit tests/LoginTest.php tests/AdministradorRepositoryIntegrationTest.php
```

Resultado:

```text
Tests: 5
Assertions: 7
Failures: 1
```

Dos cinco testes executados, quatro apresentaram comportamento esperado e um apresentou falha devido à ausência da matrícula `20250101` no banco.

---

## 9. Teste de cobertura

Para verificar a cobertura de código, foi utilizada a ferramenta PHPUnit juntamente com o Xdebug.

Comando utilizado:

```bash
XDEBUG_MODE=coverage DB_HOST=localhost DB_USER=pnld DB_PASSWORD=123456 DB_NAME=SistemaHBL ./vendor/bin/phpunit tests/LoginTest.php tests/AdministradorRepositoryIntegrationTest.php --coverage-text
```

A execução apresentou:

```text
Tests: 5
Assertions: 7
Failures: 1
```

### Cobertura obtida

```text
Classes:  0.00% (0/4)
Methods:  5.88% (1/17)
Lines:   11.04% (17/154)
```

Para o `AdministradorRepository`, especificamente:

```text
Methods: 50.00% (1/2)
Lines:   90.00% (9/10)
```

A cobertura geral ficou baixa porque a execução considerou somente os arquivos e testes relacionados à autenticação e ao repositório de administrador, enquanto o projeto possui outros arquivos e funcionalidades que não foram exercitados nessa execução.

O `AdministradorRepository`, por outro lado, apresentou **90% de cobertura de linhas** nessa execução.

---

## 10. Testes de aceitação

Além dos testes automatizados, foram realizados testes diretamente sobre o sistema em execução no servidor PHP local.

Servidor utilizado:

```text
http://localhost:8000/login.php
```

### TA02.01 — Credenciais válidas

**Entrada:**

```text
Matrícula: 1234567890
Senha: 1234567890
```

**Resultado esperado:** autenticação realizada e encaminhamento para a Página do Administrador.

**Resultado obtido:** passou.

A resposta HTTP apresentada pelo sistema foi:

```text
HTTP/1.1 302 Found
Location: funcoes.php
```

Isso comprova que as credenciais foram aceitas e que o sistema realizou o redirecionamento para `funcoes.php`.

**Status: PASSOU**

---

### TA02.02 — Matrícula válida e senha incorreta

**Entrada:**

```text
Matrícula: 1234567890
Senha: senha-errada
```

**Resultado esperado:** negar o acesso e apresentar mensagem de credenciais inválidas.

**Resultado obtido:**

```text
Matrícula ou senha inválida.
```

O sistema permaneceu na página de autenticação e não realizou o redirecionamento para a área administrativa.

**Status: PASSOU**

---

### TA02.03 — Matrícula não cadastrada

**Entrada prevista:**

```text
Matrícula: 99999999
Senha: 1234567890
```

**Resultado esperado:**

```text
Matrícula ou senha inválida.
```

Esse caso faz parte dos critérios de aceitação definidos para a US02 e possui cobertura correspondente nos testes unitários, por meio do teste `testNaoAutenticaMatriculaNaoCadastrada()`.

**Status:** não houve execução manual pelo navegador/curl registrada durante a revisão.

Portanto, não foi considerado como evidência de teste de aceitação manual executado.

---

### TA02.04 — Campos vazios

**Entrada prevista:**

```text
Matrícula: vazio
Senha: vazio
```

**Resultado esperado:**

```text
Matrícula e senha são obrigatórias.
```

A regra correspondente foi validada pelo teste unitário `testNaoPermiteLoginComCamposVazios()`.

**Status:** não houve execução manual pelo navegador/curl registrada durante a revisão.

Portanto, não foi considerado como evidência de teste de aceitação manual executado.

---

## 11. Resumo dos resultados

| Tipo de teste | Caso                               | Resultado                          |
| ------------- | ---------------------------------- | ---------------------------------- |
| Unitário      | Campos vazios                      | PASSOU                             |
| Unitário      | Credenciais válidas                | PASSOU                             |
| Unitário      | Senha inválida                     | PASSOU                             |
| Unitário      | Matrícula não cadastrada           | PASSOU                             |
| Integração    | Busca de administrador no banco    | FALHOU — dado de teste inexistente |
| Aceitação     | TA02.01 — Login válido             | PASSOU                             |
| Aceitação     | TA02.02 — Senha inválida           | PASSOU                             |
| Aceitação     | TA02.03 — Matrícula não cadastrada | Não executado manualmente          |
| Aceitação     | TA02.04 — Campos vazios            | Não executado manualmente          |

---

## 12. Evidências e reprodução de problemas

### Falha no teste de integração

**Problema identificado:** o teste `testBuscaAdministradorCadastradoNoBanco` procura pela matrícula `20250101`, mas esse registro não estava presente no banco utilizado.

**Evidência:**

```text
Failed asserting that null is not null.
```

**Linha indicada pelo PHPUnit:**

```text
tests/AdministradorRepositoryIntegrationTest.php:38
```

**Reprodução:**

1. Configurar as variáveis de ambiente do banco.
2. Executar o teste `AdministradorRepositoryIntegrationTest`.
3. O teste consulta a matrícula `20250101`.
4. A consulta não encontra o administrador.
5. O método retorna `null`.
6. O `assertNotNull()` falha.

**Observação:** recomenda-se que o teste de integração seja independente de dados previamente existentes no ambiente, criando ou preparando seus próprios dados de teste.

---

## 13. Melhorias e pontos de atenção

Durante a revisão, foram identificados os seguintes pontos:

### 13.1 Dados do teste de integração

O teste depende da existência de uma matrícula específica no banco. Isso reduz a reprodutibilidade do teste, pois o resultado depende do estado prévio do banco.

**Melhoria sugerida:** preparar os dados necessários durante o próprio teste ou utilizar uma estratégia de fixture.

### 13.2 Cobertura de testes

A cobertura geral obtida foi de 11,04% das linhas na execução realizada. Apesar disso, o `AdministradorRepository` apresentou 90% de cobertura de linhas.

**Melhoria sugerida:** ampliar a quantidade de testes automatizados para os demais componentes envolvidos na autenticação e para os diferentes fluxos do sistema.

### 13.3 Testes de aceitação

Os critérios TA02.01 e TA02.02 foram efetivamente executados contra o sistema em funcionamento. Os critérios TA02.03 e TA02.04 possuem cobertura nos testes unitários, mas não foram executados manualmente durante esta revisão.

**Melhoria sugerida:** complementar os testes automatizados com testes funcionais dos quatro critérios de aceitação sempre que houver tempo e ambiente disponível.

---

## 14. Conclusão da revisão

A revisão da US02 foi realizada considerando diferentes níveis de teste: **testes unitários, teste de integração, teste de aceitação e análise de cobertura**.

Os testes unitários relacionados às regras de autenticação passaram, demonstrando que os principais comportamentos isolados da função de autenticação estão sendo verificados.

Nos testes de aceitação realizados diretamente no sistema, o login com credenciais válidas funcionou corretamente e direcionou o usuário para `funcoes.php`. O cenário de senha incorreta também apresentou o comportamento esperado, negando o acesso e exibindo a mensagem de credenciais inválidas.

O teste de integração apresentou falha devido à dependência de um registro específico que não estava presente no banco de dados de teste. Esse ponto foi registrado como uma questão relacionada à preparação dos dados do teste.

A cobertura obtida foi de 11,04% das linhas na execução dos testes selecionados, enquanto o `AdministradorRepository` apresentou 90% de cobertura de linhas.

Dessa forma, a revisão contribuiu para a garantia de qualidade da Iteração 1, exercitando o fluxo de desenvolvimento em que o discente atua tanto como **Desenvolvedor** quanto como **QA Engineer / Testador**, realizando a validação da implementação de outro integrante da equipe e registrando os resultados, evidências e pontos de melhoria encontrados.

Esse formato fica bem mais completo e mostra claramente **sua atuação como desenvolvedora + revisora + testadora**, em vez de parecer que você só rodou PHPUnit.
