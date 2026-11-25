# Testes de Mutação — Projeto PNLD

Documento da atividade **P3 — Teste de Mutação e Ampliação da Cobertura**.

- Repositório: <https://github.com/HelenaMariano2025/projetoPNLD>
- Plano de testes: [plano-geral-de-testes.md](plano-geral-de-testes.md)
- Manual de execução: [manual-de-execucao.md](manual-de-execucao.md)
- Milestone: [Testes de Mutação (P3)](https://github.com/HelenaMariano2025/projetoPNLD/milestones)

## 1. O que é

Teste de mutação é uma técnica que avalia a qualidade dos testes, e não do código. A ferramenta faz pequenas alterações no código-fonte (cria "mutantes"), por exemplo trocar `>` por `>=`, `+` por `-`, `true` por `false` ou remover uma chamada de método. Depois, executa a suíte de testes contra cada mutante:

- Se algum teste **falha**, o mutante foi **morto** (os testes perceberam a mudança).
- Se todos os testes **passam**, o mutante **sobreviveu**, o que indica um teste fraco ou ausente.

A cobertura de código mostra quais linhas foram executadas. O teste de mutação mostra se os testes realmente **verificam** o comportamento dessas linhas.

## 2. Ferramenta e configuração

| Item | Valor |
|---|---|
| Ferramenta | [Infection](https://infection.github.io/) 0.35.9 |
| Framework de testes | PHPUnit 12.5 |
| PHP | 8.4 (local e GitHub Actions) |
| Driver de cobertura | Xdebug (`XDEBUG_MODE=coverage`) |
| Código mutado | pasta `php/` |
| Configuração | `infection.json` (raiz do projeto) |
| Metas no `infection.json` | `minMsi` e `minCoveredMsi` (ver arquivo) |

### Suítes do PHPUnit

O `phpunit.xml` define três suítes:

| Suíte | Conteúdo | Uso |
|---|---|---|
| `Projeto PNLD` (padrão) | Todos os testes | Cobertura, SonarQube e Infection no CI |
| `unit` | Testes que não acessam o banco | Execução rápida local, sem MySQL |
| `integration` | Testes que usam o MySQL real | Execução com banco configurado |

Vários testes de página (`*PaginaTest`) e de empréstimo também dependem do banco, por isso estão na suíte `integration`.

## 3. Métricas

| Métrica | Significado |
|---|---|
| **MSI** (Mutation Score Indicator) | Percentual de mutantes mortos sobre o total gerado |
| **Covered Code MSI** | Percentual de mutantes mortos entre os que estão em código coberto por testes |
| **Mutation Code Coverage** | Percentual de mutantes situados em código executado por algum teste |
| **Cobertura de linhas** (PHPUnit) | Percentual de linhas executadas pelos testes |

**Metas da atividade:** cobertura de código ≥ 70% e Covered Code MSI ≥ 70%.

## 4. Como executar

Pré-requisitos: PHP com Xdebug, MySQL com o banco `SistemaHBL` importado e o usuário `pnld` criado (detalhes no [manual de execução](manual-de-execucao.md)).

```bash
composer install

export XDEBUG_MODE=coverage
export DB_HOST=127.0.0.1 DB_USER=pnld DB_PASSWORD=pnld_local DB_NAME=SistemaHBL

# testes e cobertura
vendor/bin/phpunit --coverage-text

# testes de mutação (suíte completa; threads=1 porque os testes escrevem no mesmo banco)
vendor/bin/infection --threads=1 --show-mutations

# só um arquivo
vendor/bin/infection --threads=1 --filter=LivroValidator.php --show-mutations
```

Relatórios gerados em `docs/mutation/` (`infection.html`, `infection.log` e `summary.log`). Os arquivos `infection.html` e `infection.log` não são versionados (estão no `.gitignore`); no GitHub Actions eles são publicados como artefato.

## 5. Resultados

### 5.1 Cobertura de código (PHPUnit)

| Momento | Linhas | Métodos | Classes |
|---|---|---|---|
| Inicial (02/11/2026) | 97,47% (963/988) | 92,31% (48/52) | 72,73% (8/11) |
| **Final** (02/11/2026) | 97,67% (965/988) | 96,15% (50/52) | 81,82% (9/11) |

Meta de 70%: **atingida** desde a medição inicial.

### 5.2 Testes de mutação (Infection)

| Momento | Mutantes | Mortos | Sobreviventes | MSI | Covered MSI |
|---|---|---|---|---|---|
| Inicial | 303 | 234 | 69 | 77,23% | 77% |
| Após `LivroValidator` e `EmprestimoRepository` | 306 | 259 | 47 | 84,64% | 84% |
| **Final** | 306 | 288 | 18 | 94,12% | 94% |

Meta de Covered Code MSI ≥ 70%: **atingida** desde a medição inicial.

A linha final vem da execução completa da suíte, registrada em `docs/mutation/summary-final.log`.

Os mutantes gerados passaram de 303 para 306 entre medições, pois o Infection gera os mutantes a partir do código-fonte e a diferença pode vir de mutantes que antes saíam como não cobertos ou com tempo esgotado. Os dois valores estão registrados como foram medidos.

## 6. Mutantes sobreviventes e ações

### 6.1 Visão por arquivo

| Arquivo | Sobreviventes (inicial) | Sobreviventes (final) | Situação |
|---|---|---|---|
| `LivroValidator.php` | 22 | 0 | 58 de 58 mutantes mortos |
| `EmprestimoRepository.php` | 21 | 3 | 63 de 66 mortos; os 3 restantes são equivalentes (6.3) |
| `LivroRepository.php` | 11 | 0 | Nenhum sobrevivente no log final |
| `AlunoValidador.php` | 5 | 5 | Não tratado nesta entrega (6.4) |
| `http.php` | 5 | 5 | Não tratado nesta entrega (6.4) |
| `CadastroAdministrador.php` | 2 | 2 | Não tratado nesta entrega (6.4) |
| `AdministradorRepository.php` | 2 | 2 | Não tratado nesta entrega (6.4) |
| `TurmaRepository.php` | 1 | 1 | Não tratado nesta entrega (6.4) |

### 6.2 Ações realizadas

| Arquivo | Mutação (operador) | Causa da sobrevivência | Teste criado |
|---|---|---|---|
| `LivroValidator` (linha 8) | `UnwrapTrim`, `Coalesce` | Nenhum teste usava ISBN com espaços nem conferia a ordem das validações | ISBN com espaços nas pontas aceito; dados com dois erros conferindo a mensagem exata (`testValidaIsbnAntesDosNumeros`, `testValidaNumerosAntesDaSituacao`) |
| `LivroValidator` (linhas 19 a 26) | `UnwrapTrim`, `ArrayItemRemoval`, `Foreach_`, `PregMatchRemoveCaret`, `PregMatchRemoveDollar`, `PregMatchRemoveFlags` | Nenhum teste ultrapassava 100 caracteres nem usava acentos ou quebra de linha | Limites de 100 e 101 caracteres em título e autor, 100 caracteres acentuados, espaços nas pontas e quebra de linha no título |
| `LivroValidator` (linhas 36 e 40) | `LogicalAnd`, `IncrementInteger`, `DecrementInteger`, `ArrayItemRemoval` | ISBN `0`, `+5` e `1` nunca eram testados | `testRejeitarIsbnInvalido` (data provider) e `testAceitarIsbnMinimoIgualAUm` |
| `LivroValidator` (linhas 48 e 49) | `ArrayItemRemoval`, `DecrementInteger` | Só o campo `ano` era testado | Cada campo numérico com `0`, `-1` e `texto` |
| `LivroValidator` (linhas 70 e 80) | `LogicalNot`, `LogicalAndAllSubExprNegation`, `IncrementInteger`, `DecrementInteger` | Tipos inválidos e limite de `2147483647` sem teste | Inteiro nativo aceito; `float`, `bool`, `null` e `array` rejeitados; limite máximo aceito e acima dele rejeitado |
| `EmprestimoRepository` (`commit`/`rollback`) | `MethodCallRemoval` | Os testes consultavam o banco pela mesma conexão, que enxerga dados ainda não confirmados | Conexão "espiã" que conta chamadas: sucesso faz 1 `commit` e 0 `rollback`; falha faz 1 `rollback` e 0 `commit` |
| `EmprestimoRepository` (`close`) | `MethodCallRemoval` | Ninguém verificava o fechamento dos statements | Verifica que todo `prepare` tem um `close` correspondente |
| `EmprestimoRepository` (`$transacaoIniciada`) | `FalseValue`, `TrueValue`, `IfNegation` | Erro antes e depois do `begin_transaction` não era diferenciado | Falha antes do `begin` não chama `rollback`; falha depois do `begin` chama `rollback` uma vez |
| `EmprestimoRepository` (`error_log`) | `FunctionCallRemoval` | A mensagem de erro registrada não era conferida | Captura o `error_log` e confere o texto da exceção |
| `LivroRepository` (`consultarTodos` e `consultarDisponiveis`) | `UnwrapTrim` (linha 43) e demais mutações na montagem da consulta e na condição do filtro de título | Os testes com mock não conferiam o SQL exato, o valor ligado ao parâmetro (`%título%`) nem o caso de título vazio ou só com espaços | `testConsultarTodosRemoveEspacosDoTitulo`, `testConsultarTodosIgnoraTituloComApenasEspacos`, `testConsultarTodosIgnoraTituloVazio` e os três equivalentes de `consultarDisponiveis`, que conferem o SQL, o `bind_param` e que `bind_param` não é chamado sem título |

Os testes novos estão em `tests/LivroValidatorTest.php`, `tests/EmprestimoTransacaoEspiaoTest.php` e `tests/LivroRepositoryTest.php`.

### 6.3 Mutantes equivalentes (analisados e mantidos)

Mutantes equivalentes alteram o código sem alterar o comportamento observável, então nenhum teste consegue matá-los. Foram analisados e mantidos:

| Arquivo e linha | Operador | Justificativa |
|---|---|---|
| `EmprestimoRepository.php:67` | `FalseValue` | A atribuição `$transacaoIniciada = false` ocorre depois do `commit`/`rollback`, e nada mais no bloco `try` usa a variável. O `catch` só usa a variável se houver exceção nesse intervalo, o que não acontece. |
| `EmprestimoRepository.php:114` | `FalseValue` | Mesmo caso, no método `registrarDevolucao`. |
| `EmprestimoRepository.php:177` | `CastInt` | Com `prepare` e `get_result()`, o driver `mysqlnd` já devolve o inteiro nativo, e o cast `(int)` não altera o valor. |

Os demais sobreviventes ainda não foram analisados e estão listados na seção 6.4.

### 6.4 Sobreviventes não tratados nesta entrega

Ao final, restam 15 mutantes além dos 3 equivalentes. Eles estão em arquivos pequenos e não foram analisados um a um nesta entrega. A coluna "Causa provável" é uma hipótese a partir do operador de mutação e deve ser confirmada lendo o código e o relatório `infection.html`.

| Arquivo e linha | Operador | Causa provável |
|---|---|---|
| `AdministradorRepository.php:22` | `DecrementInteger` | Valor numérico de limite sem teste de borda |
| `AdministradorRepository.php:23` | `ReturnRemoval` | Retorno antecipado cujo efeito não é verificado |
| `AlunoValidador.php:26` a `29` (4) | `Coalesce` | Ordem das validações não conferida por mensagem exata |
| `AlunoValidador.php:77` | `CastInt` | Tipo do valor retornado não conferido com `assertSame` |
| `CadastroAdministrador.php:30` e `31` | `UnwrapTrim` | Sem teste com espaços nas pontas dos campos |
| `TurmaRepository.php:116` | `CastInt` | Tipo do valor retornado não conferido |
| `http.php:9` (4) | `Concat`, `ConcatOperandRemoval`, `MethodCallRemoval` | Texto ou cabeçalho montado por concatenação sem teste do conteúdo |
| `http.php:19` | `MethodCallRemoval` | Chamada cujo efeito não é verificado |

Todos esses mutantes estão em código coberto por testes (Mutation Code Coverage de 100%), então o caminho para matá-los é reforçar as verificações, como fizemos em `LivroValidator`.

## 7. Integração contínua

O workflow `.github/workflows/testes.yml` executa a cada push e pull request:

1. PHPUnit (PHP 8.4) com MySQL 8.4, gerando `coverage.xml`;
2. verificação de cobertura mínima de 70%;
3. Infection (publica `docs/mutation/` como artefato);
4. análise do SonarQube.

Também pode ser disparado manualmente (`workflow_dispatch`).

## 8. Fluxo de trabalho (GitFlow)

| Branch | Função |
|---|---|
| `main` | Versão entregue |
| `develop` | Integração |
| `feature/testes-mutacao` | Configuração do Infection e novos testes |

Os trabalhos foram feitos em branches e integrados por pull request, associados ao milestone **Testes de Mutação (P3)**.

## 9. Conclusão

A cobertura de linhas ficou em 97,67%, acima da meta de 70%. O MSI subiu de 77,23% para 94,12% e o Covered Code MSI de 77% para 94%, também acima da meta de 70%. Foram mortos 54 dos 69 mutantes que sobreviveram na primeira medição. Dos 18 que restam, 3 são equivalentes e estão justificados (6.3), e 15 estão listados para tratamento futuro (6.4).

## 10. Referências

- Infection: <https://infection.github.io/guide/>
- PHPUnit: <https://docs.phpunit.de/>
- [Plano geral de testes](plano-geral-de-testes.md)
- [Plano de testes das iterações 1 e 2](plano-testes-iteracoes-1-e-2.md)
