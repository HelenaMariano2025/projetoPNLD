# Relatório do Estado Atual dos Testes e Débito Técnico — Projeto PNLD

**Sistema:** HBL Control / Sistema BHL  
**Data:** 06 de outubro de 2026  
**Repositório:** https://github.com/HelenaMariano2025/projetoPNLD  
**Versão analisada:** branch `main`, commit `3fbb47200ab0f607b99c4982a64bedeb723e0aaa`.

## 1. Objetivo do documento

Este relatório apresenta o sistema e as principais melhorias que ainda precisam ser feitas no código. Também diferencia a situação dos testes quando a equipe recebeu o projeto da situação registrada na análise.

Débito técnico é o conjunto de problemas e melhorias pendentes que tornam o sistema mais difícil de corrigir, testar e manter. Por exemplo: falta de testes, regras repetidas e validações incompletas.

O documento foi elaborado a partir da análise do código e do histórico do repositório. A aplicação e os testes não foram executados durante essa análise. Também não foram consultados os resultados atuais do GitHub Actions e do SonarQube.

Por isso, os riscos descritos não devem ser confundidos com falhas reproduzidas em execução. Alterações posteriores ao commit indicado podem modificar a situação apresentada.

## 2. O que o sistema faz

O PNLD é um sistema web de controle de livros didáticos. Ele permite cadastrar alunos, turmas e livros, registrar empréstimos e devoluções e consultar o histórico.

Foi desenvolvido em PHP e utiliza o banco de dados MySQL, chamado `SistemaHBL`.

| Área | Funções principais |
| --- | --- |
| Administradores | Cadastro, login e uso de sessão |
| Alunos e turmas | Cadastro, busca e listagem; alunos também podem ser editados e inativados |
| Livros | Cadastro, busca, edição e inativação |
| Empréstimos e devoluções | Registro da retirada e da devolução dos livros, com consulta ao histórico |

Em várias páginas, o HTML, as regras do sistema e as consultas ao banco estão no mesmo arquivo. Isso dificulta a manutenção.

A equipe começou a separar o acesso ao banco em classes chamadas repositórios, como `LivroRepository`. Nas páginas analisadas, essa separação já é usada para livros, mas ainda não foi aplicada da mesma forma a alunos e turmas.

## 3. Estado dos testes: antes e depois

### 3.1 Situação antes da disciplina

Segundo a equipe, o projeto foi recebido sem testes automatizados de unidade ou de integração.

O primeiro commit registrado, `f5f3bbf`, não contém a pasta `tests/`. A primeira inclusão de testes encontrada no histórico é o commit `0aa1fb6`, de 17/09/2026.

Esse histórico reforça o relato da equipe, mas não informa a data exata em que ela recebeu o projeto.

Não havia medição de cobertura apresentada para a situação inicial. Portanto, a cobertura inicial deve ser registrada como **não medida**, sem atribuir um percentual não comprovado.

A ausência de testes automatizados constituía um débito técnico: alterações no código poderiam comprometer funcionalidades existentes sem que a equipe identificasse o problema automaticamente.

### 3.2 Situação na versão analisada

Foram encontrados 10 arquivos com 23 métodos de teste.

Essa contagem foi feita pela leitura do código. Não significa que os 23 testes foram executados ou passaram.

Existem testes de unidade relacionados a regras e classes de acesso ao banco, utilizando simulações em parte dos casos. Também existem testes de integração que verificam operações com o MySQL.

Há configuração no GitHub Actions para executar testes e gerar cobertura. Entretanto, o resultado atual dessas execuções não foi verificado durante a análise.

### 3.3 Cobertura de código

Cobertura de código indica quanto do código foi executado pelos testes.

Não foi possível confirmar um percentual atual confiável porque:

- A configuração de cobertura inclui apenas a pasta `php/`, deixando de fora as páginas PHP da raiz.
- O arquivo `coverage.xml` corresponde a outra execução e não representa a versão analisada.
- Não foi realizada uma nova execução da suíte para gerar uma medição correspondente ao commit analisado.

Portanto, não se deve informar um percentual atual nem afirmar que todo o sistema está coberto.

Uma nova medição deverá identificar o commit, a execução realizada e os arquivos incluídos no cálculo.

### 3.4 Comparação entre os estados

| Aspecto | Situação inicial | Situação na versão analisada |
| --- | --- | --- |
| Testes de unidade | Não havia testes automatizados identificados | Existem testes de regras e de classes de acesso ao banco, usando simulações em parte dos casos |
| Testes de integração | Não havia testes automatizados identificados | Existem testes que verificam operações com o banco de dados |
| Cobertura de código | Não havia medição apresentada | Há configuração e um relatório de cobertura, mas eles não permitem confirmar o percentual da versão analisada |
| Execução automática | Não havia uma suíte inicial para executar | Há configuração no GitHub Actions para executar testes e gerar cobertura; o resultado atual não foi verificado |

Os testes adicionados reduzem parte do débito técnico inicial, mas ainda há regras e páginas sem verificação suficiente.

## 4. Principais débitos técnicos

As prioridades indicam a ordem sugerida de tratamento:

- **P0:** resolver antes de disponibilizar o sistema a usuários reais.
- **P1:** resolver no próximo ciclo de trabalho.
- **P2:** planejar para facilitar a manutenção e a verificação do sistema.

### DT01 — Consultas ao banco com dados inseridos diretamente

**Prioridade:** P0.

Arquivos como `login.php`, `cadastro.php`, `add_alunos.php`, `add_turma.php` e `addEmprestimo.php` colocam dados recebidos do usuário diretamente nas consultas SQL.

Isso pode causar erros ou permitir que uma entrada altere a consulta.

**Melhoria proposta:** usar consultas preparadas, que enviam os dados separados do comando SQL.

**Como verificar:** confirmar que as entradas do usuário não são concatenadas às consultas e testar casos com aspas e caracteres especiais.

### DT02 — Senhas salvas em texto simples

**Prioridade:** P0.

O cadastro salva a senha diretamente, e o login compara esse valor com o banco. Se os dados forem expostos, as senhas ficam visíveis.

A coluna `senha`, com tamanho 20, também precisa ser ampliada para comportar o hash.

**Melhoria proposta:** usar `password_hash` para armazenar uma representação protegida da senha e `password_verify` para conferir o login. Substituir a credencial de exemplo e planejar a troca das senhas existentes.

**Como verificar:** conferir o armazenamento do hash e testar login com senha correta e incorreta.

### DT03 — Proteção de acesso e saída incompletas

**Prioridade:** P0.

Não foi identificada uma verificação comum de login nas operações administrativas analisadas. Iniciar uma sessão não garante autorização.

O botão Sair leva à página inicial, mas não encerra a sessão.

**Melhoria proposta:** exigir autenticação e autorização nas páginas administrativas, renovar o identificador de sessão após o login e encerrar a sessão ao sair.

**Como verificar:** conferir se alguém sem login consegue consultar ou alterar dados e se o acesso é bloqueado após o encerramento da sessão.

### DT04 — Links podem alterar dados

**Prioridade:** P0.

`devolverLivro.php` e `deletar_aluno.php` recebem dados pela URL para realizar alterações.

Também não foram encontrados tokens CSRF, usados para ajudar a confirmar que uma solicitação veio do próprio sistema.

**Melhoria proposta:** usar POST para alterações e validar um token ligado à sessão.

**Como verificar:** abrir um link não deve modificar dados, e solicitações sem token válido devem ser rejeitadas.

### DT05 — Dados exibidos sem proteção consistente

**Prioridade:** P0.

Alguns campos de alunos e turmas são exibidos diretamente no HTML. Um conteúdo salvo pode ser interpretado pelo navegador como código.

Parte das páginas de livros e histórico já usa `htmlspecialchars`.

**Melhoria proposta:** aplicar o tratamento adequado em todos os campos exibidos.

**Como verificar:** conferir se textos com marcação HTML aparecem como texto e não executam scripts.

### DT06 — Quantidade disponível não acompanha os empréstimos

**Prioridade:** P1.

`addEmprestimo.php` registra o empréstimo sem reduzir `qtde_disponivel` ou chamar `podeEmprestar`.

A devolução também não recompõe essa quantidade. Assim, o saldo pode ficar diferente dos empréstimos em aberto.

**Melhoria proposta:** atualizar o empréstimo e o saldo juntos, em uma transação: ou todas as alterações são concluídas, ou nenhuma é salva.

**Como verificar:** conferir se o saldo nunca fica negativo, mesmo com solicitações simultâneas, e se cada devolução válida devolve uma única unidade ao saldo.

### DT07 — O mesmo empréstimo pode ter devoluções repetidas

**Prioridade:** P1.

A aplicação não verifica uma devolução anterior, e o banco não exige que `codigo_emprestimo` seja único na tabela `devolucao`.

Isso pode duplicar registros e, após a correção do saldo, aumentar indevidamente a quantidade disponível.

**Melhoria proposta:** confirmar a regra de uma devolução por empréstimo, corrigir possíveis duplicatas e criar a restrição no banco.

**Como verificar:** a segunda solicitação não deve gerar outra devolução nem alterar o saldo novamente.

### DT08 — Cálculo da data de devolução precisa ser corrigido

**Prioridade:** P1.

`addEmprestimo.php` usa uma expressão que não soma corretamente 20 dias à data informada.

Existe uma função para esse cálculo em `php/regras.php`, mas a página não a utiliza.

**Melhoria proposta:** validar a data e usar uma única regra no fluxo real.

**Como verificar:** conferir mudanças de mês e ano e datas inválidas. Por exemplo: um empréstimo em 17/09/2026 deve ter prazo em 07/10/2026.

### DT09 — Alguns testes dependem de código ainda ausente

**Prioridade:** P1.

Os testes de empréstimo fazem referência a `php/EmprestimoRepository.php`, `php/regras_emprestimo.php` e `validarIdLivro`, que não foram encontrados na versão analisada.

Essas ausências impedem a execução completa sem erros.

**Melhoria proposta:** concluir o ciclo TDD: criar o teste que falha pelo comportamento esperado, implementar a regra e melhorar o código mantendo os testes passando.

**Como verificar:** a regra deve funcionar também na página e no banco, não apenas no teste.

### DT10 — Parte do código testado não é usada pelas páginas

**Prioridade:** P1.

Os repositórios de alunos e turmas têm testes, mas várias páginas ainda fazem suas próprias consultas.

As regras testadas de empréstimo também não são chamadas pelo fluxo real. Assim, um teste pode passar enquanto a página continua com problema.

**Melhoria proposta:** fazer as páginas usarem as mesmas classes e funções verificadas pelos testes. Acrescentar testes dos principais fluxos da aplicação.

**Como verificar:** confirmar que as operações usadas pelas páginas passam pelas regras testadas e validar os fluxos completos.

### DT11 — Validação de dados incompleta

**Prioridade:** P1.

As páginas de livros não verificam todas as faixas de valores no servidor, como quantidades negativas.

No empréstimo, faltam tratamentos para dados ausentes e livro não encontrado. As restrições do banco também não cobrem todas essas regras.

**Melhoria proposta:** validar campos obrigatórios, quantidades, datas e existência e situação dos registros antes de salvar. Reforçar as regras no banco e apresentar mensagens claras para entradas inválidas.

**Como verificar:** testar entradas válidas e inválidas e confirmar que operações rejeitadas não alteram os dados.

### DT12 — Testes de integração precisam verificar mais casos

**Prioridade:** P1.

O teste de alunos verifica apenas o tipo do resultado, sem comprovar o filtro esperado.

O teste de turmas usa e apaga o código fixo `9999`, o que pode atingir um registro existente se a base não for exclusiva de testes.

Livros já tem um teste de cadastro, consulta, atualização e exclusão com desfazimento das alterações.

**Melhoria proposta:** usar um banco exclusivo de testes, dados controlados e limpeza segura. Verificar o conteúdo dos resultados e incluir casos de erro.

**Como verificar:** os testes devem ser repetíveis, comprovar os resultados esperados e não afetar dados reais.

### DT13 — Cobertura e automação não comprovam a qualidade atual

**Prioridade:** P2.

A cobertura está limitada à pasta `php/`, e o relatório salvo não representa a versão analisada.

O GitHub Actions tem configuração de testes, mas não define cobertura mínima nem executa o scanner SonarQube.

**Melhoria proposta:** gerar um relatório após uma execução válida, identificar o commit e explicar quais arquivos foram medidos. Verificar separadamente o resultado do SonarQube.

**Como verificar:** registrar os resultados efetivos das execuções. Ter uma configuração não comprova que a análise passou.

### DT14 — Código repetido e tratamento de erros irregular

**Prioridade:** P2.

Várias páginas repetem layout e misturam regras com consultas ao banco.

Há tratamentos de erro comentados e respostas que podem mostrar detalhes internos do banco.

**Melhoria proposta:** separar gradualmente layout, regras e acesso a dados. Mostrar mensagens compreensíveis ao usuário e guardar os detalhes técnicos em registros internos de erro.

**Como verificar:** conferir o tratamento de falhas e se as mensagens exibidas não expõem detalhes internos.

### DT15 — Instalação e ambiente de testes pouco documentados

**Prioridade:** P2.

O ambiente tem Docker e variáveis de configuração, mas ainda usa credenciais locais fixas ou padrões simples.

O Dockerfile não instala Composer nem deixa claras todas as extensões necessárias ao PHPUnit. O README informa que a execução com Docker ainda não foi validada.

**Melhoria proposta:** documentar versões, dependências, configuração e comandos para executar a aplicação e os testes. Validar o Docker e separar as configurações de desenvolvimento das usadas com dados reais.

**Como verificar:** outra pessoa deve conseguir instalar e executar o sistema e os testes seguindo a documentação.

## 5. Ordem sugerida para as melhorias

Cada melhoria pode ser registrada como uma tarefa, com responsável e critério de conclusão.

O prazo e o esforço devem ser estimados pela equipe. Mudanças no banco precisam considerar a correção dos dados já existentes.

| Etapa | O que fazer | Como verificar |
| --- | --- | --- |
| 1. Segurança — DT01 a DT05 | Proteger consultas, senhas, acesso, alterações e dados exibidos | Solicitações sem autorização são bloqueadas; senhas estão protegidas; entradas não alteram consultas nem executam scripts |
| 2. Empréstimos — DT06 a DT08 e DT11 | Corrigir saldo, devolução única, prazo e validação | Saldo consistente, devoluções sem duplicação e rejeição de dados inválidos |
| 3. Testes — DT09, DT10 e DT12 | Concluir os testes pendentes e conectar as regras testadas às páginas | Testes passam em banco isolado e verificam os fluxos usados pela aplicação |
| 4. Manutenção — DT13 a DT15 | Melhorar cobertura, organização do código e documentação | Relatórios identificam a versão medida; outra pessoa consegue instalar e executar os testes seguindo o README |

## 6. Conclusão

O projeto foi recebido sem testes automatizados, conforme o relato da equipe e o histórico apresentado.

Depois, foram adicionados testes de unidade e integração, configuração de automação e classes para organizar o acesso ao banco.

Essas mudanças são avanços, mas ainda há testes incompletos e regras testadas que não são usadas pelas páginas.

As prioridades são proteger as operações administrativas, corrigir empréstimos e devoluções e fazer os testes verificarem o comportamento real do sistema.

O percentual atual de cobertura e o sucesso da suíte completa ainda precisam ser confirmados por execução.

## 7. Referências e arquivos analisados

As informações deste relatório se referem ao código e ao histórico do repositório na versão indicada no início do documento.

**Repositório:** https://github.com/HelenaMariano2025/projetoPNLD

Principais arquivos e áreas considerados:

- Páginas de login e cadastro de administradores.
- Páginas de alunos, turmas e livros.
- Páginas de empréstimos, devoluções e histórico.
- `php/AlunoRepository.php`.
- `php/TurmaRepository.php`.
- `php/LivroRepository.php`.
- `php/regras.php`.
- `php/conexao.php`.
- `sql/bancoPNLD.sql`.
- `tests/*Test.php`.
- `phpunit.xml`.
- `coverage.xml`.
- `.github/workflows/testes.yml`.
- `sonar-project.properties`.
- `composer.json`.
- `composer.lock`.
- `Dockerfile`.
- `compose.yaml`.
- `README.md`.