# Plano da Iteração 1 — Projeto PNLD

## 1. Identificação

| Campo | Informação |
| --- | --- |
| Projeto | PNLD — Controle de Livros Didáticos / HBL Control |
| Disciplina | DCT2304 — Teste de Software |
| Turma | 2026.2 — CERES/Caicó |
| Unidade | I |
| Iteração | 1 |
| Duração planejada | 21 dias corridos |
| Data de início | 15 de setembro de 2026 |
| Data de término | 26 de setembro de 2026 |
| Integrantes |  Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz |

Este documento apresenta o planejamento da primeira iteração. As atividades descritas são entregas previstas e não representam, por si só, atividades já concluídas.

O cronograma utiliza dias relativos. O Dia 1 corresponde à data de início definida pela equipe.

## 2. Objetivo

Revisar, melhorar e testar as funcionalidades de cadastro de administradores, autenticação e gerenciamento de alunos do Sistema HBL Control.

Como o projeto já possui código dessas funcionalidades, a equipe deverá aproveitar a implementação existente, identificar problemas e realizar as correções necessárias para atender aos critérios de aceitação.

Além das User Stories, a iteração inclui ajustes de segurança, configuração do ambiente de testes, integração contínua e análise de qualidade do código.

## 3. Documentos relacionados

- [Documento de Visão](001-documento-visao.md)
- [Lista de User Stories](004-lista-users-stories.md)
- [Plano de Iterações Geral](../plano-iteracoes-geral.md)

## 4. User Stories selecionadas

| User Story | Analista | Desenvolvedora | Revisora | Testadora/QA |
| --- | --- | --- | --- | --- |
| US01 — Cadastrar administrador | Isabelle | Helena | Jaine | Isabelle |
| US02 — Autenticar administrador e acessar funcionalidades | Helena | Jaine | Isabelle | Helena |
| US03 — Gerenciar alunos | Jaine | Isabelle | Helena | Jaine |

Cada integrante será responsável pelo desenvolvimento de uma User Story e participará da análise, revisão ou validação das demais.

### Estimativas iniciais

| User Story | Estimativa registrada no backlog |
| --- | --- |
| US01 | 5 horas |
| US02 | 6 horas |
| US03 | 10 horas |
| Total | 21 horas |

Essas estimativas deverão ser revisadas no início da iteração, considerando as correções necessárias e as atividades de testes, segurança e configuração. O tempo efetivamente utilizado será registrado nas issues.

## 5. Situação inicial

O sistema já possui páginas de cadastro e autenticação de administradores, gerenciamento de alunos e testes relacionados ao acesso aos dados de alunos.

A leitura do código indica pontos que precisam ser verificados e corrigidos:

- Consultas de cadastro e autenticação utilizam dados do usuário diretamente no SQL.
- As senhas são armazenadas e comparadas em texto simples.
- É necessário verificar a proteção das páginas e operações administrativas.
- Os testes de alunos precisam comprovar o comportamento das operações e os dados retornados.
- É necessário alinhar a exclusão descrita no backlog com a inativação implementada no código.
- Existem configurações de testes, GitHub Actions e SonarQube, mas seus resultados devem ser confirmados por execução.

A presença de testes e configurações não comprova que todos os testes passam ou que os critérios de aceitação estão atendidos.

## 6. Planejamento das atividades

### 6.1 US01 — Cadastrar administrador

**Responsável pelo desenvolvimento:** Helena Dantas Mariano.

| Atividade | Responsável | Resultado esperado |
| --- | --- | --- |
| Detalhar os critérios de aceitação | Isabelle | Casos de cadastro válido, campos obrigatórios e matrícula duplicada documentados |
| Criar testes das regras de cadastro | Helena | Validações verificadas por testes automatizados |
| Corrigir a validação dos campos no servidor | Helena | Cadastro inválido rejeitado com mensagem clara |
| Utilizar consultas preparadas | Helena | Entradas enviadas separadamente dos comandos SQL |
| Proteger o armazenamento das senhas | Helena | Senhas armazenadas com `password_hash` |
| Adequar a coluna de senha no banco | Helena | Campo com tamanho suficiente para armazenar o hash |
| Testar a persistência do cadastro | Helena | Dados válidos salvos e entradas inválidas rejeitadas |
| Revisar código e testes | Jaine | Pull Request revisado e correções solicitadas quando necessário |
| Executar testes de aceitação | Isabelle | Resultados e evidências registrados |

A alteração do armazenamento de senhas deverá ser alinhada com a US02. A equipe também deverá documentar como atualizar ou redefinir as senhas das contas existentes.

### 6.2 US02 — Autenticar administrador e acessar funcionalidades

**Responsável pelo desenvolvimento:** Jaine Souza da Luz.

| Atividade | Responsável | Resultado esperado |
| --- | --- | --- |
| Detalhar os critérios de aceitação | Helena | Casos de login e controle de acesso documentados |
| Criar testes de autenticação | Jaine | Login válido, credenciais inválidas e campos vazios verificados |
| Corrigir as consultas do login | Jaine | Uso de consultas preparadas |
| Adequar a verificação de senha | Jaine | Autenticação utilizando `password_verify` |
| Proteger páginas e operações administrativas | Jaine | Acesso sem autenticação bloqueado, sem alteração dos dados |
| Revisar o gerenciamento da sessão | Jaine | Identificador renovado após login e sessão encerrada ao sair |
| Testar cadastro seguido de login | Jaine | Integração entre US01 e US02 comprovada |
| Revisar código e testes | Isabelle | Pull Request revisado |
| Executar testes de aceitação | Helena | Resultados e evidências registrados |

O cadastro de uma conta não deverá conceder acesso administrativo sem a autenticação prevista.

### 6.3 US03 — Gerenciar alunos

**Responsável pelo desenvolvimento:** Isabelle Cavalcanti da Silva.

| Atividade | Responsável | Resultado esperado |
| --- | --- | --- |
| Detalhar os critérios de aceitação | Jaine | Casos de cadastro, consulta, alteração, remoção e pesquisa documentados |
| Definir a regra de exclusão ou inativação | Jaine, com a equipe | Backlog e comportamento implementado alinhados |
| Revisar as páginas de gerenciamento de alunos | Isabelle | Problemas identificados e operações conectadas às regras testadas |
| Criar ou ampliar testes unitários | Isabelle | Regras de alunos verificadas de forma isolada |
| Corrigir validações de cadastro e alteração | Isabelle | Campos obrigatórios e associação com turma existente verificados |
| Ampliar os testes de integração | Isabelle | Persistência, atualização, consulta e remoção/inativação verificadas |
| Verificar pesquisa por nome | Isabelle | Resultados correspondentes e ausência de resultados tratados corretamente |
| Revisar código e testes | Helena | Pull Request revisado |
| Executar testes de aceitação | Jaine | Resultados e evidências registrados |

Os testes deverão utilizar turmas e alunos fictícios em banco exclusivo de testes. A regra de remoção deverá considerar a preservação dos vínculos e do histórico.

### 6.4 Atividades de apoio

| Atividade | Responsável principal | Apoio |
| --- | --- | --- |
| Preparar banco isolado e dados de testes | Helena | Isabelle e Jaine |
| Conferir compatibilidade entre PHP, Composer e PHPUnit | Helena | Isabelle e Jaine |
| Executar os testes existentes e registrar o diagnóstico inicial | Isabelle | Helena e Jaine |
| Revisar e validar o GitHub Actions | Isabelle | Helena e Jaine |
| Conferir configuração e executar SonarQube | Jaine | Isabelle e Helena |
| Gerar e registrar cobertura de código | Isabelle | Helena e Jaine |
| Atualizar documentação e links no README | Isabelle | Helena e Jaine |
| Registrar resultados e pendências da iteração | Todas | — |

## 7. Cronograma

| Período | Atividades |
| --- | --- |
| Dias 1 a 3 | Análise das histórias, detalhamento dos critérios, preparação do ambiente e execução inicial dos testes |
| Dias 4 a 7 | Criação de testes e início das correções das US01, US02 e US03 |
| Dias 8 a 13 | Conclusão das implementações e dos testes de unidade e integração |
| Dias 14 a 17 | Revisão dos Pull Requests, correções e reexecução dos testes |
| Dias 18 a 20 | Testes de aceitação, análise de qualidade, cobertura e registro de evidências |
| Dia 21 | Revisão final, integração das entregas aprovadas e registro das pendências |

As atividades das três histórias poderão ocorrer em paralelo, respeitando suas dependências.

## 8. Dependências

- US01 e US02 precisam utilizar a mesma estratégia de armazenamento e verificação de senhas.
- US03 precisa de turmas existentes no banco de testes.
- A validação integrada de US03 depende do controle de acesso da US02.
- A execução automatizada depende de versões compatíveis de PHP e PHPUnit.
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
- Limpar os dados utilizados de forma segura.
- Não modificar registros reais.

### 9.3 Testes de aceitação

| User Story | Casos mínimos |
| --- | --- |
| US01 | TA01.01 a TA01.04: cadastro válido e ausência de matrícula, nome ou senha |
| US02 | TA02.01 a TA02.06: login válido, credenciais inválidas, campos obrigatórios e controle de acesso |
| US03 | TA03.01 a TA03.07: cadastro, consulta, alteração, mudança de turma, remoção e pesquisa |

No backlog, os identificadores escritos como `T402.05` e `T402.06` deverão ser padronizados para `TA02.05` e `TA02.06`.

Também deverão ser executados os casos adicionais definidos durante a análise, como matrícula duplicada e acesso após encerramento da sessão.

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

| Risco | Consequência | Tratamento |
| --- | --- | --- |
| Incompatibilidade entre cadastro e login após proteção das senhas | Administrador não consegue acessar o sistema | Coordenar US01 e US02 e testar o fluxo completo |
| Falhas de configuração do ambiente | Testes e CI não executam | Validar dependências e banco no início da iteração |
| Testes alterarem dados reais | Perda ou alteração indevida de informações | Utilizar banco isolado e dados fictícios |
| Exclusão no backlog diferente da inativação existente | Critérios de aceitação inconsistentes | Definir a regra e atualizar documentação e testes |
| Testes dependerem de arquivos ausentes | Bloqueio da suíte completa | Registrar issues e diferenciar execução parcial de completa |
| Esforço maior que o previsto | Atraso nas entregas | Reestimar tarefas e registrar ajustes no planejamento |

## 12. Controle no GitHub

As histórias e atividades deverão ser registradas em issues.

O trabalho deverá utilizar:

- Branches específicas.
- Commits convencionais referenciando as issues.
- Pull Requests destinados à branch `main`.
- Revisão por outra integrante.
- Registro dos testes e evidências no Pull Request ou na issue.

Exemplos de mensagens, substituindo `NUMERO` pela issue correspondente:

- `docs: adiciona plano da iteração 1 (#NUMERO)`
- `fix: valida cadastro de administrador (#NUMERO)`
- `test: adiciona testes de autenticação (#NUMERO)`
- `fix: ajusta gerenciamento de alunos (#NUMERO)`

## 13. Entregáveis

- US01, US02 e US03 revisadas e corrigidas conforme os critérios aprovados.
- Testes de unidade e integração relacionados às histórias.
- Registro dos testes de aceitação.
- Evidências da execução do GitHub Actions.
- Relatório de cobertura com identificação da versão e do escopo.
- Registro da análise do SonarQube.
- Documentação e README atualizados.
- Relatório de encerramento com resultados, tempo utilizado e pendências.

## 14. Observações sobre o planejamento

O planejamento considera tanto as User Stories quanto atividades de testes, ajustes e segurança do sistema, conforme a organização discutida pela equipe com o professor.

Este documento detalha somente a Iteração 1. A organização das demais etapas deverá permanecer registrada no Plano de Iterações Geral.

Mudanças de escopo, responsáveis, datas ou critérios deverão ser documentadas pela equipe.

## 15. Histórico de revisões

| Data | Versão | Descrição |
| --- | --- | --- |
| 06/10/2026 | 1.0 | Elaboração do planejamento da Iteração 1 a partir do backlog, do plano geral e da leitura do código existente |