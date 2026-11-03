# Projeto PNLD â€” Controle de Livros DidÃ¡ticos

Sistema web desenvolvido para auxiliar na organizaÃ§Ã£o e distribuiÃ§Ã£o de livros didÃ¡ticos, centralizando o cadastro de alunos, turmas e livros, alÃ©m dos registros de emprÃ©stimos e devoluÃ§Ãµes.

O projeto Ã© apresentado na aplicaÃ§Ã£o como **HBL Control / Sistema BHL**, com foco no controle de livros didÃ¡ticos do Instituto Federal, Campus Patos.

## Objetivo

Facilitar a gestÃ£o dos livros didÃ¡ticos por meio de uma plataforma que permita organizar os cadastros, registrar a distribuiÃ§Ã£o dos materiais e consultar o histÃ³rico de emprÃ©stimos e devoluÃ§Ãµes.

## Funcionalidades

- Cadastro e login de administradores.
- Cadastro e consulta de turmas.
- Pesquisa de turmas por curso.
- Cadastro de alunos e associaÃ§Ã£o Ã s turmas.
- Pesquisa de alunos por nome.
- Cadastro de livros com informaÃ§Ãµes como ISBN, tÃ­tulo, autor, ediÃ§Ã£o e quantidade disponÃ­vel.
- Pesquisa de livros por tÃ­tulo.
- Registro de emprÃ©stimos vinculados a alunos e administradores.
- Registro de devoluÃ§Ãµes.
- Consulta ao histÃ³rico de emprÃ©stimos e devoluÃ§Ãµes.


## CRUD e testes

### CRUD de Alunos

O projeto utiliza operaÃ§Ãµes de CRUD (Create, Read, Update e Delete) para o gerenciamento de alunos. As operaÃ§Ãµes implementadas incluem inserÃ§Ã£o, consulta, atualizaÃ§Ã£o e inativaÃ§Ã£o de alunos.

Para compreender a implementaÃ§Ã£o de CRUD e sua relaÃ§Ã£o com testes de software, foi utilizado como referÃªncia o tutorial:

- **PHP CRUD Tutorial â€“ Create, Read, Update & Delete**: https://www.tutorialrepublic.com/php-tutorial/php-mysql-crud-application.php

O tutorial apresenta a implementaÃ§Ã£o das operaÃ§Ãµes de cadastro, consulta, atualizaÃ§Ã£o e exclusÃ£o utilizando PHP e MySQL. A abordagem serviu como referÃªncia para compreender a estrutura das operaÃ§Ãµes CRUD no projeto e relacionÃ¡-las Ã  criaÃ§Ã£o dos testes automatizados.

Os testes do CRUD foram implementados com **PHPUnit**, utilizando testes unitÃ¡rios para verificar as operaÃ§Ãµes do `AlunoRepository` e um teste de integraÃ§Ã£o para verificar a comunicaÃ§Ã£o com o banco de dados MySQL.

## Tecnologias utilizadas

| Tecnologia | Finalidade |
| --- | --- |
| PHP | Processamento das pÃ¡ginas e das operaÃ§Ãµes do sistema |
| MySQL | Armazenamento dos dados |
| MySQLi | ComunicaÃ§Ã£o entre PHP e MySQL |
| HTML | Estrutura das pÃ¡ginas |
| CSS | EstilizaÃ§Ã£o da interface |
| JavaScript e jQuery | InteraÃ§Ãµes da interface |
| Bootstrap | Estilos e componentes visuais |

## Como executar localmente

### PrÃ©-requisitos

- PHP com a extensÃ£o `mysqli`.
- MySQL.
- Git para clonar o repositÃ³rio, ou download do projeto em ZIP pelo GitHub.

As ferramentas devem estar instaladas e configuradas no seu sistema operacional.

### Passos

1. Clone o repositÃ³rio e entre na pasta:

   ```bash
   git clone https://github.com/HelenaMariano2025/projetoPNLD.git
   cd projetoPNLD
   ```

   Se baixou o ZIP, extraia os arquivos e abra o terminal na pasta principal do projeto.

2. Inicie o MySQL e importe o arquivo `sql/bancoPNLD.sql` pelo seu gerenciador de banco de dados. Ele criarÃ¡ o banco `SistemaHBL` e suas tabelas.

3. Configure o usuÃ¡rio e a senha do banco em `php/conexao.php`. O usuÃ¡rio deve existir no MySQL e ter acesso ao banco `SistemaHBL`.

4. Na pasta principal do projeto, inicie o servidor:

   ```bash
   php -S localhost:8000
   ```

5. Abra http://localhost:8000/index.html no navegador.

> Mantenha o terminal aberto durante o uso. Para encerrar o servidor, pressione Ctrl + C. A criaÃ§Ã£o do banco e a configuraÃ§Ã£o inicial sÃ³ precisam ser feitas uma vez. Nas prÃ³ximas execuÃ§Ãµes, mantenha o MySQL ativo e inicie o servidor PHP.

### Docker (opcional)

Para executar em contÃªineres, instale o Docker com Docker Compose. Nesta opÃ§Ã£o, nÃ£o Ã© necessÃ¡rio instalar PHP e MySQL diretamente no computador.

Com o Docker em execuÃ§Ã£o, abra o terminal na pasta principal do projeto:

```bash
docker compose up --build -d
```

Acesse [http://localhost:8080/index.html](http://localhost:8080/index.html).

Na primeira execuÃ§Ã£o, o ambiente cria o banco e importa as tabelas automaticamente. Esse banco Ã© separado do MySQL instalado no computador.

Para encerrar os contÃªineres:

```bash
docker compose down
```

Os dados sÃ£o preservados entre execuÃ§Ãµes.

> ConfiguraÃ§Ã£o destinada ao desenvolvimento local. A execuÃ§Ã£o com Docker ainda nÃ£o foi validada.

## Fluxo de utilizaÃ§Ã£o

1. Cadastrar um administrador e realizar o login.
2. Cadastrar as turmas.
3. Cadastrar os alunos e vinculÃ¡-los Ã s turmas.
4. Cadastrar os livros.
5. Registrar os emprÃ©stimos.
6. Registrar as devoluÃ§Ãµes.
7. Consultar o histÃ³rico.

## Colaboradores

Os participantes com contribuiÃ§Ãµes registradas no Git podem ser consultados na [pÃ¡gina de contribuidores do projeto](https://github.com/HelenaMariano2025/projetoPNLD/graphs/contributors).

## CRUD de livros e testes

O CRUD de livros permite cadastrar, consultar, atualizar e inativar livros. A inativaÃ§Ã£o funciona como exclusÃ£o lÃ³gica para preservar o histÃ³rico de emprÃ©stimos.

Como referÃªncia para um CRUD em PHP com testes, consultei o [PHP CRUD Users API](https://github.com/jonsanchezr/php-crud-users-api). O repositÃ³rio mostra a organizaÃ§Ã£o de uma API CRUD com MySQL, possui testes na pasta `tests` e orienta a executÃ¡-los com `composer test`. A implementaÃ§Ã£o de livros deste projeto utiliza sua prÃ³pria estrutura com MySQLi e PHPUnit.

Os [testes de unidade](tests/LivroRepositoryTest.php) cobrem as quatro operaÃ§Ãµes com mocks. O [teste de integraÃ§Ã£o](tests/LivroRepositoryIntegrationTest.php) executa o CRUD com MySQL real. A [CI](.github/workflows/testes.yml) executa os testes e publica o relatÃ³rio de cobertura.
## DocumentaÃ§Ã£o e testes de software

A documentaÃ§Ã£o do projeto reÃºne informaÃ§Ãµes sobre o planejamento, a execuÃ§Ã£o e a avaliaÃ§Ã£o dos testes de software.

### DocumentaÃ§Ã£o

- [Plano Geral de Testes](docs/plano-geral-de-testes.md)
- [Plano de Testes das IteraÃ§Ãµes 1 e 2](docs/plano-testes-iteracoes-1-e-2.md)
- [Plano da IteraÃ§Ã£o 1](docs/plano-iteracao-1.md)
- [Plano da IteraÃ§Ã£o 2](docs/plano-iteracao-2.md)
- [RelatÃ³rio do Estado Atual dos Testes](docs/relatorio-estado-atual-testes.md)
- [Cobertura Inicial](docs/cobertura-inicial.txt)
- [Testes de MutaÃ§Ã£o](docs/testes-de-mutacao.md)
- [RelatÃ³rios do Infection](docs/mutation/)
- [Manual de ExecuÃ§Ã£o](docs/manual-de-execucao.md)

