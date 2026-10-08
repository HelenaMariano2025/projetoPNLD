# Plano de Iterações Geral

# 1. Objetivo

Este documento apresenta a organização prevista para o desenvolvimento do **Projeto PNLD — Controle de Livros Didáticos**, considerando a divisão das User Stories em iterações e a distribuição dos papéis entre os integrantes da equipe.

Cada iteração é composta por até **três User Stories**, com os papéis de **Analista, Desenvolvedor, Revisor e Testador/QA** distribuídos entre os integrantes.

A distribuição busca promover a participação de todos os integrantes em diferentes atividades do processo de desenvolvimento, mantendo a separação entre implementação, revisão e validação das funcionalidades.

---

# 2. Integrantes da equipe

* **Isabelle Cavalcanti da Silva**
* **Helena Dantas Mariano**
* **Jaine Souza da Luz**

---
# 3. Separação das Iterações por Unidade
## UNIDADE I
### Iteração 1

A primeira iteração é composta pelas **US01, US02 e US03**.

| User Story                                                    | Analista                     | Desenvolvedor         | Revisor                      | Testador/QA                  |
| ------------------------------------------------------------- | ---------------------------- | --------------------- | ---------------------------- | ---------------------------- |
| **US01 – Cadastrar administrador**                            | Isabelle Cavalcanti da Silva | Helena Dantas Mariano | Jaine Souza da Luz           | Isabelle Cavalcanti da Silva |
| **US02 – Autenticar administrador e acessar funcionalidades** | Helena Dantas Mariano        | Jaine Souza da Luz    | Isabelle Cavalcanti da Silva | Helena Dantas Mariano        |
| **US03 – Gerenciar alunos**                                   | Jaine Souza da Luz           | Isabelle Cavalcanti da Silva | Helena Dantas Mariano | Jaine Souza da Luz           |

### Atividades previstas

* Análise dos critérios de aceitação das US01, US02 e US03.
* Implementação das funcionalidades pelos respectivos desenvolvedores.
* Revisão das implementações por outro integrante da equipe.
* Execução dos testes de unidade e integração quando aplicável.
* Execução dos testes de aceitação e atividades de QA.
* Registro das evidências e resultados dos testes.
* Análise de qualidade do código com SonarQube.
* Criação e revisão dos Pull Requests correspondentes.
* Ajustar SonarQube
* Configurar Git Actions
---
## UNIDADE II
### Iteração 2

A segunda iteração é composta pelas **US04, US05 e US06**.

| User Story | Analista                     | Desenvolvedor                | Revisor                      | Testador/QA                  |
| ---------- | ---------------------------- | ---------------------------- | ---------------------------- | ---------------------------- |
| **US04**   | Isabelle Cavalcanti da Silva | Jaine Souza da Luz           | Helena Dantas Mariano        | Isabelle Cavalcanti da Silva |
| **US05**   | Helena Dantas Mariano        | Isabelle Cavalcanti da Silva | Jaine Souza da Luz           | Helena Dantas Mariano        |
| **US06**   | Jaine Souza da Luz           | Helena Dantas Mariano        | Isabelle Cavalcanti da Silva | Jaine Souza da Luz           |

### Atividades previstas

* Análise dos critérios de aceitação das US04, US05 e US06.
* Implementação das funcionalidades pelos respectivos desenvolvedores.
* Revisão das implementações.
* Execução dos testes de unidade e integração quando aplicável.
* Execução dos testes de aceitação e atividades de QA.
* Registro das evidências e resultados dos testes.
* Análise de qualidade do código com SonarQube.
* Criação e revisão dos Pull Requests correspondentes.

---
## UNIDADE III
### Iteração 3

A terceira iteração seguirá o mesmo modelo de organização, contemplando as User Stories seguintes à US06.

| User Story             | Analista                     | Desenvolvedor                | Revisor                      | Testador/QA                  |
| ---------------------- | ---------------------------- | ---------------------------- | ---------------------------- | ---------------------------- |
| **US07**               | Isabelle Cavalcanti da Silva | Jaine Souza da Luz           | Helena Dantas Mariano        | Isabelle Cavalcanti da Silva |
| **US08**               | Helena Dantas Mariano        | Isabelle Cavalcanti da Silva | Jaine Souza da Luz           | Helena Dantas Mariano        |
| **Próxima User Story** | Jaine Souza da Luz           | Helena Dantas Mariano        | Isabelle Cavalcanti da Silva | Jaine Souza da Luz           |

> A terceira User Story da Iteração 3 será definida conforme o planejamento final do projeto e a quantidade de User Stories estabelecida para o desenvolvimento.

### Atividades previstas

* Análise dos critérios de aceitação das User Stories da iteração.
* Implementação das funcionalidades.
* Revisão das implementações.
* Execução dos testes de unidade e integração quando aplicável.
* Execução dos testes de aceitação e atividades de QA.
* Registro das evidências e resultados dos testes.
* Análise de qualidade do código com SonarQube.
* Criação e revisão dos Pull Requests correspondentes.

---

# 4. Distribuição dos papéis

A distribuição dos papéis é realizada de forma rotativa entre as User Stories, permitindo que os integrantes participem de diferentes etapas do processo de desenvolvimento.

Os papéis considerados são:

### Analista

Responsável por analisar a User Story, seus requisitos e critérios de aceitação, contribuindo para o entendimento da funcionalidade antes da implementação.

### Desenvolvedor

Responsável pela implementação da funcionalidade, criação ou adequação dos testes relacionados e correção dos problemas encontrados durante o desenvolvimento.

### Revisor

Responsável por revisar a implementação realizada, verificando aspectos relacionados aos requisitos, organização do código e funcionamento da solução antes da integração.

### Testador/QA

Responsável por validar a funcionalidade de acordo com os critérios de aceitação, executar os testes previstos, registrar evidências e reportar eventuais problemas encontrados.

---

# 5. Fluxo de trabalho

Para cada User Story, será seguido, de forma geral, o seguinte fluxo:

```text
Análise da User Story
        ↓
Definição/validação dos critérios de aceitação
        ↓
Desenvolvimento
        ↓
Testes
        ↓
Pull Request
        ↓
Revisão
        ↓
Correções, se necessárias
        ↓
Validação/QA
        ↓
Integração da funcionalidade
```

O fluxo poderá ser ajustado conforme as necessidades de cada User Story e as atividades específicas de cada iteração.

---

# 6. Estratégia de testes e qualidade

Durante as iterações, serão utilizados diferentes níveis de testes conforme a necessidade das funcionalidades:

* **Testes unitários:** validação de funções e componentes de forma isolada.
* **Testes de integração:** validação da interação entre componentes e banco de dados.
* **Testes de aceitação:** validação dos critérios de aceitação das User Stories.
* **Testes de QA:** validação funcional das funcionalidades implementadas, com registro das evidências.
* **Cobertura de código:** acompanhamento da cobertura dos testes quando aplicável.
* **SonarQube:** análise automatizada de qualidade, bugs, vulnerabilidades, code smells, cobertura e duplicações.

---

# 7. Controle das entregas

Cada implementação deverá ser associada à respectiva User Story e registrada no controle de tarefas do projeto.

As alterações serão desenvolvidas em branches específicas e submetidas por meio de Pull Requests, permitindo a revisão antes da integração.

As evidências de testes, resultados de QA e demais informações relevantes deverão ser registradas na documentação correspondente à tarefa ou à iteração.

---

# 8. Observação sobre o planejamento

A organização apresentada neste documento representa o planejamento inicial da equipe. A distribuição poderá ser ajustada caso ocorram mudanças nas User Stories, prioridades do projeto ou necessidades identificadas durante o desenvolvimento.

Alterações relevantes na organização das iterações deverão ser registradas para manter a documentação do projeto atualizada.
