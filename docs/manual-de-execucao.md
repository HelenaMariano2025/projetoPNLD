# MANUAL DE EXECUÇÃO DO SISTEMA PNLD

## 1. Apresentação

Este manual tem como objetivo orientar os usuários na instalação, configuração, execução e utilização do Sistema PNLD — Controle de Livros Didáticos.

O documento foi elaborado para pessoas que ainda não conhecem o sistema, apresentando desde os requisitos necessários para sua execução até as orientações para acessar suas funcionalidades.

## 2. Conhecendo o sistema

O Sistema PNLD é uma aplicação desenvolvida para auxiliar no gerenciamento de informações relacionadas aos livros didáticos.

Após realizar o cadastro e entrar no sistema, o usuário encontrará a tela principal, que apresenta quatro módulos:

- **Alunos:** módulo destinado ao gerenciamento das informações dos alunos.
- **Turmas:** módulo destinado ao gerenciamento das turmas.
- **Livros:** módulo destinado ao gerenciamento do acervo de livros.
- **Empréstimos:** módulo destinado ao gerenciamento dos empréstimos de livros.

As funcionalidades disponíveis podem variar conforme as permissões de acesso do usuário.

## 3. Requisitos para execução

Antes de iniciar a instalação, verifique se o computador possui os seguintes programas:

| Programa | Finalidade |
|---|---|
| Docker Desktop | Executar a aplicação e o banco de dados em contêineres. |
| Docker Compose | Gerenciar os serviços necessários para executar o sistema. |
| Git | Baixar o projeto do repositório. |
| Navegador web | Acessar a aplicação. |

Também é necessário possuir conexão com a internet durante a obtenção do projeto e a instalação inicial das dependências.

### 3.1. Instalação dos programas necessários

**Docker Desktop**

1. Acesse o site oficial: https://www.docker.com/products/docker-desktop/.
2. Baixe a versão compatível com seu sistema operacional.
3. Execute o instalador e siga as instruções apresentadas.
4. Abra o Docker Desktop e aguarde sua inicialização.

**Git**

1. Acesse https://git-scm.com/downloads.
2. Baixe a versão correspondente ao seu sistema operacional.
3. Execute o instalador.
4. Conclua a instalação seguindo as opções recomendadas.

**Navegador**

Utilize um navegador atualizado, como Google Chrome, Microsoft Edge ou Mozilla Firefox.

## 4. Obtendo o projeto

Para baixar o Sistema PNLD, siga os passos:

1. Abra o terminal do computador (PowerShell, no Windows).
2. Acesse a pasta na qual deseja armazenar o projeto.
3. Execute o comando:

```bash
git clone https://github.com/HelenaMariano2025/projetoPNLD.git
```

4. Entre na pasta do projeto:

```bash
cd projetoPNLD
```

Após esses passos, os arquivos do sistema estarão disponíveis no computador.

## 5. Inicializando o sistema

O projeto utiliza Docker Compose para executar a aplicação e o banco de dados.

Antes de continuar, verifique se o Docker Desktop está aberto e em funcionamento.

### 5.1. Iniciando os serviços

Dentro da pasta do projeto, execute:

```bash
docker compose up --build -d
```

Esse comando realiza a construção das imagens necessárias e inicia os serviços definidos no arquivo `compose.yaml`.

O projeto utiliza dois serviços principais:

- **app:** responsável pela execução da aplicação.
- **db:** responsável pelo banco de dados MySQL.

Aguarde a conclusão do processo.

### 5.2. Verificando os serviços

Para verificar se os contêineres foram iniciados corretamente, execute:

```bash
docker compose ps
```

Confira se os serviços estão em execução. O serviço do banco de dados deve apresentar o estado `healthy`.

### 5.3. Instalando as dependências

Com os serviços iniciados, execute:

```bash
docker compose exec app composer install
```

Esse comando instala as dependências necessárias para o funcionamento da aplicação PHP.

Aguarde até que o processo seja concluído.

## 6. Acessando o sistema

Após iniciar os serviços e instalar as dependências, abra o navegador e acesse:

**http://localhost:8080/index.html**

A página inicial do Sistema PNLD será apresentada.

## 7. Cadastro e login

### 7.1. Realizando o cadastro

Para criar uma conta de acesso:

1. Na tela inicial, localize as opções **Cadastro** e **Login**, no canto superior direito.
2. Clique em **Cadastro**.
3. O sistema abrirá a página intitulada **Cadastre-se**.
4. Preencha os campos apresentados no formulário.
5. Conclua o cadastro.

Após finalizar o procedimento, o usuário poderá utilizar as credenciais cadastradas para acessar o sistema.

### 7.2. Realizando o login

Para entrar no sistema:

1. Retorne à página inicial.
2. Clique na opção **Login**, localizada no canto superior direito.
3. Informe as credenciais utilizadas no cadastro.
4. Confirme o acesso.

Após a autenticação, o usuário será direcionado à tela principal do sistema.

## 8. Utilizando os módulos

Na tela principal, estarão disponíveis os módulos Alunos, Turmas, Livros e Empréstimos.

Para acessar um módulo, selecione a opção correspondente na interface.

### 8.1. Módulo Alunos

Permite acessar as funcionalidades relacionadas ao gerenciamento dos alunos cadastrados no sistema.

### 8.2. Módulo Turmas

Permite acessar as funcionalidades relacionadas ao gerenciamento das turmas.

### 8.3. Módulo Livros

Permite acessar as funcionalidades relacionadas ao gerenciamento dos livros didáticos.

### 8.4. Módulo Empréstimos

Permite acessar as funcionalidades relacionadas aos empréstimos de livros.

**Observação:** algumas operações podem depender das permissões associadas ao usuário. Caso uma funcionalidade não esteja disponível, verifique as opções apresentadas na interface do sistema.

## 9. Verificando o funcionamento

Após acessar o sistema, recomenda-se verificar se a aplicação está funcionando corretamente.

Para isso:

1. Confirme se a tela principal foi carregada.
2. Verifique se os quatro módulos estão apresentados.
3. Acesse os módulos disponíveis.
4. Observe se as páginas são carregadas corretamente.
5. Verifique se as operações disponibilizadas pela interface podem ser realizadas.

Caso alguma página não carregue ou ocorra uma mensagem de erro, consulte a seção de solução de problemas.

## 10. Executando os testes automatizados

Esta seção é destinada à verificação técnica do funcionamento do projeto.

Os testes automatizados são executados utilizando PHPUnit.

Para executar a suíte de testes, utilize:

```bash
docker compose exec app ./vendor/bin/phpunit
```

O PHPUnit apresentará o resultado da execução, incluindo a quantidade de testes realizados, as asserções e possíveis falhas.

### 10.1. Verificando a cobertura de código

Para gerar o relatório de cobertura, execute:

```bash
docker compose exec -e XDEBUG_MODE=coverage app ./vendor/bin/phpunit --coverage-text
```

A cobertura permite verificar quais partes do código foram exercitadas durante a execução dos testes.

### 10.2. Executando os testes de mutação

O projeto também utiliza Infection para verificar a qualidade dos testes automatizados.

Para executar:

```bash
docker compose exec -e XDEBUG_MODE=coverage app ./vendor/bin/infection --threads=1
```

Os relatórios gerados podem ser consultados no diretório `docs/mutation/`.

## 11. Solução de problemas

### 11.1. O Docker Desktop não está funcionando

**Possível solução:**

1. Abra o Docker Desktop.
2. Aguarde a inicialização completa.
3. Tente executar novamente o comando de inicialização dos serviços.

### 11.2. A aplicação não abre no navegador

**Possível solução:**

1. Verifique se os contêineres estão em execução:

```bash
docker compose ps
```

2. Caso os serviços estejam parados, execute:

```bash
docker compose up -d
```

3. Atualize a página do navegador.

### 11.3. O sistema apresenta problemas relacionados às dependências

Execute novamente a instalação das dependências:

```bash
docker compose exec app composer install
```

### 11.4. O banco de dados não está disponível

Verifique o estado dos serviços:

```bash
docker compose ps
```

Aguarde a inicialização do banco de dados e confira se o serviço apresenta o estado `healthy`.

Se o problema persistir, verifique as mensagens apresentadas pelo Docker Desktop.

## 12. Encerrando e reiniciando o sistema

### 12.1. Encerrando a execução

Para parar os serviços do projeto, execute:

```bash
docker compose down
```

Esse comando encerra os contêineres sem remover os volumes do banco de dados.

**Atenção:** evite utilizar `docker compose down -v` quando desejar preservar os dados armazenados no banco.

### 12.2. Iniciando novamente

Quando desejar utilizar o sistema em outro momento, abra o Docker Desktop, acesse a pasta do projeto pelo terminal e execute:

```bash
docker compose up -d
```

Depois que os serviços estiverem em execução, acesse novamente:

http://localhost:8080/index.html

## 13. Considerações finais

Este manual apresenta as orientações necessárias para obter, configurar, executar e acessar o Sistema PNLD.

Seguindo as etapas descritas, o usuário poderá preparar seu ambiente, criar suas credenciais de acesso e conhecer os módulos disponibilizados pela aplicação.

Em caso de dificuldades durante a execução, recomenda-se verificar os comandos utilizados, o estado dos serviços no Docker Desktop e as mensagens de erro apresentadas pelo sistema.