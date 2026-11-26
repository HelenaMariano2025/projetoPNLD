# Relatório de Testes de Aceitação — US09

## 1. Identificação

- **Testadora (QA):** Jaine Souza da Luz
- **Desenvolvedora:** Helena Dantas Mariano
- **Revisora:** Isabelle Cavalcanti da Silva
- **Data:** 25/11/2026
- **User Story:** US09 — Encerrar sessão de administrador
- **Arquivo principal:** `logout.php`
- **Suíte de testes automatizada:** `tests/LogoutPaginaTest.php`
- **Sistema:** Projeto PNLD — HBL Control / Sistema BHL

## 2. Objetivo

Este relatório registra os resultados da validação da **US09 — Encerrar sessão de administrador**, verificando a invalidação da sessão no servidor, a limpeza das variáveis de sessão, a expiração dos cookies, o redirecionamento para `login.php` e o bloqueio de acesso às páginas administrativas após o logout.

## 3. Ambiente de Testes

- **Sistema operacional:** Windows
- **Execução:** Docker Compose e PHPUnit
- **Aplicação:** PHP 8.4
- **Banco de dados:** MySQL 8.4 (`SistemaHBL`)
- **Tipo de teste:** Validação funcional e suíte automatizada (`tests/LogoutPaginaTest.php`)

## 4. Cenários de Teste

### TA09.01 — Acionamento da opção "Sair" na barra de navegação

- **Procedimento:** Acessar uma página administrativa autenticado e clicar em "Sair" (`logout.php`).
- **Resultado esperado:** O sistema aciona o encerramento da sessão ativa.
- **Resultado obtido:** O botão "Sair" em `includes/cabecalho.php` direciona para `logout.php` e encerra a sessão.
- **Status:** APROVADO

### TA09.02 — Limpeza das variáveis e destruição da sessão

- **Procedimento:** Executar `logout.php` com sessão preenchida (`matricula` e `nome`).
- **Resultado esperado:** `$_SESSION` deve ficar vazio (`[]`) e a sessão ativa deve ser destruída (`session_destroy()`).
- **Resultado obtido:** Comportamento confirmado no código de `logout.php` e no teste `testLogoutLimpaSessaoERedirecionaParaLogin`.
- **Status:** APROVADO

### TA09.03 — Expiração dos cookies de sessão e identificação

- **Procedimento:** Executar `logout.php` com cookies habilitados.
- **Resultado esperado:** Cookies de sessão, `matricula` e `nome` devem ser expirados com timestamp retroativo e flags `secure`, `httponly` e `samesite`.
- **Resultado obtido:** `logout.php` expira o cookie de sessão e os cookies `matricula` e `nome` com as flags de segurança configuradas.
- **Status:** APROVADO

### TA09.04 — Redirecionamento imediato para a tela de autenticação

- **Procedimento:** Executar `logout.php` com e sem sessão prévia.
- **Resultado esperado:** Redirecionar imediatamente para `login.php`.
- **Resultado obtido:** Ambos os cenários redirecionam para `login.php`, validados em `tests/LogoutPaginaTest.php`.
- **Status:** APROVADO

### TA09.05 — Bloqueio de acesso administrativo após encerramento da sessão

- **Procedimento:** Tentar acessar páginas administrativas diretamente após o logout.
- **Resultado esperado:** Bloquear o acesso e redirecionar para `login.php`.
- **Resultado obtido:** As páginas administrativas utilizam `exigirAutenticacao()`, bloqueando acesso sem sessão ativa.
- **Status:** APROVADO

## 5. Resumo dos Resultados

| Cenário | Descrição | Status |
| --- | --- | --- |
| TA09.01 | Acionamento da opção "Sair" na barra de navegação | APROVADO |
| TA09.02 | Limpeza das variáveis e destruição da sessão | APROVADO |
| TA09.03 | Expiração dos cookies de sessão e identificação | APROVADO |
| TA09.04 | Redirecionamento imediato para `login.php` | APROVADO |
| TA09.05 | Bloqueio de acesso administrativo após logout | APROVADO |
