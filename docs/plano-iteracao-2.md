# Plano da Iteração 2 — Projeto PNLD

## 1. Identificação

| **Campo**         | **Informação**                                                           |
| ----------------- | ------------------------------------------------------------------------ |
| Projeto           | PNLD — Controle de Livros Didáticos / HBL Control                        |
| Disciplina        | DCT2304 — Teste de Software                                              |
| Turma             | 2026.2 — CERES/Caicó                                                     |
| Unidade           | I                                                                        |
| Iteração          | 2                                                                        |
| Duração planejada | 21 dias corridos                                                         |
| Data de início    | 27 de setembro de 2026                                                   |
| Data de término   | 17 de outubro de 2026                                                    |
| Integrantes       | Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz |

Este documento apresenta o planejamento da segunda iteração. As atividades descritas são entregas previstas e não representam, por si só, atividades já concluídas.

O cronograma utiliza dias relativos. O Dia 1 corresponde à data de início definida pela equipe.

---

## 2. Objetivo

Dar continuidade ao desenvolvimento, melhoria e teste das funcionalidades do Sistema HBL Control relacionadas ao gerenciamento de turmas, gerenciamento de livros e registro de empréstimos.

A equipe deverá aproveitar as implementações existentes dessas funcionalidades, identificar problemas e realizar as correções necessárias para atender aos critérios de aceitação definidos para as User Stories.

Além das User Stories, a iteração inclui atividades de testes unitários e de integração, revisão de código, testes de aceitação, integração das funcionalidades e acompanhamento da qualidade do código.

## 3. Documentos relacionados

- [Documento de Visão] - https://github.com/HelenaMariano2025/projetoPNLD/blob/main/docs/001-documento-visao.md
- [Lista de User Stories] - https://github.com/HelenaMariano2025/projetoPNLD/blob/main/docs/004-lista-users-stories.md
- [Plano da Iteração 1] - https://github.com/HelenaMariano2025/projetoPNLD/blob/main/docs/plano-iteracao-1.md
- [Relatório do Estado Atual dos Testes e Débito Técnico] - https://github.com/HelenaMariano2025/projetoPNLD/blob/main/docs/relatorio-estado-atual-testes.md

## 4. User Stories selecionadas

| **User Story**               | **Analista** | **Desenvolvedora** | **Revisora** | **Testadora/QA** |
| ---------------------------- | ------------ | ------------------ | ------------ | ---------------- |
| US04 — Gerenciar turmas      | Isabelle     | Jaine              | Helena       | Isabelle         |
| US05 — Gerenciar livros      | Helena       | Isabelle            | Jaine        | Helena           |
| US06 — Registrar empréstimos | Jaine        | Helena             | Isabelle     | Jaine            |

Cada integrante será responsável pelo desenvolvimento de uma User Story e participará da análise, revisão ou validação das demais.

### Estimativas iniciais

| **User Story**               | **Estimativa** |
| ---------------------------- | -------------- |
| US04 - Gerenciar Turmas      | 6h             |
| US05 - Gerenciar Livros      | 7h             |
| US06 - Registrar empréstimos | 10h            |
| **Total**                    | **23h**        |

As estimativas deverão ser revisadas no início da iteração, considerando as implementações existentes, as correções necessárias e as atividades de testes. O tempo efetivamente utilizado será registrado nas issues.

## 5. Situação inicial

O sistema já possui funcionalidades relacionadas ao gerenciamento de turmas, gerenciamento de livros e operações de empréstimos.

A análise do estado atual do projeto indica que as funcionalidades relacionadas aos empréstimos possuem pontos que precisam ser verificados e corrigidos, especialmente em relação às regras de disponibilidade dos livros, cálculo da data de devolução e integração entre as regras testadas e as páginas utilizadas pelo sistema.

Também existem débitos relacionados aos testes e à integração das regras de negócio com o código efetivamente utilizado pelas páginas do sistema.

Para a Iteração 2, deverão ser verificados:

- O funcionamento do cadastro, consulta, alteração, exclusão e pesquisa de turmas.
- A validação das regras relacionadas à exclusão de turmas que possuam alunos vinculados.
- O funcionamento do cadastro, consulta, alteração, exclusão e pesquisa de livros.
- A validação das regras relacionadas à exclusão de livros que possuam empréstimos ou devoluções associados.
- O funcionamento do registro de empréstimos.
- A seleção de alunos e exemplares disponíveis.
- A validação de empréstimos sem aluno informado.
- A validação de empréstimos sem exemplar informado.
- A validação da disponibilidade do exemplar.
- O armazenamento correto da data prevista para devolução.
- A atualização da quantidade disponível de exemplares após o empréstimo.
- A integração entre as regras implementadas, os testes e as páginas efetivamente utilizadas pelo sistema.
- A execução dos testes automatizados e o registro de seus resultados.

A presença de código, testes ou configurações não será considerada, isoladamente, como evidência de que os critérios de aceitação estão atendidos. Os comportamentos deverão ser confirmados por execução dos testes.

## 6. Planejamento das atividades

### 6.1 US04 — Gerenciar turmas

**Responsável pelo desenvolvimento:** Jaine Souza da Luz.

| **Atividade**                                | **Responsável** | **Resultado esperado**                                                      |
| -------------------------------------------- | --------------- | --------------------------------------------------------------------------- |
| Detalhar os critérios de aceitação           | Isabelle        | Casos de cadastro, consulta, alteração, exclusão e pesquisa documentados    |
| Revisar a implementação existente de turmas | Jaine           | Problemas e diferenças em relação aos critérios identificados               |
| Criar ou ampliar testes unitários            | Jaine           | Regras de gerenciamento de turmas verificadas de forma isolada              |
| Criar ou ampliar testes de integração        | Jaine           | Persistência, consulta, alteração e exclusão verificadas no banco de testes |
| Corrigir validações do cadastro              | Jaine           | Dados obrigatórios e regras de cadastro devidamente validados               |
| Verificar alteração de turma                 | Jaine           | Dados da turma atualizados corretamente                                     |
| Verificar exclusão de turma                  | Jaine           | Turma sem alunos vinculados pode ser removida conforme o requisito          |
| Validar proteção contra exclusão indevida    | Jaine           | Turmas com alunos vinculados não são removidas indevidamente                |
| Verificar pesquisa por curso                 | Jaine           | Resultados correspondentes apresentados corretamente                        |
| Revisar código e testes                      | Helena          | Pull Request revisado e correções solicitadas quando necessário             |
| Executar testes de aceitação                 | Isabelle        | Resultados e evidências registrados                                         |

Os testes deverão utilizar turmas e alunos fictícios em banco exclusivo de testes.

### 6.2 US05 — Gerenciar livros

**Responsável pelo desenvolvimento:** Isabelle Cavalcanti da Silva.

| **Atividade**                                | **Responsável** | **Resultado esperado**                                                   |
| -------------------------------------------- | --------------- | ------------------------------------------------------------------------ |
| Detalhar os critérios de aceitação           | Helena          | Casos de cadastro, consulta, alteração, exclusão e pesquisa documentados |
| Revisar a implementação existente de livros  | Isabelle        | Problemas e diferenças em relação aos critérios identificados            |
| Criar ou ampliar testes unitários             | Isabelle        | Regras de gerenciamento de livros verificadas de forma isolada           |
| Criar ou ampliar testes de integração         | Isabelle        | Persistência, consulta, alteração, exclusão e pesquisa verificadas       |
| Corrigir validações do cadastro               | Isabelle        | Dados obrigatórios e regras de cadastro devidamente validados            |
| Verificar alteração de livro                  | Isabelle        | Dados do livro atualizados corretamente                                  |
| Verificar exclusão de livro                   | Isabelle        | Livro sem empréstimos ou devoluções associados pode ser removido         |
| Validar proteção contra exclusão indevida     | Isabelle        | Livro com empréstimos ou devoluções não é removido indevidamente         |
| Verificar pesquisa por título                 | Isabelle        | Resultados correspondentes apresentados corretamente                     |
| Revisar código e testes                       | Jaine           | Pull Request revisado e correções solicitadas quando necessário          |
| Executar testes de aceitação                  | Helena          | Resultados e evidências registrados                                      |

Os testes deverão utilizar livros fictícios e dados controlados, evitando alterações em registros reais do sistema.

### 6.3 US06 — Registrar empréstimos

**Responsável pelo desenvolvimento:** Helena Dantas Mariano.

| **Atividade**                                    | **Responsável** | **Resultado esperado**                                          |
| ------------------------------------------------ | --------------- | --------------------------------------------------------------- |
| Detalhar os critérios de aceitação               | Jaine           | Casos de empréstimo válido e situações inválidas documentados   |
| Revisar a implementação existente de empréstimos | Helena          | Problemas e diferenças em relação aos critérios identificados   |
| Criar ou ampliar testes unitários                | Helena          | Regras de empréstimo verificadas de forma isolada               |
| Criar ou ampliar testes de integração            | Helena          | Registro e persistência dos empréstimos verificados             |
| Validar seleção de aluno                         | Helena          | Empréstimo somente realizado com aluno válido                   |
| Validar seleção de exemplar                      | Helena          | Empréstimo somente realizado com exemplar válido                |
| Validar disponibilidade do exemplar              | Helena          | Empréstimo de exemplar indisponível rejeitado                   |
| Registrar empréstimo válido                      | Helena          | Empréstimo associado ao aluno, exemplar e administrador         |
| Verificar data prevista de devolução             | Helena          | Data prevista armazenada corretamente                           |
| Verificar atualização da quantidade disponível   | Helena          | Quantidade disponível atualizada após o empréstimo              |
| Revisar código e testes                          | Isabelle        | Pull Request revisado e correções solicitadas quando necessário |
| Executar testes de aceitação                     | Jaine           | Resultados e evidências registrados                             |

A implementação deverá considerar os problemas identificados no relatório de estado atual relacionados à quantidade disponível, cálculo da data de devolução e integração entre as regras de negócio e as páginas utilizadas pelo sistema.

### 6.4 Atividades de apoio

| **Atividade**                                                    | **Responsável principal** | **Apoio**         |
| ---------------------------------------------------------------- | ------------------------- | ----------------- |
| Preparar banco isolado e dados de testes                         | Helena                    | Isabelle e Jaine  |
| Conferir compatibilidade entre PHP, Composer e PHPUnit           | Helena                    | Isabelle e Jaine  |
| Executar os testes existentes e registrar o diagnóstico inicial | Isabelle                  | Helena e Jaine    |
| Revisar e validar o GitHub Actions                               | Isabelle                  | Helena e Jaine    |
| Conferir configuração e executar SonarQube                       | Jaine                     | Isabelle e Helena |
| Gerar e registrar cobertura de código                            | Isabelle                  | Helena e Jaine    |
| Atualizar documentação e links no README                         | Isabelle                  | Helena e Jaine    |
| Registrar resultados e pendências da iteração                    | Todas                     | —                 |

## 7. Cronograma

| **Período**  | **Atividades**                                                                                           |
| ------------ | -------------------------------------------------------------------------------------------------------- |
| Dias 1 a 3   | Análise das histórias, detalhamento dos critérios, preparação do ambiente e execução inicial dos testes  |
| Dias 4 a 7   | Criação de testes e início das correções das US04, US05 e US06                                           |
| Dias 8 a 13  | Conclusão das implementações e dos testes de unidade e integração                                        |
| Dias 14 a 17 | Revisão dos Pull Requests, correções e reexecução dos testes                                             |
| Dias 18 a 20 | Testes de aceitação, análise de qualidade, cobertura e registro de evidências                            |
| Dia 21       | Revisão final, integração das entregas aprovadas e registro das pendências                               |

As atividades das três histórias poderão ocorrer em paralelo, respeitando suas dependências.

## 8. Dependências

- US04 depende da existência de dados de turmas e, para alguns cenários, de alunos vinculados no banco de testes.
- US05 precisa considerar os vínculos existentes entre livros, empréstimos e devoluções.
- US06 depende de alunos e exemplares de livros cadastrados para realização dos empréstimos.
- A validação integrada de US06 depende das funcionalidades de alunos e livros.
- A execução automatizada depende de versões compatíveis de PHP e PHPUnit.
- Os testes de empréstimos dependem de dados controlados e de um banco exclusivo para testes.
- Testes existentes que dependam de arquivos ainda ausentes deverão ter seus bloqueios registrados.

Se um bloqueio impedir a suíte completa, a equipe poderá executar os testes específicos das histórias, identificando expressamente que o resultado é parcial.

## 9. Estratégia de testes

### 9.1 Testes de unidade

Verificar regras e componentes de forma isolada, utilizando simulações quando necessário.

Para as regras desenvolvidas com TDD, seguir o ciclo:

1. Criar um teste que falhe pelo comportamento esperado.
2. Implementar a correção necessária.
3. Executar novamente o teste.
4. Melhorar o código mantendo os testes passando.

### 9.2 Testes de integração

Verificar a comunicação com o MySQL e confirmar os dados efetivamente persistidos.

Os testes deverão:

- Utilizar banco exclusivo de testes.
- Criar dados fictícios e controlados.
- Conferir valores, filtros e associações.
- Verificar as relações entre alunos, turmas, livros e empréstimos.
- Verificar a atualização da quantidade disponível dos livros.
- Limpar os dados utilizados de forma segura.
- Não modificar registros reais.

### 9.3 Testes de aceitação

| **User Story** | **Casos mínimos**                                                                                  |
| -------------- | -------------------------------------------------------------------------------------------------- |
| US04           | TA04.01 a TA04.06: cadastro, consulta, alteração, exclusão e pesquisa de turmas                   |
| US05           | TA05.01 a TA05.06: cadastro, consulta, alteração, exclusão e pesquisa de livros                   |
| US06           | TA06.01 a TA06.06: seleção de aluno e exemplar, registro de empréstimo, data prevista e validações |

Também deverão ser executados casos adicionais identificados durante a implementação e revisão, especialmente aqueles relacionados à disponibilidade de exemplares e à integração das regras de empréstimos.

### 9.4 Registro de evidências

Para cada teste de aceitação, registrar:

- Identificador do teste.
- Responsável e data.
- Versão ou commit testado.
- Dados fictícios utilizados.
- Resultado esperado.
- Resultado obtido.
- Situação: aprovado, reprovado ou bloqueado.
- Evidência e issue relacionada, quando houver.
- Resultado do reteste após a correção.

### 9.5 Cobertura e qualidade

O relatório de cobertura deverá identificar o commit, o comando utilizado e os arquivos medidos.

A configuração atual inclui a pasta `php/`. Enquanto esse escopo permanecer, o percentual não deverá ser apresentado como cobertura de todas as páginas PHP do sistema.

Os resultados do GitHub Actions e do SonarQube deverão ser registrados separadamente. A existência da configuração não será considerada evidência de execução bem-sucedida.

## 10. Critérios de conclusão

Uma User Story será considerada concluída quando:

- Seus critérios de aceitação forem atendidos.
- As páginas utilizarem as regras verificadas pelos testes.
- Os testes relacionados passarem no ambiente de testes.
- Houver evidências da validação.
- O código e os testes forem revisados por outra integrante.
- As correções solicitadas na revisão forem tratadas.
- O Pull Request for aprovado antes da integração na branch `main`.

A iteração será encerrada com o registro das entregas concluídas, dos testes executados e das pendências.

Histórias incompletas deverão permanecer identificadas como pendentes e ser replanejadas.

## 11. Riscos

| **Risco**                                                       | **Consequência**                                                | **Tratamento**                                                  |
| ---------------------------------------------------------------- | --------------------------------------------------------------- | --------------------------------------------------------------- |
| Falhas na integração entre turmas, livros, alunos e empréstimos | Funcionalidades podem apresentar comportamentos inconsistentes | Executar testes de integração e testes de regressão             |
| Problemas na configuração do ambiente                           | Testes e CI não executam                                        | Validar dependências, banco e ferramentas no início da iteração |
| Testes alterarem dados reais                                    | Perda ou alteração indevida de informações                      | Utilizar banco isolado e dados fictícios                        |
| Exclusão de turma com alunos vinculados                         | Perda de vínculos ou inconsistência dos dados                   | Criar testes específicos para impedir a exclusão indevida       |
| Exclusão de livro com empréstimos ou devoluções                 | Perda de histórico ou inconsistência dos dados                  | Validar os vínculos antes da exclusão                           |
| Exemplar indisponível ser emprestado                            | Quantidade disponível pode ficar inconsistente                  | Validar disponibilidade antes do registro do empréstimo         |
| Quantidade disponível não ser atualizada corretamente            | Dados de estoque inconsistentes                                 | Criar testes de integração para empréstimos e disponibilidade   |
| Data de devolução ser calculada incorretamente                   | Informação incorreta para o usuário                             | Criar teste específico para a regra de cálculo da data          |
| Testes dependerem de arquivos ausentes                           | Bloqueio da suíte completa                                      | Registrar issues e diferenciar execução parcial de completa     |
| Esforço maior que o previsto                                     | Atraso nas entregas                                             | Reestimar tarefas e registrar ajustes no planejamento           |

## 12. Controle no GitHub

As histórias e atividades deverão ser registradas em issues.

O trabalho deverá utilizar:

- Branches específicas.
- Commits convencionais referenciando as issues.
- Pull Requests destinadas à branch `main`.
- Revisão por outra integrante.
- Registro dos testes e evidências no Pull Request ou na issue.

Exemplos de mensagens, substituindo `NUMERO` pela issue correspondente:

- `docs: adiciona plano da iteracao 2 (#NUMERO)`
- `feat: implementa gerenciamento de turmas (#NUMERO)`
- `feat: implementa gerenciamento de livros (#NUMERO)`
- `feat: implementa registro de emprestimos (#NUMERO)`
- `test: adiciona testes de emprestimos (#NUMERO)`

## 13. Entregáveis

- US04, US05 e US06 implementadas conforme os critérios aprovados.
- Testes de unidade e integração relacionados às histórias.
- Registro dos testes de aceitação.
- Evidências da execução dos testes.
- Evidências da execução do GitHub Actions.
- Relatório de cobertura com identificação da versão e do escopo.
- Registro da análise do SonarQube.
- Documentação e README atualizados.
- Pull Requests revisadas e aprovadas.
- Relatório de encerramento com resultados, tempo utilizado e pendências.

## 14. Observações sobre o planejamento

O planejamento considera tanto as User Stories quanto as atividades de desenvolvimento, testes, revisão e integração do sistema.

A Iteração 2 terá duração de 21 dias corridos, iniciando em **27 de setembro de 2026**, imediatamente após o término planejado da Iteração 1, e encerrando em **17 de outubro de 2026**.

As três User Stories poderão ser desenvolvidas em paralelo, desde que suas dependências sejam respeitadas.

Mudanças de escopo, responsáveis, datas ou critérios deverão ser documentadas pela equipe.

Este documento detalha somente a Iteração 2. A organização geral das demais iterações deverá permanecer registrada no Plano de Iterações Geral.

## 15. Histórico de revisões

| **Data**   | **Versão** | **Descrição** |
| ---------- | ---------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 07/10/2026 | 1.0        | Elaboração do planejamento da Iteração 2 a partir do backlog, do Plano da Iteração 1 e do estado atual do projeto |