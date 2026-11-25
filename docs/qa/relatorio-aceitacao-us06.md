# Relatório de Testes de Aceitação — US06

## 1. Identificação

- **Testadora (QA):** Jaine Souza da Luz
- **Desenvolvedora:** Helena Dantas Mariano
- **Data:** 09/10/2026
- **User Story:** US06 — Registrar empréstimos
- **Branch testada:** `feat/us06-registrar-emprestimos`
- **PR testado:** PR referente à issue #505 — Registrar empréstimos
- **Sistema:** Projeto PNLD — HBL Control / Sistema BHL

## 2. Objetivo

Este relatório apresenta os resultados dos testes de aceitação realizados sobre a **US06 — Registrar empréstimos**, com o objetivo de verificar se as funcionalidades implementadas atendem aos critérios de aceitação definidos para a User Story.

Os testes buscaram verificar o registro de empréstimos, a atualização da quantidade de livros disponíveis, o tratamento de livros sem estoque, a identificação de alunos e livros e a consistência dos dados em caso de falha.

## 3. Ambiente de Testes

- **Sistema operacional:** Windows
- **Navegador:** Google Chrome
- **Execução:** Docker Compose
- **Aplicação:** PHP
- **Banco de dados:** MySQL 8.4
- **Banco:** `SistemaHBL`
- **URL utilizada:** `http://localhost:8080`
- **Tipo de teste:** Testes de aceitação manuais e execução da suíte automatizada PHPUnit

## 4. Cenários de Teste

### TA06.01 — Registro válido de empréstimo

**Objetivo:** Verificar se é possível registrar um empréstimo utilizando um aluno existente e um livro com estoque disponível.

**Procedimento:**

1. Acessar o sistema.
2. Entrar no módulo de empréstimos.
3. Informar os dados de um aluno existente.
4. Informar o código de um livro cadastrado e disponível.
5. Confirmar o registro do empréstimo.

**Resultado esperado:**

O empréstimo deve ser registrado com sucesso e o sistema deve apresentar uma mensagem de confirmação.

**Resultado obtido:**

Não foi possível concluir o cenário. A tela de livros não pôde ser acessada corretamente para consultar os livros cadastrados e obter um código válido para o empréstimo.

**Status:** BLOQUEADO

**Evidência:** Acesso ao módulo de livros retornando à página inicial após a autenticação.

---

### TA06.02 — Atualização do estoque após o empréstimo

**Objetivo:** Verificar se a quantidade de livros disponíveis é reduzida após o registro de um empréstimo válido.

**Procedimento:**

1. Consultar um livro com estoque disponível.
2. Registrar um empréstimo válido.
3. Consultar novamente a quantidade disponível do livro.
4. Comparar a quantidade anterior com a quantidade posterior ao empréstimo.

**Resultado esperado:**

A quantidade disponível deve ser reduzida em uma unidade após o registro do empréstimo.

**Resultado obtido:**

O cenário não pôde ser executado, pois não foi possível consultar os livros cadastrados nem concluir um empréstimo válido pela interface.

**Status:** BLOQUEADO

**Evidência:** Impossibilidade de acessar a tela de livros e obter os dados necessários para o teste.

---

### TA06.03 — Tentativa de empréstimo de livro sem estoque

**Objetivo:** Verificar se o sistema impede o registro de empréstimo quando não há unidades disponíveis do livro.

**Procedimento:**

1. Consultar os livros cadastrados.
2. Identificar um livro sem unidades disponíveis.
3. Tentar registrar um empréstimo desse livro.
4. Verificar a mensagem apresentada pelo sistema.

**Resultado esperado:**

O sistema deve impedir o empréstimo e informar que não há unidades disponíveis.

**Resultado obtido:**

O cenário não pôde ser executado, pois não foi possível acessar a tela de livros para identificar um registro sem estoque.

**Status:** BLOQUEADO

**Evidência:** Falha de acesso ao módulo de livros após a autenticação.

---

### TA06.04 — Registro de empréstimo para aluno existente

**Objetivo:** Verificar se o sistema permite registrar um empréstimo para um aluno cadastrado.

**Procedimento:**

1. Acessar o módulo de empréstimos.
2. Informar a matrícula de um aluno existente.
3. Informar o código de um livro válido.
4. Tentar registrar o empréstimo.

**Resultado esperado:**

O sistema deve reconhecer o aluno e permitir o registro, desde que os demais dados sejam válidos e exista estoque disponível.

**Resultado obtido:**

Não foi possível concluir o cenário, pois não havia um código de livro válido disponível para completar o fluxo pela interface.

**Status:** BLOQUEADO

**Evidência:** Impossibilidade de consultar os livros cadastrados pelo módulo correspondente.

---

### TA06.05 — Tentativa de empréstimo com livro não encontrado

**Objetivo:** Verificar o comportamento do sistema quando é informado um código de livro que não é localizado.

**Procedimento:**

1. Acessar o módulo de empréstimos.
2. Informar os dados necessários para a operação.
3. Informar um código de livro que não foi possível confirmar como válido.
4. Tentar realizar o empréstimo.
5. Verificar a mensagem apresentada pelo sistema.

**Resultado esperado:**

O sistema deve impedir o registro e informar adequadamente que o livro não foi encontrado.

**Resultado obtido:**

Ao tentar realizar o empréstimo, o sistema apresentou a mensagem:

**“livro não encontrado”**

Entretanto, como não foi possível consultar os livros cadastrados, não foi possível confirmar se o código informado correspondia a um livro inexistente ou apenas a um código desconhecido pela testadora.

**Status:** PARCIALMENTE VERIFICADO

**Evidência:** Mensagem “livro não encontrado” apresentada durante a tentativa de empréstimo.

---

### TA06.06 — Consistência dos dados em caso de falha

**Objetivo:** Verificar se uma falha durante o registro do empréstimo impede a gravação parcial dos dados e mantém o estoque consistente.

**Procedimento:**

1. Preparar uma situação controlada que provoque uma falha durante o registro.
2. Tentar realizar o empréstimo.
3. Verificar se o registro foi cancelado.
4. Conferir se a quantidade disponível do livro permaneceu consistente.

**Resultado esperado:**

A operação deve ser revertida em caso de falha, sem deixar registros parciais nem alterações indevidas no estoque.

**Resultado obtido:**

O cenário não foi executado manualmente, pois não foi possível completar o fluxo de empréstimo pela interface. A consistência dos dados em caso de falha não foi confirmada manualmente.

**Status:** NÃO EXECUTADO

**Evidência:** Cenário impedido pelas limitações de acesso e consulta dos dados necessários.

---

## 5. Resultado dos Testes

| Cenário | Descrição | Resultado |
|---|---|---|
| TA06.01 | Registro válido de empréstimo | BLOQUEADO |
| TA06.02 | Atualização do estoque | BLOQUEADO |
| TA06.03 | Livro sem estoque | BLOQUEADO |
| TA06.04 | Empréstimo para aluno existente | BLOQUEADO |
| TA06.05 | Livro não encontrado | PARCIALMENTE VERIFICADO |
| TA06.06 | Consistência dos dados em caso de falha | NÃO EXECUTADO |

**Total de cenários previstos:** 6  
**Cenários aprovados:** 0  
**Cenários reprovados:** 0  
**Cenários bloqueados:** 4  
**Cenários parcialmente verificados:** 1  
**Cenários não executados:** 1

### Resultado da suíte automatizada

Foi executado o comando `docker compose exec app vendor/bin/phpunit`.

O resultado apresentado foi:

- **Testes executados:** 48
- **Asserções:** 227
- **Erros:** 1

O erro ocorreu no teste `EmprestimoRepositoryTest::testNaoPermiteRegistrarMesmaDevolucaoDuasVezes`.

A mensagem técnica apresentou:

`mysqli_sql_exception: Duplicate entry '20212021' for key 'aluno.PRIMARY'`

Também foi apresentada uma falha de chave estrangeira durante a tentativa de exclusão do aluno, pois havia um empréstimo associado à matrícula.

Esse resultado indica um problema relacionado aos dados utilizados pelo teste e à limpeza dos registros. A execução não permite concluir, isoladamente, que a funcionalidade de empréstimo ou devolução esteja incorreta.

## 6. Bugs Encontrados

Durante os testes de aceitação, foram identificados os seguintes problemas:

**1. Falha de acesso ao módulo de livros**

Após informar usuário e senha, o sistema retorna à página inicial, em vez de abrir a tela de livros.

**Impacto:** impede consultar os livros cadastrados e obter os códigos necessários para os testes de empréstimo.

**2. Mensagem “livro não encontrado” durante a tentativa de empréstimo**

O sistema apresentou a mensagem “livro não encontrado” ao tentar realizar um empréstimo.

**Impacto:** não foi possível confirmar se a mensagem corresponde ao comportamento esperado para um livro inexistente, pois não havia acesso à listagem para verificar o código informado.

**3. Erro na suíte automatizada por conflito de dados**

O teste `testNaoPermiteRegistrarMesmaDevolucaoDuasVezes` falhou devido à tentativa de inserir uma matrícula que já existe no banco, seguida de uma falha de chave estrangeira durante a limpeza dos dados.

**Impacto:** a suíte automatizada não foi concluída sem erros, sendo necessário corrigir o isolamento dos dados utilizados pelo teste.

## 7. Sugestões de Melhoria

- Corrigir o fluxo de autenticação e navegação do módulo de livros, garantindo que o usuário consiga acessar a tela após autenticar-se.
- Facilitar a consulta dos livros cadastrados e de suas quantidades disponíveis.
- Melhorar o feedback apresentado durante o registro de empréstimos, distinguindo códigos inexistentes de outras situações de erro.
- Utilizar dados exclusivos nos testes automatizados, evitando conflitos com registros preexistentes.
- Garantir que a limpeza dos dados de teste respeite as dependências entre empréstimos, devoluções, alunos e turmas.

## 8. Limitações dos Testes

Os testes manuais foram limitados por problemas de acesso ao módulo de livros, o que impediu a execução completa dos cenários que dependem de livros cadastrados e de seus códigos.

Não foi possível confirmar manualmente:

- o registro de um empréstimo válido;
- a atualização do estoque;
- o bloqueio de empréstimo de livro sem estoque;
- a consistência dos dados após uma falha.

Também foi executada a suíte automatizada PHPUnit, que apresentou um erro relacionado à utilização de dados existentes no banco de testes.

Não foram realizados testes de desempenho, segurança ou carga. Os cenários bloqueados não foram considerados aprovados ou reprovados funcionalmente, pois não houve evidências suficientes para essa conclusão.

## 9. Conclusão

Com base nos testes realizados, **não foi possível aprovar integralmente a US06 — Registrar empréstimos**.

A execução manual foi prejudicada pela falha de acesso ao módulo de livros, que impediu consultar os registros disponíveis e obter códigos válidos para concluir os cenários de empréstimo. Durante uma tentativa, o sistema apresentou a mensagem “livro não encontrado”, mas não foi possível determinar se o código informado correspondia a um livro realmente inexistente.

A suíte automatizada também apresentou um erro no teste de devolução duplicada, relacionado à utilização de uma matrícula já existente e à limpeza dos dados de teste.

Recomenda-se corrigir o acesso ao módulo de livros, ajustar o isolamento dos dados dos testes automatizados e repetir os cenários que permaneceram bloqueados. Até que essas verificações sejam realizadas, a US06 deve permanecer **sem aprovação conclusiva nos cenários avaliados**.