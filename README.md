# Projeto PNLD — Controle de Livros Didáticos

Sistema web desenvolvido para auxiliar na organização e distribuição de livros didáticos, centralizando o cadastro de alunos, turmas e livros, além dos registros de empréstimos e devoluções.

O projeto é apresentado na aplicação como **HBL Control / Sistema BHL**, com foco no controle de livros didáticos do Instituto Federal, Campus Patos.

## Objetivo

Facilitar a gestão dos livros didáticos por meio de uma plataforma que permita organizar os cadastros, registrar a distribuição dos materiais e consultar o histórico de empréstimos e devoluções.

## Funcionalidades

- Cadastro e login de administradores.
- Cadastro e consulta de turmas.
- Pesquisa de turmas por curso.
- Cadastro de alunos e associação às turmas.
- Pesquisa de alunos por nome.
- Cadastro de livros com informações como ISBN, título, autor, edição e quantidade disponível.
- Pesquisa de livros por título.
- Registro de empréstimos vinculados a alunos e administradores.
- Registro de devoluções.
- Consulta ao histórico de empréstimos e devoluções.


## CRUD e testes

### CRUD de Alunos

O projeto utiliza operações de CRUD (Create, Read, Update e Delete) para o gerenciamento de alunos. As operações implementadas incluem inserção, consulta, atualização e inativação de alunos.

Para compreender a implementação de CRUD e sua relação com testes de software, foi utilizado como referência o tutorial:

- **PHP CRUD Tutorial – Create, Read, Update & Delete**: https://www.tutorialrepublic.com/php-tutorial/php-mysql-crud-application.php

O tutorial apresenta a implementação das operações de cadastro, consulta, atualização e exclusão utilizando PHP e MySQL. A abordagem serviu como referência para compreender a estrutura das operações CRUD no projeto e relacioná-las à criação dos testes automatizados.

Os testes do CRUD foram implementados com **PHPUnit**, utilizando testes unitários para verificar as operações do `AlunoRepository` e um teste de integração para verificar a comunicação com o banco de dados MySQL.

## Tecnologias utilizadas

| Tecnologia | Finalidade |
| --- | --- |
| PHP | Processamento das páginas e das operações do sistema |
| MySQL | Armazenamento dos dados |
| MySQLi | Comunicação entre PHP e MySQL |
| HTML | Estrutura das páginas |
| CSS | Estilização da interface |
| JavaScript e jQuery | Interações da interface |
| Bootstrap | Estilos e componentes visuais |

## Como executar localmente

### Pré-requisitos

- PHP com a extensão `mysqli`.
- MySQL.
- Git para clonar o repositório, ou download do projeto em ZIP pelo GitHub.

As ferramentas devem estar instaladas e configuradas no seu sistema operacional.

### Passos

1. Clone o repositório e entre na pasta:

   ```bash
   git clone https://github.com/HelenaMariano2025/projetoPNLD.git
   cd projetoPNLD
   ```

   Se baixou o ZIP, extraia os arquivos e abra o terminal na pasta principal do projeto.

2. Inicie o MySQL e importe o arquivo `sql/bancoPNLD.sql` pelo seu gerenciador de banco de dados. Ele criará o banco `SistemaHBL` e suas tabelas.

3. Configure o usuário e a senha do banco em `php/conexao.php`. O usuário deve existir no MySQL e ter acesso ao banco `SistemaHBL`.

4. Na pasta principal do projeto, inicie o servidor:

   ```bash
   php -S localhost:8000
   ```

5. Abra http://localhost:8000/index.html no navegador.

> Mantenha o terminal aberto durante o uso. Para encerrar o servidor, pressione Ctrl + C. A criação do banco e a configuração inicial só precisam ser feitas uma vez. Nas próximas execuções, mantenha o MySQL ativo e inicie o servidor PHP.

### Docker (opcional)

Para executar em contêineres, instale o Docker com Docker Compose. Nesta opção, não é necessário instalar PHP e MySQL diretamente no computador.

Com o Docker em execução, abra o terminal na pasta principal do projeto:

```bash
docker compose up --build -d
```

Acesse [http://localhost:8080/index.html](http://localhost:8080/index.html).

Na primeira execução, o ambiente cria o banco e importa as tabelas automaticamente. Esse banco é separado do MySQL instalado no computador.

Para encerrar os contêineres:

```bash
docker compose down
```

Os dados são preservados entre execuções.

> Configuração destinada ao desenvolvimento local. A execução com Docker ainda não foi validada.

## Fluxo de utilização

1. Cadastrar um administrador e realizar o login.
2. Cadastrar as turmas.
3. Cadastrar os alunos e vinculá-los às turmas.
4. Cadastrar os livros.
5. Registrar os empréstimos.
6. Registrar as devoluções.
7. Consultar o histórico.

## Colaboradores

Os participantes com contribuições registradas no Git podem ser consultados na [página de contribuidores do projeto](https://github.com/HelenaMariano2025/projetoPNLD/graphs/contributors).