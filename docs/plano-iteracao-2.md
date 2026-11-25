# Plano da Iteração 2 — Projeto PNLD

## 1. Identificação

| Campo | Informação |
|---|---|
| Projeto | PNLD — Controle de Livros Didáticos / HBL Control |
| Disciplina | DCT2304 — Teste de Software |
| Turma | 2026.2 — CERES/Caicó |
| Unidade | I |
| Iteração | 2 |
| Duração planejada | 21 dias corridos |
| Data de início | 27 de setembro de 2026 |
| Data de término | 17 de outubro de 2026 |
| Integrantes | Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz |
| Data desta revisão | 09/10/2026 |
| Versão | 1.1 |

Este documento apresenta o planejamento da segunda iteração. As atividades descritas são entregas previstas e não representam, por si só, atividades já concluídas.

O cronograma utiliza dias relativos. O Dia 1 corresponde a 27 de setembro de 2026.

Nesta revisão, foram consideradas as análises atualizadas da US04 e US05 e o alinhamento com o Plano Geral de Testes e o Plano de Teste das Iterações 1 e 2.

## 2. Objetivo

Dar continuidade ao desenvolvimento, melhoria e teste das funcionalidades do Sistema HBL Control relacionadas ao gerenciamento de turmas, gerenciamento de livros e registro de empréstimos.

A equipe deverá aproveitar as implementações existentes dessas funcionalidades, identificar diferenças em relação às análises e realizar as correções necessárias para atender aos critérios de aceitação.

Para a US04, o planejamento passa a contemplar explicitamente o impedimento de cadastro de turma com código duplicado e o bloqueio da inativação de turma com alunos vinculados.

Para a US05, deverão ser verificados os oito campos do cadastro, a consulta da disponibilidade, a exclusão com e sem vínculos e a pesquisa pelo título com e sem resultados.

Além das User Stories, a iteração inclui testes de unidade e integração, revisão de código, testes de aceitação, integração das funcionalidades e acompanhamento da qualidade do código.

## 3. Documentos relacionados

- [Documento de Visão](001-documento-visao.md).
- [Lista de User Stories](004-lista-users-stories.md).
- [Plano da Iteração 1](plano-iteracao-1.md).
- [Plano Geral de Testes](plano-geral-de-testes.md).
- [Plano de Teste das Iterações 1 e 2](plano-testes-iteracoes-1-e-2.md).
- [Relatório do Estado Atual dos Testes e Débito Técnico](relatorio-estado-atual-testes.md).
- Análise US04 — Gerenciar turmas, versão recebida em 09/10/2026.
- Análise US05 — Gerenciar livros, versão recebida em 09/10/2026.
- Análise US06 — Registrar empréstimos utilizada no planejamento vigente.

Os links das análises deverão ser incluídos quando os documentos forem publicados no repositório.

## 4. User Stories selecionadas

| User Story | Analista | Desenvolvedora | Revisora | Testadora/QA |
|---|---|---|---|---|
| US04 — Gerenciar turmas | Isabelle | Jaine | Helena | Isabelle |
| US05 — Gerenciar livros | Helena | Isabelle | Jaine | Helena |
| US06 — Registrar empréstimos | Jaine | Helena | Isabelle | Jaine |

Cada integrante será responsável pelo desenvolvimento de uma User Story e participará da análise, revisão ou validação das demais.

Na US05, Helena permanece responsável pelos testes de aceitação, conforme a identificação e o fluxo de responsabilidades da análise. A indicação diferente na tabela de testes da análise deverá ser corrigida pela equipe.

### 4.1 Estimativas iniciais

| User Story | Estimativa |
|---|---|
| US04 — Gerenciar turmas | 8h |
| US05 — Gerenciar livros | 10h |
| US06 — Registrar empréstimos | 8h |
| **Total** | **26h** |

As estimativas deverão ser revisadas conforme as implementações existentes, as correções necessárias e as atividades de testes.

A inclusão dos novos cenários da US04 deverá ser considerada na revisão do esforço. O tempo efetivamente utilizado será registrado nas issues.

## 5. Situação inicial e pontos de verificação

O sistema já possui funcionalidades relacionadas ao gerenciamento de turmas, gerenciamento de livros e operações de empréstimos.

O relatório de estado atual identifica pontos que precisam ser verificados, especialmente nas regras de disponibilidade dos livros, no cálculo da data de devolução e na integração entre as regras testadas e as páginas utilizadas pelo sistema.

Esses registros representam o diagnóstico da versão analisada no relatório. A existência de uma falha na versão avaliada anteriormente não comprova que ela permanece na versão atual; sua situação deverá ser confirmada por execução dos testes.

Para a Iteração 2, deverão ser verificados:

- Cadastro, consulta, alteração, exclusão e pesquisa de turmas.
- Persistência dos campos código, curso, período, série e matriz curricular.
- Impedimento de cadastro de turma com código duplicado.
- Exclusão de turma sem alunos vinculados.
- Bloqueio da inativação de turma com alunos vinculados.
- Pesquisa de turmas pelo curso, com e sem resultados.
- Cadastro, consulta, alteração, exclusão e pesquisa de livros.
- Persistência dos campos ISBN, título, autor, editora, ano, edição, situação e quantidade disponível.
- Exclusão de livros sem vínculos com empréstimos ou devoluções.
- Bloqueio da exclusão de livros com empréstimos ou devoluções associados.
- Pesquisa de livros pelo título, com e sem resultados.
- Consulta e atualização da situação e da quantidade disponível dos livros.
- Registro de empréstimos com aluno e livro válidos.
- Validação de empréstimos sem aluno informado.
- Validação de empréstimos sem livro informado.
- Validação da existência e da disponibilidade do livro.
- Armazenamento da data prevista para devolução, com prazo de 20 dias.
- Atualização da quantidade disponível após o empréstimo.
- Associação do empréstimo ao administrador autenticado.
- Consistência entre o registro do empréstimo e a atualização do estoque.
- Integração entre as regras implementadas, os testes e as páginas efetivamente utilizadas.
- Proteção das páginas e operações administrativas contra acesso sem autenticação.
- Execução dos testes automatizados e registro de seus resultados.

A presença de código, testes ou configurações não será considerada, isoladamente, como evidência de que os critérios de aceitação estão atendidos. Os comportamentos deverão ser confirmados por execução dos testes.

## 6. Planejamento das atividades

### 6.1 US04 — Gerenciar turmas

**Responsável pelo desenvolvimento:** Jaine Souza da Luz.

| Atividade | Responsável | Resultado esperado |
|---|---|---|
| Revisar os critérios e cenários BDD | Isabelle | TA04.01 a TA04.08 documentados e relacionados aos casos do PTI |
| Alinhar as seções da análise | Isabelle | Novos cenários incluídos também na tabela de critérios e no plano de testes da análise |
| Esclarecer exclusão e inativação | Isabelle e Jaine | Mecanismo da operação permitida sem vínculos definido e registrado |
| Revisar a implementação existente | Jaine | Diferenças em relação aos critérios identificadas |
| Criar ou ampliar testes de unidade | Jaine | Regras e validações verificadas de forma isolada, com mocks quando necessário |
| Criar ou ampliar testes de integração | Jaine | Persistência, consulta, alteração, operação sem vínculos e bloqueios verificados no banco de testes |
| Verificar o cadastro dos cinco campos | Jaine | Código, curso, período, série e matriz curricular persistidos corretamente |
| Aplicar validações definidas no projeto | Jaine | Dados tratados conforme obrigatoriedade e formatos aprovados |
| Impedir código duplicado | Jaine | Cadastro impedido, registro existente preservado e mensagem prevista apresentada |
| Verificar consulta e alteração | Jaine | Dados corretos e alterações apresentadas em nova consulta |
| Verificar exclusão sem alunos | Jaine | Operação confirmada e turma ausente das consultas seguintes |
| Bloquear inativação com alunos | Jaine | Operação impedida, situação da turma e vínculos preservados |
| Verificar pesquisa pelo curso | Jaine | Somente turmas correspondentes apresentadas |
| Verificar pesquisa sem resultados | Jaine | Mensagem de nenhuma turma encontrada apresentada |
| Revisar código e testes | Helena | Pull Request revisado e correções solicitadas quando necessário |
| Executar testes de aceitação | Isabelle | T04.01 a T04.08 executados, com resultados e evidências registrados |

Para o código duplicado, a mensagem prevista no TA04.07 é:

> Este código já pertence a uma turma cadastrada.

Para a tentativa de inativação com alunos vinculados, a mensagem prevista no TA04.08 é:

> Esta turma não pode ser excluída, pois existem alunos matriculados nela.

Os testes deverão utilizar turmas e alunos fictícios em banco exclusivo de testes.

A análise utiliza exclusão em TA04.04 e inativação em TA04.08. A equipe deverá esclarecer o mecanismo aplicado à operação permitida sem vínculos, preservando os comportamentos observáveis definidos nos critérios.

### 6.2 US05 — Gerenciar livros

**Responsável pelo desenvolvimento:** Isabelle Cavalcanti da Silva.

| Atividade | Responsável | Resultado esperado |
|---|---|---|
| Revisar os critérios de aceitação | Helena | TA05.01 a TA05.06 relacionados aos casos do PTI |
| Documentar o bloqueio de exclusão com vínculos | Helena | Caso complementar T05.05 mantido conforme o plano de testes da análise |
| Revisar a implementação existente | Isabelle | Diferenças em relação aos critérios identificadas |
| Criar ou ampliar testes de unidade | Isabelle | Regras e validações verificadas de forma isolada, com mocks quando necessário |
| Criar ou ampliar testes de integração | Isabelle | Persistência, consulta, alteração, exclusão, bloqueios e pesquisa verificados |
| Verificar cadastro dos oito campos | Isabelle | ISBN, título, autor, editora, ano, edição, situação e quantidade disponível persistidos corretamente |
| Aplicar validações definidas no projeto | Isabelle | Dados tratados conforme obrigatoriedade, formatos e limites aprovados |
| Verificar consulta do livro | Isabelle | Todos os campos apresentados conforme o registro selecionado |
| Verificar alteração do livro | Isabelle | Alterações persistidas e apresentadas em nova consulta |
| Verificar situação e quantidade disponível | Isabelle | Valores apresentados correspondem aos dados persistidos |
| Verificar exclusão sem vínculos | Isabelle | Exclusão confirmada e livro ausente das consultas seguintes |
| Bloquear exclusão com vínculos | Isabelle | Livro, empréstimos, devoluções e relacionamentos preservados |
| Verificar pesquisa pelo título | Isabelle | Registros correspondentes apresentados corretamente |
| Verificar pesquisa sem resultados | Isabelle | Mensagem de nenhum livro encontrado apresentada |
| Verificar controle de acesso | Isabelle | Páginas e operações de livros exigem autenticação |
| Revisar código e testes | Jaine | Pull Request revisado e correções solicitadas quando necessário |
| Executar testes de aceitação | Helena | T05.01 a T05.07 executados, com resultados e evidências registrados |

Os testes deverão utilizar livros fictícios e dados controlados, evitando alterações em registros reais do sistema.

Os oito campos do cadastro deverão ser conferidos nas operações de cadastro, consulta e alteração. Obrigatoriedade individual, tipos, formatos e limites deverão seguir as definições aprovadas do projeto.

O caso T05.05 verifica o bloqueio da exclusão com vínculos. T05.06 corresponde à pesquisa pelo título e T05.07 à pesquisa sem resultados.

### 6.3 US06 — Registrar empréstimos

**Responsável pelo desenvolvimento:** Helena Dantas Mariano.

| Atividade | Responsável | Resultado esperado |
|---|---|---|
| Detalhar os critérios de aceitação | Jaine | Casos válidos, inválidos e de consistência documentados |
| Revisar a implementação existente | Helena | Diferenças em relação aos critérios identificadas |
| Criar ou ampliar testes de unidade | Helena | Regras de empréstimo verificadas de forma isolada |
| Criar ou ampliar testes de integração | Helena | Persistência, vínculos e atualização do estoque verificados |
| Validar seleção de aluno | Helena | Ausência de aluno impede o registro; cenário válido utiliza aluno existente |
| Validar seleção de livro | Helena | Ausência de livro ou identificador inexistente impede o registro |
| Validar disponibilidade do livro | Helena | Empréstimo de livro indisponível rejeitado |
| Registrar empréstimo válido | Helena | Empréstimo associado ao aluno, livro e administrador |
| Verificar data prevista de devolução | Helena | Prazo de 20 dias calculado e armazenado corretamente |
| Verificar atualização da quantidade disponível | Helena | Quantidade reduzida em uma unidade após empréstimo válido, sem valor negativo |
| Verificar administrador responsável | Helena | Empréstimo associado ao administrador autenticado |
| Conferir regras no fluxo real | Helena | Página utilizada pelo sistema aplica as regras verificadas pelos testes |
| Verificar consistência após falha | Helena | Falha de atualização do estoque não deixa empréstimo e quantidade inconsistentes |
| Revisar código e testes | Isabelle | Pull Request revisado e correções solicitadas quando necessário |
| Executar testes de aceitação | Jaine | Resultados e evidências registrados |

A implementação deverá considerar os pontos identificados no relatório de estado atual e confirmar sua situação na versão avaliada.

Neste planejamento, exemplar representa uma unidade disponível do livro, sem pressupor um cadastro individual de cada exemplar.

A equipe deverá alinhar o identificador utilizado para selecionar o livro, evitando divergências entre código, ISBN, interface e processamento.

### 6.4 Atividades de apoio

| Atividade | Responsável principal | Apoio |
|---|---|---|
| Preparar banco isolado e dados de testes | Helena | Isabelle e Jaine |
| Conferir compatibilidade entre PHP, Composer e PHPUnit | Helena | Isabelle e Jaine |
| Executar os testes existentes e registrar o diagnóstico da versão avaliada | Isabelle | Helena e Jaine |
| Revisar e validar o GitHub Actions | Isabelle | Helena e Jaine |
| Conferir configuração e executar SonarQube | Jaine | Isabelle e Helena |
| Gerar e registrar cobertura de código | Isabelle | Helena e Jaine |
| Atualizar PTG, PTI e documentos relacionados | Isabelle | Helena e Jaine |
| Atualizar links no README | Isabelle | Helena e Jaine |
| Registrar resultados e pendências da iteração | Todas | — |

## 7. Cronograma

| Período | Datas | Atividades |
|---|---|---|
| Dias 1 a 3 | 27/09 a 29/09/2026 | Análise das histórias, detalhamento dos critérios, preparação do ambiente e execução inicial dos testes |
| Dias 4 a 7 | 30/09 a 03/10/2026 | Criação de testes e início das correções das US04, US05 e US06 |
| Dias 8 a 13 | 04/10 a 09/10/2026 | Conclusão planejada das implementações e dos testes de unidade e integração; alinhamento com as análises atualizadas |
| Dias 14 a 17 | 10/10 a 13/10/2026 | Revisão dos Pull Requests, correções e reexecução dos testes |
| Dias 18 a 20 | 14/10 a 16/10/2026 | Testes de aceitação, análise de qualidade, cobertura e registro de evidências |
| Dia 21 | 17/10/2026 | Revisão final, integração das entregas aprovadas e registro das pendências |

As atividades das três histórias poderão ocorrer em paralelo, respeitando suas dependências.

Este cronograma mantém as datas do planejamento original. A atualização em 09/10/2026 não comprova que as atividades previstas para os dias anteriores foram concluídas. A situação real deverá ser registrada nas issues e no relatório de execução.

As datas da iteração não substituem os prazos de entrega definidos pela disciplina.

## 8. Dependências

- US04 depende de turmas cadastradas e, para alguns cenários, de alunos vinculados no banco de testes.
- O teste de código duplicado depende de uma turma previamente cadastrada com código conhecido.
- US05 precisa considerar os vínculos existentes entre livros, empréstimos e devoluções.
- US06 depende de alunos e livros cadastrados para realização dos empréstimos.
- A validação integrada de US06 depende das funcionalidades de alunos e livros.
- As páginas e operações das três histórias dependem do controle de acesso administrativo da US02.
- A execução automatizada depende de versões compatíveis de PHP e PHPUnit.
- Os testes de integração dependem de dados controlados e de um banco exclusivo de testes.
- A verificação de consistência após falha depende de um mecanismo de simulação em ambiente controlado.
- Testes que dependam de arquivos ou componentes ausentes deverão ter seus bloqueios registrados.

Se um bloqueio impedir a suíte completa, a equipe poderá executar os testes específicos das histórias, identificando expressamente que o resultado é parcial.

A ausência de definição de uma regra deverá ser registrada como pendência de análise antes da avaliação do caso correspondente.

## 9. Estratégia de testes

A estratégia geral está definida no [Plano Geral de Testes](plano-geral-de-testes.md). Os casos detalhados estão no [PTI](plano-testes-iteracoes-1-e-2.md).

### 9.1 Testes de unidade

Verificar regras e componentes de forma isolada, utilizando mocks quando necessário.

Priorizar:

- Código de turma duplicado.
- Verificação de vínculos antes das operações de exclusão/inativação.
- Validações de campos conforme as regras aprovadas.
- Disponibilidade do livro para empréstimo.
- Impedimento de quantidade negativa.
- Cálculo da data prevista de devolução.

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
- Verificar a persistência dos cinco campos de turmas.
- Verificar a persistência dos oito campos de livros.
- Conferir que a tentativa de código duplicado não cria outra turma nem modifica o registro existente.
- Conferir que a tentativa de inativação de turma com alunos não altera sua situação nem seus vínculos.
- Conferir que a exclusão bloqueada de livro preserva os registros relacionados.
- Verificar as relações entre alunos, turmas, livros e empréstimos.
- Verificar a atualização da quantidade disponível dos livros.
- Verificar consistência após falha simulada na atualização do estoque.
- Limpar os dados utilizados de forma segura.
- Não modificar registros reais.

### 9.3 Testes de aceitação

| User Story | Critérios e referências | Casos previstos no PTI |
|---|---|---|
| US04 | TA04.01 a TA04.08: cadastro, consulta, alteração, exclusão sem alunos, pesquisa com e sem resultados, código duplicado e bloqueio de inativação com alunos | T04.01 a T04.08 |
| US05 | TA05.01 a TA05.06, além do bloqueio de exclusão com vínculos previsto em T05.05 da análise | T05.01 a T05.07 |
| US06 | TA06.01 a TA06.12: registro, vínculos, prazo, campos obrigatórios, disponibilidade, quantidade, administrador e consistência | T06.01 a T06.12, com verificação complementar de falha em T06.12.1 |

T06.13 e T06.14 do PTI representam verificações agrupadas das suítes de unidade e integração. Elas complementam os testes de aceitação e não substituem sua execução pela QA.

Também deverão ser executados os casos de regressão relacionados às alterações.

A proteção de páginas e operações administrativas deverá ser verificada conforme T02.06 do PTI, incluindo as funcionalidades alteradas nesta iteração.

### 9.4 Registro de evidências

Para cada teste de aceitação, registrar:

- Identificador do teste.
- User Story e critério relacionado.
- Responsável e data.
- Versão ou commit testado.
- Dados fictícios utilizados.
- Resultado esperado.
- Resultado obtido.
- Situação: aprovado, reprovado, bloqueado ou não executado.
- Evidência e issue relacionada, quando houver.
- Resultado do reteste após a correção.

Os novos casos da US04 deverão ter seus resultados registrados após execução. Sua inclusão no planejamento não significa que foram executados ou aprovados.

### 9.5 Cobertura e qualidade

O relatório de cobertura deverá identificar o commit, o comando utilizado e os arquivos medidos.

A versão anterior deste planejamento registrava a pasta `php/` como escopo da medição. A configuração utilizada na execução deverá ser conferida e registrada. Se a medição abranger somente essa pasta, o percentual não deverá ser apresentado como cobertura de todas as páginas PHP do sistema.

A meta de cobertura e suas condições de aprovação deverão seguir o Plano Geral de Testes. Enquanto uma meta estiver apenas proposta, não deverá ser apresentada como condição já aprovada pela equipe.

Os resultados do GitHub Actions e do SonarQube deverão ser registrados separadamente. A existência da configuração não será considerada evidência de execução bem-sucedida.

O token do SonarQube deverá permanecer em GitHub Secrets, sem ser incluído no código ou na documentação.

## 10. Critérios de conclusão

Uma User Story será considerada concluída quando:

- Seus critérios de aceitação forem atendidos.
- Os casos complementares previstos para a história forem executados e avaliados.
- As páginas utilizarem as regras verificadas pelos testes.
- Os testes relacionados e a regressão necessária passarem no ambiente de testes.
- Houver evidências da validação.
- Nenhum defeito crítico ou alto permanecer aberto no escopo entregue.
- O código e os testes forem revisados por outra integrante.
- As correções solicitadas na revisão forem tratadas.
- O Pull Request for aprovado antes da integração na branch `main`.

Para a US04, a conclusão inclui a verificação de código duplicado e do bloqueio da inativação com alunos vinculados.

Para a US05, a conclusão inclui a conferência dos oito campos, da exclusão com e sem vínculos e da pesquisa com e sem resultados.

A conclusão da iteração deverá atender também aos critérios de cobertura, CI e análise estática definidos no Plano Geral de Testes.

A iteração será encerrada com o registro das entregas concluídas, dos testes executados e das pendências.

Histórias incompletas deverão permanecer identificadas como pendentes e ser replanejadas. Casos bloqueados ou não executados não deverão ser registrados como aprovados.

## 11. Riscos

| Risco | Consequência | Tratamento |
|---|---|---|
| Falhas na integração entre turmas, livros, alunos e empréstimos | Comportamentos inconsistentes | Executar testes de integração e regressão |
| Problemas na configuração do ambiente | Testes e CI não executam | Validar dependências, banco e ferramentas |
| Testes alterarem dados reais | Perda ou alteração indevida de informações | Utilizar banco isolado e dados fictícios |
| Cadastro de turma com código duplicado | Ambiguidade na identificação das turmas | Executar T04.07 e verificar persistência |
| Inativação de turma com alunos vinculados | Perda de vínculos ou inconsistência | Executar T04.08 e conferir preservação da situação e dos vínculos |
| Termos exclusão e inativação sem definição uniforme na US04 | Avaliação incompleta da operação permitida | Registrar com a analista o mecanismo aplicado à turma sem vínculos |
| Exclusão de livro com empréstimos ou devoluções | Perda de histórico ou inconsistência | Executar T05.05 e conferir os vínculos |
| Campos de livros não persistidos ou apresentados corretamente | Cadastro e consulta incompletos | Conferir os oito campos em cadastro, consulta e alteração |
| Livro indisponível ser emprestado | Quantidade disponível inconsistente | Validar disponibilidade antes do registro |
| Quantidade disponível não ser atualizada corretamente | Dados de estoque inconsistentes | Executar testes de integração para empréstimos e disponibilidade |
| Falha parcial entre empréstimo e atualização do estoque | Empréstimo e quantidade incompatíveis | Simular falha e verificar preservação do estado inicial |
| Data prevista calculada incorretamente | Informação incorreta para o usuário | Testar o prazo de 20 dias, incluindo mudança de mês e ano |
| Operações administrativas sem proteção | Acesso ou alteração indevida | Executar regressão do controle de acesso |
| Testes dependerem de arquivos ausentes | Bloqueio da suíte completa | Registrar issues e diferenciar execução parcial de completa |
| Critérios ou responsáveis divergentes na análise | Perda de rastreabilidade | Alinhar análise, PTI e distribuição de responsabilidades |
| Novos cenários aumentarem o esforço | Atraso nas entregas | Reestimar tarefas e registrar ajustes no planejamento |

## 12. Controle no GitHub

As histórias e atividades deverão ser registradas em issues.

O trabalho deverá utilizar:

- Branches específicas.
- Commits convencionais referenciando as issues.
- Pull Requests destinadas à branch `main`.
- Revisão por outra integrante.
- Registro dos testes e evidências no Pull Request ou na issue.

Exemplos de mensagens, substituindo `NUMERO` pela issue correspondente:

- `docs: atualiza plano da iteracao 2 (#NUMERO)`
- `feat: implementa gerenciamento de turmas (#NUMERO)`
- `fix: impede cadastro de turma com codigo duplicado (#NUMERO)`
- `test: verifica bloqueio de inativacao de turma com alunos (#NUMERO)`
- `feat: implementa gerenciamento de livros (#NUMERO)`
- `test: verifica exclusao de livro com vinculos (#NUMERO)`
- `feat: implementa registro de emprestimos (#NUMERO)`
- `test: adiciona testes de emprestimos (#NUMERO)`

Os novos cenários e eventuais correções deverão ser vinculados às issues e aos casos correspondentes do PTI.

## 13. Entregáveis

- US04, US05 e US06 implementadas conforme os critérios aprovados.
- Testes de unidade e integração relacionados às histórias.
- Verificações de código duplicado e de bloqueio da inativação de turma com alunos.
- Verificações dos oito campos de livros e das operações com e sem vínculos.
- Registro dos testes de aceitação.
- Evidências da execução dos testes.
- Evidências da execução do GitHub Actions.
- Relatório de cobertura com identificação da versão e do escopo.
- Registro da análise do SonarQube.
- PTG, PTI, análises e README alinhados.
- Pull Requests revisadas e aprovadas.
- Relatório de encerramento com resultados, tempo utilizado e pendências.

## 14. Observações sobre o planejamento

O planejamento considera as User Stories e as atividades de desenvolvimento, testes, revisão e integração do sistema.

A Iteração 2 mantém a duração planejada de 21 dias corridos, iniciando em 27 de setembro de 2026 e encerrando em 17 de outubro de 2026.

As três User Stories poderão ser desenvolvidas em paralelo, desde que suas dependências sejam respeitadas.

Mudanças de escopo, responsáveis, datas ou critérios deverão ser documentadas pela equipe.

Na análise atualizada da US04, os cenários TA04.07 e TA04.08 ainda precisam ser incorporados à tabela de critérios e ao plano de testes da própria análise. A orientação que solicita a definição da regra para código duplicado e turma com alunos também deverá ser alinhada aos novos cenários já definidos.

Na US05, o bloqueio da exclusão com vínculos permanece como caso complementar previsto no plano de testes da análise. A indicação de responsável nessa tabela deverá ser alinhada à identificação e ao fluxo de responsabilidades.

Este documento detalha somente a Iteração 2. A organização geral das demais iterações deverá permanecer registrada no Plano de Iterações Geral.

Os resultados efetivamente obtidos deverão ser registrados no relatório de execução ou de encerramento, com evidências e identificação da versão testada.

## 15. Histórico de revisões

| Data | Versão | Descrição |
|---|---|---|
| 07/10/2026 | 1.0 | Elaboração do planejamento da Iteração 2 a partir do backlog, do Plano da Iteração 1 e do estado atual do projeto |
| 09/10/2026 | 1.1 | Atualização conforme as análises da US04 e US05; inclusão dos cenários de código duplicado e bloqueio de inativação com alunos; detalhamento dos oito campos de livros; alinhamento com PTG e PTI; correção da formatação Markdown |