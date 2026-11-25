# Plano Geral de Testes de Software

**Projeto:** Sistema HBL Control — Projeto PNLD  
**Equipe:** Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz  
**Processo:** YP-Agentic  
**Data:** 09/10/2026  
**Versão:** 1.1 — atualização conforme as análises da US02, US04 e US05

**Documentos de entrada:** Documento de Visão, Lista de User Stories e análises das histórias. Nesta revisão, foram consideradas as análises atualizadas da US02, US04 e US05 recebidas em 09/10/2026. A validação do Documento de Visão e do PRD deve ser confirmada pela equipe.

Este documento define a estratégia geral dos testes. Os casos detalhados ficam no Plano de Teste das Iterações (PTI).

## 1. Visão Geral do Sistema

Ver [Documento de Visão](001-documento-visao.md), nas seções de propósito, público-alvo e contexto.

### 1.1 Objetivos dos testes

- Verificar se as funcionalidades atendem aos critérios de aceitação das User Stories.
- Identificar falhas nas regras de negócio e na comunicação com o banco de dados.
- Verificar a integridade dos vínculos entre turmas, alunos, livros, empréstimos e devoluções.
- Confirmar o bloqueio de acesso administrativo sem autenticação.
- Evitar que alterações prejudiquem funcionalidades já testadas, por meio de testes de regressão.
- Registrar resultados e defeitos com evidências que permitam sua reprodução.

## 2. Escopo do Plano de Testes

### 2.1 Itens no escopo

Serão realizados testes de unidade, integração e sistema para as oito histórias. Cada iteração executará os testes das histórias previstas em seu planejamento, além da regressão necessária.

| História | Foco dos testes | Critérios e referências |
|---|---|---|
| US01 — Cadastrar administrador | Cadastro válido e ausência de matrícula, nome ou senha | TA01.01 a TA01.04 |
| US02 — Autenticar administrador e acessar funcionalidades | Credenciais válidas e inválidas; matrícula e senha vazias, juntas e separadamente; acesso autenticado e bloqueio de páginas e operações sem autenticação | TA02.01 a TA02.06 da tabela de critérios da análise |
| US03 — Gerenciar alunos | Cadastro, consulta, alteração, associação à turma, inativação e pesquisa por nome | TA03.01 a TA03.07 |
| US04 — Gerenciar turmas | Cadastro, consulta e alteração dos cinco campos; exclusão sem alunos vinculados; pesquisa por curso; bloqueio de código duplicado e de inativação com alunos vinculados | TA04.01 a TA04.08 dos cenários BDD da análise |
| US05 — Gerenciar livros | Cadastro, consulta e alteração dos oito campos; exclusão sem vínculos; bloqueio de exclusão com empréstimos ou devoluções; pesquisa por título com e sem resultados | TA05.01 a TA05.06 e T05.05 do plano de testes da análise |
| US06 — Registrar empréstimos | Registro, vínculos, prazo de devolução, campos obrigatórios, disponibilidade, redução da quantidade, associação ao administrador e consistência entre empréstimo e estoque | TA06.01 a TA06.12 da análise utilizada no PTI |
| US07 — Registrar devoluções | Registro, atualização do empréstimo e disponibilidade, ausência de empréstimo e devolução repetida | TA07.01 a TA07.06 |
| US08 — Consultar histórico de empréstimos | Consulta com empréstimos e devoluções, dados relacionados e histórico vazio | TA08.01 a TA08.05 |

Para a US02, este plano utiliza os identificadores TA02.01 a TA02.06 da tabela de critérios da análise atualizada. Nessa tabela, TA02.05 corresponde ao acesso após autenticação e TA02.06 ao bloqueio de acesso sem autenticação.

Os cenários de matrícula vazia e senha vazia são variações de TA02.04 no PTI, pois a seção BDD da análise reutiliza TA02.05 e TA02.06 para esses comportamentos. Essa numeração deve ser alinhada na análise.

Na US04, os cenários BDD TA04.07 e TA04.08 passam a integrar o escopo de testes, embora ainda não estejam incluídos na tabela de critérios e no plano de testes da própria análise.

Na US05, o bloqueio da exclusão de livros com vínculos está previsto em T05.05 do plano de testes da análise e será verificado como caso complementar aos critérios TA05.01 a TA05.06.

Também serão verificadas a persistência dos dados e a restrição de acesso prevista no RNF4. Outros RNFs serão incluídos após consulta e validação do Documento de Visão.

Para as Iterações 1 e 2, a distribuição informada pela equipe é:

- Iteração 1: US01, US02 e US03.
- Iteração 2: US04, US05 e US06.

US07 e US08 permanecem no escopo geral do sistema, mas não integram o planejamento de aceitação das Iterações 1 e 2 deste PTI.

### 2.2 Itens fora do escopo desta versão

- Funcionalidades não previstas nas US01 a US08, incluindo novos relatórios ou exportação em PDF.
- Testes de carga, estresse e metas de desempenho, enquanto não houver requisitos e métricas aprovados.
- Auditoria especializada de segurança e testes de penetração.
- Certificação de acessibilidade e compatibilidade ampla entre navegadores, enquanto não houver critérios definidos.

Essas exclusões não dispensam a verificação funcional do acesso administrativo nem a investigação de falhas observadas durante os testes.

## 3. Estratégia de Testes por Requisito Não Funcional (RNF)

A descrição oficial dos RNFs deve permanecer no [Documento de Visão](001-documento-visao.md). A tabela registra a estratégia correspondente ao RNF informado na US02.

| RNF | Categoria | Abordagem de teste | Critério de aceitação |
|---|---|---|---|
| RNF4 | Segurança e controle de acesso | Tentar acessar páginas e operações administrativas sem sessão autenticada, inclusive por URL direta; verificar a resposta e o estado do banco; confirmar acesso com sessão autorizada | Todas as páginas e operações administrativas previstas no PTI bloqueiam acesso sem autenticação, solicitam autenticação e não alteram dados; o administrador autenticado e autorizado acessa as funções previstas |

A análise estática com SonarQube complementará esses testes. Ela não comprova, sozinha, que o controle de acesso funciona. Regras adicionais de autorização dependerão dos perfis definidos no Documento de Visão.

## 4. Tipos e Níveis de Teste

### 4.1 Testes de unidade

- **Foco:** funções e métodos de validação e regras de negócio isoladas, incluindo campos obrigatórios, validação de credenciais, código de turma duplicado, impedimento de exclusão/inativação com vínculos, disponibilidade do livro e validação de devolução repetida.
- **Abordagem:** testar entradas válidas e inválidas; utilizar mocks quando a unidade depender de banco de dados ou outro componente externo.
- **Responsável:** desenvolvedor da história, com revisão pelo revisor designado.
- **Ferramenta prevista:** PHPUnit.
- **Cobertura:** medir cobertura de linhas com PHPUnit e Xdebug ou PCOV. Como meta inicial proposta, atingir pelo menos 80% nas regras de negócio novas ou alteradas que forem delimitadas como escopo de medição. A equipe deve aprovar essa meta antes de utilizá-la como condição de saída.

A cobertura global deve ser registrada separadamente. Código legado fora da alteração não deve ser apresentado como coberto por uma medição parcial. Percentual de cobertura não substitui a verificação dos comportamentos esperados.

### 4.2 Testes de integração

- **Foco:** comunicação do código PHP com o MySQL e interação entre os componentes.
- **Abordagem:** executar operações reais em banco exclusivo de testes, preparar os registros necessários e limpar os dados entre execuções. Mocks não substituem a verificação da persistência real neste nível.
- **Verificações principais:** autenticação com credenciais persistidas; proteção das operações administrativas; gravação, consulta e atualização dos cadastros; associação aluno–turma; impedimento de cadastro de turma com código duplicado; preservação dos registros e relacionamentos nas operações bloqueadas; vínculos do empréstimo; consistência entre empréstimo e estoque; atualização após devolução e correspondência dos dados apresentados no histórico.
- **Responsável:** desenvolvedor da história; o revisor executa e avalia os testes.
- **Ferramentas previstas:** PHPUnit e MySQL de testes.

### 4.3 Testes de sistema e aceitação

- **Foco:** fluxos completos pela interface, usando os critérios de aceitação das User Stories.
- **Abordagem:** execução manual em navegador, com dados fictícios, comparação entre resultado esperado e observado e registro de evidências. A validação de aceitação deve ser registrada pelo responsável de testes e, quando houver, pelo representante do usuário.
- **Responsável:** testador designado para cada história, diferente de quem a implementou.
- **Detalhamento:** no PTI, cada caso terá ID, história e critério relacionado, pré-condições, passos, dados de entrada e resultado esperado.

### 4.4 Regressão e ordem de execução

Após uma correção ou alteração, repetir o caso afetado e os testes dos fluxos relacionados. Priorizar autenticação e controle de acesso, empréstimos, devoluções e integridade dos vínculos.

Como ordem técnica de preparação dos testes, utilizar administrador cadastrado e autenticação; depois turmas, alunos e livros; por fim empréstimos, devoluções e histórico.

Essa ordem organiza os dados de teste e não substitui o Plano de Iterações. Caso uma história dependa de uma funcionalidade prevista para outra iteração, os registros necessários deverão ser preparados no banco de testes.

## 5. Ferramentas e Ambiente de Testes

As ferramentas abaixo compõem a estratégia do projeto PHP/MySQL. A equipe deve confirmar a configuração e registrar as versões utilizadas antes da execução.

| Categoria | Ferramenta | Finalidade |
|---|---|---|
| Testes de unidade e integração | PHPUnit | Executar testes automatizados e gerar resultados |
| Cobertura | Xdebug ou PCOV, integrado ao PHPUnit | Coletar cobertura de linhas e gerar relatório Clover |
| Análise estática | SonarQube | Identificar problemas de código e apresentar a cobertura importada; o SonarQube não executa o PHPUnit |
| Integração contínua | GitHub Actions | Instalar dependências, executar testes, gerar cobertura e enviar a análise ao SonarQube |
| Gestão de defeitos e revisão | GitHub Issues e Pull Requests | Registrar falhas, acompanhar correções e revisar alterações |
| Testes de sistema | Navegador e capturas de tela | Executar os fluxos e registrar evidências |
| Persistência | MySQL em banco separado | Verificar consultas, gravações e vínculos reais |
| Padronização do ambiente | Docker, se adotado pela equipe | Reproduzir o ambiente PHP/MySQL e suas dependências |

### 5.1 Preparação do ambiente e dos dados

- Disponibilizar aplicação PHP, dependências e extensão de conexão com MySQL compatíveis com o projeto.
- Utilizar banco exclusivo de testes, com estrutura correspondente à versão avaliada.
- Usar dados fictícios: administradores, turmas, alunos, livros com unidades disponíveis e indisponíveis, empréstimos ativos e devolvidos.
- Preparar turmas com códigos diferentes e uma tentativa de reutilização de código existente.
- Preparar turmas com e sem alunos vinculados.
- Preparar livros com e sem vínculos com empréstimos ou devoluções.
- Preparar e limpar os dados de forma repetível, sem alterar registros reais.
- Identificar branch e commit avaliado, versões do ambiente e data de execução.
- Registrar cobertura real somente após execução bem-sucedida do coletor. Falta de relatório significa cobertura não medida, não 0% nem meta atingida.

### 5.2 Integração contínua e SonarQube

O pipeline deverá:

1. Obter o código do repositório.
2. Preparar o ambiente PHP e suas extensões.
3. Instalar as dependências do projeto.
4. Preparar o banco de testes necessário aos testes de integração.
5. Executar os testes automatizados.
6. Gerar o relatório de cobertura.
7. Executar o SonarQube Scanner apontando para o servidor do LABENS.
8. Disponibilizar os resultados e relatórios da execução.

O arquivo `sonar-project.properties` deverá identificar o projeto, os arquivos de código, os testes, as exclusões e o caminho do relatório de cobertura.

O token do SonarQube deverá ser armazenado em GitHub Secrets e utilizado pelo workflow, sem ser incluído no código ou nos documentos.

A existência do workflow não comprova execução bem-sucedida. O relatório deverá informar o link da execução avaliada, sua situação e eventuais falhas. Resultados de execução, cobertura e análise estática devem ser registrados com a versão correspondente.

## 6. Riscos e Contingências

| Risco | Impacto | Ação de prevenção ou contingência |
|---|---|---|
| Atraso na implementação | Redução do tempo para testes | Preparar os casos antecipadamente e priorizar fluxos essenciais |
| Diferenças entre ambientes | Resultados inconsistentes | Documentar versões e dependências; utilizar ambiente padronizado quando disponível |
| Dados de uma execução afetarem outra | Falhas intermitentes ou falsos resultados | Preparar e limpar o banco de testes antes de cada cenário |
| Código legado difícil de testar isoladamente | Menor capacidade de automatização | Isolar regras de negócio gradualmente e complementar com integração e testes de sistema |
| Falha na coleta de cobertura ou no envio ao SonarQube | Ausência de evidência da medição | Conferir os relatórios locais e registrar a indisponibilidade; repetir a coleta após correção |
| Ambiguidade nas regras de exclusão, datas ou quantidade disponível | Resultados esperados indefinidos | Analista esclarece a regra antes da aprovação do caso; não deduzir regras apenas pelo comportamento atual |
| Divergência nos identificadores de requisitos e critérios | Perda de rastreabilidade | Conferir Documento de Visão, lista de histórias e PTI e registrar a padronização aprovada |
| Uso dos termos exclusão e inativação na US04 sem definição uniforme | Dificuldade de avaliar a persistência após a operação | Esclarecer o mecanismo da operação permitida sem vínculos e verificar o bloqueio quando houver alunos |
| Falta de proteção nas operações administrativas | Acesso indevido ou alteração de dados | Testar páginas e operações do servidor sem autenticação |
| Falha parcial entre empréstimo e atualização do estoque | Registros inconsistentes | Simular falha em ambiente controlado e verificar a preservação do estado inicial |

## 7. Papéis e Responsabilidades

| Papel | Responsabilidade |
|---|---|
| Analista | Detalhar critérios de aceitação, esclarecer regras e revisar a ligação entre requisitos e casos de teste |
| Desenvolvedor | Implementar a história, criar e executar testes de unidade e integração e corrigir defeitos |
| Revisor | Revisar implementação e testes, executar a suíte e verificar evidências e cobertura |
| Testador | Executar testes de sistema e aceitação, registrar defeitos, repetir testes após correções e elaborar o relatório |
| Equipe | Revisar este plano, aprovar metas e ferramentas e registrar a decisão de conclusão de cada iteração |

### 7.1 Distribuição por User Story

Para US02, US04 e US05, foram consideradas as responsabilidades da identificação e do fluxo de responsabilidades das análises atualizadas.

| História | Analista | Desenvolvedor | Revisor | Testador |
|---|---|---|---|---|
| US01 | Isabelle | Helena | Jaine | Isabelle |
| US02 | Helena | Jaine | Isabelle | Helena |
| US03 | Jaine | Isabelle | Helena | Jaine |
| US04 | Isabelle | Jaine | Helena | Isabelle |
| US05 | Helena | Isabelle | Jaine | Helena |
| US06 | Jaine | Helena | Isabelle | Jaine |
| US07 | Isabelle | Jaine | Helena | Isabelle |
| US08 | Helena | Isabelle | Jaine | Helena |

As tabelas de testes das análises da US02 e da US05 apresentam responsáveis diferentes daqueles definidos na identificação e no fluxo de responsabilidades. Neste plano, Helena é a responsável pela aceitação de ambas as histórias. A equipe deverá alinhar essas informações nos documentos de análise.

## 8. Critérios de Entrada e Saída

### 8.1 Critérios de entrada

Para iniciar os testes de uma história:

- História, regras e critérios de aceitação definidos e revisados.
- Casos detalhados registrados no PTI para o nível a executar.
- Código da unidade ou funcionalidade disponível, com branch e commit identificados.
- Ambiente, dependências e banco de testes preparados, quando necessários ao nível de teste.
- Dados fictícios e credenciais de teste disponíveis.
- Responsáveis e forma de registro das evidências definidos.

No TDD, o teste de unidade pode ser escrito e executado antes da implementação para demonstrar a falha esperada. A funcionalidade completa é condição para seus testes de sistema, não para começar a escrever testes.

### 8.2 Critérios de saída

Para considerar os testes da história ou iteração concluídos:

- Todos os casos planejados para o escopo entregue foram executados e seus resultados registrados.
- Todos os critérios de aceitação das histórias entregues foram atendidos.
- Testes automatizados do escopo e regressão relacionada passaram na versão final avaliada.
- Nenhum defeito crítico ou alto permanece aberto no escopo entregue. Considerar crítico ou alto o defeito que permita acesso indevido, provoque perda ou corrupção de dados ou impeça um fluxo essencial sem alternativa viável.
- Falhas de menor impacto têm responsável, encaminhamento e aceite explícito da equipe para eventual pendência.
- Relatório de cobertura gerado e analisado; a meta aprovada pela equipe foi atendida. Enquanto a meta de 80% estiver apenas proposta, o relatório deve registrar essa pendência e não declarar aprovação por cobertura.
- Problemas relevantes de análise estática foram avaliados; caso exista Quality Gate configurado, seu resultado e eventuais pendências foram registrados.
- Pipeline de CI executado com sucesso na versão final avaliada, com evidência das etapas de testes, cobertura e envio da análise ao SonarQube.
- Evidências e relatório de testes estão disponíveis, e revisor e testador registraram a conclusão.

Caso bloqueado, não executado ou com falha não deve ser registrado como aprovado. Qualquer exceção aos critérios de saída deve ter justificativa e decisão explícita da equipe no relatório.

## 9. Registro dos Resultados e Rastreabilidade

Relacionar cada caso do PTI à User Story e ao critério de aceitação correspondente.

No relatório de execução, registrar:

- ID do caso.
- Responsável.
- Data.
- Commit avaliado.
- Resultado esperado.
- Resultado observado.
- Situação: aprovado, reprovado, bloqueado ou não executado.
- Evidências.
- Issue vinculada, quando houver defeito.

Cada defeito deve informar passos para reprodução, dados utilizados, ambiente, comportamento esperado, comportamento observado e impacto. Após a correção, repetir o teste e atualizar a Issue com o resultado.

Este documento é um plano: não declara testes executados, percentuais de cobertura obtidos ou funcionalidades aprovadas. Esses resultados pertencem ao Relatório de Testes.

## 10. Referências e Pendências de Revisão

- [Modelo YP-Agentic — Plano Geral de Testes](https://github.com/tacianosilva/engenharia-software/blob/main/yp-agentic/templates/plano-geral-testes.md).
- [Documento de Visão](001-documento-visao.md).
- [Lista de User Stories](004-lista-users-stories.md).
- [Plano da Iteração 1](plano-iteracao-1.md).
- [Plano da Iteração 2](plano-iteracao-2.md).
- [Plano de Teste das Iterações 1 e 2](plano-testes-iteracoes-1-e-2.md).
- [Relatório do Estado Atual dos Testes](relatorio-estado-atual-testes.md).
- Análises das User Stories, incluindo as versões atualizadas da US02, US04 e US05 recebidas em 09/10/2026: inserir os links definitivos quando publicadas no repositório.
- PRD: informar link e situação de validação, ou registrar com o docente como este documento se aplica ao projeto.
- Projeto Arquitetural e relatórios de execução dos testes: inserir os links definitivos quando disponíveis.

Antes de aprovar esta versão, confirmar:

- Ferramentas e versões utilizadas.
- Meta e escopo de cobertura.
- RNFs do Documento de Visão.
- Regra de inativação de aluno com vínculos.
- Mecanismo de exclusão/inativação de turma sem vínculos.
- Obrigatoriedade e formatos dos campos ainda não definidos nas análises.
- Padronização dos critérios e responsáveis divergentes.
- Regras de sessão, encerramento da autenticação e autorização.
- Links das análises e dos relatórios de execução.