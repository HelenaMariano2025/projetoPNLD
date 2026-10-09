# Plano de Teste da Iteração

**Projeto:** Sistema HBL Control  
**Iteração:** Iterações 1 e 2 — distribuição das US a confirmar no Plano de Iterações  
**Período:** A preencher conforme o Plano de Iterações  
**Data:** 08/10/2026  
**Versão:** 0.3  
**Membros Responsáveis:** Isabelle, Helena e Jaine — desenvolvedoras, revisoras e testadoras conforme a US  

**Documentos de entrada:** Plano de Iterações, Lista de User Stories e respectivas análises. Os caminhos desses documentos devem ser vinculados na seção 7. A estratégia geral de testes pertence ao Plano Geral de Testes.

## 1. Objetivos da Iteração

Verificar o cadastro e a autenticação de administradores, o gerenciamento de alunos, turmas e livros e o registro de empréstimos previstos para as Iterações 1 e 2.

Validar os critérios de aceitação, a persistência dos dados, o controle de acesso e a integridade dos relacionamentos. Para empréstimos, verificar a disponibilidade, a redução da quantidade disponível e o prazo de devolução de 20 dias.

Este documento consolida o planejamento das duas iterações. A distribuição das US e as datas deverão ser preenchidas conforme o Plano de Iterações, sem presumir a iteração a partir do número da US.

## 2. User Stories (US) Abordadas na Iteração

Os dados completos de requisitos, prioridade, tamanho e responsabilidades pertencem à Lista de User Stories e às análises.

As referências abaixo identificam as análises fornecidas. Substituir pelos links dos documentos no repositório quando disponíveis.

| ID US | Título | Iteração | 
|---|---|---|
| US01 | Cadastrar administrador | 1 |
| US02 | Autenticar administrador e acessar funcionalidades | 1 |
| US03 | Gerenciar alunos | 1 |
| US04 | Gerenciar turmas | 2 |
| US05 | Gerenciar livros | 2 |
| US06 | Registrar empréstimos | 2 |

## 3. Matriz de Casos de Teste de Aceitação da Iteração

Os casos operacionalizam os critérios de aceitação das análises. Os IDs Txx.xx foram preservados para manter o vínculo com os planos fornecidos.

A coluna de pré-condições detalha a preparação específica de cada caso, conforme o enunciado da atividade.

Os dados de entrada são descritos por suas características. Os valores concretos serão preparados antes da execução. Os testes devem usar ambiente e banco de testes, com dados restaurados entre cenários que alterem cadastros ou estoque.

### US01 — Cadastrar administrador

- **Requisitos associados:** RF1 e RF4.
- **Responsável pelos testes de aceitação:** Isabelle.
- **Pré-condições gerais:** tela de cadastro disponível.

| ID CT | Cenário e referência | Pré-condições | Passos | Dados de Entrada | Resultado Esperado | Tipo |
|---|---|---|---|---|---|---|
| T01.01 | Cadastrar administrador com dados válidos (TA01.01) | Tela de cadastro de administrador disponível. | 1. Acessar a tela de cadastro; 2. Preencher matrícula, nome e senha válidos; 3. Confirmar o cadastro; 4. Conferir a resposta e o novo registro. | Matrícula, nome e senha válidos. | Administrador cadastrado e mensagem de sucesso apresentada. | Manual / Aceitação |
| T01.02 | Tentar cadastrar administrador sem matrícula (TA01.02) | Tela de cadastro de administrador disponível. | 1. Acessar o cadastro; 2. Preencher nome e senha e deixar a matrícula vazia; 3. Tentar cadastrar; 4. Conferir a resposta e a ausência de novo registro. | Nome e senha válidos; matrícula vazia. | Cadastro impedido e indicação de matrícula obrigatória. | Manual / Aceitação |
| T01.03 | Tentar cadastrar administrador sem nome (TA01.03) | Tela de cadastro de administrador disponível. | 1. Acessar o cadastro; 2. Preencher matrícula e senha e deixar o nome vazio; 3. Tentar cadastrar; 4. Conferir a resposta e a ausência de novo registro. | Matrícula e senha válidas; nome vazio. | Cadastro impedido e indicação de nome obrigatório. | Manual / Aceitação |
| T01.04 | Tentar cadastrar administrador sem senha (TA01.04) | Tela de cadastro de administrador disponível. | 1. Acessar o cadastro; 2. Preencher matrícula e nome e deixar a senha vazia; 3. Tentar cadastrar; 4. Conferir a resposta e a ausência de novo registro. | Matrícula e nome válidos; senha vazia. | Cadastro impedido e indicação de senha obrigatória. | Manual / Aceitação |

### US02 — Autenticar administrador e acessar funcionalidades

- **Requisitos associados:** RF1, RF2, RF5 e RNF4.
- **Responsável pelos testes de aceitação:** Helena.
- **Pré-condições gerais:** tela de autenticação disponível e administrador cadastrado para os casos que exigem credenciais existentes.

| ID CT | Cenário e referência | Pré-condições | Passos | Dados de Entrada | Resultado Esperado | Tipo |
|---|---|---|---|---|---|---|
| T02.01 | Autenticar com credenciais válidas (TA02.01) | Administrador cadastrado; usuário sem sessão autenticada na tela de autenticação. | 1. Acessar a autenticação; 2. Informar matrícula e senha corretas; 3. Confirmar; 4. Conferir o encaminhamento e o estado de autenticação. | Matrícula cadastrada e senha correta. | Administrador autenticado e encaminhado à Página do Administrador. | Manual / Aceitação |
| T02.02 | Rejeitar senha incorreta (TA02.02) | Administrador cadastrado; usuário sem sessão autenticada. | 1. Informar matrícula cadastrada e senha incorreta; 2. Confirmar; 3. Conferir a mensagem; 4. Tentar acessar a área administrativa. | Matrícula cadastrada e senha incorreta. | Autenticação negada, mensagem de credenciais inválidas e área administrativa não acessível. | Manual / Aceitação |
| T02.03 | Rejeitar matrícula não cadastrada (TA02.03) | Usuário sem sessão autenticada; matrícula inexistente no cadastro. | 1. Informar matrícula inexistente e uma senha; 2. Confirmar; 3. Conferir a resposta; 4. Tentar acessar a área administrativa. | Matrícula não cadastrada e uma senha. | Acesso negado, mensagem de credenciais inválidas e nenhuma sessão autenticada criada. | Manual / Aceitação |
| T02.04 | Validar matrícula e senha vazias (TA02.04) | Tela de autenticação disponível; usuário sem sessão autenticada. | 1. Deixar ambos os campos vazios; 2. Tentar confirmar; 3. Conferir a indicação dos campos obrigatórios. | Matrícula e senha vazias. | Autenticação impedida e indicação de que matrícula e senha são obrigatórias. | Manual / Aceitação |
| T02.04.1 | Validar matrícula vazia (TA02.04) | Tela de autenticação disponível; usuário sem sessão autenticada. | 1. Preencher somente a senha; 2. Tentar confirmar; 3. Conferir a indicação do campo ausente. | Matrícula vazia e senha preenchida. | Autenticação impedida e indicação de matrícula obrigatória. | Manual / Aceitação |
| T02.04.2 | Validar senha vazia (TA02.04) | Administrador cadastrado; usuário sem sessão autenticada. | 1. Preencher somente a matrícula; 2. Tentar confirmar; 3. Conferir a indicação do campo ausente. | Matrícula cadastrada e senha vazia. | Autenticação impedida e indicação de senha obrigatória. | Manual / Aceitação |
| T02.05 | Acessar funcionalidades após autenticação (TA02.05) | Administrador cadastrado e autorizado; telas administrativas disponíveis. | 1. Autenticar com credenciais válidas; 2. Abrir cada tela administrativa pelo menu; 3. Conferir sua apresentação e a manutenção da sessão; 4. Retornar à Página do Administrador entre verificações. | Credenciais válidas; acessos a Alunos, Turmas, Livros, Empréstimos, Devoluções, Histórico de Empréstimos e relatórios previstos. | Acesso às telas e operações autorizadas, mantendo o estado de autenticação. | Manual / Aceitação |
| T02.06 | Bloquear acesso direto sem autenticação (TA02.06) | Sessão não autenticada; URLs e operações administrativas identificadas; estado inicial do banco registrado. | 1. Abrir sessão sem autenticação; 2. Tentar acessar diretamente as páginas restritas; 3. Enviar requisições às operações restritas; 4. Conferir a resposta e o banco. | URLs restritas e requisições de consulta, cadastro, alteração e exclusão com dados de teste. | Acesso bloqueado, autenticação solicitada, dados restritos não disponibilizados e nenhuma alteração indevida. | Manual / Sistema e Integração |

### US03 — Gerenciar alunos

- **Requisitos associados:** RF1, RF2, RF6 e RF12.
- **Responsável pelos testes de aceitação:** Jaine.
- **Pré-condições gerais:** administrador autenticado, funcionalidade disponível e registros preparados.
- **Definição pendente:** regra de inativação de aluno com vínculos. T03.06 permanece bloqueado para avaliação até essa definição.

| ID CT | Cenário e referência | Pré-condições | Passos | Dados de Entrada | Resultado Esperado | Tipo |
|---|---|---|---|---|---|---|
| T03.01 | Cadastrar aluno (TA03.01) | Administrador autenticado e turma existente. | 1. Acessar o cadastro de alunos; 2. Preencher os dados e informar a turma; 3. Confirmar; 4. Consultar o aluno. | Matrícula, nome, data de nascimento, endereço, sexo, e-mail e código de turma existente, com dados válidos. | Aluno salvo e associado à turma informada; mensagem de sucesso. | Manual / Aceitação |
| T03.02 | Consultar aluno (TA03.02) | Administrador autenticado e aluno ativo cadastrado, associado a uma turma. | 1. Acessar Alunos; 2. Localizar e consultar o aluno; 3. Conferir seus dados e a turma. | Identificação do aluno. | Dados do aluno e associação à turma apresentados corretamente. | Manual / Aceitação |
| T03.03 | Alterar dados do aluno (TA03.03) | Administrador autenticado e aluno cadastrado. | 1. Localizar o aluno e abrir a edição; 2. Alterar os dados; 3. Salvar; 4. Consultar novamente. | Novas informações válidas para os campos editáveis. | Alteração confirmada e dados atualizados na consulta. | Manual / Aceitação |
| T03.04 | Alterar turma do aluno (TA03.04) | Administrador autenticado; aluno associado a uma turma; outra turma existente. | 1. Abrir a edição; 2. Selecionar outra turma existente; 3. Salvar; 4. Consultar novamente. | Identificação do aluno e código da nova turma. | Associação atualizada e nova turma apresentada na consulta. | Manual / Aceitação |
| T03.05 | Inativar aluno sem vínculos (TA03.05) | Administrador autenticado e aluno ativo sem empréstimos vinculados. | 1. Localizar o aluno; 2. Solicitar exclusão/inativação e confirmar, se solicitado; 3. Consultar a lista de ativos; 4. Conferir o registro no banco de testes. | Identificação do aluno. | Aluno inativo e ausente da lista de ativos; registro preservado, conforme RN03.09. | Manual / Aceitação |
| T03.06 | Verificar inativação de aluno com vínculos (TA03.05) | Administrador autenticado; aluno ativo com empréstimo vinculado; preparar também cenário com devolução relacionada. | 1. Conferir os vínculos; 2. Solicitar exclusão/inativação; 3. Conferir resposta, situação do aluno e registros relacionados; 4. Repetir no cenário com devolução. | Identificação do aluno com vínculos. | Registros e vínculos preservados; permissão ou bloqueio da inativação conforme regra definida pela equipe. | Manual / Aceitação |
| T03.07 | Pesquisar aluno pelo nome (TA03.06) | Administrador autenticado e alunos cadastrados com nomes diferentes. | 1. Pesquisar pelo nome completo; 2. Conferir os resultados; 3. Repetir com parte do nome. | Nome completo e parte do nome de um aluno. | Somente registros correspondentes ao nome ou trecho pesquisado. | Manual / Aceitação |
| T03.08 | Pesquisar nome sem correspondência (TA03.07) | Administrador autenticado; nenhum aluno correspondente ao nome pesquisado. | 1. Acessar a pesquisa; 2. Informar o nome; 3. Pesquisar. | Nome sem correspondência. | Mensagem informando que nenhum aluno foi encontrado. | Manual / Aceitação |

### US04 — Gerenciar turmas

- **Requisitos associados:** RF1, RF2, RF7 e RF12.
- **Responsável pelos testes de aceitação:** Isabelle.
- **Pré-condições gerais:** administrador autenticado, funcionalidade disponível e registros preparados.

| ID CT | Cenário e referência | Pré-condições | Passos | Dados de Entrada | Resultado Esperado | Tipo |
|---|---|---|---|---|---|---|
| T04.01 | Cadastrar turma (TA04.01) | Administrador autenticado e tela Turmas disponível. | 1. Acessar o cadastro; 2. Preencher os cinco campos; 3. Confirmar; 4. Consultar a turma. | Código, curso, período, série e matriz curricular válidos. | Turma salva e apresentada na consulta; mensagem de sucesso. | Manual / Aceitação |
| T04.02 | Consultar turma (TA04.02) | Administrador autenticado e turma cadastrada. | 1. Acessar Turmas; 2. Selecionar a turma; 3. Conferir os cinco campos. | Identificação da turma. | Código, curso, período, série e matriz curricular correspondentes à turma selecionada. | Manual / Aceitação |
| T04.03 | Alterar turma (TA04.03) | Administrador autenticado e turma cadastrada. | 1. Abrir a edição da turma; 2. Alterar os dados; 3. Salvar; 4. Consultar novamente. | Novas informações válidas para os campos editáveis. | Alteração confirmada e dados atualizados em nova consulta. | Manual / Aceitação |
| T04.04 | Excluir turma sem alunos (TA04.04) | Administrador autenticado e turma sem alunos vinculados. | 1. Localizar a turma; 2. Solicitar exclusão e confirmar, se solicitado; 3. Consultar novamente as turmas. | Identificação da turma sem alunos. | Exclusão confirmada e turma ausente das consultas seguintes. | Manual / Aceitação |
| T04.05 | Pesquisar turmas pelo curso (TA04.05) | Administrador autenticado e turmas cadastradas em cursos diferentes. | 1. Acessar a pesquisa; 2. Informar o curso e pesquisar; 3. Conferir os resultados. | Curso com turmas cadastradas. | Somente turmas correspondentes ao curso pesquisado. | Manual / Aceitação |
| T04.06 | Pesquisar curso sem turmas (TA04.06) | Administrador autenticado; nenhuma turma correspondente ao curso pesquisado. | 1. Acessar a pesquisa; 2. Informar o curso e pesquisar. | Curso sem turmas correspondentes. | Mensagem informando que nenhuma turma foi encontrada. | Manual / Aceitação |

### US05 — Gerenciar livros

- **Requisitos associados:** RF1, RF2, RF8 e RF12.
- **Responsável pelos testes de aceitação:** Helena.
- **Pré-condições gerais:** administrador autenticado, funcionalidade disponível e registros preparados.

| ID CT | Cenário e referência | Pré-condições | Passos | Dados de Entrada | Resultado Esperado | Tipo |
|---|---|---|---|---|---|---|
| T05.01 | Cadastrar livro (TA05.01) | Administrador autenticado e tela Livros disponível. | 1. Acessar o cadastro; 2. Preencher os oito campos; 3. Confirmar; 4. Consultar o livro e conferir persistência. | ISBN, título, autor, editora, ano, edição, situação e quantidade disponível válidos. | Livro salvo, dados correspondentes aos informados e mensagem de sucesso. | Manual / Aceitação |
| T05.02 | Consultar livro (TA05.02) | Administrador autenticado e livro cadastrado com valores conhecidos. | 1. Acessar Livros; 2. Selecionar o livro; 3. Conferir os oito campos com o registro de teste. | Identificação do livro. | ISBN, título, autor, editora, ano, edição, situação e quantidade disponível corretos. | Manual / Aceitação |
| T05.03 | Alterar livro (TA05.03) | Administrador autenticado e livro cadastrado. | 1. Abrir a edição; 2. Alterar os dados; 3. Salvar; 4. Consultar novamente e conferir persistência. | Novas informações válidas para os campos editáveis. | Alteração confirmada e novos dados corretos, incluindo situação e quantidade disponível quando alteradas. | Manual / Aceitação |
| T05.04 | Excluir livro sem vínculos (TA05.04) | Administrador autenticado e livro sem empréstimos ou devoluções vinculados. | 1. Localizar o livro; 2. Solicitar e confirmar exclusão; 3. Consultar novamente o acervo. | Identificação do livro sem vínculos. | Exclusão confirmada e livro ausente das consultas seguintes. | Manual / Aceitação |
| T05.05 | Bloquear exclusão com vínculos (T05.05 da análise; PA05.06) | Administrador autenticado; livro com empréstimo vinculado; preparar também cenário com devolução relacionada. | 1. Conferir os vínculos; 2. Solicitar exclusão e confirmar, se solicitado; 3. Conferir resposta, livro e registros relacionados; 4. Repetir no cenário com devolução. | Identificação do livro vinculado. | Exclusão bloqueada; livro, empréstimos, devoluções e vínculos preservados. | Manual / Aceitação |
| T05.06 | Pesquisar livros pelo título (TA05.05) | Administrador autenticado e livros com títulos correspondentes e não correspondentes à pesquisa. | 1. Acessar a pesquisa; 2. Informar o título e pesquisar; 3. Conferir os resultados. | Título correspondente a livros cadastrados. | Registros correspondentes ao título pesquisado, sem incluir títulos sem correspondência. | Manual / Aceitação |
| T05.07 | Pesquisar título sem correspondência (TA05.06) | Administrador autenticado; nenhum livro correspondente ao título informado. | 1. Acessar a pesquisa; 2. Informar o título e pesquisar; 3. Conferir a resposta. | Título sem correspondência. | Mensagem informando que nenhum livro foi encontrado. | Manual / Aceitação |

### US06 — Registrar empréstimos

- **Requisitos associados:** RF13.
- **Responsável pelos testes de aceitação:** Jaine.
- **Responsáveis pelos testes automatizados e revisão:** Helena e Isabelle.
- **Pré-condições gerais:** funcionalidade disponível, administrador autenticado e registros preparados.
- **Preparação específica:** definir código ou ISBN como identificador; utilizar a mesma definição na interface e no processamento. Exemplar representa uma unidade disponível do livro.

| ID CT | Cenário e referência | Pré-condições | Passos | Dados de Entrada | Resultado Esperado | Tipo |
|---|---|---|---|---|---|---|
| T06.01 | Registrar empréstimo válido (TA06.01) | Administrador autenticado, aluno existente e livro disponível. | 1. Acessar o registro; 2. Informar aluno, livro e data; 3. Confirmar; 4. Consultar o empréstimo. | Matrícula do aluno, identificador do livro e data válida. | Empréstimo registrado com sucesso. | Manual / Aceitação |
| T06.02 | Conferir dados e vínculos (TA06.02) | Administrador autenticado, aluno existente e livro disponível. | 1. Registrar empréstimo válido; 2. Consultar o registro no sistema ou banco de testes; 3. Conferir dados e relacionamentos. | Aluno, livro e data válidos; identidade do administrador da sessão. | Registro contém aluno, livro, administrador responsável, data do empréstimo e data prevista. | Manual / Aceitação |
| T06.03 | Calcular data prevista (TA06.03) | Administrador autenticado, aluno existente e livro disponível; disponibilidade restaurada entre execuções. | 1. Registrar com 17/09/2026; 2. Conferir a data prevista; 3. Restaurar os dados necessários; 4. Repetir com 20/12/2026. | Aluno e livro válidos; datas 17/09/2026 e 20/12/2026. | Prazo de 20 dias: 07/10/2026 para a primeira data e 09/01/2027 para a segunda. | Manual / Aceitação |
| T06.04 | Impedir empréstimo sem aluno (TA06.04) | Administrador autenticado e livro disponível. | 1. Preencher livro e data; 2. Deixar aluno vazio e tentar registrar; 3. Conferir registros e estoque. | Livro e data válidos; matrícula ausente. | Registro impedido, nenhum novo empréstimo e estoque inalterado. | Manual / Aceitação |
| T06.05 | Impedir empréstimo sem livro (TA06.05) | Administrador autenticado e aluno existente. | 1. Preencher aluno e data; 2. Deixar livro vazio e tentar registrar; 3. Conferir os empréstimos. | Aluno e data válidos; livro ausente. | Registro impedido e nenhum novo empréstimo. | Manual / Aceitação |
| T06.06 | Impedir empréstimo de livro indisponível (TA06.06) | Administrador autenticado, aluno existente e livro com disponibilidade zero. | 1. Informar aluno, livro e data; 2. Tentar registrar; 3. Conferir empréstimos e estoque. | Aluno válido, livro indisponível e data válida. | Registro impedido; nenhum novo empréstimo; quantidade permanece zero. | Manual / Aceitação |
| T06.07 | Reduzir quantidade disponível (TA06.07) | Administrador autenticado, aluno existente e livro com três unidades disponíveis. | 1. Conferir as três unidades; 2. Registrar empréstimo válido; 3. Consultar a quantidade disponível. | Aluno, livro e data válidos. | Empréstimo registrado e quantidade reduzida de três para duas. | Manual / Aceitação |
| T06.08 | Impedir quantidade negativa (TA06.08) | Administrador autenticado, aluno existente e livro com disponibilidade zero. | 1. Tentar registrar empréstimo; 2. Conferir quantidade disponível e registros. | Aluno válido, livro com disponibilidade zero e data válida. | Empréstimo impedido; quantidade permanece zero e não fica negativa. | Manual / Aceitação |
| T06.09 | Impedir empréstimo com livro inexistente (TA06.09) | Administrador autenticado e aluno existente; identificador de livro sem correspondência. | 1. Informar os dados; 2. Tentar registrar; 3. Conferir resposta e registros. | Aluno válido, identificador inexistente de livro e data válida. | Registro impedido, nenhum novo empréstimo e resposta adequada ao usuário. | Manual / Aceitação |
| T06.10 | Associar administrador autenticado (TA06.10) | Administrador da sessão identificado; aluno existente e livro disponível. | 1. Registrar na sessão identificada; 2. Consultar o empréstimo; 3. Conferir o administrador responsável. | Aluno, livro e data válidos. | Empréstimo associado ao administrador autenticado que realizou a operação. | Manual / Aceitação |
| T06.11 | Aplicar regras no fluxo real (TA06.11) | Administrador autenticado; página real disponível; dados preparados para cenários válidos e inválidos. | 1. Executar cada cenário pela página real; 2. Conferir validações e disponibilidade; 3. Conferir cálculo de 20 dias; 4. Revisar se o fluxo utiliza as regras testadas. | Aluno ausente; livro ausente; livro indisponível; empréstimo válido com data conhecida. | Regras aplicadas no fluxo real, entradas inválidas rejeitadas e cálculo correto. | Manual / Aceitação |
| T06.12 | Manter empréstimo e estoque consistentes (TA06.12) | Administrador autenticado, aluno existente e livro com uma unidade disponível. | 1. Conferir estoque inicial; 2. Registrar empréstimo válido; 3. Conferir novo registro e estoque. | Aluno, livro e data válidos. | Um novo empréstimo criado e quantidade disponível reduzida para zero. | Manual / Aceitação |
| T06.12.1 | Manter consistência após falha (TA06.12) | Banco controlado; aluno existente; livro disponível; mecanismo de simulação de falha no estoque. | 1. Registrar estado inicial; 2. Configurar falha; 3. Executar empréstimo; 4. Conferir registros e estoque; 5. Remover simulação e restaurar ambiente. | Dados válidos e falha simulada na atualização do estoque. | Operação cancelada; nenhum empréstimo gravado sem atualização correspondente do estoque; estado inicial preservado. | Automatizado / Integração |
| T06.13 | Executar testes de unidade das regras (TA06.03, TA06.04, TA06.05, TA06.06 e TA06.08) | Regras implementadas; testes disponíveis; dependências isoladas com mocks quando necessário. | 1. Executar testes das validações; 2. Executar disponibilidade; 3. Executar cálculo de data; 4. Conferir resultados. | Matrícula e livro ausentes e válidos; disponibilidade zero e positiva; datas 17/09/2026 e 20/12/2026. | Campos ausentes rejeitados, indisponibilidade impede empréstimo e prazo de 20 dias correto em mudanças de mês e ano. | Automatizado / Unidade |
| T06.14 | Executar testes de integração (TA06.01, TA06.02, TA06.07, TA06.09 e TA06.12) | Banco preparado; administrador e aluno existentes; livros disponíveis e indisponíveis; simulação de falha disponível. | 1. Preparar dados; 2. Executar operações integradas ao banco; 3. Conferir persistência, vínculos, datas e estoque; 4. Verificar falha; 5. Restaurar dados entre cenários. | Cenário válido; livro inexistente; livro indisponível; falha de atualização do estoque. | Cenários válidos persistem dados e atualizam estoque; inválidos ou com falha não geram registros indevidos nem inconsistência. | Automatizado / Integração |

### 3.1 Observações de rastreabilidade

- **US02:** TA02.01 a TA02.06 seguem a tabela de critérios. Os três casos de campos vazios cobrem TA02.04. T02.05 cobre acesso autenticado e T02.06 cobre bloqueio sem autenticação.
- **US03:** T03.05 e T03.06 cobrem TA03.05 sem vínculos e com vínculos. A inativação mantém o registro, conforme RN03.09.
- **US04:** os casos seguem os cenários corrigidos de turmas. Validações de formato, códigos duplicados e exclusão com alunos vinculados deverão gerar casos adicionais quando definidas.
- **US05:** T05.05 verifica o bloqueio de exclusão com vínculos previsto no plano da análise. T05.06 cobre TA05.05 e T05.07 cobre TA05.06.
- **US06:** T06.12.1 cobre a falha de atualização do estoque. T06.13 e T06.14 são verificações agrupadas das suítes automatizadas, complementares aos casos de aceitação, e não substituem a validação pela QA.
- A devolução efetiva pertence à US07. As telas de devoluções e relatórios são verificadas neste plano quanto ao acesso ou à preservação de vínculos.

## 4. Critérios de Entrada e Saída da Iteração

### 4.1 Critérios de Entrada

- US da iteração identificadas no Plano de Iterações e na Lista de User Stories.
- Especificações necessárias aprovadas e regras do caso definidas.
- Versão da aplicação disponível e identificada no ambiente de testes.
- Registros, credenciais e pré-condições preparados.
- Mecanismo controlado de simulação disponível nos casos de falha.

### 4.2 Critérios de Saída (Iteração aceita pelo cliente)

-  100% dos casos de aceitação planejados para a iteração executados.
-  Nenhum bug de prioridade Alta ou Crítica em aberto.
-  Critérios de aceitação atendidos e evidências registradas.
-  Testes de unidade e integração relevantes aprovados.
-  Casos reprovados executados novamente após correção.
-  Homologação formal do cliente ou responsável pela validação da atividade.

Um caso bloqueado não deve ser marcado como aprovado. Bloqueios e regras pendentes devem ser resolvidos ou formalmente tratados antes da aceitação da iteração.

## 5. Cronograma de Execução na Iteração

| Atividade | Responsável (YP-Agentic) | Iteração | Data Início | Data Fim |
|---|---|---|---|---|
| Confirmar distribuição das US e revisar casos | Isabelle, Helena e Jaine / Analistas | 1 e 2 | A definir | A definir |
| Preparar ambiente e dados | Desenvolvedora da US | Conforme Plano de Iterações | A definir | A definir |
| Executar testes de unidade e integração | Desenvolvedora e revisora da US | Conforme Plano de Iterações | A definir | A definir |
| Executar aceitação da US01 e US04 | Isabelle / Testadora | Conforme Plano de Iterações | A definir | A definir |
| Executar aceitação da US02 e US05 | Helena / Testadora | Conforme Plano de Iterações | A definir | A definir |
| Executar aceitação da US03 e US06 | Jaine / Testadora | Conforme Plano de Iterações | A definir | A definir |
| Corrigir falhas e repetir testes | Desenvolvedora, revisora e testadora da US | Conforme Plano de Iterações | A definir | A definir |
| Registrar evidências e elaborar relatório | Testadora da US | Conforme Plano de Iterações | A definir | A definir |
| Sessão de aceitação e homologação | Testadoras / Cliente ou responsável pela atividade | 1 e 2 | A definir | A definir |

## 6. Riscos da Iteração

| Risco | Impacto | Ação Mitigatória |
|---|---|---|
| Distribuição das US e datas ainda não confirmadas | Alto | Vincular os casos ao Plano de Iterações antes da execução |
| Regra de inativação de aluno com vínculos indefinida | Alto | Definir com a analista antes de avaliar T03.06 |
| Diferença entre código do livro e ISBN no empréstimo | Alto | Alinhar interface, consulta, validação e dados de teste |
| Empréstimo e atualização do estoque inconsistentes | Alto | Executar T06.12 e T06.12.1 e verificar persistência |
| Regras testadas não utilizadas pela página real | Alto | Executar T06.11 e revisar integração das regras |
| Dados modificados por um teste interferirem no seguinte | Médio | Preparar e restaurar dados entre casos |
| Acesso direto a operações administrativas sem autenticação | Alto | Executar T02.06 para páginas e operações do servidor |
| Critérios ou IDs divergentes entre seções das análises | Médio | Utilizar o mapeamento da seção 3.1 e registrar ajustes |
| Ambiente ou funcionalidade indisponível | Alto | Conferir critérios de entrada e registrar bloqueios |

## 7. Referências

- Plano Geral de Testes: docs/plano-geral-de-testes.md.
- Plano de Iterações: inserir caminho do documento no repositório.
- Lista de User Stories: inserir caminho do documento no repositório.
- Especificações e análises das US01 a US06: referências da seção 2; vincular os caminhos no repositório.
- Relatório de Testes: inserir caminho quando criado.
- [Modelo YP-Agentic de Plano de Teste da Iteração](https://github.com/tacianosilva/engenharia-software/blob/main/yp-agentic/templates/plano-teste-iteracao.md).

Os resultados serão registrados no Relatório de Testes com ID do caso, iteração, versão ou commit, data, responsável, resultado obtido, situação e evidência ou issue.

Situações possíveis: não executado, aprovado, reprovado e bloqueado.