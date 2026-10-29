# Relatório de Testes de Aceitação — US04: Gerenciar turmas

## Identificação

- Testadora: Isabelle Cavalcanti da Silva
- Usuário GitHub: Isabellecavalcant
- Desenvolvedora: Jaine 
- Data: 09/10/2026
- User Story: US04 — Gerenciar turmas
- Branch de implementação: não confirmada nesta execução; código obtido pelo PR #19
- Branch local utilizada nos testes: qa/us04-pr19
- Commit testado: 05cd7efbbf008e854f7d449c8d1a3387cda99319
- PR testado: https://github.com/HelenaMariano2025/projetoPNLD/pull/19
- Referência: documento “US04 — Gerenciar Turmas”, cenários TA04.01 a TA04.08.

## Objetivo

Verificar cadastro, consulta, alteração, exclusão e pesquisa de turmas, além do bloqueio de código duplicado e da exclusão de turmas com alunos vinculados, conforme os cenários Gherkin da US04.

## Ambiente

- Sistema operacional: Ubuntu
- Navegador: Firefox
- Aplicação: PHP 8.3.6
- Execução: servidor local PHP (`php -S localhost:8000`)
- Banco de dados: MySQL — SistemaHBL
- Endereço base: http://localhost:8000
- Pré-condição: administrador autenticado.

## Casos executados

### TA04.01 — Cadastrar turma com dados válidos

Passos:

1. Acessar Adicionar turma.
2. Informar código 90401, curso QA Informatica e sigla QAINFO.
3. Informar período 1, série 1, matriz Matriz QA 2026 e situação Ativo.
4. Confirmar o cadastro.
5. Voltar à listagem e conferir os dados.

Resultado esperado: salvar a turma e apresentar uma mensagem de sucesso.

Resultado observado: a tela apresentou “Turma cadastrada com sucesso!” e a turma 90401 apareceu na listagem com os dados informados.

Status: Passou na verificação pela interface.

Limite da evidência: persistência verificada por nova consulta pela interface; não houve consulta direta ao banco.

Evidências: [Confirmação do cadastro](evidencias/ta04-01-cadastro.png) e [Consulta após cadastro](evidencias/ta04-02-consulta.png).

### TA04.02 — Consultar turma

Passos:

1. Acessar a listagem de turmas.
2. Localizar a turma 90401.
3. Conferir código, curso, período, série e matriz curricular.

Resultado esperado: apresentar os dados correspondentes à turma selecionada.

Resultado observado: foram exibidos código 90401, curso QA Informatica, período 1, série 1 e matriz Matriz QA 2026. A sigla QAINFO também foi exibida.

Status: Passou na verificação pela interface.

Evidência: [Dados da turma](evidencias/ta04-02-consulta.png).

### TA04.03 — Alterar turma

Passos:

1. Clicar em Editar na turma 90401.
2. Alterar período para 2, série para 2 e matriz para Matriz QA Atualizada.
3. Salvar e conferir os dados na listagem.

Resultado esperado: confirmar a alteração e apresentar os dados atualizados em nova consulta.

Resultado observado: a tela apresentou “Turma atualizada com sucesso!” e exibiu período 2, série 2 e matriz Matriz QA Atualizada.

Status: Passou na verificação pela interface.

Evidência: [Confirmação e dados atualizados](evidencias/ta04-03-edicao.png).

### TA04.04 — Excluir turma sem alunos vinculados

Passos:

1. Utilizar a turma de teste 90402, curso QA Administracao, sigla QAADM, período 1, série 1 e matriz Matriz QA 2026, sem alunos vinculados.
2. Clicar em Excluir e confirmar a operação.
3. Pesquisar novamente por QA Administracao.

Resultado esperado: confirmar a exclusão e deixar de apresentar a turma nas consultas seguintes.

Resultado observado: foi exibida a mensagem “Turma excluída com sucesso.” A testadora confirmou que a busca posterior não apresentou a turma excluída.

Status: Passou na verificação pela interface, conforme confirmação da testadora.

Limite da evidência: o print registra a confirmação da exclusão; a ausência na busca posterior foi informada pela testadora, sem print específico anexado.

Evidência: [Confirmação da exclusão](evidencias/ta04-04-exclusao.png).

### TA04.05 — Pesquisar turmas pelo curso

Passos:

1. Manter cadastradas as turmas 90401 — QA Informatica e 90402 — QA Administracao.
2. Digitar QA Informatica no campo de pesquisa.
3. Clicar em Buscar.
4. Conferir o resultado e tentar navegar pelas setas.

Resultado esperado: apresentar somente turmas correspondentes ao curso pesquisado.

Resultado observado: a testadora confirmou que, após clicar em Buscar, somente QA Informatica ficou disponível e não foi possível navegar para QA Administracao.

Status: Passou na verificação pela interface, conforme confirmação da testadora.

Limite da evidência: os prints anexados mostram as duas turmas e a preparação da busca; não comprovam isoladamente o resultado após o envio. A confirmação do filtro foi fornecida pela testadora.

Evidências de preparação: [Turma de outro curso e termo digitado](evidencias/ta04-05-preparacao.png) e [Turma de Informática](evidencias/ta04-05-turma-informatica.png).

### TA04.06 — Pesquisar curso sem turmas

Passos:

1. Informar CursoInexistenteQA no campo de pesquisa.
2. Clicar em Buscar.
3. Conferir a mensagem e a ausência de turmas no resultado.

Resultado esperado: informar que nenhuma turma foi encontrada.

Resultado observado: a tela apresentou “Nenhuma turma encontrada para o curso pesquisado.” e não exibiu dados de turmas.

Status: Passou na verificação pela interface.

Evidência: [Busca sem resultados](evidencias/ta04-06-sem-resultados.png).

### TA04.07 — Impedir cadastro com código duplicado

Passos:

1. Manter cadastrada a turma 90401.
2. Acessar Adicionar turma.
3. Tentar cadastrar outra turma com código 90401 e os demais campos válidos.
4. Confirmar o cadastro.

Resultado esperado: impedir o cadastro e apresentar “Este código já pertence a uma turma cadastrada.”

Resultado observado: o cadastro foi bloqueado com a mensagem “Já existe uma turma cadastrada com esse código.”

Status: Falhou na correspondência literal da mensagem especificada; o bloqueio funcional passou.

Evidência: [Bloqueio de código duplicado](evidencias/ta04-07-duplicado.png).

### TA04.08 — Impedir operação em turma com alunos vinculados

Passos:

1. Cadastrar o aluno de teste 202690401 — Aluno Teste QA US04, ativo e vinculado à turma 90401 — QA Informatica.
2. Conferir o vínculo na listagem de alunos.
3. Acessar Turmas e localizar a turma 90401.
4. Clicar em Excluir e confirmar a operação.
5. Conferir a mensagem e a permanência da turma na listagem.

Resultado esperado: impedir a operação e apresentar “Esta turma não pode ser excluída, pois existem alunos matriculados nela.”

Resultado observado: a tela apresentou “Não é possível excluir esta turma porque existem alunos vinculados.” A turma 90401 permaneceu na listagem.

Status: Falhou na correspondência literal da mensagem especificada; o bloqueio funcional passou.

Limite do cenário: o Gherkin denomina a operação “inativação”, enquanto a interface utiliza “Excluir”. Foi executado o fluxo disponível pelo botão Excluir; não foi testado um fluxo separado de mudança da situação para Inativo.

Evidências: [Aluno vinculado](evidencias/ta04-08-aluno-vinculado.png) e [Exclusão bloqueada e turma preservada](evidencias/ta04-08-bloqueio.png).

## Bugs e inconsistências encontrados

Não foram reproduzidas falhas nas regras funcionais dos fluxos executados. Foram identificadas duas divergências de mensagem em relação ao texto literal do Gherkin:

| ID | Cenário | Esperado | Observado | Impacto |
| --- | --- | --- | --- | --- |
| INC04.01 | TA04.07 | Este código já pertence a uma turma cadastrada. | Já existe uma turma cadastrada com esse código. | Baixo: a duplicidade foi bloqueada, mas o texto não corresponde ao especificado. |
| INC04.02 | TA04.08 | Esta turma não pode ser excluída, pois existem alunos matriculados nela. | Não é possível excluir esta turma porque existem alunos vinculados. | Baixo: a operação foi bloqueada, mas o texto não corresponde ao especificado. |

Para reproduzir INC04.01, repetir os passos do TA04.07. Para reproduzir INC04.02, repetir os passos do TA04.08 com um aluno vinculado à turma.

## Sugestões de melhoria

- Alinhar com a analista os textos das mensagens da interface e dos cenários TA04.07 e TA04.08.
- Esclarecer na especificação a diferença entre excluir e inativar uma turma, indicando qual operação deve ser testada no TA04.08.
- Manter o termo pesquisado no campo após a busca.
- Ocultar ou desabilitar as setas do carrossel quando não houver resultados ou houver apenas um resultado.
- Documentar os campos adicionais Sigla do curso e Situação, presentes na interface.
- Preservar os dados preenchidos após a rejeição de um cadastro duplicado, para facilitar a correção.

As sugestões de interface não representam falhas nas regras funcionais verificadas.

## Resultado e limites

- Cenários executados: 8.
- Passaram sem divergência identificada nos critérios avaliados: 6.
- Falharam exclusivamente na correspondência literal de mensagens: 2 (TA04.07 e TA04.08).
- Falhas nas regras funcionais dos fluxos executados: 0.

As operações de cadastro, consulta, alteração, exclusão sem vínculos e pesquisa funcionaram na execução manual. O cadastro duplicado e a exclusão com alunos vinculados foram bloqueados, com mensagens semanticamente compatíveis, mas diferentes das especificadas.

Os resultados foram obtidos pela interface e pelas confirmações da testadora. Não houve consulta direta ao banco. Os resultados finais de TA04.04 e TA04.05 não possuem prints específicos; suas limitações estão indicadas nos respectivos casos.

Não foram avaliados nesta execução segurança, concorrência, desempenho, cobertura, Quality Gate do SonarQube ou um fluxo separado de inativação. Os dados de teste 90401 e aluno 202690401 permaneceram no ambiente ao término; não foi registrada limpeza posterior.

A recomendação é alinhar as mensagens e a terminologia do TA04.08 antes de considerar todos os cenários integralmente conformes à especificação.