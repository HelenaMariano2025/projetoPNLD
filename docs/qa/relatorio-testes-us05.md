# Relatório de Testes — US05

## 1. Identificação

* **Testadora (QA):** Helena
* **Desenvolvedora:** Isabelle Cavalcanti da Silva
* **Data:** 09/10/2026
* **User Story:** US05 — Gerenciar livros
* **Branch testada:** `feat/us05-gerenciar-livros`
* **PR testado:** [PR #16](https://github.com/HelenaMariano2025/projetoPNLD/pull/16)
* **Sistema:** Projeto PNLD — HBL Control / Sistema BHL

## 2. Objetivo

Este relatório apresenta os resultados dos testes realizados sobre a **US05 — Gerenciar livros**, com o objetivo de verificar o comportamento das funcionalidades implementadas e sua conformidade com os critérios de aceitação definidos para a User Story.

A avaliação contempla testes automatizados unitários e de integração relacionados à validação dos dados, ao cadastro, à consulta, à alteração e à exclusão de livros, incluindo a restrição de exclusão de livros vinculados a empréstimos.

## 3. Ambiente de Testes

* **Sistema operacional:** Linux
* **Linguagem da aplicação:** PHP
* **Versão do PHP:** 8.4.8
* **Framework de testes:** PHPUnit 12.5.35
* **Banco de dados:** MariaDB 11.8.1
* **Banco utilizado:** `SistemaHBL`
* **Mecanismo de armazenamento das tabelas verificadas:** InnoDB
* **Tipo de testes executados:** Testes automatizados unitários e de integração
* **Ambiente de execução:** Worktree isolada da branch `feat/us05-gerenciar-livros`

## 4. Cenários de Teste

### TA05.01 — Cadastro válido de livro

**Objetivo:** Verificar se um livro pode ser cadastrado com os dados obrigatórios válidos.

**Procedimento:**

1. Verificar a validação dos dados de cadastro.
2. Executar o teste automatizado de cadastro no repositório.
3. Verificar, no teste de integração, a persistência dos dados do livro no banco.

**Resultado esperado:** O livro deve ser cadastrado corretamente, com seus dados persistidos no banco de dados.

**Resultado obtido:** Os testes automatizados relacionados ao repositório e à persistência dos dados foram aprovados. O teste de integração verifica os campos do livro cadastrado. A mensagem de sucesso na interface foi validada manualmente.

**Status:** VALIDADO

### TA05.02 — Consulta de livro

**Objetivo:** Verificar se os dados de um livro cadastrado podem ser consultados corretamente.

**Procedimento:**

1. Cadastrar um livro temporário durante o teste de integração.
2. Consultar o livro pelo repositório.
3. Conferir os dados retornados pelo banco de dados.

**Resultado esperado:** Os dados do livro devem ser recuperados corretamente.

**Resultado obtido:** O teste de integração foi aprovado e verificou os dados retornados para o livro cadastrado. A apresentação de todos os campos na interface foi validada manualmente.

**Status:** VALIDADO

### TA05.03 — Alteração dos dados do livro

**Objetivo:** Verificar se os dados de um livro podem ser alterados e recuperados após a atualização.

**Procedimento:**

1. Cadastrar um livro temporário.
2. Alterar seus dados por meio do repositório.
3. Consultar novamente o registro.
4. Verificar os dados atualizados.

**Resultado esperado:** Os dados devem ser atualizados corretamente e permanecer disponíveis para consulta.

**Resultado obtido:** O teste de integração foi aprovado e verificou os dados após a alteração. A mensagem de confirmação e o comportamento da tela de edição foram validados manualmente.

**Status:** VALIDADO

### TA05.04 — Exclusão de livro sem vínculos

**Objetivo:** Verificar se um livro sem empréstimos associados pode ser excluído.

**Procedimento:**

1. Cadastrar um livro temporário.
2. Executar a exclusão pelo repositório.
3. Consultar o banco de dados para verificar a remoção do registro.
4. Verificar se o livro deixa de ser retornado pela consulta.

**Resultado esperado:** O livro deve ser excluído corretamente e não deve aparecer nas consultas posteriores.

**Resultado obtido:** O teste de integração foi aprovado, verificando a exclusão do registro e sua ausência na consulta posterior.

**Status:** PASSOU — TESTE AUTOMATIZADO

### TA05.05 — Pesquisa por título e restrição de exclusão de livro vinculado

**Objetivo:** Verificar a pesquisa de livros pelo título e impedir a exclusão de livros associados a empréstimos.

**Procedimento:**

1. Executar o teste unitário de pesquisa por título.
2. Verificar se a consulta utiliza o termo informado na pesquisa.
3. Criar um livro temporário associado a um empréstimo.
4. Tentar excluir o livro.
5. Verificar se a exclusão é bloqueada e se o histórico permanece preservado.

**Resultado esperado:** A pesquisa deve retornar os livros correspondentes ao título informado. Um livro vinculado a empréstimos não deve ser excluído.

**Resultado obtido:** Os testes unitários e de integração relacionados à pesquisa e à restrição de exclusão foram aprovados. A pesquisa parcial por título e as mensagens apresentadas pela interface foram validados manualmente.

**Status:** VALIDADO

### TA05.06 — Pesquisa sem resultados

**Objetivo:** Verificar o comportamento do sistema quando nenhum livro corresponde ao termo pesquisado.

**Procedimento:**

1. Informar um título inexistente na pesquisa.
2. Executar a consulta.
3. Verificar o retorno da aplicação e a mensagem apresentada ao usuário.

**Resultado esperado:** O sistema deve informar que nenhum livro foi encontrado.

**Resultado obtido:** O teste de integração verifica que um livro excluído deixa de ser retornado pela consulta. Mensagens apresentadas pela interface foram validados manualmente.

**Status:** VALIDADO

## 5. Resultado dos Testes Automatizados

| Tipo de teste        | Testes executados | Verificações | Resultado  |
| -------------------- | ----------------: | -----------: | ---------- |
| Testes unitários     |                14 |           52 | PASSOU     |
| Testes de integração |                 2 |           39 | PASSOU     |
| **Total**            |            **16** |       **91** | **PASSOU** |

### Evidências de execução

**Testes unitários:**

* Arquivos: `LivroRepositoryTest.php`, `LivroValidatorTest.php` e `LivroExclusaoTest.php`
* Resultado: 14 testes aprovados e 52 verificações.

**Testes de integração:**

* Arquivos: `LivroRepositoryIntegrationTest.php` e `LivroExclusaoIntegrationTest.php`
* Resultado: 2 testes aprovados e 39 verificações.

Os resultados foram obtidos com o PHPUnit 12.5.35, utilizando PHP 8.4.8 e conexão com o banco de dados MariaDB.

## 6. Bugs Encontrados

Durante os testes automatizados executados, não foram identificadas falhas nos cenários cobertos pelos testes.

Os 16 testes automatizados foram aprovados, totalizando 91 verificações.


## 8. Limitações dos Testes

A limpeza dos dados temporários foi prevista nos testes de integração por meio de transações e `ROLLBACK`. Entretanto, a presença de um registro denominado `Livro de Teste` no banco não foi suficiente para determinar sua origem, e sua exclusão não foi realizada.

## 9. Conclusão

Os testes automatizados executados para a **US05 — Gerenciar livros** apresentaram resultados satisfatórios: 16 testes aprovados e 91 verificações, sem falhas ou erros na execução registrada.

Os testes forneceram evidências favoráveis ao funcionamento das operações de repositório, à validação dos dados e à restrição de exclusão de livros vinculados a empréstimos.

