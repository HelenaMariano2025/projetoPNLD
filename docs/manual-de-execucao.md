# Manual de Execução — Projeto PNLD

## 1. Introdução

Este manual apresenta os procedimentos necessários para configurar e executar o Sistema de Controle de Livros Didáticos (PNLD), incluindo a inicialização do ambiente, o acesso ao banco de dados e a execução dos testes automatizados.

O projeto utiliza PHP, MySQL, Docker Compose, Composer e PHPUnit.

## 2. Requisitos necessários

Para executar o projeto utilizando Docker, é necessário ter:

- Docker instalado;
- Docker Compose disponível;
- Git instalado para obter o código-fonte;
- Navegador web para acessar o sistema.

O ambiente Docker utiliza PHP 8.4 e MySQL 8.4.

## 3. Obtenção do projeto

Clone o repositório:

```bash
git clone https://github.com/HelenaMariano2025/projetoPNLD.git
```

Entre na pasta do projeto:

```bash
cd projetoPNLD
```

## 4. Inicialização do ambiente com Docker

Na pasta principal do projeto, execute:

```bash
docker compose up --build -d
```

Esse comando constrói a imagem da aplicação, cria os contêineres e inicia os serviços.

O ambiente possui dois serviços principais:

- **app:** executa a aplicação PHP;
- **db:** executa o banco de dados MySQL 8.4.

O banco de dados utilizado é `SistemaHBL`. Na primeira inicialização, o Docker configura o banco e importa o arquivo `sql/bancoPNLD.sql` automaticamente.

Para verificar se os contêineres estão funcionando, execute:

```bash
docker compose ps
```

O serviço do banco de dados deve apresentar o estado `healthy`.

## 5. Instalação das dependências

Com os contêineres em execução, instale as dependências do projeto:

```bash
docker compose exec app composer install
```

O Composer instala as bibliotecas declaradas no arquivo `composer.json`, incluindo PHPUnit e Infection.

## 6. Acesso ao sistema

Após a inicialização do ambiente, abra o navegador e acesse:

http://localhost:8080/index.html

O sistema permite realizar operações de cadastro e gerenciamento de administradores, turmas, alunos, livros, empréstimos e devoluções, além de consultar o histórico.

## 7. Execução dos testes automatizados

Para executar a suíte completa de testes automatizados, utilize:

```bash
docker compose exec app ./vendor/bin/phpunit
```

O PHPUnit executará os testes localizados na pasta `tests`.

Na execução realizada durante a preparação deste manual, foram obtidos:

- 191 testes;
- 576 asserções;
- 1 depreciação sinalizada pelo PHPUnit.

## 8. Geração do relatório de cobertura

Para executar os testes e gerar o relatório de cobertura de código, utilize:

```bash
docker compose exec -e XDEBUG_MODE=coverage app ./vendor/bin/phpunit --coverage-text
```

A opção `XDEBUG_MODE=coverage` habilita a coleta de cobertura pelo Xdebug.

A execução realizada apresentou:

- Cobertura de classes: 81,82%;
- Cobertura de métodos: 96,15%;
- Cobertura de linhas: 97,67%.

O projeto utiliza como referência uma meta mínima de 70% de cobertura.

## 9. Testes de mutação

Os testes de mutação são realizados com a ferramenta Infection.

Para executar a análise, utilize:

```bash
docker compose exec -e XDEBUG_MODE=coverage app ./vendor/bin/infection --threads=1
```

A ferramenta modifica temporariamente trechos do código-fonte para verificar se os testes automatizados conseguem identificar essas alterações.

O tempo de execução pode variar conforme a quantidade de mutantes e o tempo necessário para executar os testes.

Os relatórios são gerados na pasta:

```text
docs/mutation/
```

Entre os arquivos de relatório estão:

- `infection.log`;
- `infection.html`;
- `summary.log`.

Os resultados devem ser analisados considerando os mutantes mortos, sobreviventes, ignorados e os que atingiram o limite de tempo. A ocorrência de timeouts deve ser considerada na interpretação das métricas.

## 10. Encerramento do ambiente

Para encerrar os contêineres, execute:

```bash
docker compose down
```

Esse comando interrompe e remove os contêineres, mas preserva os dados armazenados no volume do banco de dados.

Para iniciar o ambiente novamente, basta executar:

```bash
docker compose up -d
```

**Importante:** não utilize `docker compose down -v` se desejar preservar os dados do banco, pois a opção `-v` remove os volumes associados.

## 11. Considerações finais

Este manual reúne os procedimentos necessários para executar o Sistema PNLD utilizando Docker, instalar suas dependências e realizar os testes automatizados, incluindo a análise de cobertura e os testes de mutação.

A documentação complementar do projeto está disponível na pasta `docs` do repositório.
