# Processo BSI \- Modelo Conceitual e Modelo de Dados

	Modelo para o Doc 002 \- Documento com o modelo conceitual das entidades do software.

	Este documento descreve o modelo conceitual do software e é composto por um conjunto de Entidades e seus relacionamentos. Pode ser feito com Diagramas de Classes da UML ou com outras metodologias de modelagem como Modelagem Entidade \- Relacionamento. Deve conter o modelo de Dados e caso necessário um dicionário de dados.

	Este documento deve conter os seguintes conteúdos:

1. Introdução  
   1. Descrição do Documento (para que serve esse documento?)  
   2. Histórico de revisões  
2. Modelo Conceitual  
   1. Descrição das Entidades  
3. Modelo de Dados  
4. Dicionário de Dados  
5. Referências

**Histórico de revisões**

| Data | Versão | Descrição | Autor |
| :---: | :---: | :---: | :---: |
| 19/03/2018 | 1.0 | Documento inicial | Taciano Morais Silva |
| 14/09/2026 | 1.1 | Atualização do documento conforme o Sistema HBL e inclusão do modelo conceitual, modelo de dados e dicionário de dados.  | Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz  |

UNIVERSIDADE FEDERAL DO RIO GRANDE DO NORTE  
CENTRO DE ENSINO SUPERIOR DO SERIDÓ  
DEPARTAMENTO DE CIÊNCIAS EXATAS E APLICADAS  
PROGRAMA DE GRADUAÇÃO EM SISTEMAS DE INFORMAÇÃO

HELENA DANTAS MARIANO  
ISABELLE CAVALCANTI DA SILVA  
JAINE SOUZA DA LUZ

**SISTEMA HBL: Modelo Conceitual e Modelo de Dados**

Caicó – RN  
2026

# Sumário {#sumário}

[**Sumário**](#sumário)	**[3](#sumário)**

[**Descrição**](#descrição)	**[4](#descrição)**

[Histórico de revisões](#histórico-de-revisões)	[4](#histórico-de-revisões)

[**Modelo Conceitual**](#modelo-conceitual)	**[4](#modelo-conceitual)**

[Descrição das Entidades](#descrição-das-entidades)	[4](#descrição-das-entidades)

[**Modelo de Dados**](#modelo-de-dados)	**[5](#modelo-de-dados)**

[**Dicionário de Dados**](#dicionário-de-dados)	**[5](#dicionário-de-dados)**

[**Referências**](#referências)	**[5](#referências)**

# 

# 

# 

# Descrição {#descrição}

Este documento tem como objetivo apresentar o modelo conceitual e o modelo de dados do Sistema HBL, descrevendo as principais entidades, seus atributos e os relacionamentos existentes entre elas.

O modelo apresentado tem como finalidade representar a estrutura das informações utilizadas pelo sistema de gerenciamento de livros didáticos do IFPB – Campus Patos, servindo como referência para a organização e implementação do banco de dados.

O Sistema HBL é uma solução web destinada ao gerenciamento de livros didáticos fornecidos pelo Programa Nacional do Livro e do Material Didático (PNLD). O sistema contempla funcionalidades relacionadas ao cadastro e gerenciamento de alunos, turmas, livros e exemplares, além do registro de empréstimos, devoluções e geração de relatórios.

## Histórico de revisões {#histórico-de-revisões}

| Data | Versão | Descrição | Autor |
| :---: | :---: | :---: | :---: |
| 01/03/2018 | 1.0 | Documento inicial | Taciano Morais Silva |
| 14/09/2026 | 1.1 | Atualização do documento conforme o Sistema HBL e inclusão do modelo conceitual, modelo de dados e dicionário de dados.  | Helena Dantas Mariano, Isabelle Cavalcanti da Silva e Jaine Souza da Luz  |

# Modelo Conceitual {#modelo-conceitual}

O modelo conceitual representa as entidades que fazem parte do Sistema HBL, seus principais atributos e os relacionamentos existentes entre elas.

O modelo foi elaborado a partir dos requisitos definidos para o sistema e representa os elementos envolvidos no gerenciamento de alunos, turmas, livros, exemplares, empréstimos e devoluções.

No modelo conceitual do Sistema HBL são identificadas as seguintes entidades:

* Aluno;  
* Turma;  
* Livro;  
* Exemplar;  
* Status\_exemplar;  
* Empréstimo;  
* Devolução;  
* Administrador.

Figura 1 — Modelo Conceitual do banco de dados do Sistema HBL 

![Modelo Conceitual](/images/modelo_conceitual.jpeg)

## Descrição das Entidades {#descrição-das-entidades}

	**Aluno:** A entidade **Aluno** representa os estudantes que utilizam os livros didáticos administrados pelo sistema. Seus dados incluem matrícula, nome, data de nascimento, endereço, sexo, e-mail, situação e código da turma. A entidade está relacionada à **Turma**, indicando a qual turma o aluno pertence, e ao **Empréstimo**, representando os empréstimos de exemplares realizados pelo aluno. Os atributos previstos para o aluno também estão descritos no Documento de Visão.

**Turma:** A entidade **Turma** representa as turmas cadastradas no sistema. Possui informações como código, curso, sigla do curso, período, série, matriz curricular e situação. Uma turma pode estar associada a diversos alunos, permitindo organizar os estudantes de acordo com sua respectiva turma.

**Livro:** A entidade **Livro** representa as obras didáticas cadastradas no sistema. Seus atributos incluem código, ISBN, título, autor, editora, ano, edição, situação e quantidade disponível. Um livro pode possuir diversos exemplares, permitindo que cada cópia física seja controlada individualmente. O Documento de Visão prevê o cadastro e gerenciamento dessas informações.

**Exemplar:** A entidade **Exemplar** representa uma cópia individual de um livro. Essa entidade permite controlar cada exemplar por meio de seu código de tombo e registrar informações específicas, como código do livro, data de aquisição, tipo, status e observações. O controle individual dos exemplares é previsto no Documento de Visão por meio do gerenciamento de exemplares através de tombos.

**Status\_exemplar:** A entidade **Status\_exemplar** representa os possíveis estados de um exemplar no sistema. Possui um código e uma descrição, permitindo associar um determinado status a cada exemplar. Essa entidade auxilia no controle da situação individual dos exemplares.

**Empréstimo:** A entidade **Empréstimo** representa o registro da retirada de um exemplar por um aluno. Possui informações como código do empréstimo, código do tombo, código do livro, data do empréstimo, data prevista para devolução, matrícula do aluno e administrador responsável. O registro de empréstimos associa o exemplar ao aluno e ao administrador responsável.

**Devolução:** A entidade **Devolução** representa o registro da devolução de um livro emprestado. Possui código da devolução, código do empréstimo e data da devolução. A devolução permite atualizar o histórico e a disponibilidade dos materiais.

**Administrador:** A entidade **Administrador** representa o funcionário do setor acadêmico responsável pela utilização das funcionalidades administrativas do sistema. Seus atributos incluem matrícula, nome e senha. O administrador realiza a autenticação no sistema e é responsável pelo gerenciamento de alunos, turmas, livros, empréstimos, devoluções e relatórios.

# Modelo de Dados {#modelo-de-dados}

O modelo de dados utilizado para representar a estrutura do banco de dados do Sistema HBL é o **Modelo Entidade-Relacionamento (MER)**, sendo posteriormente representado em um modelo lógico de banco de dados. O modelo lógico apresenta as entidades como tabelas e detalha seus atributos, tipos de dados e relacionamentos, permitindo uma representação mais próxima da implementação do banco de dados.

No desenvolvimento do Sistema HBL, o banco de dados utilizado é o **MySQL**, com o modelo lógico elaborado para organizar as informações persistidas pelo sistema. O TCC informa que o MySQL Workbench foi utilizado para o desenvolvimento, administração e manipulação do banco de dados.

Figura 2 — Modelo Lógico do banco de dados do Sistema HBL 

![Modelo Lógico](/images/modelo_logico.jpeg)

O diagrama utilizado no projeto representa as entidades, seus atributos, relacionamentos e cardinalidades. Essa estrutura está relacionada aos requisitos definidos para o sistema, que contemplam o gerenciamento de alunos, turmas, livros e exemplares, além do registro de empréstimos e devoluções. 

# Dicionário de Dados {#dicionário-de-dados}

O dicionário de dados apresenta os principais atributos das entidades persistidas no banco de dados, indicando seus tipos e sua finalidade.   
	**Tabela 1 — Dicionário de dados da entidade Aluno**

| CAMPO | TIPO | DESCRIÇÃO |
| :---- | :---- | :---- |
| Matrícula | BIGINT | Identificador do aluno. |
| Nome | VARCHAR(100) | Nome completo do aluno. |
| DataNasc | DATE | Data de nascimento do aluno. |
| Endereco | VARCHAR(200) | Endereço do aluno. |
| Sexo | VARCHAR(1) | Sexo informado para o aluno. |
| Email | VARCHAR(100) | Endereço de e-mail do aluno. |
| Situacao | ENUM | Situação cadastral do aluno. |
| CodigoTurma | INT | Identificador da turma à qual o aluno pertence. |

### **Tabela 2 — Dicionário de dados da entidade Turma**

| CAMPO | TIPO | DESCRIÇÃO |
| :---- | :---- | :---- |
| Codigo | INT | Identificador da turma. |
| Curso | VARCHAR(20) | Curso ao qual a turma pertence. |
| siglaCurso | VARCHAR(50) | Sigla do curso. |
| Periodo | INT | Período da turma. |
| Serie | INT | Série correspondente à turma. |
| matrizCurricular | VARCHAR(200) | Matriz curricular da turma. |
| Situacao | ENUM | Situação da turma. |

### **Tabela 3 — Dicionário de dados da entidade Livro**

| CAMPO | TIPO | DESCRIÇÃO |
| :---- | :---- | :---- |
| Codigo | INT | Identificador do livro. |
| Isbn | BIGINT | Número ISBN do livro. |
| Titulo | VARCHAR(100) | Título da obra. |
| Autor | VARCHAR(100) | Autor do livro. |
| Editora | VARCHAR(100) | Editora responsável pela publicação. |
| Ano | INT | Ano de publicação. |
| Situacao | ENUM | Situação do livro. |
| Edicao | INT | Número da edição. |
| qtde\_disponivel | INT | Quantidade de exemplares disponíveis. |

### **Tabela 4 — Dicionário de dados da entidade Exemplar**

| CAMPO | TIPO  | DESCRIÇÃO |
| :---- | :---- | :---- |
| Codigo\_tombo | BIGINT | Identificador individual do exemplar. |
| Codigo\_livro | INT | Identificador do livro ao qual o exemplar pertence. |
| Data\_aquisicao | DATE | Data de aquisição do exemplar. |
| Tipo | INT | Tipo do exemplar. |
| Status | INT | Identificador do status do exemplar. |
| Observacao | VARCHAR(500) | Observações relacionadas ao exemplar. |

### **Tabela 5 — Dicionário de dados da entidade Status\_exemplar**

| CAMPO | TIPO | DESCRIÇÃO |
| :---- | :---- | :---- |
| Codigo | INT | Identificador do status. |
| Descricao | VARCHAR(100) | Descrição do status do exemplar. |

### **Tabela 6 — Dicionário de dados da entidade Empréstimo**

| CAMPO | TIPO | DESCRIÇÃO |
| :---- | :---- | :---- |
| Codigo\_emprestimo | INT | Identificador do empréstimo. |
| Codigo\_tombo | BIGINT | Identificador do exemplar emprestado. |
| Codigo\_livro | INT | Identificador do livro livro relacionado ao empréstimo. |
| dataEmprestimo | DATE | Data em que o empréstimo foi realizado. |
| dataDevolucao | DATE | Data prevista para devolução. |
| matricula\_aluno | BIGINT | Matrícula do aluno que recebeu o exemplar. |
| adm\_responsavel | BIGINT | Matrícula do administrador responsável pelo empréstimo. |

### **Tabela 7 — Dicionário de dados da entidade Administrador**

| CAMPO | TIPO | DESCRIÇÃO |
| :---- | :---- | :---- |
| Matricula | BIGINT | Identificador/matrícula do administrador. |
| Nome | VARCHAR(50) | Nome do administrador. |
| Senha | VARCHAR(20) | Senha utilizada para autenticação. |

### **Tabela 8 — Dicionário de dados da entidade Devolução**

| CAMPO | TIPO | DESCRIÇÃO |
| :---- | :---- | :---- |
| Codigo\_devolucao | INT | Identificador da devolução. |
| Codigo\_emprestimo | INT | Identificador do empréstimo relacionado à devolução. |
| dataDevolucao | DATE | Data em que o livro foi devolvido. |

### 

# Referências {#referências}

Link para o Documento de Visão: [https://docs.google.com/document/d/1s5gq7Pw8K6H5evGXFF3mIa4JmKgtDbrKjxW2ar0grGM/edit?usp=sharing](https://docs.google.com/document/d/1s5gq7Pw8K6H5evGXFF3mIa4JmKgtDbrKjxW2ar0grGM/edit?usp=sharing)  


