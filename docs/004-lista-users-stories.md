# Processo BSI \- Lista de User Stories

	Modelo para o Doc 004 \- Documento com a Lista de User Stories.


# Descrição

Este documento apresenta a lista de histórias de usuário do Sistema HBL Control, elaboradas a partir dos requisitos definidos no Documento de Visão. As histórias descrevem as funcionalidades sob a perspectiva do administrador, abrangendo cadastro e autenticação, gerenciamento de alunos, turmas e livros, registro de empréstimos e devoluções e geração do Histórico de Empréstimos.

Para cada história, são apresentados os requisitos envolvidos, a prioridade, a estimativa de desenvolvimento, o tamanho funcional, os responsáveis e os testes de aceitação, auxiliando no planejamento, na implementação e na validação do sistema.

## Histórico de revisões

| Data | Versão | Descrição | Autor |
| ----- | ----- | ----- | ----- |
| 22/09/2026 | 1.0 | Elaboração inicial da lista de histórias de usuário do Sistema HBL Control. | Helena Dantas Mariano Isabelle Cavalcanti da Silva Jaine Souza da Luz |

# Lista de User Stories 

| User Story US01 \- Cadastrar administrador |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como responsável pela gestão dos livros didáticos, quero cadastrar uma conta de administrador informando matrícula, nome e senha para posteriormente acessar as funcionalidades administrativas do Sistema HBL Control. |  |  |
| ***Requisitos envolvidos*** | RF1 e RF4 |  |  |
| ***Prioridade*** | Essencial |  |  |
| ***Estimativa*** | 5h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Isabelle (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Helena (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Jaine (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Isabelle (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA01.01 | Preencher matrícula, nome e senha com dados válidos e confirmar o cadastro. O sistema deve cadastrar o administrador e apresentar uma mensagem de sucesso. |  |  |
| TA01.02 | *Tentar cadastrar sem informar a matrícula. O sistema deve impedir o cadastro e indicar que a matrícula é obrigatória.* |  |  |
| TA01.03 | Tentar cadastrar sem informar o nome. O sistema deve impedir o cadastro e indicar que o nome é obrigatório. |  |  |
| TA01.04 | Tentar cadastrar sem informar a senha. O sistema deve impedir o cadastro e indicar que a senha é obrigatória. |  |  |

| User Story US02 \- Autenticar administrador e acessar funcionalidades |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como administrador cadastrado, quero me autenticar utilizando matrícula e senha para acessar a área administrativa e as funcionalidades de gestão de alunos, turmas, livros, empréstimos, devoluções e relatórios |  |  |
| ***Requisitos envolvidos*** | RF1, RF2, RF5 e RNF4 |  |  |
| ***Prioridade*** | Essencial |  |  |
| ***Estimativa*** | 6h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Helena (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Jaine (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Isabelle (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Helena (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA02.01 | Na tela Autenticação, informar matrícula e senha válidas e confirmar. O sistema deve autenticar o administrador e encaminhá-lo à Página do Administrador. |  |  |
| TA02.02 | Informar uma matrícula cadastrada e uma senha incorreta. O sistema deve negar o acesso e apresentar uma mensagem de credenciais inválidas. |  |  |
| TA02.03 | Informar uma matrícula não cadastrada. O sistema deve negar o acesso e apresentar uma mensagem de credenciais inválidas. |  |  |
| TA02.04 | Tentar autenticar sem preencher matrícula ou senha. O sistema deve impedir a autenticação e indicar os campos obrigatórios não preenchidos. |  |  |
| T402.05 | Após a autenticação, acessar as telas Alunos, Turmas, Livros, Empréstimos e Devoluções e Histórico de Empréstimos. O sistema deve permitir o acesso às funcionalidades administrativas correspondentes. |  |  |
| T402.06 | Tentar acessar diretamente uma página ou operação administrativa sem autenticação. O sistema deve bloquear o acesso, solicitar autenticação e não realizar alterações nos dados. |  |  |

| User Story US03 \- Gerenciar alunos |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como administrador, quero cadastrar, consultar, alterar, excluir e pesquisar alunos pelo nome para manter os dados dos estudantes atualizados e vinculados às respectivas turmas. O cadastro deve contemplar matrícula, nome, data de nascimento, endereço, sexo, e-mail e código da turma. |  |  |
| ***Requisitos envolvidos*** | RF1, RF2, RF6 e RF12 |  |  |
| ***Prioridade*** | Essencial |  |  |
| ***Estimativa*** | 10h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Jaine (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Isabelle (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Helena (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Jaine (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA03.01 | Na tela Alunos, informar matrícula, nome, data de nascimento, endereço, sexo, e-mail e código de uma turma existente e confirmar o cadastro. O sistema deve salvar o aluno, associá-lo à turma indicada e apresentar uma mensagem de sucesso. |  |  |
| TA03.02 | Consultar um aluno cadastrado. O sistema deve apresentar os dados correspondentes ao aluno selecionado, incluindo sua associação à turma. |  |  |
| TA03.03 | Alterar os dados de um aluno com informações válidas e salvar. O sistema deve confirmar a alteração e apresentar os dados atualizados em uma nova consulta. |  |  |
| TA03.04 | Alterar a turma de um aluno para outra turma existente e salvar. O sistema deve atualizar a associação e apresentar a nova turma na consulta do aluno. |  |  |
| TA03.05 | Excluir um aluno sem vínculos com empréstimos ou devoluções. O sistema deve confirmar a exclusão e deixar de apresentar o cadastro nas consultas seguintes. |  |  |
| TA03.06 | Pesquisar pelo nome de um aluno cadastrado. O sistema deve apresentar os registros correspondentes ao nome pesquisado. |  |  |
| TA03.07 | Pesquisar um nome sem correspondência nos cadastros. O sistema deve informar que nenhum aluno foi encontrado.  |  |  |

| User Story US04 \- Gerenciar turmas |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como administrador, quero cadastrar, consultar, alterar, excluir e pesquisar turmas pelo curso para manter as informações acadêmicas organizadas e disponíveis para a associação dos alunos. O cadastro deve contemplar código, curso, período, série e matriz curricular. |  |  |
| ***Requisitos envolvidos*** | RF1, RF2, RF7 e RF12 |  |  |
| ***Prioridade*** | Essencial |  |  |
| ***Estimativa*** | 8h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Isabelle (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Jaine (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Helena (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Isabelle (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA04.01 | Na tela Turmas, informar código, curso, período, série e matriz curricular com dados válidos e confirmar o cadastro. O sistema deve salvar a turma e apresentar uma mensagem de sucesso. |  |  |
| TA04.02 | Consultar uma turma cadastrada. O sistema deve apresentar código, curso, período, série e matriz curricular correspondentes à turma selecionada. |  |  |
| TA04.03 | Alterar os dados de uma turma com informações válidas e salvar. O sistema deve confirmar a alteração e apresentar os dados atualizados em uma nova consulta. |  |  |
| TA04.04 | Excluir uma turma sem alunos vinculados. O sistema deve confirmar a exclusão e deixar de apresentar o cadastro nas consultas seguintes. |  |  |
| TA04.05 | Pesquisar turmas pelo curso. O sistema deve apresentar somente as turmas correspondentes ao curso pesquisado. |  |  |
| TA04.06 | Pesquisar um curso sem turmas correspondentes. O sistema deve informar que nenhuma turma foi encontrada. |  |  |

| User Story US05 \- Gerenciar livros |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como administrador, quero cadastrar, consultar, alterar, excluir e pesquisar livros pelo título para manter o acervo atualizado e consultar a disponibilidade dos materiais. O cadastro deve contemplar ISBN, título, autor, editora, ano, edição, situação e quantidade disponível. |  |  |
| ***Requisitos envolvidos*** | RF1, RF2, RF8 e RF12 |  |  |
| ***Prioridade*** | Essencial |  |  |
| ***Estimativa*** | 10h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Helena (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Isabelle (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Jaine (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Helena (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA05.01 | Na tela Livros, informar ISBN, título, autor, editora, ano, edição, situação e quantidade disponível com dados válidos e confirmar o cadastro. O sistema deve salvar o livro e apresentar uma mensagem de sucesso. |  |  |
| TA05.02 | Consultar um livro cadastrado. O sistema deve apresentar os dados correspondentes ao livro selecionado, incluindo situação e quantidade disponível. |  |  |
| TA05.03 | Alterar os dados de um livro com informações válidas e salvar. O sistema deve confirmar a alteração e apresentar os dados atualizados em uma nova consulta. |  |  |
| TA05.04 | Excluir um livro sem vínculos com empréstimos ou devoluções. O sistema deve confirmar a exclusão e deixar de apresentar o cadastro nas consultas seguintes. |  |  |
| TA05.05 | Pesquisar livros pelo título. O sistema deve apresentar os registros correspondentes ao título pesquisado. |  |  |
| TA05.06 | Pesquisar um título sem correspondência nos cadastros. O sistema deve informar que nenhum livro foi encontrado. |  |  |

| User Story US06 \- Registrar empréstimos |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como administrador, quero registrar o empréstimo de livros didáticos para alunos, vinculando o exemplar, a data do empréstimo, a data de devolução prevista e o administrador responsável, para manter o controle dos materiais emprestados. |  |  |
| ***Requisitos envolvidos*** | RF13 |  |  |
| ***Prioridade*** | Essencial |  |  |
| ***Estimativa*** | 8h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Jaine (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Helena (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Isabelle (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Jaine (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA06.01 | Selecionar um aluno, um exemplar disponível e informar os dados necessários para o empréstimo. O sistema deve registrar o empréstimo com sucesso. |  |  |
| TA06.02 | Realizar um empréstimo válido. O sistema deve associar o exemplar ao aluno e ao administrador responsável. |  |  |
| TA06.03 | Informar uma data de devolução prevista válida. O sistema deve armazenar a data junto ao registro do empréstimo. |  |  |
| TA06.04 | Tentar registrar um empréstimo sem selecionar um aluno. O sistema deve impedir o registro. |  |  |
| TA06.05 | Tentar registrar um empréstimo sem selecionar um exemplar. O sistema deve impedir o registro. |  |  |
| TA06.06 | Tentar emprestar um exemplar que não esteja disponível. O sistema deve impedir o empréstimo. |  |  |

| User Story US07 \- Registrar devoluções |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como administrador, quero registrar a devolução dos livros emprestados, informando a data de devolução, para manter o histórico atualizado e controlar a disponibilidade dos materiais. |  |  |
| ***Requisitos envolvidos*** | RF14 |  |  |
| ***Prioridade*** | Essencial |  |  |
| ***Estimativa*** | 6h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Isabelle (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Jaine (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Helena (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Isabelle (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA07.01 | Selecionar um empréstimo ativo e informar uma data de devolução válida. O sistema deve registrar a devolução |  |  |
| TA07.02 | Realizar uma devolução válida. O sistema deve atualizar o registro do empréstimo com a data de devolução. |  |  |
| TA07.03 | Após registrar a devolução, consultar os empréstimos ativos. O empréstimo devolvido não deve mais ser apresentado como ativo. |  |  |
| TA07.04 | Após registrar a devolução, consultar a disponibilidade do exemplar. O sistema deve atualizar sua disponibilidade para permitir novo empréstimo. |  |  |
| TA07.05 | Tentar registrar uma devolução sem selecionar um empréstimo. O sistema deve impedir a operação. |  |  |
| TA07.06 | Tentar registrar novamente a devolução de um empréstimo já devolvido. O sistema deve impedir a operação ou informar que a devolução já foi registrada. |  |  |

| User Story US08 \- Consultar histórico de empréstimos |  |  |  |
| ----- | :---- | :---- | :---- |
| ***Descrição*** | Como administrador, quero consultar o histórico de empréstimos realizados, para acompanhar os registros de empréstimos e devoluções dos livros didáticos. |  |  |
| ***Requisitos envolvidos*** | RF15 |  |  |
| ***Prioridade*** | Importante |  |  |
| ***Estimativa*** | 6h | **Tempo Gasto (real):** | A registrar |
| ***Tamanho Funcional*** | a registrar |  |  |
| ***Analista*** | Helena (responsável por especificar/detalhar o US). |  |  |
| ***Desenvolvedor*** | Isabelle (responsável por implementar e realizar testes de unidade e testes de integração). |  |  |
| ***Revisor*** | Jaine (responsável por avaliar a implementação e executar os –testes de unidade e testes de integração). |  |  |
| ***Testador*** | Helena (responsável por executar os Testes de Aceitação e fazer o relatório de testes). |  |  |
| **Testes de Aceitação (TA)** |  |  |  |
| Código | Descrição |  |  |
| TA08.01 | Acessar a tela de Histórico de Empréstimos. O sistema deve apresentar os registros disponíveis. |  |  |
| TA08.02 | Consultar um histórico que possua empréstimos registrados. O sistema deve apresentar os dados correspondentes aos empréstimos. |  |  |
| TA08.03 | Consultar um histórico que possua devoluções registradas. O sistema deve apresentar as informações referentes às devoluções. |  |  |
| TA08.04 | Consultar o histórico sem possuir registros de empréstimos. O sistema deve informar que não existem registros disponíveis. |  |  |
| TA08.05 | Verificar um registro de empréstimo no histórico. O sistema deve apresentar as informações relacionadas ao aluno, exemplar, data de empréstimo e situação da devolução, quando disponíveis. |  |  |

# Referências 

(coloque aqui, artigos, livros e sites utilizados e citados no documento)

Modelos BSI \- Doc 001 \- Documento de Visão. Acessível em: [https\://docs.google.com/document/d/1NeBIFFvBeuzjwo\_Py0sLOrmnc\_faVB4LU4U9r4Q8A78/edit?usp=sharing](https://docs.google.com/document/d/1NeBIFFvBeuzjwo_Py0sLOrmnc_faVB4LU4U9r4Q8A78/edit?usp=sharing)
