Prática 3: Auditoria e Melhoria de Usabilidade e Acessibilidade

Responsável: Jaine · Issue: #46 · Tela auditada e corrigida: add_turma.php (Adicionar Turma)

1. Auditoria de acessibilidade (WCAG 2.2)

Ferramenta: Lighthouse 13.5.0 (Chrome 157, desktop, categoria Accessibility), executado sobre o sistema rodando localmente em http://localhost:8080, com o usuário autenticado.

Observações sobre a execução. As duas auditorias (antes e depois) foram feitas em modo Navigation (carregamento da página). Por causa da pouca memória da máquina usada (cerca de 1,8 GB disponíveis para o Docker), algumas tentativas falharam, com todos os itens em Error! e os avisos "Clearing the browser cache timed out" e NO_FCP ("The page did not paint any content"). Foram usadas as execuções que concluíram, ambas com o aviso de cache nos primeiros resultados. Uma execução intermediária em modo Snapshot (21 de 23 verificações aprovadas) confirmou a mesma lista de itens do resultado final. Para o "depois", a janela anônima do Chrome com as demais aplicações fechadas permitiu concluir a medição.

Diagnóstico inicial de add_turma.php (antes): pontuação de acessibilidade 79.

Item	Critério WCAG relacionado	Problema encontrado
Elementos select sem label associado	1.3.1 Informação e relações; 4.1.2 Nome, função, valor	O campo "Situação" (Ativo/Inativo) não tem rótulo acessível para leitor de tela
Links sem nome discernível	2.4.4 Finalidade do link; 4.1.2	O link do logotipo (navbar-brand) não tem href, a imagem tem alt="" e há um </a> sobrando
Contraste de cores insuficiente	1.4.3 Contraste (mínimo)	Os links do menu (a.nav-link, "HOME" e "VOLTAR") dentro de div.header_bottom têm texto claro sobre o fundo verde da barra, com razão de contraste abaixo da mínima
Documento sem landmark main	1.3.1; 2.4.1 Ignorar blocos	O conteúdo principal não está marcado como <main>

O Lighthouse indicou ainda 10 itens para verificação manual, que não foram tratados.

2. Auditoria de usabilidade

Método. Inspeção do código-fonte das telas (add_turma.php, aluno.php, turmas.php, funcoes.php, login.php) e dos registros do servidor, conduzida com apoio de IA (Claude) e organizada pelas 10 heurísticas de Nielsen e pela característica Capacidade de Interação da ISO/IEC 25010:2023. Revisão crítica: análise pela autora, sem revisão adicional.

Heurística de Nielsen	Observação	Avaliação
1. Visibilidade do estado do sistema	Cadastro de turma mostra a mensagem na própria página (alert-info); o aluno mostra a mensagem após redirecionamento (alert-success)	Atende
2. Correspondência com o mundo real	Mensagens e rótulos em português; o aluno é "inativado", enquanto a US03 fala em "excluir"	Parcial
3. Controle e liberdade do usuário	Há "VOLTAR" e "HOME", mas "Sair" apenas leva a index.html e não encerra a sessão	Problema
4. Consistência e padrões	Telas de turma e de aluno têm aparência e feedback diferentes (tema do site × painéis próprios; alert-info × alert-success)	Problema
5. Prevenção de erros	Confirmação antes de inativar aluno e excluir turma; bloqueio da exclusão de turma com alunos vinculados; token CSRF no formulário de aluno	Atende
6. Reconhecimento em vez de memorização	Em add_turma.php os campos usam só placeholder como rótulo, que some ao digitar; em aluno.php há label visível	Problema
7. Flexibilidade e eficiência	Pesquisa por curso (turmas) e por nome (alunos)	Atende
8. Design estético e minimalista	Cabeçalho com dados de exemplo ("+01 123455678990", "ifpb@gmail.com"); imagens inexistentes retornam 404 nos registros do servidor (/images/.png, /images/treatment-side-img.jpg)	Problema
9. Ajudar a reconhecer e corrigir erros	Mensagens claras ("Já existe uma turma cadastrada com esse código."), mas a validação genérica ("Preencha os campos obrigatórios com valores válidos.") não diz qual campo falhou; antes do conserto do BUG-01 o usuário via um Fatal error técnico do PHP	Parcial
10. Ajuda e documentação	Não encontrada nas telas analisadas	Problema

Capacidade de Interação (ISO/IEC 25010:2023), subcaracterísticas observadas:

Proteção contra erros do usuário: boa (confirmações, bloqueio de exclusão, CSRF).
Autodescrição e reconhecimento da adequação: prejudicadas pelos campos sem rótulo visível em add_turma.php e pelo select sem rótulo acessível.
Inclusão (acessibilidade): nota 79 no Lighthouse, com os problemas da seção 1.
Assistência ao usuário: sem ajuda ou documentação na interface.
3. Correções realizadas (antes × depois)

Arquivo alterado: add_turma.php.

Correção 1. Rótulo no campo Situação (WCAG 1.3.1 / 4.1.2)

Antes:

html
<select name="situacao" required>

Depois:

html
<select name="situacao" aria-label="Situação da turma" required>
Correção 2. Link do logotipo com nome e destino (WCAG 2.4.4 / 4.1.2)

Antes:

html
<a class="navbar-brand" >
  <img src="images/White and navy simple book store logo.png" alt="">
</a>
</a>

Depois:

html
<a class="navbar-brand" href="funcoes.php">
  <img src="images/White and navy simple book store logo.png" alt="Logo do sistema, ir para a página inicial">
</a>

Foi removido também o </a> sobrando, que tornava o HTML inválido.

Correção 3. Landmark principal (WCAG 1.3.1 / 2.4.1)

Antes: <section class="contact_section layout_padding-bottom"> ... </section>

Depois: <main><section class="contact_section layout_padding-bottom"> ... </section></main>

Resultado (Lighthouse, categoria Accessibility, modo Navigation, mesma tela)
	Antes	Depois
Pontuação de Accessibility	79	92
Verificações aprovadas	14	21
Select sem label	Falha	Corrigido
Documento sem landmark main	Falha	Corrigido
Link sem nome discernível	Falha	Ainda falha: o Lighthouse lista o elemento apenas como a, sem classe ou identificador
Contraste insuficiente	Falha	Ainda falha (não corrigido): a.nav-link ("HOME" e "VOLTAR") dentro de div.header_bottom, sobre a barra verde do topo

A pontuação subiu 13 pontos (79 → 92), e dos quatro itens que falhavam, dois foram eliminados (select sem label e ausência de main). O link do logotipo foi corrigido no código, mas o Lighthouse ainda aponta um link sem nome em outro ponto da página; como o relatório da ferramenta mostra o elemento apenas como a, sem como localizá-lo, ele fica registrado como problema pendente, assim como o contraste, que depende do CSS do tema do site.

4. Limitações
Apenas uma tela (add_turma.php) foi auditada e corrigida; as demais telas têm problemas semelhantes (por exemplo, o logotipo e o cabeçalho se repetem nas páginas do tema).
O contraste não foi corrigido, pois depende do CSS do tema usado em todo o site.
O link sem nome restante não foi identificado, pois o Lighthouse o reportou apenas como a.
Lighthouse detecta só parte dos problemas; os 10 itens de verificação manual não foram tratados.
A análise de usabilidade foi feita a partir do código e dos registros, sem teste com usuários e sem revisão adicional da equipe.