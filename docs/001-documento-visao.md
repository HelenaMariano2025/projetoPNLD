# Processo BSI \- Documento de Visão

	Modelo para o Doc 001 \- Documento de Visão. O documento de visão do projeto apresenta informações sobre a equipe, o projeto, lista de requisitos e riscos iniciais do projeto escrito após a Conversa com o Cliente. Modelo baseado nas características do processo easYProcess (YP).

1. **Introdução** 

Este documento tem como propósito apresentar e documentar os requisitos do Sistema HBL, estabelecendo as principais funcionalidades e características que o sistema deverá oferecer. O documento busca servir como referência para o desenvolvimento, organização e validação do sistema, permitindo que a equipe tenha uma visão clara das necessidades do produto e dos requisitos que deverão ser atendidos.  
O Sistema HBL é uma solução web desenvolvida para auxiliar no gerenciamento de livros didáticos fornecidos pelo Programa Nacional do Livro e do Material Didático (PNLD). O sistema automatiza o controle de empréstimos, devoluções e a gestão de alunos, turmas e livros, substituindo o processo manual baseado em registros de papel e trazendo maior agilidade, eficácia, segurança e organização para o setor acadêmico.

2. **Descrição geral**   
     
   **2.1 Requisitos funcionais**  
* *RF1* – O sistema deverá disponibilizar telas específicas para os processos de autenticação, cadastro, gestão de alunos, gestão de turmas, gestão de livros, registro de empréstimos, registro de devoluções e geração de relatórios. As telas serão: Página Inicial, Cadastro, Autenticação, Página do Administrador, Alunos, Turmas, Livros, Empréstimos e Devoluções, e Histórico de Empréstimos.  
* *RF2* \- O administrador terá acesso a todas as telas e funcionalidades do sistema, mediante autenticação prévia.  
* *RF3* \- O sistema deve ser capaz de gerar relatórios: Histórico de Empréstimos.  
* *RF4* \- O sistema deverá permitir o cadastro de novos administradores, com campos obrigatórios para matrícula, nome e senha.  
* *RF5* \- O sistema deverá permitir a autenticação de usuários previamente cadastrados, por meio de matrícula e senha.  
* *RF6* \- O sistema deverá permitir o cadastro, consulta, alteração e exclusão de alunos, com informações como matrícula, nome, data de nascimento, endereço, sexo, e-mail e código da turma.  
* *RF7* \- O sistema deverá permitir o cadastro, consulta, alteração e exclusão de turmas, com informações como código, curso, período, série e matriz curricular  
* *RF8* \- O sistema deverá permitir o cadastro, consulta, alteração e exclusão de livros, com informações como ISBN, título, autor, editora, ano, edição, situação e quantidade disponível.  
* *RF9* \- O sistema deverá permitir o registro de empréstimos de livros, associando o exemplar ao aluno e ao administrador responsável.  
* *RF10* \- O sistema deverá permitir o registro de devoluções de livros, com data de devolução.  
* *RF11 –* O sistema deverá exibir uma mensagem de “Nenhum livro emprestado no momento” quando não houver empréstimos ativos.  
* *RF12 –* O sistema deverá permitir a busca de alunos pelo nome, turmas pelo curso e livros pelo título*.*  
* *RF13 –* O sistema deverá gerar um PDF do Histórico de Empréstimos.


**2.2 Requisitos não-funcionais**

* *RNF1* – O sistema deverá possuir uma interface simples, intuitiva e de fácil compreensão, permitindo que os usuários realizem as operações sem necessidade de treinamento prévio.  
* *RNF2* – O sistema deverá ser acessível via navegador web.  
* *RNF3* – O sistema deverá rodar em ambientes Windows e Linux.  
* *RNF4* – O sistema deverá garantir a segurança das informações, restringindo o acesso às funcionalidades administrativas a usuários devidamente autenticados e autorizados.  
* *RNF5* – O sistema deverá ser feito o log de ações dos usuários.

2.3 **Perfis dos usuários**   
As pessoas que irão interagir com o sistema. Essa definição é fundamental para entender quem são os usuários, o que eles precisam fazer e como o sistema deve se comportar para atender às suas necessidades.

2.5 **Riscos**   
São eventos ou condições incertas que, se ocorrerem, podem impactar negativamente o projeto, afetando o cumprimento dos prazos, a qualidade do software, os custos ou o alcance dos objetivos definidos. No contexto de desenvolvimento de software, a identificação e o gerenciamento de riscos são essenciais para prevenir problemas e planejar ações mitigadoras.

2.6 **Perspectiva do produto**  
O Sistema HBL será um sistema web destinado ao gerenciamento de livros didáticos fornecidos pelo PNLD no IFPB – Campus Patos. O sistema permitirá que o administrador cadastre alunos, turmas e livros, registre empréstimos e devoluções, e gere relatórios de histórico de empréstimos. A interação do administrador com o sistema será realizada por meio de uma sequência de telas que conduzirá o processo desde a autenticação até a geração de relatórios. 

**Histórico de revisões**

| Data | Versão | Descrição | Autor |
| :---: | :---: | ----- | ----- |
| 01/03/2018 | 1.0 | Documento inicial | Taciano Morais Silva |
| 02/09/2026 | 1.1 | Adição da seção Histórico de Revisões neste documento e no modelo | Taciano Morais Silva |
| 16/11/2018 | 1.2 | Pequenos ajustes no texto | Taciano Morais Silva |
| 27/12/2018 | 1.3 | Melhorias no texto de descrição do documento. | Taciano Morais Silva |
| 22/02/2020 | 1.4 | Ajustes de formatação do documento. | Taciano Morais Silva |
| 22/02/2020 | 1.5 | Renomeando de Doc 003 para Doc 001\. | Taciano Morais Silva |

UNIVERSIDADE FEDERAL DO RIO GRANDE DO NORTE  
CENTRO DE ENSINO SUPERIOR DO SERIDÓ  
DEPARTAMENTO DE COMPUTAÇÃO E TECNOLOGIA  
CURSO DE BACHARELADO EM SISTEMAS DE INFORMAÇÃO

# Modelos BSI \- Doc 001 \- Documento de Visão

HELENA DANTAS MARIANO  
ISABELLE CAVALCANTI DA SILVA  
JAINE SOUZA DA LUZ

**SISTEMA HBL: Documento de Visão**

Caicó – RN  
2026

# Sumário {#sumário}

[**Sumário**](#sumário)	**[3](#sumário)**

[**Equipe e Definição de Papéis**](#equipe-e-definição-de-papéis)	**[4](#equipe-e-definição-de-papéis)**

[Matriz de Competências](#matriz-de-competências)	[4](#matriz-de-competências)

[**Descrição do Projeto**](#descrição-do-projeto)	**[4](#descrição-do-projeto)**

[Requisitos Funcionais](#requisitos-funcionais)	[4](#requisitos-funcionais)

[Requisitos não-Funcionais](#requisitos-não-funcionais)	[5](#requisitos-não-funcionais)

[Perfis dos Usuários](#perfis-dos-usuários)	[5](#perfis-dos-usuários)

[Riscos](#riscos)	[5](#riscos)

[**Referências**](#referências)	**[6](#referências)**

# 

# 

# 

# Histórico de revisões

| Data | Versão | Descrição | Autor |
| ----- | ----- | ----- | ----- |
| 19/03/2018 | 1.0 | Documento inicial | Taciano Morais Silva |
|  |  |  |  |

# Equipe e Definição de Papéis {#equipe-e-definição-de-papéis}

	

| Equipe | Papel | E-mail |
| :---- | :---- | :---- |
| Taciano | Cliente Professor | tacianosilva@gmail.com |
| Helena | Analista, Desenvolvedor | helena.mariano.123@ufrn.edu.br |
| Isabelle | Analista, Desenvolvedor | isabelle.silva.712@ufrn.edu.br |
| Jaine | Analista, Desenvolvedor | jaine.luz.138@ufrn.edu.br |

## 	Matriz de Competências {#matriz-de-competências}

| Equipe | Competências |
| :---- | :---- |
| Taciano | Desenvolvedor Java, Junit, Eclipse, JSP, JSF, Hibernate, Matemática, Latex, etc |
| Helena Dantas Mariano | Desenvolvedor python, C |
| Isabelle Cavalcanti da Silva | Desenvolvedor python, C |
| Jaine Souza da Luz | Desenvolvedor python, C |

# Descrição do Projeto {#descrição-do-projeto}

	O projeto Sistema HBL é um sistema web de gerenciamento desenvolvido para auxiliar no controle de livros didáticos fornecidos pelo Programa Nacional do Livro e do Material Didático (PNLD) no IFPB Campus Patos. O sistema tem como objetivo principal automatizar e otimizar o fluxo de empréstimos, devoluções e a gestão de alunos, turmas, livros e exemplares.

## 	Perfis dos Usuários  {#perfis-dos-usuários}

	O sistema poderá ser utilizado por apenas um usuário:

	**Funcionário do Setor Acadêmico**  
	Esse usuário deverá realizar autenticação para acessar as funcionalidades administrativas. Será responsável pelo gerenciamento de alunos, turmas, livros, empréstimos, devoluções e geração de relatórios. O administrador deverá possuir capacitação para utilizar o sistema, mas não será necessário que possua conhecimentos técnicos ou especializados em informática.

## 	Requisitos Funcionais {#requisitos-funcionais}

**RF01 \- Incluir Aluno:** Um aluno tem os atributos matrícula, nome, data de nascimento, endereço, sexo, e-mail, situação e código da turma. (Ator: Administrador) 

**RF02 \- Alterar Aluno:** A alteração permite a mudança de dados cadastrais como endereço, e-mail e demais informações do discente. (Ator: Administrador) 

**RF03 \- Listar/Consultar Alunos:** O sistema permite a busca e listagem de alunos cadastrados por meio de filtros como nome. (Ator: Administrador)

**RF04 \- Visualizar Aluno:** Exibição detalhada das informações de um aluno específico. (Ator: Administrador) 

**RF05 \- Excluir Aluno:** O sistema permite a remoção de registros de alunos. (Ator: Administrador) 

**RF07 \- Incluir Turma:** Permite cadastrar turmas informando dados como código, curso, sigla do curso, período, série, matriz curricular e situação. (Ator: Administrador) 

**RF08 \- Alterar Turma:** Permite a modificação de dados das turmas cadastradas. (Ator: Administrador) 

**RF09 \- Listar Turmas:** Permite buscar e listar turmas com base em critérios como o curso. (Ator: Administrador) 

**RF10 \- Visualizar Turma:** Exibição detalhada das informações de uma turma. (Ator: Administrador)   
**RF11 \- Excluir Turma:** Permite a exclusão de turmas do sistema. (Ator: Administrador) 

**RF12 \- Gestão de Livros e Exemplares:** O sistema permite cadastrar, visualizar, alterar, excluir livros (título, autor, editora, ano, ISBN, edição, quantidade disponível) e gerenciar exemplares individuais por meio de tombos. (Ator: Administrador) 

**RF13 \- Registro de Empréstimos:** O sistema permite registrar o empréstimo de livros didáticos aos alunos, vinculando o exemplar, a data de empréstimo, a data de devolução prevista e o administrador responsável. (Ator: Administrador)

**RF14 \- Registro de Devoluções:** O sistema permite registrar a devolução dos livros emprestados, atualizando o histórico e a disponibilidade dos materiais. (Ator: Administrador) 

**RF15 \- Geração de Relatórios:** O sistema deve ser capaz de gerar relatórios e histórico de empréstimos. (Ator: Administrador) 

**RF16 \- Autenticação e Cadastro de Administradores:** O sistema dispõe de telas de cadastro de novos administradores e autenticação por matrícula e senha para acesso restrito às funcionalidades de gestão. (Ator: Administrador) 

## 	Requisitos não-Funcionais {#requisitos-não-funcionais}

**RNF01 \- Interface Web e Acessibilidade:** O sistema deverá possuir uma interface web simples, intuitiva e de fácil compreensão, permitindo a navegação e o gerenciamento sem necessidade de treinamentos complexos

**RNF02 \- Compatibilidade de Ambiente:** O sistema deverá ser compatível com servidores web que suportem PHP e banco de dados MySQL, rodando adequadamente em ambientes Windows e Linux 

**RNF03 \- Segurança e Controle de Acesso:** O sistema deverá garantir a segurança das informações acadêmicas, restringindo estritamente as funcionalidades administrativas e operacionais a usuários autenticados

**RNF04 \- Tecnologias de Desenvolvimento:** O sistema deve ser estruturado utilizando as tecnologias HTML, CSS, JavaScript, PHP e MySQL, conforme a arquitetura proposta para a solução web 

## Riscos {#riscos}

Preencher na tabela os riscos identificados para o início do projeto. Essa tabela deve ser atualizada ao final de cada iteração na reunião de acompanhamento.

| Data | Risco | Prioridade | Responsável | Status | Providência/Solução |
| :---- | :---- | :---- | :---- | :---- | :---- |
| 10/09/2026 | Não aprendizado das ferramentas utilizadas pelos componentes do grupo | Alta | Todas | Vigente | Reforçar estudos sobre as ferramentas e aulas com a integrante que conhece a ferramenta |
| 10/09/2026 | Ausência por qualquer motivo do cliente | Média | Gerente | Vigente | Planejar o cronograma tendo em base a agenda do cliente |
| 10/09/2026 | Não conclusão das funcionalidades do software no tempo estimado | Baixa | Todas | Vigente | Acompanhar de perto o desenvolvimento de cada membro da equipe |

# Referências {#referências}

(coloque aqui, artigos, livros e sites utilizados e citados no documento)  
