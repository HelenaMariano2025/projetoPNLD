# Relatório de Testes de Aceitação — US01: Cadastrar administrador

## Identificação

- Testadora: Isabelle Cavalcanti da Silva
- Usuário GitHub: Isabellecavalcant
- Desenvolvedora: Helena Dantas Mariano
- Data: 06/10/2026
- User Story: US01 — Cadastrar administrador
- Branch de implementação: task/469
- Branch local utilizada nos testes: qa/helena-pr10
- Commit testado: eea4156
- PR testado: https://github.com/HelenaMariano2025/projetoPNLD/pull/10

## Objetivo

Verificar o cadastro de administrador com matrícula, nome e senha,
assim como as mensagens de validação dos campos obrigatórios,
conforme os critérios TA01.01 a TA01.04.

## Ambiente

- Sistema operacional: Ubuntu
- Navegador: Firefox
- Execução: Docker Compose
- Aplicação: PHP
- Banco de dados: MySQL 8.4 — SistemaHBL
- Endereço: http://localhost:8080/cadastro.php
- Aplicação em execução e banco com status healthy.

## Casos executados

### TA01.01 — Cadastro com dados válidos

Passos:

1. Acessar a tela de cadastro.
2. Informar matrícula 202646401.
3. Informar nome Administrador Teste QA Isabelle.
4. Informar uma senha de teste.
5. Clicar em Cadastrar.

Resultado esperado: cadastrar o administrador e apresentar mensagem
de sucesso.

Resultado observado: a tela apresentou
“Administrador cadastrado com sucesso.”

Status: Passou na verificação pela interface.

Limite da evidência: não foi realizada consulta direta ao banco
para confirmar a persistência.

Evidência: [Cadastro válido](evidencias/ta01-01.png)

### TA01.02 — Matrícula não informada

Passos:

1. Deixar a matrícula vazia.
2. Informar nome e senha.
3. Clicar em Cadastrar.

Resultado esperado: impedir o cadastro e indicar que a matrícula
é obrigatória.

Resultado observado: a tela apresentou “A matrícula é obrigatória.”,
sem mensagem de sucesso.

Status: Passou na verificação pela interface.

Evidência: [Matrícula obrigatória](evidencias/ta01-02.png)

### TA01.03 — Nome não informado

Passos:

1. Informar matrícula 202646402.
2. Deixar o nome vazio.
3. Informar uma senha de teste.
4. Clicar em Cadastrar.

Resultado esperado: impedir o cadastro e indicar que o nome
é obrigatório.

Resultado observado: a tela apresentou “O nome é obrigatório.”,
sem mensagem de sucesso.

Status: Passou na verificação pela interface.

Evidência: [Nome obrigatório](evidencias/ta01-03.png)

### TA01.04 — Senha não informada

Passos:

1. Informar matrícula 202646402.
2. Informar nome Administrador Teste QA Isabelle.
3. Deixar a senha vazia.
4. Clicar em Cadastrar.

Resultado esperado: impedir o cadastro e indicar que a senha
é obrigatória.

Resultado observado: a tela apresentou “A senha é obrigatória.”,
sem mensagem de sucesso.

Status: Passou na verificação pela interface.

Evidência: [Senha obrigatória](evidencias/ta01-04.png)

## Bugs encontrados

Não foram observadas falhas na interface nos quatro cenários
executados. Não houve bugs reproduzidos para documentar nesta execução.

## Sugestões de melhoria

- Adicionar rótulos visíveis aos campos, além dos placeholders.
- Especificar os critérios de matrícula e senha válidas.
- Incluir um cenário de aceitação para matrícula já cadastrada.

Essas sugestões não representam falhas nos critérios executados.

## Resultado e limites

- Cenários executados: 4.
- Passaram na verificação pela interface: 4.
- Falharam na verificação pela interface: 0.

As mensagens apresentadas corresponderam aos quatro critérios
de aceitação. A execução foi manual pela interface.

Não foram verificadas diretamente a persistência do cadastro válido
nem a ausência de inserções nos casos inválidos. Também não foram
avaliados nesta execução autenticação, segurança ou outros fluxos.