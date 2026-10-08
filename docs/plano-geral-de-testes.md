# Plano Geral de Testes de Software

**Projeto:** Sistema HBL Control — Projeto PNLD  
**Equipe:** Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz  
**Processo:** YP-Agentic  
**Data:** 08/10/2026  
**Versão:** 1.0 — proposta para revisão da equipe

**Documentos de entrada:** Documento de Visão e Lista de User Stories, versão de 22/09/2026. A lista foi consultada para elaborar este plano; a validação do Documento de Visão e do PRD deve ser confirmada pela equipe.

Este documento define a estratégia geral dos testes. Os casos detalhados ficam no Plano de Teste das Iterações (PTI).

## 1. Visão Geral do Sistema

Ver [Documento de Visão](001-documento-visao.md), seção de propósito, público-alvo e contexto.

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

| História | Foco dos testes | Critérios de aceitação da lista |
| --- | --- | --- |
| US01 — Cadastrar administrador | Cadastro válido e ausência de matrícula, nome ou senha | TA01.01 a TA01.04 |
| US02 — Autenticar administrador e acessar funcionalidades | Credenciais válidas e inválidas, campos obrigatórios, navegação e bloqueio de acesso direto sem autenticação | TA02.01 a TA02.04; T402.05 e T402.06 |
| US03 — Gerenciar alunos | Cadastro, consulta, alteração, associação à turma, exclusão sem vínculos e pesquisa por nome | TA03.01 a TA03.07 |
| US04 — Gerenciar turmas | Cadastro, consulta, alteração, exclusão sem alunos vinculados e pesquisa por curso | TA04.01 a TA04.06 |
| US05 — Gerenciar livros | Cadastro, consulta, alteração, exclusão sem vínculos, pesquisa por título e consulta de disponibilidade | TA05.01 a TA05.06 |
| US06 — Registrar empréstimos | Registro, vínculos, data prevista, ausência de aluno ou exemplar e indisponibilidade do exemplar | TA06.01 a TA06.06 |
| US07 — Registrar devoluções | Registro, atualização do empréstimo e disponibilidade, ausência de empréstimo e devolução repetida | TA07.01 a TA07.06 |
| US08 — Consultar histórico de empréstimos | Consulta com empréstimos e devoluções, dados relacionados e histórico vazio | TA08.01 a TA08.05 |

Os identificadores T402.05 e T402.06 foram preservados conforme a lista recebida. A equipe deve confirmar se serão padronizados como TA02.05 e TA02.06 e atualizar a rastreabilidade nos documentos relacionados.

Também serão verificadas a persistência dos dados e a restrição de acesso prevista no RNF4. Outros RNFs serão incluídos após consulta e validação do Documento de Visão; este plano não atribui requisitos não fornecidos.

### 2.2 Itens fora do escopo desta versão

- Funcionalidades não previstas nas US01 a US08, incluindo novos relatórios ou exportação em PDF.
- Testes de carga, estresse e metas de desempenho, enquanto não houver requisitos e métricas aprovados.
- Auditoria especializada de segurança e testes de penetração.
- Certificação de acessibilidade e compatibilidade ampla entre navegadores, enquanto não houver critérios definidos.

Essas exclusões não dispensam a verificação funcional do acesso administrativo nem a investigação de falhas observadas durante os testes.

## 3. Estratégia de Testes por Requisito Não Funcional (RNF)

A descrição oficial dos RNFs deve permanecer no [Documento de Visão](001-documento-visao.md). A tabela registra a estratégia correspondente ao RNF informado na US02.

| RNF | Categoria | Abordagem de teste | Critério de aceitação |
| --- | --- | --- | --- |
| RNF4 | Segurança e controle de acesso | Tentar acessar páginas e operações administrativas sem sessão autenticada, inclusive por URL direta; verificar a resposta e o estado do banco; confirmar acesso com sessão autorizada | Todas as páginas e operações administrativas previstas no PTI bloqueiam acesso sem autenticação, solicitam autenticação e não alteram dados; o administrador autenticado e autorizado acessa as funções previstas |

A análise estática com SonarQube complementará esses testes. Ela não comprova, sozinha, que o controle de acesso funciona. Regras adicionais de autorização dependerão dos perfis definidos no Documento de Visão.

## 4. Tipos e Níveis de Teste

### 4.1 Testes de unidade

- **Foco:** funções e métodos de validação e regras de negócio isoladas, como campos obrigatórios, disponibilidade do exemplar e validação de devolução repetida.
- **Abordagem:** testar entradas válidas e inválidas; utilizar mocks quando a unidade depender de banco de dados ou outro componente externo.
- **Responsável:** desenvolvedor da história, com revisão pelo revisor designado.
- **Ferramenta prevista:** PHPUnit.
- **Cobertura:** medir cobertura de linhas com PHPUnit e Xdebug ou PCOV. Como meta inicial proposta, atingir pelo menos 80% nas regras de negócio novas ou alteradas que forem delimitadas como escopo de medição. A equipe deve aprovar essa meta antes de utilizá-la como condição de saída.

A cobertura global deve ser registrada separadamente. Código legado fora da alteração não deve ser apresentado como coberto por uma medição parcial. Percentual de cobertura não substitui a verificação dos comportamentos esperados.

### 4.2 Testes de integração

- **Foco:** comunicação do código PHP com o MySQL e interação entre os componentes.
- **Abordagem:** executar operações reais em banco exclusivo de testes, preparar os registros necessários e limpar os dados entre execuções. Mocks não substituem a verificação da persistência real neste nível.
- **Verificações principais:** gravação e atualização de cadastros, associação aluno–turma, vínculos do empréstimo, atualização após devolução e correspondência dos dados apresentados no histórico.
- **Responsável:** desenvolvedor da história; o revisor executa e avalia os testes.
- **Ferramentas previstas:** PHPUnit e MySQL de testes.

### 4.3 Testes de sistema e aceitação

- **Foco:** fluxos completos pela interface, usando os critérios de aceitação das User Stories.
- **Abordagem:** execução manual em navegador, com dados fictícios, comparação entre resultado esperado e observado e registro de evidências. A validação de aceitação deve ser registrada pelo responsável de testes e, quando houver, pelo representante do usuário.
- **Responsável:** testador designado para cada história, diferente de quem a implementou.
- **Detalhamento:** no PTI, cada caso terá ID, história e critério relacionado, pré-condições, passos, dados de entrada e resultado esperado.

### 4.4 Regressão e ordem de execução

Após uma correção ou alteração, repetir o caso afetado e os testes dos fluxos relacionados. Priorizar autenticação e controle de acesso, empréstimos, devoluções e integridade dos vínculos.

Como ordem técnica de preparação dos testes, utilizar administrador cadastrado e autenticação; depois turmas, alunos e livros; por fim empréstimos, devoluções e histórico. Essa ordem organiza os dados de teste e não substitui o Plano de Iterações Geral.

## 5. Ferramentas e Ambiente de Testes

As ferramentas abaixo compõem a estratégia proposta para o projeto PHP/MySQL. A equipe deve confirmar a configuração e registrar versões utilizadas antes da execução.

| Categoria | Ferramenta | Finalidade |
| --- | --- | --- |
| Testes de unidade e integração | PHPUnit | Executar testes automatizados e gerar resultados |
| Cobertura | Xdebug ou PCOV, integrado ao PHPUnit | Coletar cobertura de linhas e gerar relatório Clover |
| Análise estática | SonarQube | Identificar problemas de código e apresentar a cobertura importada; o SonarQube não executa o PHPUnit |
| Integração contínua | GitHub Actions | Executar testes automaticamente em pushes e pull requests, após configuração do workflow |
| Gestão de defeitos e revisão | GitHub Issues e Pull Requests | Registrar falhas, acompanhar correções e revisar alterações |
| Testes de sistema | Navegador e capturas de tela | Executar os fluxos e registrar evidências |
| Persistência | MySQL em banco separado | Verificar consultas, gravações e vínculos reais |
| Padronização do ambiente | Docker, se adotado pela equipe | Reproduzir o ambiente PHP/MySQL e suas dependências |

### 5.1 Preparação do ambiente e dos dados

- Disponibilizar aplicação PHP, dependências e extensão de conexão com MySQL compatíveis com o projeto.
- Utilizar banco exclusivo de testes, com estrutura correspondente à versão avaliada.
- Usar dados fictícios: administradores, turmas, alunos, livros, exemplares disponíveis e indisponíveis, empréstimos ativos e devolvidos.
- Preparar e limpar os dados de forma repetível, sem alterar registros reais.
- Identificar branch e commit avaliado, versões do ambiente e data de execução.
- Registrar cobertura real somente após execução bem-sucedida do coletor. Falta de relatório significa cobertura não medida, não 0% nem meta atingida.

## 6. Riscos e Contingências

| Risco | Impacto | Ação de prevenção ou contingência |
| --- | --- | --- |
| Atraso na implementação | Redução do tempo para testes | Preparar os casos antecipadamente e priorizar fluxos essenciais |
| Diferenças entre ambientes | Resultados inconsistentes | Documentar versões e dependências; utilizar ambiente padronizado quando disponível |
| Dados de uma execução afetarem outra | Falhas intermitentes ou falsos resultados | Preparar e limpar o banco de testes antes de cada cenário |
| Código legado difícil de testar isoladamente | Menor capacidade de automatização | Isolar regras de negócio gradualmente e complementar com integração e testes de sistema |
| Falha na coleta de cobertura ou no envio ao SonarQube | Ausência de evidência da medição | Conferir os relatórios locais e registrar a indisponibilidade; repetir a coleta após correção |
| Ambiguidade nas regras de exclusão, datas ou quantidade disponível | Resultados esperados indefinidos | Analista esclarece a regra antes da aprovação do caso; não deduzir regras apenas pelo comportamento atual |
| Divergência nos identificadores de requisitos e critérios | Perda de rastreabilidade | Conferir Documento de Visão, lista de histórias e PTI e registrar a padronização aprovada |

## 7. Papéis e Responsabilidades

| Papel | Responsabilidade |
| --- | --- |
| Analista | Detalhar critérios de aceitação, esclarecer regras e revisar a ligação entre requisitos e casos de teste |
| Desenvolvedor | Implementar a história, criar e executar testes de unidade e integração e corrigir defeitos |
| Revisor | Revisar implementação e testes, executar a suíte e verificar evidências e cobertura |
| Testador | Executar testes de sistema e aceitação, registrar defeitos, repetir testes após correções e elaborar o relatório |
| Equipe | Revisar este plano, aprovar metas e ferramentas e registrar a decisão de conclusão de cada iteração |

### 7.1 Distribuição por User Story

Distribuição preservada da lista fornecida:

| História | Analista | Desenvolvedor | Revisor | Testador |
| --- | --- | --- | --- | --- |
| US01 | Isabelle | Helena | Jaine | Isabelle |
| US02 | Helena | Jaine | Isabelle | Helena |
| US03 | Jaine | Isabelle | Helena | Jaine |
| US04 | Isabelle | Jaine | Helena | Isabelle |
| US05 | Helena | Isabelle | Jaine | Helena |
| US06 | Jaine | Helena | Isabelle | Jaine |
| US07 | Isabelle | Jaine | Helena | Isabelle |
| US08 | Helena | Isabelle | Jaine | Helena |

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
- [Documento de Visão](001-documento-visao.md): caminho informado na lista de histórias; confirmar sua existência no repositório.
- Lista de User Stories — Sistema HBL Control, versão 1.0, de 22/09/2026, fornecida para elaboração deste plano: inserir o link definitivo no repositório.
- PRD: informar link e situação de validação, ou registrar com o docente como este documento se aplica ao projeto.
- Especificações das User Stories, Projeto Arquitetural, PTI e Relatório de Testes: inserir os links definitivos quando disponíveis.

Antes de aprovar esta versão, confirmar ferramentas e versões, meta e escopo de cobertura, RNFs do Documento de Visão, regras ainda ambíguas e links dos documentos.

A distribuição das histórias entre iterações deve vir do Plano de Iterações Geral e não foi presumida neste PTG.