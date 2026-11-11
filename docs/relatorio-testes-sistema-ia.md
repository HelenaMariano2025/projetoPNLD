# Relatório de Testes de Sistema e Testes Instrumentais com IA

## Sistema HBL Control — Projeto PNLD

**Prática 2 — Automação de Testes de Sistema, Testes Instrumentais com IA, Usabilidade e Acessibilidade**

---

## 1. Introdução

Este documento apresenta os resultados da execução da Prática 2 de testes instrumentais com agente de Inteligência Artificial no Sistema HBL Control, desenvolvido como parte do Projeto PNLD.

O objetivo foi avaliar o comportamento da aplicação por meio da interação direta com sua interface gráfica, utilizando um agente de IA para explorar os módulos, executar ações, verificar respostas e identificar comportamentos inesperados.

A avaliação teve como referência as oito histórias de usuário documentadas em `docs/004-lista-users-stories.md`, abrangendo cadastro e autenticação de administradores, gerenciamento de alunos, turmas e livros, registro de empréstimos e devoluções e consulta ao histórico de empréstimos.

A execução também permitiu observar limitações funcionais e erros que impediram a realização de determinados critérios de aceitação.

## 2. Identificação do ambiente

| Item | Detalhe |
|---|---|
| Projeto | Sistema HBL Control — Projeto PNLD |
| Agente | Antigravity AI Test Agent — Browser Subagent |
| Modelo utilizado | Gemini 3.8 Flash High |
| Ferramentas | Google Antigravity, Browser Subagent, navegador integrado e Playwright runner |
| Navegador | Chromium / Google Chrome (ambiente integrado) |
| Sistema operacional | Linux Ubuntu x86_64 |
| Ambiente da aplicação | Contêineres Docker |
| Aplicação | PHP e MySQL |
| URL | `http://localhost:8080` |
| Documentação de referência | `docs/004-lista-users-stories.md` e `docs/001-documento-visao.md` |

## 3. Metodologia

A avaliação foi realizada por meio da exploração instrumental da interface gráfica, orientada pelos critérios de aceitação das histórias de usuário.

Inicialmente, o agente analisou a documentação do projeto para compreender as funcionalidades esperadas. Em seguida, navegou pelas telas da aplicação, identificando formulários, campos, botões, mensagens e operações disponíveis.

Os critérios foram avaliados por meio de ações como:

- Preenchimento e envio de formulários;
- Tentativas de envio com campos obrigatórios vazios;
- Utilização de credenciais válidas e inválidas;
- Consulta e pesquisa de registros;
- Verificação de mensagens de sucesso e validação;
- Observação de erros apresentados pela aplicação;
- Verificação de redirecionamentos e restrições de acesso;
- Inspeção de elementos da interface e mensagens no navegador.

Foram utilizados os identificadores originais dos critérios de aceitação, como TA01.01, TA03.01, TA05.01 e TA08.05.

A avaliação abrangeu as oito histórias de usuário. Entretanto, nem todos os critérios puderam ser executados, principalmente em razão de erros fatais encontrados nos módulos de turmas, empréstimos e devoluções.

### 3.1. Preservação dos dados

A execução utilizou dados fictícios identificados pelo prefixo `TESTE_IA_` nas operações de cadastro e avaliação.

O agente informou ter excluído o livro utilizado no teste após concluir o ciclo de cadastro, consulta, edição e exclusão. Também foi realizado o cadastro de um administrador de teste com matrícula `998877`.

A remoção desse administrador não foi confirmada nas evidências disponíveis. Portanto, não é possível afirmar que todos os dados criados durante a sessão foram removidos.

Não foram realizadas alterações diretamente no banco de dados nem modificações no código-fonte durante a execução instrumental.

## 4. Resultados da execução

Os resultados foram classificados em quatro categorias:

- **Aprovado:** o comportamento observado correspondeu ao critério de aceitação.
- **Reprovado:** foi observado um comportamento contrário ao esperado ou uma falha que impediu a operação.
- **Inconclusivo:** não foi possível obter evidências suficientes para determinar o comportamento.
- **Não executado:** o cenário não foi realizado, geralmente por depender de uma funcionalidade indisponível.

### 4.1. US01 — Cadastrar administrador

| Critério | Resultado | Observações |
|---|---|---|
| TA01.01 — Cadastro válido | Aprovado | Administrador de teste cadastrado com matrícula `998877`. O sistema apresentou a mensagem "Administrador cadastrado com sucesso.". |
| TA01.02 — Matrícula não informada | Aprovado | O sistema impediu o cadastro e apresentou a mensagem "A matrícula é obrigatória.". |
| TA01.03 — Nome não informado | Aprovado | O sistema impediu o cadastro e apresentou a mensagem "O nome é obrigatório.". |
| TA01.04 — Senha não informada | Aprovado | O sistema impediu o cadastro e apresentou a mensagem "A senha é obrigatória.". |

**Resultado da história:** os quatro critérios foram aprovados. O formulário apresentou as validações esperadas para os campos obrigatórios e permitiu o cadastro com dados válidos.

### 4.2. US02 — Autenticar administrador e acessar funcionalidades

| Critério | Resultado | Observações |
|---|---|---|
| TA02.01 — Login válido | Aprovado | As credenciais válidas `1234567890/1234567890` permitiram a autenticação e o redirecionamento para `funcoes.php`. |
| TA02.02 — Senha incorreta | Aprovado | O acesso foi negado e o sistema apresentou "Matrícula ou senha inválida.". |
| TA02.03 — Matrícula não cadastrada | Aprovado | O acesso foi negado e o sistema apresentou "Matrícula ou senha inválida.". |
| TA02.04 — Campos vazios | Aprovado | O sistema impediu a autenticação e apresentou "Matrícula e senha são obrigatórias.". |
| T402.05 — Acesso às funcionalidades após login | Parcialmente aprovado | Foi possível acessar `aluno.php`, `livros.php`, `emprestimosDevolucoes.php` e `historico.php`. O acesso a `turmas.php` foi impedido por erro fatal de PHP. |
| T402.06 — Bloqueio de acesso sem autenticação | Aprovado | Tentativas de acesso direto a páginas administrativas sem autenticação foram redirecionadas para `login.php`. |

**Resultado da história:** a autenticação e a proteção das rotas apresentaram os comportamentos esperados. Entretanto, o acesso a todas as funcionalidades administrativas não foi confirmado, pois a tela de turmas apresentou erro fatal.

### 4.3. US03 — Gerenciar alunos

| Critério | Resultado | Observações |
|---|---|---|
| TA03.01 — Cadastro de aluno | Reprovado | O formulário exige a seleção de uma turma, mas não havia turmas disponíveis. O cadastro ficou impossibilitado. |
| TA03.02 — Consulta de aluno | Inconclusivo | Não foi possível cadastrar um aluno de teste para verificar a consulta. |
| TA03.03 — Alteração de dados | Inconclusivo | Não havia registros de alunos disponíveis para realizar a edição. |
| TA03.04 — Alteração da turma | Inconclusivo | Não havia turmas disponíveis para verificar a alteração do vínculo. |
| TA03.05 — Exclusão de aluno | Inconclusivo | Não foi possível executar a exclusão de um registro de teste. |
| TA03.06 — Pesquisa com correspondência | Inconclusivo | Não havia aluno de teste cadastrado para verificar a pesquisa com correspondência. |
| TA03.07 — Pesquisa sem correspondência | Aprovado | A pesquisa por `NOME_INEXISTENTE_XYZ` apresentou a mensagem "Nenhum aluno encontrado.". |

**Resultado da história:** a pesquisa sem correspondência funcionou conforme esperado. Entretanto, o cadastro e os demais cenários dependentes de registros de alunos ficaram comprometidos pela ausência de turmas disponíveis.

### 4.4. US04 — Gerenciar turmas

| Critério | Resultado | Observações |
|---|---|---|
| TA04.01 — Cadastro de turma | Reprovado | A página `add_turma.php` apresentou erro fatal de PHP relacionado à função `exigirAutenticacao()`. |
| TA04.02 — Consulta de turma | Reprovado | A página `turmas.php` apresentou erro fatal de PHP e não carregou a listagem. |
| TA04.03 — Alteração de turma | Não executado | A indisponibilidade do módulo impediu a execução do cenário. |
| TA04.04 — Exclusão de turma | Não executado | A indisponibilidade do módulo impediu a execução do cenário. |
| TA04.05 — Pesquisa por curso | Reprovado | A tela de turmas não carregou devido ao erro fatal. |
| TA04.06 — Pesquisa sem correspondência | Reprovado | A tela de turmas não carregou devido ao erro fatal. |

**Resultado da história:** o módulo de gerenciamento de turmas apresentou falha que impediu a utilização das funcionalidades de cadastro, consulta e pesquisa. Os cenários de alteração e exclusão não puderam ser executados.

### 4.5. US05 — Gerenciar livros

| Critério | Resultado | Observações |
|---|---|---|
| TA05.01 — Cadastro de livro | Aprovado | O livro de teste foi cadastrado com sucesso. O sistema apresentou a mensagem "Novo livro adicionado com sucesso!". |
| TA05.02 — Consulta de livro | Aprovado | O registro foi apresentado na tabela com informações de ISBN, título, autor, editora, ano, edição e quantidade disponível. |
| TA05.03 — Alteração de livro | Aprovado | O título foi alterado para `TESTE_IA_Engenharia de Software Modificada`, e a alteração foi refletida na listagem. |
| TA05.04 — Exclusão de livro | Aprovado | O sistema confirmou a exclusão e apresentou a mensagem "Livro excluído com sucesso.". |
| TA05.05 — Pesquisa por título | Aprovado | A pesquisa pelo título de teste retornou o registro correspondente. |
| TA05.06 — Pesquisa sem correspondência | Aprovado | A pesquisa por `TITULO_INEXISTENTE_XYZ` apresentou a mensagem "Nenhum livro encontrado para o título informado.". |

**Resultado da história:** todos os seis critérios foram aprovados. O módulo de livros permitiu executar o ciclo de cadastro, consulta, edição, exclusão e pesquisa.

### 4.6. US06 — Registrar empréstimos

| Critério | Resultado | Observações |
|---|---|---|
| TA06.01 — Registro de empréstimo válido | Reprovado | A página `addEmprestimo.php` apresentou erro fatal de PHP relacionado à função `exigirAutenticacao()`. |
| TA06.02 — Associação entre aluno e administrador | Não executado | A falha no carregamento da tela impediu a realização do empréstimo. |
| TA06.03 — Armazenamento da data prevista | Não executado | A falha no carregamento da tela impediu a realização do empréstimo. |
| TA06.04 — Empréstimo sem aluno | Reprovado | Não foi possível acessar o formulário para verificar a validação pela interface. |
| TA06.05 — Empréstimo sem exemplar | Não executado | A falha no carregamento da tela impediu a realização do cenário. |
| TA06.06 — Empréstimo de exemplar indisponível | Não executado | A falha no carregamento da tela impediu a realização do cenário. |

**Resultado da história:** a funcionalidade de registro de empréstimos ficou indisponível devido ao erro fatal apresentado em `addEmprestimo.php`. Os critérios que dependiam do formulário não puderam ser validados.

### 4.7. US07 — Registrar devoluções

| Critério | Resultado | Observações |
|---|---|---|
| TA07.01 — Devolução válida | Reprovado | A página `devolverLivro.php` apresentou erro fatal de PHP relacionado à função `exigirAutenticacao()`. |
| TA07.02 — Atualização da data de devolução | Não executado | A falha impediu o processamento da operação. |
| TA07.03 — Retirada dos empréstimos ativos | Não executado | A falha impediu a verificação da atualização da listagem. |
| TA07.04 — Atualização da disponibilidade | Não executado | A falha impediu a verificação da disponibilidade do exemplar. |
| TA07.05 — Devolução sem empréstimo selecionado | Reprovado | O acesso à página sem parâmetros também apresentou erro fatal de PHP. |
| TA07.06 — Devolução duplicada | Não executado | Não foi possível executar a operação de devolução. |

**Resultado da história:** o módulo de devoluções apresentou erro fatal antes de permitir a execução das operações. Assim, não foi possível verificar o fluxo normal de devolução nem suas validações.

### 4.8. US08 — Consultar histórico de empréstimos

| Critério | Resultado | Observações |
|---|---|---|
| TA08.01 — Acesso ao histórico | Aprovado | A página `historico.php` carregou corretamente e apresentou o relatório de empréstimos. |
| TA08.02 — Histórico com empréstimos | Inconclusivo | Não havia registros de empréstimos históricos disponíveis para verificar a apresentação dos dados. |
| TA08.03 — Histórico com devoluções | Inconclusivo | Não havia registros de devoluções disponíveis para verificar a apresentação das informações. |
| TA08.04 — Histórico vazio | Aprovado | O sistema apresentou a mensagem "Nenhum registro encontrado.". |
| TA08.05 — Informações do histórico | Aprovado | A tabela apresentou oito colunas: Código, Livro, Aluno, Matrícula, Data do empréstimo, Data prevista, Data da devolução e Status. |

**Resultado da história:** a tela de histórico e sua estrutura foram verificadas com sucesso. Entretanto, não foi possível validar a apresentação de registros reais de empréstimos e devoluções.

## 5. Problemas identificados

Durante a execução, o agente identificou falhas que afetaram diretamente a utilização de alguns módulos.

### 5.1. Erro fatal no módulo de turmas

**História afetada:** US04 — Gerenciar turmas.

**Critérios relacionados:** TA04.01, TA04.02, TA04.05 e TA04.06.

**Descrição:** as páginas responsáveis pelo cadastro e pela consulta de turmas apresentaram erro fatal antes de carregar a interface.

**Mensagens observadas:**

Em `turmas.php`:

```text
Fatal error: Uncaught Error: Call to undefined function exigirAutenticacao()
in /app/turmas.php on line 9
```

Em `add_turma.php`:

```text
Fatal error: Uncaught Error: Call to undefined function exigirAutenticacao()
in /app/add_turma.php on line 5
```

**Impacto:** impossibilidade de utilizar o módulo de turmas pela interface gráfica, comprometendo cadastro, consulta e pesquisa.

### 5.2. Erro fatal no registro de empréstimos

**História afetada:** US06 — Registrar empréstimos.

**Critérios relacionados:** TA06.01, TA06.04, TA06.05 e TA06.06.

**Descrição:** ao acessar a página `addEmprestimo.php`, a aplicação apresentou erro fatal relacionado à chamada da função `exigirAutenticacao()`.

**Mensagem observada:**

```text
Fatal error: Uncaught Error: Call to undefined function exigirAutenticacao()
in /app/addEmprestimo.php on line 7
```

**Impacto:** impossibilidade de acessar o formulário e realizar novos empréstimos pela interface.

### 5.3. Erro fatal no registro de devoluções

**História afetada:** US07 — Registrar devoluções.

**Critérios relacionados:** TA07.01, TA07.05 e TA07.06.

**Descrição:** a página `devolverLivro.php` apresentou erro fatal ao ser acessada.

**Mensagem observada:**

```text
Fatal error: Uncaught Error: Call to undefined function exigirAutenticacao()
in /app/devolverLivro.php on line 6
```

**Impacto:** impossibilidade de executar operações de devolução e verificar as atualizações relacionadas ao empréstimo e à disponibilidade do exemplar.

### 5.4. Bloqueio em cascata no gerenciamento de alunos

**História afetada:** US03 — Gerenciar alunos.

**Critérios relacionados:** TA03.01 a TA03.06.

**Descrição:** o formulário de cadastro de alunos exige a seleção de uma turma. Entretanto, não havia turmas disponíveis, e o módulo responsável pelo gerenciamento delas estava inacessível.

Durante a tentativa de cadastro, o navegador apresentou a validação de campo obrigatório, indicando que era necessário selecionar um item da lista.

**Impacto:** impossibilidade de cadastrar um aluno de teste e, consequentemente, de executar os cenários que dependiam desse registro.

### 5.5. Erro de JavaScript no console

**Histórias relacionadas:** US02, US06 e US07.

**Descrição:** durante a exploração das páginas, foi identificado no console do navegador um erro relacionado ao arquivo `custom.js`.

**Mensagem observada:**

```text
Uncaught TypeError: Cannot set properties of null (setting 'innerHTML')
```

**Localização informada:** `custom.js`, linha 6.

**Impacto:** o erro indica uma tentativa de manipular um elemento que não foi encontrado no DOM. Seu impacto funcional não foi completamente determinado durante a sessão, mas representa uma falha adicional a ser investigada.

## 6. Evidências coletadas

Durante a execução, o agente registrou capturas de tela das principais etapas, incluindo telas iniciais, validações, operações realizadas e erros encontrados.

A relação de evidências informada pelo agente está organizada abaixo.

| Arquivo | Descrição |
|---|---|
| `step_2.png` | Tela inicial de autenticação |
| `step_4.png` | Validação de campos vazios no login |
| `step_6.png` | Mensagem de senha incorreta |
| `step_8.png` | Mensagem de matrícula não cadastrada |
| `step_10.png` | Autenticação bem-sucedida e painel administrativo |
| `step_24.png` | Erro fatal em `turmas.php` |
| `step_26.png` | Bloqueio de rota restrita sem autenticação |
| `step_30.png` | Tela de cadastro de administrador |
| `step_32.png` | Validação de matrícula obrigatória |
| `step_34.png` | Validação de nome obrigatório |
| `step_39.png` | Validação de senha obrigatória |
| `step_41.png` | Cadastro de administrador realizado |
| `step_43.png` | Tela de gerenciamento de alunos |
| `step_51.png` | Pesquisa de aluno sem correspondência |
| `step_53.png` | Bloqueio de cadastro de aluno sem turma |
| `step_55.png` | Erro fatal em `add_turma.php` |
| `step_63.png` | Tela de gerenciamento de livros |
| `step_65.png` | Pesquisa de livro sem correspondência |
| `step_71.png` | Cadastro de livro realizado |
| `step_76.png` | Livro apresentado na listagem |
| `step_78.png` | Pesquisa por título de livro |
| `step_84.png` | Alteração de livro realizada |
| `step_86.png` | Exclusão de livro confirmada |
| `step_88.png` | Tela de empréstimos e devoluções |
| `step_90.png` | Erro fatal em `addEmprestimo.php` |
| `step_92.png` | Erro fatal em `devolverLivro.php` |
| `step_94.png` | Tela de histórico de empréstimos |

As capturas foram geradas durante a sessão do Antigravity. Para acompanhar este relatório no repositório, os arquivos devem ser organizados no diretório de evidências da Prática 2, preservando seus nomes originais.

## 7. Limitações da execução

A execução não conseguiu validar integralmente todos os critérios de aceitação devido a limitações encontradas durante a navegação.

As principais limitações foram:

1. **Indisponibilidade do módulo de turmas:** os erros fatais em `turmas.php` e `add_turma.php` impediram a execução dos cenários de cadastro, consulta, alteração e exclusão.

2. **Dependência entre turmas e alunos:** a ausência de turmas disponíveis impossibilitou o cadastro de alunos de teste, comprometendo os critérios que dependiam desses registros.

3. **Indisponibilidade do módulo de empréstimos:** o erro fatal em `addEmprestimo.php` impediu a realização de empréstimos e a verificação de suas validações.

4. **Indisponibilidade do módulo de devoluções:** o erro fatal em `devolverLivro.php` impediu a verificação das operações de devolução e das atualizações decorrentes.

5. **Ausência de registros históricos:** a inexistência de empréstimos e devoluções disponíveis limitou a validação do conteúdo do histórico.

6. **Integridade dos dados de teste:** embora o agente tenha informado a exclusão do livro utilizado na avaliação, a remoção do administrador de teste não foi confirmada nas evidências disponíveis.

Dessa forma, os resultados representam o comportamento observado na versão da aplicação e no ambiente disponíveis durante a sessão, não constituindo uma validação integral de todas as funcionalidades.

## 8. Análise crítica da utilização do agente de IA

A utilização do Browser Subagent do Antigravity permitiu avaliar o sistema por meio de interações orientadas por instruções em linguagem natural. Diferentemente dos testes automatizados tradicionais, nos quais os passos e as verificações são previamente implementados em código, o agente interpretou os requisitos e explorou a interface de maneira adaptativa.

A experiência permitiu observar diferenças entre as duas abordagens.

| Dimensão | Testes automatizados tradicionais (Prática 1) | Testes instrumentais com agente de IA (Prática 2) |
|---|---|---|
| Interpretação dos requisitos | Os cenários precisam ser traduzidos em código e verificações explícitas. | O agente interpreta instruções em linguagem natural e utiliza os requisitos para orientar a exploração. |
| Navegação | Segue os passos e seletores programados. | Pode adaptar a navegação de acordo com os elementos encontrados na interface. |
| Identificação de falhas | Detecta falhas previstas nas asserções implementadas. | Pode identificar erros inesperados durante a exploração, como mensagens fatais e problemas no console. |
| Determinismo | Tende a apresentar maior previsibilidade quando o ambiente e os dados são controlados. | O caminho de exploração pode variar conforme o raciocínio e as decisões do modelo. |
| Reprodutibilidade | Os mesmos testes podem ser executados repetidamente com os mesmos passos definidos. | Depende da qualidade das instruções, do contexto fornecido e das condições da aplicação. |
| Manutenção | Alterações na interface podem exigir atualização de seletores e scripts. | Pode se adaptar a algumas mudanças visuais, mas continua dependendo da clareza dos elementos e das instruções. |
| Tempo de execução | Geralmente apresenta execução mais rápida e adequada a verificações frequentes. | Pode demandar mais tempo devido às etapas de interpretação, navegação e tomada de decisão. |
| Dependência de contexto | Depende principalmente da implementação dos testes e da configuração do ambiente. | Depende das instruções, dos requisitos, das restrições e das informações fornecidas ao agente. |

### 8.1. Benefícios observados

Durante a atividade, o agente demonstrou capacidade de percorrer diferentes módulos, interpretar os critérios de aceitação e identificar comportamentos que impediram a execução de determinados fluxos.

Um dos principais benefícios foi a identificação dos erros fatais relacionados à função `exigirAutenticacao()`, encontrados nas funcionalidades de turmas, empréstimos e devoluções.

Também foi possível observar o bloqueio indireto do cadastro de alunos pela ausência de turmas disponíveis, além de identificar um erro de JavaScript no console do navegador.

Essas observações demonstram o potencial dos agentes de IA para apoiar atividades exploratórias e ampliar a análise do comportamento de uma aplicação.

### 8.2. Limitações observadas

Apesar dos benefícios, a utilização do agente também apresentou limitações.

A execução dependeu de instruções detalhadas para delimitar o escopo, preservar os dados existentes e estabelecer os comportamentos esperados. Além disso, o agente não conseguiu contornar falhas que impediam o carregamento das páginas.

Outro aspecto importante é que a interpretação dos resultados precisa ser revisada por uma pessoa responsável pelos testes. A identificação de uma mensagem de erro não significa, por si só, que sua causa-raiz tenha sido comprovada.

Também é necessário conferir as evidências, principalmente quando o agente realiza operações de cadastro, edição ou exclusão.

### 8.3. Complementaridade entre as abordagens

A comparação realizada indica que os testes automatizados tradicionais e os testes instrumentais com IA possuem finalidades complementares.

Os testes E2E da Prática 1 são importantes para validar fluxos conhecidos de forma repetível, com verificações programadas e possibilidade de integração a pipelines de CI/CD.

Já o agente de IA pode contribuir com a exploração de interfaces, interpretação de requisitos e identificação de comportamentos inesperados que não estejam contemplados nos scripts existentes.

Assim, a utilização conjunta das duas abordagens pode ampliar a qualidade da avaliação do software, desde que os resultados produzidos pelo agente sejam verificados e documentados adequadamente.

## 9. Conclusão

A Prática 2 permitiu realizar uma exploração instrumental das oito histórias de usuário do Sistema HBL Control por meio de um agente de Inteligência Artificial.

A execução apresentou resultados positivos nos módulos de cadastro de administradores, autenticação e gerenciamento de livros. Também foi possível verificar a estrutura da tela de histórico de empréstimos e identificar comportamentos relacionados à pesquisa sem correspondência.

Por outro lado, foram encontradas falhas que comprometeram a execução de diversos critérios, especialmente nos módulos de turmas, empréstimos e devoluções. Os erros fatais relacionados à função `exigirAutenticacao()` impediram o carregamento de páginas essenciais, enquanto a ausência de turmas disponíveis bloqueou o cadastro de alunos.

A atividade demonstrou que agentes de IA podem contribuir para testes exploratórios, apoiando a navegação por diferentes módulos e a identificação de problemas durante a interação com a aplicação. Entretanto, sua utilização exige instruções claras, controle sobre as operações realizadas e revisão humana dos resultados.

Conclui-se que os testes instrumentais com agentes de IA representam uma abordagem complementar aos testes automatizados tradicionais. Enquanto os testes E2E oferecem maior previsibilidade para a validação de fluxos conhecidos, os agentes podem ampliar a exploração do sistema e auxiliar na descoberta de problemas não previstos inicialmente.

Dessa forma, recomenda-se a utilização combinada das duas abordagens, aproveitando a repetibilidade dos testes automatizados e a capacidade exploratória dos agentes de IA para apoiar a avaliação contínua da qualidade do Sistema HBL Control.

## 10. Referências

- PROJETO PNLD. **Documento de Visão — Sistema HBL Control**. Arquivo `docs/001-documento-visao.md`.
- PROJETO PNLD. **Lista de User Stories — Sistema HBL Control**. Arquivo `docs/004-lista-users-stories.md`.
- GOOGLE. **Google Antigravity**. Ambiente utilizado para a execução instrumental com agente de IA.
- PLAYWRIGHT. **Playwright**. Ferramenta utilizada como suporte à interação automatizada com o navegador.
