# Lista de User Stories — Sistema HBL Control

## 1. Identificação do Projeto

| Campo | Valor |
| --- | --- |
| Projeto | Sistema HBL Control — Projeto PNLD |
| Versão | 1.0 |
| Data | 22/09/2026 |
| Equipe | Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz |
| Papéis | Analista, Desenvolvedor, Revisor e Testador, conforme distribuição por história |

### Histórico de revisões

| Data | Versão | Descrição | Autor |
| --- | --- | --- | --- |
| 22/09/2026 | 1.0 | Elaboração inicial da lista de histórias de usuário do Sistema HBL Control | Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz |

## 2. Estrutura da Lista

Este documento apresenta a lista de histórias de usuário do Sistema HBL Control, elaboradas a partir dos requisitos definidos no Documento de Visão.

As histórias descrevem as funcionalidades sob a perspectiva do administrador, abrangendo cadastro e autenticação, gerenciamento de alunos, turmas e livros, registro de empréstimos e devoluções e consulta ao Histórico de Empréstimos.

Para cada história, são apresentados os requisitos envolvidos, a prioridade, a estimativa de desenvolvimento, o tamanho funcional, os responsáveis e os testes de aceitação.

As prioridades “Essencial” e “Importante” foram preservadas do documento original. Sua correspondência com P0, P1 e P2 deverá ser definida pela equipe.

Os identificadores dos requisitos também foram preservados. As dependências e as prioridades individuais dos requisitos internos não estavam especificadas no documento original.

## 3. User Stories

### US01 — Cadastrar administrador

| Campo | Valor |
| --- | --- |
| Story | Como responsável pela gestão dos livros didáticos, quero cadastrar uma conta de administrador informando matrícula, nome e senha para posteriormente acessar as funcionalidades administrativas do Sistema HBL Control |
| Prioridade | Essencial — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF1 e RF4 |
| Estimativa | 5h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Isabelle |
| Desenvolvedor | Helena |
| Revisor | Jaine |
| Testador | Isabelle |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF1 | Disponibilizar a tela correspondente ao cadastro de administrador | A definir |
| RF4 | Permitir o cadastro de administrador com matrícula, nome e senha obrigatórios | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

Os testes de aceitação originais foram mantidos como critérios de validação da história.

| Código | Descrição |
| --- | --- |
| TA01.01 | Preencher matrícula, nome e senha com dados válidos e confirmar o cadastro. O sistema deve cadastrar o administrador e apresentar uma mensagem de sucesso |
| TA01.02 | Tentar cadastrar sem informar a matrícula. O sistema deve impedir o cadastro e indicar que a matrícula é obrigatória |
| TA01.03 | Tentar cadastrar sem informar o nome. O sistema deve impedir o cadastro e indicar que o nome é obrigatório |
| TA01.04 | Tentar cadastrar sem informar a senha. O sistema deve impedir o cadastro e indicar que a senha é obrigatória |

### US02 — Autenticar administrador e acessar funcionalidades

| Campo | Valor |
| --- | --- |
| Story | Como administrador cadastrado, quero me autenticar utilizando matrícula e senha para acessar a área administrativa e as funcionalidades de gestão de alunos, turmas, livros, empréstimos, devoluções e relatórios |
| Prioridade | Essencial — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF1, RF2, RF5 e RNF4 |
| Estimativa | 6h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Helena |
| Desenvolvedor | Jaine |
| Revisor | Isabelle |
| Testador | Helena |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF1 | Disponibilizar as telas de autenticação e das funcionalidades administrativas | A definir |
| RF2 | Permitir ao administrador acessar as telas e funcionalidades mediante autenticação prévia | A definir |
| RF5 | Permitir autenticação por matrícula e senha de usuário previamente cadastrado | A definir |
| RNF4 | Restringir as funcionalidades administrativas a usuários autenticados e autorizados | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

| Código | Descrição |
| --- | --- |
| TA02.01 | Na tela Autenticação, informar matrícula e senha válidas e confirmar. O sistema deve autenticar o administrador e encaminhá-lo à Página do Administrador |
| TA02.02 | Informar uma matrícula cadastrada e uma senha incorreta. O sistema deve negar o acesso e apresentar uma mensagem de credenciais inválidas |
| TA02.03 | Informar uma matrícula não cadastrada. O sistema deve negar o acesso e apresentar uma mensagem de credenciais inválidas |
| TA02.04 | Tentar autenticar sem preencher matrícula ou senha. O sistema deve impedir a autenticação e indicar os campos obrigatórios não preenchidos |
| T402.05 | Após a autenticação, acessar as telas Alunos, Turmas, Livros, Empréstimos e Devoluções e Histórico de Empréstimos. O sistema deve permitir o acesso às funcionalidades administrativas correspondentes |
| T402.06 | Tentar acessar diretamente uma página ou operação administrativa sem autenticação. O sistema deve bloquear o acesso, solicitar autenticação e não realizar alterações nos dados |

### US03 — Gerenciar alunos

| Campo | Valor |
| --- | --- |
| Story | Como administrador, quero cadastrar, consultar, alterar, excluir e pesquisar alunos pelo nome para manter os dados dos estudantes atualizados e vinculados às respectivas turmas. O cadastro deve contemplar matrícula, nome, data de nascimento, endereço, sexo, e-mail e código da turma |
| Prioridade | Essencial — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF1, RF2, RF6 e RF12 |
| Estimativa | 10h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Jaine |
| Desenvolvedor | Isabelle |
| Revisor | Helena |
| Testador | Jaine |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF1 | Disponibilizar a tela de gerenciamento de alunos | A definir |
| RF2 | Permitir acesso administrativo mediante autenticação prévia | A definir |
| RF6 | Permitir cadastro, consulta, alteração e exclusão de alunos com os dados previstos na história | A definir |
| RF12 | Permitir pesquisa de alunos pelo nome | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

| Código | Descrição |
| --- | --- |
| TA03.01 | Na tela Alunos, informar matrícula, nome, data de nascimento, endereço, sexo, e-mail e código de uma turma existente e confirmar o cadastro. O sistema deve salvar o aluno, associá-lo à turma indicada e apresentar uma mensagem de sucesso |
| TA03.02 | Consultar um aluno cadastrado. O sistema deve apresentar os dados correspondentes ao aluno selecionado, incluindo sua associação à turma |
| TA03.03 | Alterar os dados de um aluno com informações válidas e salvar. O sistema deve confirmar a alteração e apresentar os dados atualizados em uma nova consulta |
| TA03.04 | Alterar a turma de um aluno para outra turma existente e salvar. O sistema deve atualizar a associação e apresentar a nova turma na consulta do aluno |
| TA03.05 | Excluir um aluno sem vínculos com empréstimos ou devoluções. O sistema deve confirmar a exclusão e deixar de apresentar o cadastro nas consultas seguintes |
| TA03.06 | Pesquisar pelo nome de um aluno cadastrado. O sistema deve apresentar os registros correspondentes ao nome pesquisado |
| TA03.07 | Pesquisar um nome sem correspondência nos cadastros. O sistema deve informar que nenhum aluno foi encontrado |

### US04 — Gerenciar turmas

| Campo | Valor |
| --- | --- |
| Story | Como administrador, quero cadastrar, consultar, alterar, excluir e pesquisar turmas pelo curso para manter as informações acadêmicas organizadas e disponíveis para a associação dos alunos. O cadastro deve contemplar código, curso, período, série e matriz curricular |
| Prioridade | Essencial — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF1, RF2, RF7 e RF12 |
| Estimativa | 8h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Isabelle |
| Desenvolvedor | Jaine |
| Revisor | Helena |
| Testador | Isabelle |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF1 | Disponibilizar a tela de gerenciamento de turmas | A definir |
| RF2 | Permitir acesso administrativo mediante autenticação prévia | A definir |
| RF7 | Permitir cadastro, consulta, alteração e exclusão de turmas com os dados previstos na história | A definir |
| RF12 | Permitir pesquisa de turmas pelo curso | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

| Código | Descrição |
| --- | --- |
| TA04.01 | Na tela Turmas, informar código, curso, período, série e matriz curricular com dados válidos e confirmar o cadastro. O sistema deve salvar a turma e apresentar uma mensagem de sucesso |
| TA04.02 | Consultar uma turma cadastrada. O sistema deve apresentar código, curso, período, série e matriz curricular correspondentes à turma selecionada |
| TA04.03 | Alterar os dados de uma turma com informações válidas e salvar. O sistema deve confirmar a alteração e apresentar os dados atualizados em uma nova consulta |
| TA04.04 | Excluir uma turma sem alunos vinculados. O sistema deve confirmar a exclusão e deixar de apresentar o cadastro nas consultas seguintes |
| TA04.05 | Pesquisar turmas pelo curso. O sistema deve apresentar somente as turmas correspondentes ao curso pesquisado |
| TA04.06 | Pesquisar um curso sem turmas correspondentes. O sistema deve informar que nenhuma turma foi encontrada |

### US05 — Gerenciar livros

| Campo | Valor |
| --- | --- |
| Story | Como administrador, quero cadastrar, consultar, alterar, excluir e pesquisar livros pelo título para manter o acervo atualizado e consultar a disponibilidade dos materiais. O cadastro deve contemplar ISBN, título, autor, editora, ano, edição, situação e quantidade disponível |
| Prioridade | Essencial — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF1, RF2, RF8 e RF12 |
| Estimativa | 10h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Helena |
| Desenvolvedor | Isabelle |
| Revisor | Jaine |
| Testador | Helena |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF1 | Disponibilizar a tela de gerenciamento de livros | A definir |
| RF2 | Permitir acesso administrativo mediante autenticação prévia | A definir |
| RF8 | Permitir cadastro, consulta, alteração e exclusão de livros com os dados previstos na história | A definir |
| RF12 | Permitir pesquisa de livros pelo título | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

| Código | Descrição |
| --- | --- |
| TA05.01 | Na tela Livros, informar ISBN, título, autor, editora, ano, edição, situação e quantidade disponível com dados válidos e confirmar o cadastro. O sistema deve salvar o livro e apresentar uma mensagem de sucesso |
| TA05.02 | Consultar um livro cadastrado. O sistema deve apresentar os dados correspondentes ao livro selecionado, incluindo situação e quantidade disponível |
| TA05.03 | Alterar os dados de um livro com informações válidas e salvar. O sistema deve confirmar a alteração e apresentar os dados atualizados em uma nova consulta |
| TA05.04 | Excluir um livro sem vínculos com empréstimos ou devoluções. O sistema deve confirmar a exclusão e deixar de apresentar o cadastro nas consultas seguintes |
| TA05.05 | Pesquisar livros pelo título. O sistema deve apresentar os registros correspondentes ao título pesquisado |
| TA05.06 | Pesquisar um título sem correspondência nos cadastros. O sistema deve informar que nenhum livro foi encontrado |

### US06 — Registrar empréstimos

| Campo | Valor |
| --- | --- |
| Story | Como administrador, quero registrar o empréstimo de livros didáticos para alunos, vinculando o exemplar, a data do empréstimo, a data de devolução prevista e o administrador responsável, para manter o controle dos materiais emprestados |
| Prioridade | Essencial — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF13 |
| Estimativa | 8h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Jaine |
| Desenvolvedor | Helena |
| Revisor | Isabelle |
| Testador | Jaine |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF13 | Registrar empréstimos vinculando exemplar, aluno, administrador responsável, data do empréstimo e data prevista de devolução, conforme a segunda lista de requisitos do Documento de Visão original | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

| Código | Descrição |
| --- | --- |
| TA06.01 | Selecionar um aluno, um exemplar disponível e informar os dados necessários para o empréstimo. O sistema deve registrar o empréstimo com sucesso |
| TA06.02 | Realizar um empréstimo válido. O sistema deve associar o exemplar ao aluno e ao administrador responsável |
| TA06.03 | Informar uma data de devolução prevista válida. O sistema deve armazenar a data junto ao registro do empréstimo |
| TA06.04 | Tentar registrar um empréstimo sem selecionar um aluno. O sistema deve impedir o registro |
| TA06.05 | Tentar registrar um empréstimo sem selecionar um exemplar. O sistema deve impedir o registro |
| TA06.06 | Tentar emprestar um exemplar que não esteja disponível. O sistema deve impedir o empréstimo |

### US07 — Registrar devoluções

| Campo | Valor |
| --- | --- |
| Story | Como administrador, quero registrar a devolução dos livros emprestados, informando a data de devolução, para manter o histórico atualizado e controlar a disponibilidade dos materiais |
| Prioridade | Essencial — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF14 |
| Estimativa | 6h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Isabelle |
| Desenvolvedor | Jaine |
| Revisor | Helena |
| Testador | Isabelle |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF14 | Registrar devoluções e atualizar histórico e disponibilidade dos materiais, conforme a segunda lista de requisitos do Documento de Visão original | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

| Código | Descrição |
| --- | --- |
| TA07.01 | Selecionar um empréstimo ativo e informar uma data de devolução válida. O sistema deve registrar a devolução |
| TA07.02 | Realizar uma devolução válida. O sistema deve atualizar o registro do empréstimo com a data de devolução |
| TA07.03 | Após registrar a devolução, consultar os empréstimos ativos. O empréstimo devolvido não deve mais ser apresentado como ativo |
| TA07.04 | Após registrar a devolução, consultar a disponibilidade do exemplar. O sistema deve atualizar sua disponibilidade para permitir novo empréstimo |
| TA07.05 | Tentar registrar uma devolução sem selecionar um empréstimo. O sistema deve impedir a operação |
| TA07.06 | Tentar registrar novamente a devolução de um empréstimo já devolvido. O sistema deve impedir a operação ou informar que a devolução já foi registrada |

### US08 — Consultar histórico de empréstimos

| Campo | Valor |
| --- | --- |
| Story | Como administrador, quero consultar o histórico de empréstimos realizados, para acompanhar os registros de empréstimos e devoluções dos livros didáticos |
| Prioridade | Importante — classificação P0/P1/P2 a definir |
| Requisitos envolvidos | RF15 |
| Estimativa | 6h |
| Tempo gasto real | A registrar |
| Tamanho funcional | A registrar |
| Analista | Helena |
| Desenvolvedor | Isabelle |
| Revisor | Jaine |
| Testador | Helena |

#### Requisitos Internos

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF15 | Permitir a consulta ao histórico de empréstimos e devoluções, conforme a segunda lista de requisitos do Documento de Visão original | A definir |

#### Dependências

- A definir pela equipe.

#### Critérios de Aceitação (alto nível)

| Código | Descrição |
| --- | --- |
| TA08.01 | Acessar a tela de Histórico de Empréstimos. O sistema deve apresentar os registros disponíveis |
| TA08.02 | Consultar um histórico que possua empréstimos registrados. O sistema deve apresentar os dados correspondentes aos empréstimos |
| TA08.03 | Consultar um histórico que possua devoluções registradas. O sistema deve apresentar as informações referentes às devoluções |
| TA08.04 | Consultar o histórico sem possuir registros de empréstimos. O sistema deve informar que não existem registros disponíveis |
| TA08.05 | Verificar um registro de empréstimo no histórico. O sistema deve apresentar as informações relacionadas ao aluno, exemplar, data de empréstimo e situação da devolução, quando disponíveis |

### Responsabilidades dos papéis

- **Analista:** especificar e detalhar a User Story.
- **Desenvolvedor:** implementar e realizar testes de unidade e integração.
- **Revisor:** avaliar a implementação e executar os testes de unidade e integração.
- **Testador:** executar os testes de aceitação e elaborar o relatório de testes.

## 4. Resumo das Prioridades

| Prioridade original | Quantidade de US | Histórias |
| --- | --- | --- |
| Essencial | 7 | US01, US02, US03, US04, US05, US06 e US07 |
| Importante | 1 | US08 |

A classificação P0/P1/P2 e as prioridades individuais dos requisitos internos deverão ser definidas pela equipe. Nenhuma correspondência foi atribuída nesta reorganização.

## 5. Glossário

O glossário não estava preenchido no documento original.

| Termo | Definição |
| --- | --- |
| A definir pela equipe | A definir pela equipe |

## 6. Referências

- [Documento de Visão do projeto](001-documento-visao.md)
- [Modelos BSI — Doc 001 — Documento de Visão](https://docs.google.com/document/d/1NeBIFFvBeuzjwo_Py0sLOrmnc_faVB4LU4U9r4Q8A78/edit?usp=sharing)

PRD e Especificação de User Stories: links não informados no documento original.