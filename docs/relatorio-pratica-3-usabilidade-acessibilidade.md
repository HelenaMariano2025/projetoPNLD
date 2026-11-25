# Prática 3: Auditoria e Melhoria de Usabilidade e Acessibilidade

**Responsável:** Jaine  
**Issue:** #46  
**Tela auditada e corrigida:** `add_turma.php` (Adicionar Turma)

## 1. Auditoria de acessibilidade (WCAG 2.2)

**Ferramenta:** Lighthouse 13.5.0 (Chrome 157, desktop, categoria Accessibility), executado com o sistema em funcionamento localmente em `http://localhost:8080` e com o usuário autenticado.

**Observações sobre a execução:** As duas auditorias (antes e depois das correções) foram realizadas no modo Navigation (carregamento da página). Devido à pouca memória disponível na máquina utilizada (cerca de 1,8 GB para o Docker), algumas tentativas falharam, apresentando todos os itens como Error! e os avisos "Clearing the browser cache timed out" e NO_FCP ("The page did not paint any content").

Foram consideradas as execuções que concluíram a análise, embora ambas tenham apresentado o aviso relacionado ao cache nos primeiros resultados. Uma execução intermediária, realizada no modo Snapshot (21 de 23 verificações aprovadas), confirmou a mesma lista de itens identificados no resultado final. Para a medição posterior às correções, a utilização de uma janela anônima do Chrome, com as demais aplicações fechadas, permitiu concluir a análise.

**Diagnóstico inicial de `add_turma.php` (antes das correções): pontuação de acessibilidade 79.**

| Item | Critério WCAG relacionado | Problema encontrado |
|---|---|---|
| Elementos `select` sem label associado | 1.3.1 Informação e relações; 4.1.2 Nome, função, valor | O campo "Situação" (Ativo/Inativo) não possuía um rótulo acessível para leitores de tela. |
| Links sem nome discernível | 2.4.4 Finalidade do link; 4.1.2 | O link do logotipo (`navbar-brand`) não possuía `href`, a imagem apresentava `alt=""` e havia uma tag `</a>` sobrando. |
| Contraste de cores insuficiente | 1.4.3 Contraste (mínimo) | Os links do menu (`a.nav-link`), "HOME" e "VOLTAR", dentro de `div.header_bottom`, apresentavam texto claro sobre o fundo verde da barra, com uma relação de contraste abaixo do mínimo exigido. |
| Documento sem landmark `main` | 1.3.1; 2.4.1 Ignorar blocos | O conteúdo principal da página não estava identificado com o elemento `<main>`. |

O Lighthouse também indicou dez itens que exigiam verificação manual, os quais não foram tratados nesta prática.

## 2. Auditoria de usabilidade

**Método:** Inspeção do código-fonte das telas (`add_turma.php`, `aluno.php`, `turmas.php`, `funcoes.php` e `login.php`) e dos registros do servidor, realizada com apoio da IA Claude e organizada com base nas dez heurísticas de Nielsen e na característica Capacidade de Interação da ISO/IEC 25010:2023.

A revisão crítica foi realizada pela autora, sem uma revisão adicional.

| Heurística de Nielsen | Observação | Avaliação |
|---|---|---|
| 1. Visibilidade do estado do sistema | O cadastro de turma apresenta a mensagem na própria página (`alert-info`), enquanto a tela de aluno apresenta a mensagem após o redirecionamento (`alert-success`). | Atende |
| 2. Correspondência com o mundo real | As mensagens e os rótulos estão em português. Entretanto, o aluno é "inativado", enquanto a US03 utiliza o termo "excluir". | Parcial |
| 3. Controle e liberdade do usuário | Existem as opções "VOLTAR" e "HOME", mas a opção "Sair" apenas direciona para `index.html`, sem encerrar a sessão. | Problema |
| 4. Consistência e padrões | As telas de turma e de aluno apresentam diferenças visuais e de feedback (tema do site × painéis próprios; `alert-info` × `alert-success`). | Problema |
| 5. Prevenção de erros | Existem confirmações antes de inativar alunos e excluir turmas, bloqueio da exclusão de turmas com alunos vinculados e utilização de token CSRF no formulário de aluno. | Atende |
| 6. Reconhecimento em vez de memorização | Em `add_turma.php`, os campos utilizam apenas placeholders como identificação, que desaparecem durante a digitação. Em `aluno.php`, há labels visíveis. | Problema |
| 7. Flexibilidade e eficiência | Há pesquisa por curso na tela de turmas e por nome na tela de alunos. | Atende |
| 8. Design estético e minimalista | O cabeçalho apresenta dados de exemplo ("+01 123455678990" e "[ifpb@gmail.com](mailto:ifpb@gmail.com)"). Além disso, os registros do servidor indicam imagens inexistentes que retornam erro 404 (`/images/.png` e `/images/treatment-side-img.jpg`). | Problema |
| 9. Ajudar a reconhecer e corrigir erros | Existem mensagens claras, como "Já existe uma turma cadastrada com esse código.". Entretanto, a validação genérica ("Preencha os campos obrigatórios com valores válidos.") não informa qual campo apresentou o problema. Antes da correção do BUG-01, o usuário também visualizava um Fatal error técnico do PHP. | Parcial |
| 10. Ajuda e documentação | Não foram encontrados recursos de ajuda ou documentação nas telas analisadas. | Problema |

**Capacidade de Interação (ISO/IEC 25010:2023), subcaracterísticas observadas:**

- **Proteção contra erros do usuário:** boa, considerando a existência de confirmações, bloqueios de exclusão e proteção CSRF.
- **Autodescrição e reconhecimento da adequação:** prejudicados pela ausência de rótulos visíveis nos campos de `add_turma.php` e pela falta de um rótulo acessível no elemento `select`.
- **Inclusão (acessibilidade):** pontuação 79 no Lighthouse, com os problemas apresentados na seção 1.
- **Assistência ao usuário:** ausência de recursos de ajuda ou documentação na interface.

## 3. Correções realizadas (antes × depois)

**Arquivo alterado:** `add_turma.php`.

### Correção 1. Rótulo no campo Situação (WCAG 1.3.1 / 4.1.2)

**Antes:**

```html
<select name="situacao" required>
```

**Depois:**

```html
<select name="situacao" aria-label="Situação da turma" required>
```

### Correção 2. Link do logotipo com nome e destino (WCAG 2.4.4 / 4.1.2)

**Antes:**

```html
<a class="navbar-brand" >
  <img src="images/White and navy simple book store logo.png" alt="">
</a>
</a>
```

**Depois:**

```html
<a class="navbar-brand" href="funcoes.php">
  <img src="images/White and navy simple book store logo.png" alt="Logo do sistema, ir para a página inicial">
</a>
```

Também foi removida a tag `</a>` excedente, que tornava o HTML inválido.

### Correção 3. Landmark principal (WCAG 1.3.1 / 2.4.1)

**Antes:**

```html
<section class="contact_section layout_padding-bottom">
    ...
</section>
```

**Depois:**

```html
<main>
    <section class="contact_section layout_padding-bottom">
        ...
    </section>
</main>
```

### Resultado (Lighthouse, categoria Accessibility, modo Navigation, mesma tela)

| Indicador | Antes | Depois |
|---|---:|---:|
| Pontuação de Accessibility | 79 | 92 |
| Verificações aprovadas | 14 | 21 |
| Select sem label | Falha | Corrigido |
| Documento sem landmark `main` | Falha | Corrigido |
| Link sem nome discernível | Falha | Ainda falha |
| Contraste insuficiente | Falha | Ainda falha (não corrigido) |

A pontuação aumentou 13 pontos (79 → 92). Dos quatro itens que apresentavam falhas, dois foram eliminados: o `select` sem label e a ausência do elemento `main`.

O link do logotipo foi corrigido no código, mas o Lighthouse continuou apontando um link sem nome em outro ponto da página. Como o relatório da ferramenta identificou o elemento apenas como `a`, sem apresentar classe ou identificador que permitisse localizá-lo, esse problema foi mantido como pendência.

O problema de contraste também permaneceu sem correção, pois depende do CSS do tema do site. Ele afeta os links `a.nav-link` ("HOME" e "VOLTAR"), localizados dentro de `div.header_bottom`, sobre a barra verde do topo.

## 4. Limitações

- Apenas uma tela (`add_turma.php`) foi auditada e corrigida. As demais telas apresentam problemas semelhantes, como o logotipo e o cabeçalho, que se repetem nas páginas do tema.
- O contraste não foi corrigido, pois depende do CSS do tema utilizado em todo o site.
- O link sem nome discernível restante não foi identificado, pois o Lighthouse o reportou apenas como `a`.
- O Lighthouse identifica somente parte dos problemas de acessibilidade. Os dez itens que exigiam verificação manual não foram tratados.
- A análise de usabilidade foi realizada com base no código-fonte e nos registros do servidor, sem testes com usuários e sem uma revisão adicional da equipe.