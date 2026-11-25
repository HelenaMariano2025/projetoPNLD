# Relatório de Testes de Aceitação — US03

## 1. Identificação

* **Testadora (QA):** Jaine Souza da Luz
* **Desenvolvedora:** Isabelle Cavalcanti da Silva
* **Data:** 07/10/2026
* **User Story:** US03 — Gerenciar alunos
* **Branch testada:** `feat/us03-gerenciar-alunos`
* **PR testado:** [PR #12](https://github.com/HelenaMariano2025/projetoPNLD/pull/12)
* **Sistema:** Projeto PNLD — HBL Control / Sistema BHL

## 2. Objetivo

Este relatório apresenta os resultados dos testes de aceitação realizados sobre a **US03 — Gerenciar alunos**, com o objetivo de verificar se as funcionalidades implementadas atendem aos critérios de aceitação definidos para a User Story.

Os testes foram realizados manualmente, utilizando a interface da aplicação, contemplando cadastro, consulta, alteração, mudança de turma, inativação e pesquisa de alunos.

## 3. Ambiente de Testes

* **Sistema operacional:** Windows
* **Navegador:** Google Chrome
* **Execução:** Docker Compose
* **Aplicação:** PHP
* **Banco de dados:** MySQL 8.4
* **Banco:** `SistemaHBL`
* **URL utilizada:** `http://localhost:8080/aluno.php`
* **Tipo de teste:** Teste de aceitação manual

## 4. Cenários de Teste

### TA03.01 — Cadastro válido de aluno

**Objetivo:** Verificar se é possível cadastrar um aluno com dados válidos.

**Procedimento:**

1. Acessar a tela de gerenciamento de alunos.
2. Preencher os campos obrigatórios com dados válidos.
3. Selecionar uma turma válida.
4. Confirmar o cadastro.

**Resultado esperado:**
O aluno deve ser cadastrado com sucesso e uma mensagem de confirmação deve ser apresentada.

**Resultado obtido:**
O aluno foi cadastrado corretamente e a aplicação apresentou mensagem informando que o aluno foi registrado com sucesso.

**Status:** PASSOU

**Evidência:** `docs/qa/evidencias/ta03-01.jpeg`

---

### TA03.02 — Consulta de aluno

**Objetivo:** Verificar se um aluno cadastrado pode ser consultado corretamente.

**Procedimento:**

1. Acessar a tela de gerenciamento de alunos.
2. Consultar um aluno previamente cadastrado.
3. Verificar os dados apresentados.

**Resultado esperado:**
Os dados do aluno devem ser apresentados corretamente.

**Resultado obtido:**
O aluno foi localizado e seus dados foram apresentados corretamente.

**Status:** PASSOU

**Evidência:** `docs/qa/evidencias/ta03-02.jpeg`

---

### TA03.03 — Alteração dos dados do aluno

**Objetivo:** Verificar se os dados de um aluno podem ser alterados.

**Procedimento:**

1. Localizar um aluno cadastrado.
2. Selecionar a opção de edição.
3. Alterar os dados necessários.
4. Salvar as alterações.

**Resultado esperado:**
Os dados devem ser atualizados e a aplicação deve informar que a alteração foi realizada com sucesso.

**Resultado obtido:**
Os dados do aluno foram alterados corretamente e a aplicação apresentou mensagem de confirmação da atualização.

**Status:** PASSOU

**Evidência:** `docs/qa/evidencias/ta03-03.jpeg`

---

### TA03.04 — Mudança de turma

**Objetivo:** Verificar se é possível alterar a turma associada a um aluno.

**Procedimento:**

1. Localizar um aluno cadastrado.
2. Acessar a opção de edição.
3. Selecionar uma nova turma válida.
4. Salvar as alterações.

**Resultado esperado:**
A turma do aluno deve ser atualizada corretamente.

**Resultado obtido:**
A turma associada ao aluno foi alterada corretamente.

**Status:** PASSOU

**Evidência:** `docs/qa/evidencias/ta03-04.jpeg`

---

### TA03.05 — Inativação de aluno

**Objetivo:** Verificar se um aluno pode ser inativado sem que seu histórico seja perdido.

**Procedimento:**

1. Localizar um aluno cadastrado.
2. Selecionar a opção de inativação.
3. Confirmar a operação.
4. Verificar as mensagens apresentadas pelo sistema.

**Resultado esperado:**
O aluno deve ser inativado e o sistema deve informar que seu histórico será preservado.

**Resultado obtido:**
O sistema apresentou o aviso sobre a preservação do histórico e, após a confirmação, informou que o aluno foi inativado com sucesso.

**Status:** PASSOU

**Evidência:** `docs/qa/evidencias/ta03-05.jpeg`

---

### TA03.06 — Pesquisa por nome

**Objetivo:** Verificar se a pesquisa de alunos pelo nome funciona corretamente.

**Procedimento:**

1. Acessar a tela de gerenciamento de alunos.
2. Informar o nome ou parte do nome de um aluno cadastrado.
3. Executar a pesquisa.

**Resultado esperado:**
O sistema deve apresentar os alunos correspondentes ao termo informado.

**Resultado obtido:**
A pesquisa retornou corretamente o aluno correspondente ao nome informado.

**Status:** PASSOU

**Evidência:** `docs/qa/evidencias/ta03-06.jpeg`

---

### TA03.07 — Pesquisa sem resultado

**Objetivo:** Verificar o comportamento do sistema quando uma pesquisa não encontra alunos correspondentes.

**Procedimento:**

1. Acessar a tela de gerenciamento de alunos.
2. Informar um nome inexistente.
3. Executar a pesquisa.

**Resultado esperado:**
O sistema deve informar que nenhum aluno foi encontrado.

**Resultado obtido:**
O sistema apresentou uma mensagem adequada informando que não havia resultados para a pesquisa realizada.

**Status:** PASSOU

**Evidência:** `docs/qa/evidencias/ta03-07.jpeg`

## 5. Resultado dos Testes

| Cenário | Descrição                | Resultado |
| ------- | ------------------------ | --------- |
| TA03.01 | Cadastro válido de aluno | PASSOU    |
| TA03.02 | Consulta de aluno        | PASSOU    |
| TA03.03 | Alteração dos dados      | PASSOU    |
| TA03.04 | Mudança de turma         | PASSOU    |
| TA03.05 | Inativação de aluno      | PASSOU    |
| TA03.06 | Pesquisa por nome        | PASSOU    |
| TA03.07 | Pesquisa sem resultado   | PASSOU    |

**Total de cenários executados:** 7
**Cenários aprovados:** 7
**Cenários reprovados:** 0
**Cenários bloqueados:** 0

## 6. Bugs Encontrados

Durante a execução dos testes de aceitação, **não foram identificados bugs que impedissem o funcionamento dos critérios de aceitação da US03**.

Todos os sete cenários previstos foram executados e apresentaram o comportamento esperado.

## 7. Sugestões de Melhoria

Embora não tenham sido identificadas falhas nos critérios de aceitação testados, foi observada uma oportunidade de melhoria na interface:

* Disponibilizar uma forma de consultar ou visualizar explicitamente os alunos que foram inativados.

Essa sugestão não foi considerada um bug, pois a funcionalidade de inativação foi executada corretamente e os critérios de aceitação testados foram atendidos.

## 8. Limitações dos Testes

Os testes realizados neste relatório foram testes de aceitação **manuais e funcionais**, concentrados no comportamento da aplicação pela interface do usuário.

Não foram realizados, neste relatório:

* testes de desempenho;
* testes de segurança;
* testes de carga;
* análise aprofundada do código-fonte;
* testes automatizados adicionais.

O objetivo foi validar a implementação da US03 sob a perspectiva de usuário e verificar o atendimento aos cenários de aceitação definidos para a User Story.

## 9. Conclusão

Com base nos testes realizados, a **US03 — Gerenciar alunos** apresentou comportamento adequado para os cenários de aceitação avaliados.

Os **7 cenários executados foram aprovados**, não sendo identificados bugs durante a execução dos testes.

Dessa forma, sob a perspectiva dos testes de aceitação realizados, a implementação da US03 está **aprovada para os cenários avaliados**.
