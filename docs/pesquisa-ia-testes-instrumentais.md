# Pesquisa: IA em testes de sistema, testes instrumentais, usabilidade e acessibilidade

**Projeto:** PNLD — Sistema de Controle de Livros Didáticos

**Atividade:** P3 — Passo 3 (Pesquisa: IA em Testes de Sistema e Teste Instrumental)

**Data das consultas:** 08/11/2026

> Este documento é uma extensão do relatório `pesquisa-comparativa-ferramentas-e2e.md`. Ele fundamenta a Prática 2 (teste instrumental com agente de IA) e a Prática 3 (auditoria de usabilidade e acessibilidade). As ferramentas de IA mudam rápido: versões, comandos e recursos citados refletem a documentação consultada na data acima.

---

## 1. Introdução e escopo

A Prática 1 (Passo 4) desta atividade automatizou testes de sistema com scripts (Selenium WebDriver e PHPUnit). Agentes de IA para programação abrem uma segunda possibilidade: em vez de apenas **escrever** o código de um teste, o agente pode **operar** a aplicação em execução, observar o que acontece e relatar um diagnóstico.

Esta pesquisa responde a três perguntas:

1. Qual é a diferença entre pedir a uma IA que gere um script de teste e conduzir um **teste instrumental** (*agentic testing*)?
2. Como o **Claude Code** e o **Google Antigravity** executam esse tipo de teste, e que riscos isso traz?
3. Como ferramentas de auditoria e agentes de IA se complementam na validação de **usabilidade** (Capacidade de Interação, ISO/IEC 25010:2023) e **acessibilidade** (WCAG 2.2)?

Um ponto vale para todo o documento: os agentes acessam a aplicação por uma URL, como qualquer navegador. Por isso o backend em PHP do PNLD não limita a escolha do agente, ao contrário do que ocorre com a ferramenta de scripts (ver seção 5 do relatório comparativo).

---

## 2. Geração de script × teste instrumental

### 2.1 Os dois modos de usar IA

- **Geração de código de teste (estático).** A IA produz um arquivo, como `AutenticacaoE2ETest.php`, que depois roda sozinho, sem IA. O teste existe independentemente de quem o escreveu.
- **Teste instrumental (*agentic testing*).** A IA controla um navegador (ou terminal) com a aplicação rodando: navega, preenche formulários, lê a árvore de acessibilidade e o DOM, consulta o console e a rede, captura telas e compara o resultado com o comportamento esperado. O produto é um diagnóstico com evidências, e não um arquivo de teste.

### 2.2 Comparação

| Aspecto | Script gerado (Passo 4) | Teste instrumental por IA (Passo 5) |
|---|---|---|
| Resultado | Teste repetível no repositório | Relato e evidências de uma sessão |
| Interação com a aplicação | Nenhuma durante a escrita; só na execução do teste | Contínua: o agente observa e decide o próximo passo |
| Determinismo | Alto: mesmos passos a cada execução | Menor: o caminho pode variar entre sessões |
| Custo por execução | Praticamente zero depois de pronto | Consome tempo e *tokens* a cada sessão |
| Manutenção | Seletores quebram quando a interface muda | Adapta-se à mudança, mas exige nova sessão |
| Descoberta de problemas novos | Só encontra o que foi escrito | Pode notar problemas não previstos (mensagens confusas, erros no console) |
| Integração em CI | Direta (headless) | Menos direta e mais cara |

Os dois modos se complementam: o agente explora e descobre; os achados relevantes viram scripts de regressão.

### 2.3 Abordagem híbrida já existente

O Playwright documenta três agentes de teste que combinam os dois modos: um **planner** que explora a aplicação e produz um plano de testes em Markdown, um **generator** que transforma o plano em arquivos de teste e um **healer** que executa os testes e tenta reparar os que falham. Eles são instalados no projeto com `npx playwright init-agents` e são documentados para o executor Playwright Test (JavaScript/TypeScript).

Como o grupo adotou Selenium com PHP, esses agentes não se aplicam diretamente. A ideia, porém, serve de modelo: a Prática 2 pode usar um agente como "planner" (explorar um cenário Gherkin e relatar divergências), e os achados úteis podem virar novos testes PHPUnit/Selenium.

---

## 3. Model Context Protocol (MCP)

O MCP é o protocolo aberto que permite a aplicações de IA usarem ferramentas e dados externos. A especificação define mensagens em JSON-RPC 2.0 entre três papéis: **hosts** (aplicações de IA que iniciam as conexões), **clientes** (conectores dentro do host) e **servidores** (serviços que oferecem contexto e capacidades).

Um servidor pode oferecer três tipos de recurso: **tools** (funções que o modelo executa), **resources** (dados de contexto) e **prompts** (modelos de mensagem). Para testes de navegador, o que importa são as *tools*: um servidor MCP de navegador expõe ações como navegar, clicar, preencher campos, tirar capturas e ler o console.

A própria especificação ressalta que o acesso a dados e a execução de código trazem considerações de segurança e confiança. Isso reaparece nos cuidados da seção 4.4.

---

## 4. Agentes de IA em testes instrumentais

### 4.1 Claude Code com servidores MCP

O Claude Code é um agente que roda no terminal. Sozinho, ele lê o código, executa comandos (por exemplo, subir o servidor local) e analisa logs. Conectado a servidores MCP, ele ganha ferramentas de navegador e passa a realizar o teste instrumental: sobe a aplicação, navega pelos fluxos e diagnostica falhas.

**Como se conecta.** A documentação oficial descreve o comando `claude mcp add`, com servidores locais (processo na máquina, via `stdio`) ou remotos (HTTP). O transporte SSE é tratado como obsoleto. O comando `/mcp`, dentro de uma sessão, mostra o estado de cada servidor, e servidores definidos em um arquivo `.mcp.json` do projeto precisam ser aprovados antes de rodar.

**Servidores MCP de navegador relevantes:**

| Servidor | O que faz | Observações |
|---|---|---|
| **Playwright MCP** (Microsoft) | Controla um navegador real e entrega ao agente um *snapshot* estruturado de acessibilidade em vez de imagens | Instalação típica: `claude mcp add playwright -- npx @playwright/mcp@latest`; a opção `--headless` roda sem janela; inclui uma ferramenta de execução de código arbitrário que pede o mesmo cuidado que qualquer execução sem isolamento |
| **Chrome DevTools MCP** (equipe do Chrome) | Permite ao agente controlar e inspecionar um Chrome ao vivo: requisições de rede, console com *stack traces*, capturas de tela e análise de desempenho | Suporta oficialmente apenas Chrome e Chrome for Testing; coleta estatísticas de uso por padrão; expõe o conteúdo do navegador ao cliente MCP |
| **Puppeteer MCP** | Servidor de referência para controlar o Chrome via Puppeteer | **Arquivado em maio de 2025**, sem correções nem atualizações de segurança; os guias de compatibilidade recomendam o Playwright MCP ou o Chrome DevTools MCP |
| **Selenium MCP** | Existe uma opção comunitária (`@angiejones/mcp-selenium`, baseada em WebDriver) | Não oficial; não foi avaliada nesta pesquisa; seria a mais próxima da ferramenta de scripts do grupo |

O fato de o Puppeteer MCP estar arquivado merece atenção: o enunciado cita essa ferramenta, mas ela não deve ser a escolha para a prática. Há também relatos recentes de agentes de programação instalando servidores MCP descontinuados por força do hábito do treinamento, o que reforça escolher servidores mantidos.

**Fluxo típico no PNLD (Prática 2):**

1. Subir a aplicação (`docker compose up -d`, acessível em `http://localhost:8080`).
2. Registrar o servidor MCP escolhido no Claude Code.
3. Dar ao agente o papel de QA, a credencial do administrador de teste e um cenário em Gherkin.
4. O agente navega, executa os passos, confere o resultado e lista divergências, com capturas de tela e mensagens do console.
5. A equipe revisa o relato e decide o que vira correção ou novo teste automatizado.

**Controle de permissões.** Por padrão o Claude Code pede confirmação antes de chamar uma ferramenta MCP, e é possível liberar ferramentas específicas por nome nas configurações do projeto.

### 4.2 Google Antigravity

A documentação do Antigravity descreve um **subagente de navegador** (*browser subagent*). Ele é acionado pelo comando `/browser`, seguido da tarefa, e recebe a delegação do agente principal em um contexto isolado.

- **Como controla o navegador.** O subagente dirige o **Google Chrome** do próprio usuário por meio do servidor **Chrome DevTools MCP**, que o Antigravity inicia automaticamente. O Chrome precisa estar aberto, na versão 144 ou superior, com a depuração remota habilitada em `chrome://inspect/#remote-debugging`.
- **O que ele consegue fazer.** Navegar e gerenciar abas; clicar, digitar, preencher formulários, enviar arquivos e tratar diálogos; obter um *snapshot* de acessibilidade, capturas de tela, mensagens do console e requisições de rede; avaliar JavaScript na página, registrar *traces* de desempenho e **executar uma auditoria Lighthouse**; emular viewport e condições de rede.
- **Evidências.** Quando o subagente interage com a página (clica, digita, preenche), o Antigravity pode gravar um vídeo WebM da sessão e salvá-lo como *artefato*. Capturas de tela também ficam disponíveis como artefatos, o que serve de prova visual da execução.
- **Controles de segurança.** A execução de JavaScript na página pede aprovação por padrão (as opções são pedir revisão, prosseguir sempre ou desabilitar). As **Browser Actuation Rules** definem listas de sites permitidos e negados. Administradores de organizações podem desativar o recurso.

Observação de método: materiais de terceiros e versões anteriores do Antigravity mencionam uma extensão do Chrome, e a documentação oficial atual descreve o Chrome DevTools MCP. Isso varia por produto (App, IDE, CLI) e por versão, então a configuração deve ser confirmada na versão instalada antes da prática.

### 4.3 Comparativo para a escolha do agente

| Critério | Claude Code + Playwright MCP | Antigravity (`/browser`) |
|---|---|---|
| Mecanismo de controle | Servidor MCP escolhido pelo usuário | Chrome DevTools MCP gerenciado pelo produto |
| Navegador | Gerenciado pelo servidor (opção headless) | Chrome do usuário, com depuração remota ligada |
| Evidências | Capturas e relato no terminal | Capturas, vídeo WebM e artefatos |
| Auditoria Lighthouse | Não faz parte do Playwright MCP | Disponível entre as ferramentas do subagente |
| Controles | Permissões por ferramenta | Política de JavaScript e lista de sites |
| Pré-requisitos | Node.js e Claude Code | Antigravity e Chrome 144+ |

A escolha depende do que o grupo tem instalado. Qualquer um cumpre o Passo 5, desde que registre ambiente, prompt, passos executados e evidências.

### 4.4 Riscos e cuidados

- **Injeção de prompt.** Páginas e dados lidos pelo agente podem conter instruções maliciosas. A documentação do Claude Code avisa que servidores MCP de terceiros são usados por conta e risco do usuário, sobretudo os que acessam conteúdo não confiável. Use apenas a aplicação local e dados de teste.
- **Credenciais.** Use um administrador de teste, nunca contas reais. O PNLD já tem um administrador de semente para isso.
- **Servidores mantidos.** Prefira servidores ativos (Playwright MCP, Chrome DevTools MCP) a servidores arquivados.
- **Privacidade.** O Chrome DevTools MCP expõe o conteúdo do navegador ao agente e coleta estatísticas de uso; avalie a opção de desativar a coleta.
- **Reprodutibilidade.** Registre o prompt, o modelo e a data, porque a sessão não se repete exatamente.

---

## 5. Usabilidade e acessibilidade

### 5.1 Referenciais

**ISO/IEC 25010:2023.** A edição de 2023 substituiu a característica *Usabilidade* por **Capacidade de Interação** (*interaction capability*). Suas subcaracterísticas são: reconhecibilidade da adequação, capacidade de aprendizado, operabilidade, proteção contra erros do usuário, engajamento do usuário, inclusividade, assistência ao usuário e autodescritividade. A norma também dividiu a antiga *acessibilidade* em inclusividade e assistência ao usuário, e trocou "estética da interface" por engajamento do usuário.

**Heurísticas de Nielsen.** Dez princípios gerais para inspecionar interfaces: visibilidade do estado do sistema; correspondência entre o sistema e o mundo real; controle e liberdade do usuário; consistência e padrões; prevenção de erros; reconhecimento em vez de memorização; flexibilidade e eficiência de uso; estética e design minimalista; ajuda para reconhecer, diagnosticar e corrigir erros; ajuda e documentação. A avaliação heurística costuma ser feita por poucos avaliadores independentes, com os achados reunidos e priorizados por gravidade.

**WCAG 2.2.** Publicada como Recomendação do W3C em 5 de outubro de 2023, acrescenta nove critérios de sucesso à versão 2.1 e remove o critério 4.1.1 (Análise), considerado obsoleto. Novos critérios:

| Nível | Critérios |
|---|---|
| A | 3.2.6 Ajuda consistente; 3.3.7 Entrada redundante |
| AA | 2.4.11 Foco não obscurecido (mínimo); 2.5.7 Movimentos de arrastar; 2.5.8 Tamanho do alvo (mínimo, 24 por 24 pixels CSS, com exceções); 3.3.8 Autenticação acessível (mínimo) |
| AAA | 2.4.12 Foco não obscurecido (aprimorado); 2.4.13 Aparência do foco; 3.3.9 Autenticação acessível (aprimorado) |

A atividade pede os níveis A e AA. Ficam fora do escopo os critérios AAA.

### 5.2 Ferramentas livres de auditoria

| Ferramenta | O que faz | Como entra na Prática 3 | Limitações |
|---|---|---|---|
| **Google Lighthouse** | Pontuação de acessibilidade de 0 a 100: média ponderada das auditorias, com pesos baseados na avaliação de impacto do axe; cada auditoria é aprovada ou reprovada por inteiro | Rodar nas telas principais, com a sessão do administrador autenticada, no DevTools do Chrome | Auditorias manuais não entram na nota; uma única falha numa auditoria zera os pontos dela |
| **axe-core** | Motor de regras de código aberto mantido pela Deque, base de várias outras ferramentas | Extensão do navegador ou execução via JavaScript | Regras automáticas não cobrem tudo (ver 5.3) |
| **`@axe-core/webdriverjs`** | Integração oficial do axe com o Selenium WebDriverJS | Possível, mas exige Node.js, e os testes do grupo são em PHP | Não há integração oficial documentada para PHP; uma alternativa a validar na prática é injetar o `axe-core` pela execução de JavaScript do `php-webdriver` |
| **WAVE** (WebAIM) | Extensão do navegador que marca na página erros, erros de contraste, alertas, estrutura e ARIA | Inspeção visual e apoio à revisão manual | Verificação manual, sem uso em CI; não prova que o conteúdo é acessível |
| **Pa11y** | Ferramenta de linha de comando e Node.js; usa HTML CodeSniffer por padrão e pode usar o axe | Tem *ações* (preencher campo, clicar) que permitem autenticar antes de testar | Exige Node.js; as regras do HTML CodeSniffer diferem das do axe |

### 5.3 O que a automação e a IA cobrem

**Ferramentas automáticas.** A Deque, mantenedora do axe, publicou em 2021 um estudo sobre mais de 2.000 auditorias, 13.000 páginas e cerca de 300.000 problemas. Segundo o estudo, a automação identificou em média 57% do **volume** de problemas de acessibilidade. É um percentual de ocorrências, e não de critérios WCAG: o restante, como a qualidade de textos alternativos, a ordem do foco e a clareza de mensagens de erro, ainda precisa de revisão humana.

**Agentes de IA na avaliação heurística.** A evidência é mista, e por isso a revisão humana é obrigatória:

- Um estudo apresentado no INTERACT 2025 (Guerino et al.) comparou o GPT-4o a especialistas em uma avaliação heurística de Nielsen em um sistema web. A IA encontrou apenas 21,2% dos problemas identificados pelos especialistas, apontou 27 problemas novos e cerca de 24% do que apontou eram falsos positivos, ou seja, alucinações. Foi melhor em estética e design minimalista e em correspondência com o mundo real, e pior em flexibilidade, controle e liberdade do usuário.
- Um caso do Baymard Institute, comentado por Jakob Nielsen em 2023, avaliou o ChatGPT 4 em 12 capturas de tela de comércio eletrônico e concluiu que a IA identificou 24% dos problemas conhecidos.
- Outro trabalho, sobre avaliação heurística "sintética" com modelos multimodais, relata que o modelo encontrou mais problemas que avaliadores humanos individuais, mas com a ressalva de que os resultados precisam ser filtrados e consolidados.

A conclusão prática: a IA é útil como **revisor adicional rápido**, que sugere candidatos e encontra problemas que a equipe não previu, mas não substitui a avaliação da equipe nem a navegação real por teclado e leitor de tela.

### 5.4 Estratégia proposta para o PNLD

1. **Auditoria automática:** Lighthouse e axe (ou WAVE) em cada tela principal, com o administrador autenticado, registrando pontuação e lista de violações.
2. **Inspeção por agente:** o agente analisa a interface, o DOM, o *snapshot* de acessibilidade e o fluxo, com um prompt que cita as heurísticas de Nielsen e as subcaracterísticas da Capacidade de Interação.
3. **Revisão crítica da equipe:** confirmar ou descartar cada achado da IA (considerando os falsos positivos) e testar manualmente a navegação só por teclado.
4. **Correção e comparação:** corrigir pelo menos dois problemas e documentar o antes e o depois (código, interface e pontuação).

**Candidatos observados no HTML da tela de login** (a confirmar na auditoria, não são resultados):

| Observação | Critério ou heurística relacionada |
|---|---|
| Os campos Matrícula e Senha têm só `placeholder`, sem `<label>` associado | WCAG 1.3.1, 3.3.2 e 4.1.2; o Lighthouse dá peso máximo à auditoria "Form elements have associated labels" |
| O banner de cookies tem posição fixa na base da tela | WCAG 2.4.11 (foco não obscurecido), pela possibilidade de cobrir elementos focados |
| Links do cabeçalho com `href` vazio | Nielsen: consistência e controle; links que não levam a lugar nenhum |

---

## 6. Limitações desta pesquisa

- Algumas características do Playwright MCP e do Chrome DevTools MCP foram confirmadas pela documentação do Claude Code, pelo repositório do Chrome DevTools MCP e por guias de terceiros, e não pelo README oficial do Playwright MCP, que não pôde ser aberto diretamente.
- A comparação dos agentes é documental. Ela será confirmada na Prática 2, com a execução real.
- Os estudos sobre IA em avaliação heurística usam modelos específicos e amostras pequenas, e os números não se transferem automaticamente para outros modelos ou sistemas.
- O Selenium MCP comunitário e a injeção do `axe-core` via `php-webdriver` não foram avaliados.
- A ISO/IEC 25010:2023 é uma norma paga. As informações aqui vieram do prefácio de pré-visualização da norma e de resumos de terceiros.

---

## 7. Referências

ANTIGRAVITY. **Browser command (/browser)**. Google Antigravity Docs. Disponível em: https://antigravity.google/docs/commands/browser/. Acesso em: 8 nov. 2026.

ANTIGRAVITY. **Browser overview**. Google Antigravity Docs. Disponível em: https://antigravity.google/docs/ide/browser/. Acesso em: 8 nov. 2026.

ANTHROPIC. **Connect Claude Code to tools via MCP**. Claude Code Docs. Disponível em: https://code.claude.com/docs/en/mcp. Acesso em: 8 nov. 2026.

ANTHROPIC. **Claude Code MCP** (aviso sobre servidores de terceiros e injeção de prompt). Disponível em: https://docs.anthropic.com/en/docs/claude-code/mcp. Acesso em: 8 nov. 2026.

CHROME DEVTOOLS. **chrome-devtools-mcp**: Chrome DevTools for agents. GitHub. Disponível em: https://github.com/ChromeDevTools/chrome-devtools-mcp. Acesso em: 8 nov. 2026.

CURRENTS. **Claude Code** (permissões de ferramentas MCP). Disponível em: https://docs.currents.dev/ai/connect/claude-code. Acesso em: 8 nov. 2026.

DEQUE SYSTEMS. **Deque study shows its automated testing identifies 57 percent of digital accessibility issues**. 2021. Disponível em: https://www.deque.com/blog/automated-testing-study-identifies-57-percent-of-digital-accessibility-issues. Acesso em: 8 nov. 2026.

DEQUE SYSTEMS. **@axe-core/webdriverjs**. npm. Disponível em: https://www.npmjs.com/package/@axe-core/webdriverjs. Acesso em: 8 nov. 2026.

GOOGLE. **Automated UI testing with Antigravity (AGY) CLI, BrowserMCP, Playwright, and the Browser Agent**. Google Codelabs. Disponível em: https://codelabs.developers.google.com/agentic-ui-automation-with-antigravity. Acesso em: 8 nov. 2026.

GOOGLE CHROME. **Lighthouse accessibility score**. Chrome for Developers. Disponível em: https://developer.chrome.com/docs/lighthouse/accessibility/scoring. Acesso em: 8 nov. 2026.

GUERINO, G. et al. **Can GPT-4o evaluate usability like human experts? A comparative study on issue identification in heuristic evaluation**. arXiv:2506.16345, 2025 (INTERACT 2025). Disponível em: https://arxiv.org/abs/2506.16345. Acesso em: 8 nov. 2026.

HELP NET SECURITY. **AI coding agents are installing MCP servers that will never be patched**. 3 nov. 2026. Disponível em: https://www.helpnetsecurity.com/2026/11/03/mcphantom-deprecated-mcp-servers/. Acesso em: 8 nov. 2026.

ISO/IEC. **ISO/IEC 25010:2023 — Systems and software engineering — SQuaRE — Product quality model** (prefácio, pré-visualização). Disponível em: https://cdn.standards.iteh.ai/samples/78176/13ff8ea97048443f99318920757df124/ISO-IEC-25010-2023.pdf. Acesso em: 8 nov. 2026.

ARC42. **ISO 25010 update 2023**. Disponível em: https://quality.arc42.org/articles/iso-25010-update-2023. Acesso em: 8 nov. 2026.

SONARSOURCE. **ISO/IEC 25010 explained**. Disponível em: https://www.sonarsource.com/es/resources/library/iso-iec-25010-explained/. Acesso em: 8 nov. 2026.

MICROSOFT. **Playwright MCP**. GitHub. Disponível em: https://github.com/microsoft/playwright-mcp. Acesso em: 8 nov. 2026.

MICROSOFT. **Playwright Test Agents**. Playwright Docs. Disponível em: https://playwright.dev/docs/test-agents. Acesso em: 8 nov. 2026.

MODEL CONTEXT PROTOCOL. **Specification (draft)**. Disponível em: https://modelcontextprotocol.io/specification/draft/index. Acesso em: 8 nov. 2026.

MODEL CONTEXT PROTOCOL. **Model Context Protocol Servers (Archived)**. GitHub. Disponível em: https://github.com/modelcontextprotocol/servers-archived. Acesso em: 8 nov. 2026.

NIELSEN, J. **10 usability heuristics for user interface design**. Nielsen Norman Group. Disponível em: https://www.nngroup.com/articles/ten-usability-heuristics/. Acesso em: 8 nov. 2026.

NIELSEN, J. **Unreliability of AI in evaluating UX screenshots**. UX Tigers, 2023 (comenta estudo do Baymard Institute). Disponível em: https://www.uxtigers.com/post/ai-ux-evaluation. Acesso em: 8 nov. 2026.

PA11Y. **Pa11y: your automated accessibility testing pal**. GitHub. Disponível em: https://github.com/pa11y/pa11y. Acesso em: 8 nov. 2026.

WEBAIM. **WAVE web accessibility evaluation tools**. Disponível em: https://wave.webaim.org/. Acesso em: 8 nov. 2026.

W3C. **What's new in WCAG 2.2**. Web Accessibility Initiative. Disponível em: https://www.w3.org/WAI/standards-guidelines/wcag/new-in-22/. Acesso em: 8 nov. 2026.
