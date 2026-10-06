# Documento de Visão — Sistema HBL

**Data:** A definir pela equipe  
**Versão:** A definir pela equipe

## 1. Introdução

### 1.1 Propósito

Este documento tem como propósito apresentar e documentar os requisitos do Sistema HBL, estabelecendo as principais funcionalidades e características que o sistema deverá oferecer.

O documento busca servir como referência para o desenvolvimento, organização e validação do sistema, permitindo que a equipe tenha uma visão clara das necessidades do produto e dos requisitos que deverão ser atendidos.

### 1.2 Escopo do Projeto

O Sistema HBL é uma solução web desenvolvida para auxiliar no gerenciamento de livros didáticos fornecidos pelo Programa Nacional do Livro e do Material Didático (PNLD).

O sistema será destinado ao IFPB — Campus Patos e permitirá que o administrador cadastre alunos, turmas e livros, registre empréstimos e devoluções e gere relatórios de histórico de empréstimos.

A interação do administrador com o sistema será realizada por meio de uma sequência de telas que conduzirá o processo desde a autenticação até a geração de relatórios.

## 2. Problema e Oportunidade

### 2.1 Problema

O gerenciamento dos livros didáticos utiliza um processo manual baseado em registros de papel.

O projeto busca substituir esse processo no controle de empréstimos, devoluções e na gestão de alunos, turmas e livros.

### 2.2 Oportunidade

A automatização desse processo busca proporcionar maior agilidade, eficácia, segurança e organização para o setor acadêmico.

### 2.3 Solução Proposta

O Sistema HBL é um sistema web de gerenciamento desenvolvido para auxiliar no controle de livros didáticos fornecidos pelo PNLD no IFPB — Campus Patos.

Seu objetivo principal é automatizar e otimizar o fluxo de empréstimos, devoluções e a gestão de alunos, turmas, livros e exemplares.

## 3. Stakeholders e Usuários

### 3.1 Stakeholders

| Nome | Responsabilidade |
| --- | --- |
| Taciano Morais Silva | Cliente Professor |
| Helena Dantas Mariano | Analista e Desenvolvedora |
| Isabelle Cavalcanti da Silva | Analista e Desenvolvedora |
| Jaine Souza da Luz | Analista e Desenvolvedora |

**Contatos da equipe:**

| Nome | E-mail |
| --- | --- |
| Taciano Morais Silva | tacianosilva@gmail.com |
| Helena Dantas Mariano | helena.mariano.123@ufrn.edu.br |
| Isabelle Cavalcanti da Silva | isabelle.silva.712@ufrn.edu.br |
| Jaine Souza da Luz | jaine.luz.138@ufrn.edu.br |

**Matriz de competências:**

| Nome | Competências |
| --- | --- |
| Taciano Morais Silva | Java, JUnit, Eclipse, JSP, JSF, Hibernate, Matemática, LaTeX, entre outras |
| Helena Dantas Mariano | Python e C |
| Isabelle Cavalcanti da Silva | Python e C |
| Jaine Souza da Luz | Python e C |

### 3.2 Perfis de Usuário

| Perfil | Descrição | Papéis no processo YP-Agentic |
| --- | --- | --- |
| Funcionário do Setor Acadêmico — Administrador | Deverá realizar autenticação para acessar as funcionalidades administrativas. Será responsável pelo gerenciamento de alunos, turmas, livros, empréstimos, devoluções e geração de relatórios | Não especificados no documento original |

O sistema possui um perfil de usuário descrito: o funcionário do setor acadêmico, que atua como administrador.

O administrador deverá possuir capacitação para utilizar o sistema, mas não será necessário que possua conhecimentos técnicos ou especializados em informática.

## 4. Requisitos

### 4.1 Requisitos Funcionais (alto nível)

As prioridades individuais dos requisitos não foram preenchidas no documento original.

| ID | Descrição | Prioridade |
| --- | --- | --- |
| RF1 | O sistema deverá disponibilizar telas específicas para autenticação, cadastro, gestão de alunos, gestão de turmas, gestão de livros, registro de empréstimos, registro de devoluções e geração de relatórios. As telas serão: Página Inicial, Cadastro, Autenticação, Página do Administrador, Alunos, Turmas, Livros, Empréstimos e Devoluções e Histórico de Empréstimos | A definir |
| RF2 | O administrador terá acesso a todas as telas e funcionalidades do sistema, mediante autenticação prévia | A definir |
| RF3 | O sistema deverá ser capaz de gerar o relatório Histórico de Empréstimos | A definir |
| RF4 | O sistema deverá permitir o cadastro de novos administradores, com campos obrigatórios para matrícula, nome e senha | A definir |
| RF5 | O sistema deverá permitir a autenticação de usuários previamente cadastrados por meio de matrícula e senha | A definir |
| RF6 | O sistema deverá permitir o cadastro, consulta, alteração e exclusão de alunos, com matrícula, nome, data de nascimento, endereço, sexo, e-mail e código da turma | A definir |
| RF7 | O sistema deverá permitir o cadastro, consulta, alteração e exclusão de turmas, com código, curso, período, série e matriz curricular | A definir |
| RF8 | O sistema deverá permitir o cadastro, consulta, alteração e exclusão de livros, com ISBN, título, autor, editora, ano, edição, situação e quantidade disponível | A definir |
| RF9 | O sistema deverá permitir o registro de empréstimos de livros, associando o exemplar ao aluno e ao administrador responsável | A definir |
| RF10 | O sistema deverá permitir o registro de devoluções de livros, com data de devolução | A definir |
| RF11 | O sistema deverá exibir a mensagem “Nenhum livro emprestado no momento” quando não houver empréstimos ativos | A definir |
| RF12 | O sistema deverá permitir a busca de alunos pelo nome, turmas pelo curso e livros pelo título | A definir |
| RF13 | O sistema deverá gerar um PDF do Histórico de Empréstimos | A definir |

#### Detalhamento dos requisitos presente no documento original

O documento original também apresenta a lista abaixo, com outra numeração. Ela foi preservada sem unificação dos identificadores.

O ator indicado para todos os requisitos desta lista é o Administrador.

| ID original | Descrição | Prioridade |
| --- | --- | --- |
| RF01 — Incluir Aluno | Um aluno tem os atributos matrícula, nome, data de nascimento, endereço, sexo, e-mail, situação e código da turma | A definir |
| RF02 — Alterar Aluno | Permitir a mudança de dados cadastrais, como endereço, e-mail e demais informações do discente | A definir |
| RF03 — Listar/Consultar Alunos | Permitir a busca e listagem de alunos cadastrados por meio de filtros como nome | A definir |
| RF04 — Visualizar Aluno | Exibir detalhadamente as informações de um aluno específico | A definir |
| RF05 — Excluir Aluno | Permitir a remoção de registros de alunos | A definir |
| RF07 — Incluir Turma | Permitir cadastrar turmas com código, curso, sigla do curso, período, série, matriz curricular e situação | A definir |
| RF08 — Alterar Turma | Permitir a modificação dos dados das turmas cadastradas | A definir |
| RF09 — Listar Turmas | Permitir buscar e listar turmas com base em critérios como curso | A definir |
| RF10 — Visualizar Turma | Exibir detalhadamente as informações de uma turma | A definir |
| RF11 — Excluir Turma | Permitir a exclusão de turmas do sistema | A definir |
| RF12 — Gestão de Livros e Exemplares | Permitir cadastrar, visualizar, alterar e excluir livros, com título, autor, editora, ano, ISBN, edição e quantidade disponível, e gerenciar exemplares individuais por meio de tombos | A definir |
| RF13 — Registro de Empréstimos | Permitir registrar empréstimos de livros didáticos aos alunos, vinculando exemplar, data de empréstimo, data prevista de devolução e administrador responsável | A definir |
| RF14 — Registro de Devoluções | Permitir registrar a devolução dos livros emprestados, atualizando o histórico e a disponibilidade dos materiais | A definir |
| RF15 — Geração de Relatórios | Permitir gerar relatórios e histórico de empréstimos | A definir |
| RF16 — Autenticação e Cadastro de Administradores | Disponibilizar cadastro de novos administradores e autenticação por matrícula e senha para acesso restrito às funcionalidades de gestão | A definir |

### 4.2 Requisitos Não Funcionais

| ID | Descrição |
| --- | --- |
| RNF1 | O sistema deverá possuir uma interface simples, intuitiva e de fácil compreensão, permitindo que os usuários realizem as operações sem necessidade de treinamento prévio |
| RNF2 | O sistema deverá ser acessível via navegador web |
| RNF3 | O sistema deverá rodar em ambientes Windows e Linux |
| RNF4 | O sistema deverá garantir a segurança das informações, restringindo o acesso às funcionalidades administrativas a usuários devidamente autenticados e autorizados |
| RNF5 | O sistema deverá registrar as ações dos usuários em log |

#### Detalhamento dos requisitos não funcionais presente no documento original

A segunda lista do documento original foi preservada abaixo, sem unificação dos identificadores.

| ID original | Descrição |
| --- | --- |
| RNF01 — Interface Web e Acessibilidade | O sistema deverá possuir uma interface web simples, intuitiva e de fácil compreensão, permitindo a navegação e o gerenciamento sem necessidade de treinamentos complexos |
| RNF02 — Compatibilidade de Ambiente | O sistema deverá ser compatível com servidores web que suportem PHP e MySQL, rodando adequadamente em ambientes Windows e Linux |
| RNF03 — Segurança e Controle de Acesso | O sistema deverá garantir a segurança das informações acadêmicas, restringindo as funcionalidades administrativas e operacionais a usuários autenticados |
| RNF04 — Tecnologias de Desenvolvimento | O sistema deverá utilizar HTML, CSS, JavaScript, PHP e MySQL, conforme a arquitetura proposta para a solução web |

> **Nota:** dependendo do porte do projeto, este documento pode absorver o modelo conceitual e o detalhamento dos requisitos. Para projetos maiores, o detalhamento técnico migra para o Documento de Modelos e para a Especificação de User Stories.

## 5. Restrições do Projeto

- Utilização das tecnologias HTML, CSS, JavaScript, PHP e MySQL, conforme descrito no documento original.
- Execução em ambiente com suporte a PHP e banco de dados MySQL.
- Outras restrições técnicas, de prazo ou de recursos: a definir pela equipe.

## 6. Riscos

A tabela deverá ser atualizada ao final de cada iteração, na reunião de acompanhamento.

| Data | Risco | Prioridade | Responsável | Providência/Solução |
| --- | --- | --- | --- | --- |
| 10/09/2026 | Não aprendizado das ferramentas utilizadas pelos componentes do grupo | Alta | Todas | Reforçar os estudos sobre as ferramentas e realizar atividades de aprendizagem com a integrante que conhece a ferramenta |
| 10/09/2026 | Ausência, por qualquer motivo, do cliente | Média | Gerente | Planejar o cronograma considerando a agenda do cliente |
| 10/09/2026 | Não conclusão das funcionalidades do software no tempo estimado | Baixa | Todas | Acompanhar de perto o desenvolvimento de cada membro da equipe |

Os três riscos estavam registrados com status “Vigente” no documento original.

## 7. Critérios de Sucesso

Os critérios de sucesso não estavam preenchidos no documento original.

| Métrica | Valor Atual | Meta | Prazo |
| --- | --- | --- | --- |
| A definir pela equipe | A definir pela equipe | A definir pela equipe | A definir pela equipe |

## 8. Referências

O documento original não apresenta referências preenchidas.

- Outras referências: a definir pela equipe.