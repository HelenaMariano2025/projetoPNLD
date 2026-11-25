# Pesquisa comparativa de ferramentas para automação de testes de sistema

**Projeto:** PNLD — Sistema de Controle de Livros Didáticos  
**Atividade:** P3 — Automação de Testes de Sistema  
**Ferramentas analisadas:** Selenium, Cypress e Playwright  
**Ferramenta selecionada:** Selenium WebDriver

---

## 1. Introdução

Os testes de sistema são utilizados para verificar se uma aplicação funciona de acordo com os requisitos estabelecidos. Entre eles, os testes de ponta a ponta (*End-to-End* — E2E) permitem avaliar o funcionamento de um sistema a partir da perspectiva de seus usuários, simulando ações realizadas diretamente na interface.

Esses testes podem envolver atividades como acessar o sistema, realizar login, preencher formulários, cadastrar informações, consultar registros e verificar as mensagens apresentadas ao usuário.

A automação de testes E2E permite executar essas atividades de maneira repetível, reduzindo a necessidade de realizar manualmente os mesmos procedimentos após alterações no sistema.

Entre as ferramentas utilizadas para essa finalidade estão Selenium, Cypress e Playwright. Embora as três permitam automatizar navegadores, apresentam diferenças em arquitetura, linguagens de programação, recursos de depuração, execução e integração com ambientes de desenvolvimento.

Este documento apresenta uma pesquisa comparativa dessas ferramentas, considerando suas características, vantagens, desvantagens e adequação ao Projeto PNLD — Sistema de Controle de Livros Didáticos. Ao final, apresenta a justificativa da ferramenta selecionada para a implementação dos testes E2E do projeto.

## 2. Contextualização do Projeto PNLD

O Projeto PNLD é uma aplicação web desenvolvida para auxiliar no gerenciamento de livros didáticos, alunos, turmas, empréstimos e devoluções.

A estrutura tecnológica identificada no projeto utiliza:

| Tecnologia | Utilização no PNLD |
|---|---|
| PHP 8.4 | Processamento das páginas e operações do sistema. |
| MySQL | Armazenamento dos dados. |
| MySQLi | Comunicação entre PHP e MySQL. |
| HTML e CSS | Estrutura e apresentação das páginas. |
| JavaScript e jQuery | Interações da interface. |
| Bootstrap | Estilização e componentes visuais. |
| Docker e Docker Compose | Execução do ambiente de desenvolvimento em contêineres. |
| PHPUnit 12.5 | Execução dos testes automatizados existentes. |

O projeto já possui uma estrutura de testes automatizados com PHPUnit, utilizada para verificar o comportamento de partes do código.

Entretanto, os testes E2E possuem outro objetivo: verificar o funcionamento de fluxos completos por meio de um navegador, simulando as interações de uma pessoa usuária.

Por esse motivo, a escolha da ferramenta de automação deve considerar a possibilidade de trabalhar com a estrutura existente, principalmente com PHP, PHPUnit e Docker.

## 3. Ferramentas analisadas

### 3.1. Selenium

O Selenium é um conjunto de ferramentas de código aberto destinado à automação de navegadores. Seu principal componente é o Selenium WebDriver, que permite controlar navegadores por meio de comandos enviados a uma implementação compatível com o protocolo WebDriver.

O WebDriver define uma interface de comunicação entre os programas de teste e os navegadores. Dessa maneira, os testes podem realizar ações como abrir páginas, localizar elementos, clicar em botões, preencher campos e verificar informações apresentadas na aplicação.

**Arquitetura e funcionamento**

O Selenium WebDriver trabalha por meio de uma arquitetura de comunicação entre o código de teste, o WebDriver e o navegador.

Essa comunicação pode acontecer diretamente com um driver de navegador ou por meio de um servidor Selenium remoto.

No caso de execução remota, o código de teste envia comandos ao Selenium Server, que encaminha as solicitações ao navegador configurado. O resultado das operações retorna ao código de teste.

O Selenium Grid amplia essa estrutura, permitindo distribuir as execuções entre diferentes máquinas, navegadores e ambientes.

**Linguagens e compatibilidade**

O projeto Selenium mantém bibliotecas oficiais para linguagens como:

- Java;
- Python;
- JavaScript;
- C#;
- Ruby.

O Selenium não disponibiliza uma implementação oficial de sua biblioteca de cliente para PHP. Entretanto, existe a biblioteca comunitária `php-webdriver/webdriver`, que permite controlar navegadores por meio do protocolo WebDriver utilizando PHP.

De acordo com o README do repositório oficial [php-webdriver/php-webdriver](https://github.com/php-webdriver/php-webdriver), a biblioteca é compatível com Selenium Server nas versões 2.x, 3.x e 4.x e pode ser instalada utilizando Composer.

É importante destacar que essa biblioteca é mantida pela comunidade e não pela equipe oficial do Selenium.

**Integração com frameworks de testes**

A integração entre `php-webdriver/webdriver` e PHPUnit não acontece automaticamente apenas pela instalação da biblioteca.

Entretanto, existem projetos que facilitam essa integração:

- **Steward:** integra diretamente o php-webdriver ao PHPUnit, oferece execução paralela e permite gerar resultados em formato JUnit.
- **Symfony Panther:** utiliza php-webdriver e disponibiliza a classe `PantherTestCase` para integração com PHPUnit.
- **Laravel Dusk:** utiliza php-webdriver e disponibiliza uma estrutura própria para testes por meio de `DuskTestCase`.
- **Codeception:** oferece um módulo WebDriver que utiliza php-webdriver e disponibiliza recursos para testes de aceitação e organização de cenários.

Essas alternativas demonstram que existem diferentes formas de estruturar testes de navegador utilizando PHP, embora cada uma tenha seus próprios requisitos e configurações.

Fontes: [README oficial do php-webdriver](https://github.com/php-webdriver/php-webdriver) e [Steward](https://github.com/lmc-eu/steward).

**Gravação, depuração e relatórios**

O Selenium WebDriver permite desenvolver testes por meio de código, oferecendo controle sobre as ações realizadas no navegador.

O ecossistema também possui o Selenium IDE, uma ferramenta separada que permite gravar e reproduzir interações no navegador, auxiliando na criação de casos de teste.

A depuração pode ser realizada por meio dos recursos da linguagem utilizada, dos logs, das ferramentas de desenvolvimento do navegador e de ferramentas complementares.

A geração de relatórios, screenshots e vídeos depende da implementação dos testes e das ferramentas adicionais utilizadas. Esses recursos não devem ser considerados automaticamente disponíveis em toda instalação básica do Selenium WebDriver.

**Execução com Docker e integração contínua**

O projeto oficial SeleniumHQ mantém o repositório [docker-selenium](https://github.com/SeleniumHQ/docker-selenium), que disponibiliza imagens Docker com o Selenium Server e navegadores configurados.

Uma dessas imagens é a `selenium/standalone-chrome`, que reúne o servidor e o navegador Chrome em um contêiner.

O repositório também disponibiliza imagens com Chromium, Firefox e outras opções. As imagens multi-arquitetura podem atender ambientes AMD64 e ARM64, conforme a versão e o navegador selecionados. Para computadores ARM64, a disponibilidade deve ser conferida na matriz de imagens: o Chromium é uma alternativa disponível, e versões atuais do Chrome também possuem suporte ARM64 conforme a documentação do projeto.

O Selenium pode ser utilizado em pipelines de integração contínua, como GitHub Actions, desde que o servidor, o navegador e os testes sejam configurados adequadamente.

**Situação no Projeto PNLD:** o projeto possui configuração Docker para executar a aplicação, mas não disponibiliza atualmente um serviço Docker Selenium configurado *(na data da pesquisa; o serviço foi adicionado na etapa prática, [seção 10.1](secao-pratica1-selenium.md))*. O Dockerfile existente prepara o ambiente PHP, não o servidor Selenium nem o navegador utilizado na automação.

Portanto, será necessário adicionar ou executar separadamente um serviço Selenium para que os testes E2E possam controlar um navegador.

O workflow atual do PNLD também ainda não executa testes E2E. A integração com GitHub Actions é uma possibilidade de implementação futura, não uma funcionalidade já validada no projeto.

**Vantagens**

- Possui ampla utilização e uma comunidade consolidada.
- É uma ferramenta de código aberto, com licença Apache 2.0.
- Permite automatizar os principais navegadores disponíveis.
- Oferece suporte a diferentes linguagens de programação.
- Possibilita executar testes localmente ou remotamente.
- Permite distribuir execuções por meio do Selenium Grid.
- Pode ser utilizado com PHP por meio de uma biblioteca comunitária.
- Possui alternativas de integração com PHPUnit e outros frameworks.
- Conta com ferramentas complementares, como Selenium IDE, para gravação e reprodução de interações.
- Pode ser executado em ambientes Docker e em pipelines de integração contínua.

**Desvantagens**

- A configuração inicial pode ser mais trabalhosa, principalmente quando envolve servidor remoto, drivers e navegadores.
- Não possui biblioteca oficial de cliente para PHP.
- A biblioteca `php-webdriver/webdriver` é mantida pela comunidade, não pela equipe oficial do Selenium.
- A integração com PHPUnit exige uma implementação ou ferramenta complementar.
- A geração de relatórios, vídeos e evidências pode exigir configurações adicionais.
- A sincronização com elementos carregados dinamicamente exige configuração adequada de esperas.
- A manutenção das versões do servidor, dos drivers e dos navegadores pode exigir atenção.

### 3.2. Cypress

O Cypress é uma ferramenta de automação de testes voltada para aplicações web. Permite desenvolver testes E2E e testes de componentes utilizando JavaScript ou TypeScript.

A ferramenta possui uma interface interativa que facilita o acompanhamento das execuções e a identificação de falhas.

**Arquitetura e funcionamento**

O Cypress possui uma arquitetura própria de execução, na qual o código de teste trabalha integrado ao navegador e à aplicação em teste.

Essa abordagem permite acompanhar as ações realizadas durante a execução, inspecionar elementos e observar o comportamento da aplicação.

Diferentemente do modelo tradicional do Selenium WebDriver, o Cypress utiliza mecanismos próprios de automação e não depende de um servidor Selenium para controlar o navegador.

**Linguagens e compatibilidade**

Os testes do Cypress são escritos em JavaScript ou TypeScript.

Isso não significa que a aplicação testada precise ser desenvolvida nessas linguagens. O Cypress pode testar aplicações cujo backend utilize PHP, Java, Python ou outras tecnologias.

No Projeto PNLD, por exemplo, seria possível utilizar Cypress para testar a aplicação PHP. Entretanto, os scripts de automação precisariam ser desenvolvidos em JavaScript ou TypeScript.

A documentação oficial apresenta suporte aos navegadores Chrome, Edge e Firefox, além de suporte experimental ao WebKit, mecanismo utilizado pelo Safari. Esse suporte experimental possui limitações e não deve ser considerado equivalente ao suporte estável dos demais navegadores.

**Gravação, depuração e relatórios**

O Cypress oferece uma interface interativa chamada Open Mode, que permite acompanhar a execução dos testes, visualizar comandos e inspecionar o estado da aplicação.

Também disponibiliza recursos de depuração, incluindo histórico de comandos e snapshots que ajudam a compreender o comportamento da aplicação durante a execução.

O Cypress Studio permite registrar interações realizadas na aplicação e gerar comandos de teste. É possível adicionar verificações manualmente. Já as recomendações de asserções feitas por inteligência artificial pertencem ao Studio AI e dependem de recursos do Cypress Cloud.

A ferramenta também permite capturar screenshots e vídeos das execuções, conforme a configuração utilizada.

**Execução em integração contínua**

O Cypress pode ser executado em ambientes de integração contínua, inclusive no GitHub Actions.

O comando `cypress run` permite executar testes em modo headless, sem a necessidade de exibir visualmente o navegador.

A documentação também apresenta imagens Docker próprias e orientações para utilização em pipelines automatizados.

**Vantagens**

- Possui configuração relativamente simples para projetos web.
- Oferece interface interativa para acompanhar os testes.
- Disponibiliza recursos de depuração e inspeção de elementos.
- Possibilita visualizar o histórico de comandos executados.
- Permite capturar screenshots e vídeos conforme a configuração.
- Possui mecanismos de espera automática que auxiliam na sincronização com a aplicação.
- Conta com documentação abrangente e comunidade ativa.
- Pode testar aplicações desenvolvidas em PHP, mesmo exigindo JavaScript ou TypeScript nos scripts de teste.
- Oferece recursos de gravação de interações por meio do Cypress Studio.
- Permite execução em modo headless e integração com pipelines de CI.

**Desvantagens**

- Os testes precisam ser escritos em JavaScript ou TypeScript.
- Não permite utilizar diretamente os testes PHP existentes no PHPUnit.
- Possui limitações em determinados cenários que exigem controle de múltiplas abas ou navegadores simultâneos.
- O suporte ao WebKit ainda é experimental.
- Alguns recursos avançados de gravação, acompanhamento e análise dependem do Cypress Cloud.
- A adoção no PNLD exigiria manter uma estrutura de testes em linguagem diferente daquela utilizada no backend.

Fontes: [Cypress Documentation](https://docs.cypress.io/), [Launching Browsers](https://docs.cypress.io/app/references/launching-browsers) e [Cypress Studio](https://docs.cypress.io/app/guides/cypress-studio).

### 3.3. Playwright

O Playwright é uma ferramenta de automação de navegadores desenvolvida para testes e outras atividades de automação web.

Permite controlar diferentes navegadores por meio de APIs e oferece recursos para execução, depuração, captura de evidências e integração contínua.

**Arquitetura e funcionamento**

O Playwright utiliza uma arquitetura própria de comunicação com os navegadores, oferecendo APIs que permitem controlar páginas, contextos e sessões de navegação.

A ferramenta cria contextos de navegador isolados, o que permite executar testes sem compartilhar diretamente cookies e outros dados de sessão entre eles.

Também possui um executor de testes próprio para JavaScript e TypeScript, chamado Playwright Test.

Para outras linguagens, como Python, Java e .NET, a ferramenta disponibiliza bibliotecas que podem ser utilizadas com os respectivos frameworks de teste.

**Linguagens e compatibilidade**

O Playwright oferece suporte oficial às seguintes linguagens:

- JavaScript;
- TypeScript;
- Python;
- Java;
- .NET.

A ferramenta permite automatizar os navegadores Chromium, Firefox e WebKit.

Não possui uma implementação oficial para PHP. Assim, no Projeto PNLD, seria necessário desenvolver os testes utilizando uma das linguagens suportadas, mantendo uma estrutura separada dos testes PHP existentes.

**Gravação, depuração e relatórios**

O Playwright disponibiliza ferramentas que auxiliam na criação e na manutenção dos testes:

- **Codegen:** permite registrar interações realizadas no navegador e gerar código inicial para os testes.
- **Playwright Inspector:** auxilia na inspeção e depuração dos testes.
- **Trace Viewer:** permite analisar a execução por meio de rastreamentos, snapshots do DOM, solicitações de rede e outras informações.
- **Screenshots e vídeos:** permitem registrar evidências das execuções, conforme a configuração.
- **HTML Reporter:** disponibiliza relatórios das execuções quando utilizado com o executor Playwright Test.

A disponibilidade de alguns recursos depende da linguagem, do executor e da configuração adotada.

**Execução em integração contínua**

O Playwright pode ser executado em ambientes de integração contínua, incluindo GitHub Actions.

Permite executar os testes em modo headless e disponibiliza documentação específica para configuração em pipelines.

O Playwright Test executa arquivos de teste em paralelo por padrão. Também permite configurar a quantidade de processos utilizados e distribuir a suíte entre diferentes máquinas por meio de *sharding*.

**Vantagens**

- Possui suporte oficial a JavaScript, TypeScript, Python, Java e .NET.
- Permite automatizar Chromium, Firefox e WebKit.
- Oferece execução em modo headless.
- Disponibiliza ferramentas de gravação de interações e geração de código.
- Possui recursos de depuração, como Inspector e Trace Viewer.
- Permite capturar screenshots e vídeos conforme a configuração.
- Oferece relatórios por meio do executor Playwright Test.
- Possui mecanismos de espera automática.
- Oferece isolamento de contextos de navegador.
- Permite execução paralela e distribuição de testes entre máquinas.
- Possui integração com pipelines de CI.

**Desvantagens**

- Não possui implementação oficial para PHP.
- Exige a utilização de outra linguagem para desenvolver os testes do PNLD.
- A adoção exigiria manter uma estrutura de testes separada do PHPUnit.
- A instalação dos navegadores e de suas dependências pode exigir configuração adicional no ambiente de execução.
- A quantidade de recursos disponíveis pode tornar a configuração inicial mais complexa para equipes sem experiência na ferramenta.

Fontes: [Supported Languages](https://playwright.dev/docs/languages), [Parallelism](https://playwright.dev/docs/test-parallel) e [Continuous Integration](https://playwright.dev/docs/ci).

## 4. Comparação das ferramentas

A tabela apresenta uma comparação geral considerando os recursos e as características documentadas de cada ferramenta.

| Critério | Selenium | Cypress | Playwright | Fonte |
|---|---|---|---|---|
| Licença | Apache 2.0 | MIT | Apache 2.0 | Documentações e repositórios oficiais |
| Código aberto | Sim | Sim | Sim | Repositórios oficiais |
| Linguagens principais | Java, Python, JavaScript, C# e Ruby | JavaScript e TypeScript | JavaScript, TypeScript, Python, Java e .NET | [Selenium](https://www.selenium.dev/documentation/), [Cypress](https://docs.cypress.io/) e [Playwright](https://playwright.dev/docs/languages) |
| Utilização com backend PHP | Sim, por biblioteca comunitária | Sim | Sim | [php-webdriver](https://github.com/php-webdriver/php-webdriver) e documentações das ferramentas |
| Escrita de testes em PHP | Por `php-webdriver/webdriver` | Não | Não possui binding oficial | [php-webdriver](https://github.com/php-webdriver/php-webdriver) |
| Navegadores | Principais navegadores, conforme drivers disponíveis | Chrome, Edge e Firefox; WebKit experimental | Chromium, Firefox e WebKit | Documentações oficiais |
| Arquitetura | WebDriver e comunicação com drivers ou servidor Selenium | Arquitetura própria integrada ao navegador | Arquitetura própria de automação | Documentações oficiais |
| Gravação de interações | Selenium IDE | Cypress Studio | Codegen | [Selenium IDE](https://www.selenium.dev/selenium-ide/), [Cypress Studio](https://docs.cypress.io/app/guides/cypress-studio) e [Codegen](https://playwright.dev/docs/codegen) |
| Depuração | Logs, ferramentas do navegador e recursos complementares | Open Mode e histórico de comandos | Inspector e Trace Viewer | Documentações oficiais |
| Screenshots | Por implementação e recursos complementares | Disponível | Disponível | Documentações oficiais |
| Vídeos | Por ferramentas e configurações complementares | Disponível por configuração | Disponível por configuração | Documentações oficiais |
| Relatórios | Dependem do framework e de ferramentas complementares | Relatórios configuráveis e recursos adicionais | HTML Reporter no Playwright Test e outros formatos | Documentações oficiais |
| Execução headless | Sim | Sim | Sim | Documentações oficiais |
| Execução paralela | Possível com Selenium Grid e configuração apropriada | Disponível conforme configuração e recursos utilizados | Disponível no Playwright Test | [Selenium Grid](https://www.selenium.dev/documentation/grid/), [Cypress](https://docs.cypress.io/) e [Playwright](https://playwright.dev/docs/test-parallel) |
| Integração com GitHub Actions | Possível, ainda não implementada no PNLD | Possível | Possível | Documentações oficiais de CI |

**Observação:** os recursos apresentados dependem das versões, dos navegadores, dos frameworks e das configurações utilizadas. A tabela diferencia as capacidades das ferramentas de recursos que exigem componentes adicionais. A coluna de fontes direciona às documentações utilizadas na comparação.

## 5. Escolha da ferramenta e adequação ao Projeto PNLD

A escolha de uma ferramenta de testes E2E deve considerar a realidade tecnológica do projeto, a experiência da equipe e a facilidade de manutenção dos testes.

No PNLD, o backend é desenvolvido em PHP 8.4 e os testes automatizados existentes utilizam PHPUnit 12.5. Por isso, a possibilidade de desenvolver os testes E2E em PHP representa um critério relevante.

O Cypress poderia testar a aplicação sem necessidade de alterar o backend PHP. Sua interface interativa e seus recursos de depuração seriam vantagens para o desenvolvimento dos testes. Entretanto, os scripts precisariam ser escritos em JavaScript ou TypeScript, exigindo uma estrutura adicional de testes.

O Playwright também poderia testar o PNLD e oferece recursos relevantes de gravação, rastreamento, relatórios e execução paralela. Entretanto, como não possui implementação oficial para PHP, seria necessário desenvolver os testes em outra linguagem, como Python, Java ou TypeScript.

O Selenium permite utilizar PHP por meio da biblioteca comunitária `php-webdriver/webdriver`. Essa possibilidade favorece a continuidade tecnológica do projeto, pois os testes podem ser escritos na mesma linguagem utilizada no backend.

Além disso, existem alternativas que facilitam a integração com PHPUnit, como Steward, Symfony Panther e Laravel Dusk. Portanto, a integração não precisa necessariamente ser construída do zero, embora seja necessário avaliar qual abordagem é mais adequada ao projeto.

Para a atividade de automação de testes de sistema do Projeto PNLD, foi selecionado o **Selenium WebDriver**, utilizando a biblioteca comunitária `php-webdriver/webdriver`.

A decisão considera principalmente:

1. **Compatibilidade com PHP:** permite controlar navegadores por meio de scripts PHP.
2. **Continuidade tecnológica:** possibilita manter os testes E2E na mesma linguagem utilizada no backend.
3. **Possibilidade de utilização com PHPUnit:** existem ferramentas que facilitam a integração com o framework já utilizado no projeto.
4. **Automação de navegadores:** permite simular os principais fluxos de utilização do sistema, como login, cadastro de alunos, cadastro de livros e registro de empréstimos.
5. **Execução em ambiente Docker:** pode utilizar o projeto docker-selenium para disponibilizar o servidor e o navegador em um contêiner separado.
6. **Integração contínua:** permite executar os testes em pipelines automatizados, incluindo GitHub Actions, após a configuração necessária.

A escolha não significa que o Selenium seja superior ao Cypress ou ao Playwright em todos os aspectos. As outras ferramentas apresentam recursos integrados de depuração, gravação e relatórios que podem facilitar determinadas atividades.

Entretanto, considerando a estrutura tecnológica do PNLD, a possibilidade de utilizar PHP nos testes foi considerada um fator importante para a decisão.

## 6. Verificação do ambiente e limitações da solução escolhida

A utilização do Selenium com PHP apresenta algumas necessidades de configuração e validação. Por isso, antes da implementação dos cenários E2E, foram consideradas as condições do ambiente de desenvolvimento do PNLD.

### 6.1. Verificação das extensões PHP

O ambiente Docker do projeto foi consultado por meio do comando:

```bash
docker compose exec app php -m
```

A saída apresentada no terminal incluiu as extensões:

- `curl`;
- `json`;
- `mysqli`;
- `zip`.

As extensões `curl`, `json` e `zip` são requisitos declarados pela biblioteca `php-webdriver/webdriver`. A extensão `mysqli` já é utilizada pela aplicação para comunicação com o banco de dados.

Essa verificação demonstra que as extensões necessárias identificadas para a biblioteca estão disponíveis no ambiente PHP consultado.

### 6.2. Simulação da instalação da biblioteca

Também foi realizada uma simulação de instalação por meio do Composer, utilizando o comando:

```bash
docker compose exec app composer require --dev php-webdriver/webdriver --dry-run
```

O resultado apresentado pelo Composer identificou a versão `1.16.0` como a versão a ser utilizada e indicou a operação de instalação da dependência.

A saída também apresentou um aviso de vulnerabilidade em uma dependência, recomendando a execução de `composer audit` para identificação do pacote afetado.

Essa simulação foi uma verificação preliminar. A instalação efetiva da biblioteca, a configuração do servidor Selenium e a comunicação com o navegador foram realizadas na etapa prática e estão documentadas na   [seção 10.1](secao-pratica1-selenium.md).

### 6.3. Limitações identificadas

**Biblioteca PHP comunitária**

A biblioteca `php-webdriver/webdriver` não é mantida oficialmente pelo projeto Selenium. Dessa forma, é necessário acompanhar sua documentação, atualizações e compatibilidade com as versões utilizadas.

**Integração com PHPUnit**

A integração não ocorre automaticamente pela instalação da biblioteca. Existem alternativas como Steward, Symfony Panther, Laravel Dusk e Codeception, mas será necessário selecionar e configurar a abordagem adequada ao PNLD.

*(resolvido na etapa prática, [seção 10](secao-pratica1-selenium.md)).*

**Configuração do servidor e navegador**

O Selenium WebDriver precisa se comunicar com um servidor Selenium ou com um driver de navegador compatível.

O Projeto PNLD ainda não possui o serviço Docker Selenium configurado. Portanto, essa estrutura deverá ser adicionada ou executada separadamente.

*(resolvido na etapa prática, [seção 10](secao-pratica1-selenium.md)).*

**Estabilidade dos testes**

Os testes E2E dependem do funcionamento da aplicação, do banco de dados, do navegador e da comunicação entre esses componentes.

Para reduzir falhas, será necessário utilizar seletores confiáveis, esperas explícitas quando apropriado e dados de teste controlados.

**Evidências e relatórios**

A captura de screenshots, logs e relatórios deverá ser planejada na implementação. O Selenium WebDriver não fornece automaticamente todos esses recursos em uma instalação básica.

**Integração contínua**

Embora o Selenium possa ser utilizado com GitHub Actions, o workflow atual do PNLD ainda não executa testes E2E. A configuração e a execução deverão ser implementadas e validadas posteriormente.

## 7. Planejamento de utilização no PNLD

A implementação dos testes E2E deverá seguir etapas que permitam configurar o ambiente e validar os fluxos principais da aplicação.

| Etapa | Atividade | Resultado esperado |
|---|---|---|
| 1 | Definir a abordagem de integração entre PHP WebDriver e PHPUnit | Estrutura de testes selecionada |
| 2 | Instalar a biblioteca e as dependências necessárias | Disponibilizar o cliente WebDriver no projeto |
| 3 | Configurar o servidor Selenium | Disponibilizar o serviço de automação |
| 4 | Configurar o navegador utilizado nos testes | Permitir a execução das interações |
| 5 | Criar a estrutura dos testes E2E | Organizar os scripts por funcionalidade |
| 6 | Implementar os cenários BDD definidos nas histórias de usuário | Automatizar fluxos completos do sistema |
| 7 | Executar os testes e corrigir falhas | Validar o funcionamento dos cenários |
| 8 | Registrar screenshots, logs e resultados | Produzir evidências das execuções |
| 9 | Avaliar a execução em GitHub Actions | Verificar a possibilidade de automatizar os testes na integração contínua |

Essa sequência representa o planejamento da implementação. A conclusão de cada etapa deverá ser comprovada por meio dos arquivos, comandos executados e evidências produzidas.

## 8. Considerações finais

A pesquisa comparativa demonstrou que Selenium, Cypress e Playwright são ferramentas capazes de automatizar testes de aplicações web, mas apresentam diferenças importantes em suas arquiteturas, linguagens, recursos e formas de execução.

O Cypress oferece uma experiência interativa de desenvolvimento e depuração. O Playwright disponibiliza recursos abrangentes de automação, rastreamento, relatórios e execução paralela. O Selenium se destaca pela flexibilidade de linguagens e pela possibilidade de execução remota por meio de seu servidor e do Selenium Grid.

No contexto do Projeto PNLD, o Selenium foi selecionado considerando a possibilidade de desenvolver os testes E2E em PHP por meio da biblioteca comunitária `php-webdriver/webdriver`, favorecendo a continuidade com a tecnologia já utilizada no projeto.

A verificação preliminar do ambiente identificou as extensões PHP necessárias e demonstrou que o Composer consegue resolver a versão 1.16.0 da biblioteca em uma simulação. Essas etapas foram posteriormente concluídas: a biblioteca foi instalada, o servidor Selenium foi configurado, a comunicação com o navegador foi estabelecida e os cenários do fluxo de cadastro e login foram executados, conforme descrito na [seção 10](secao-pratica1-selenium.md).

Dessa forma, a pesquisa fundamenta a escolha da ferramenta e orientou a implementação dos testes E2E, cujos resultados estão apresentados na  [seção 10](secao-pratica1-selenium.md).

## 9. Referências

CYPRESS. **Cypress Documentation**. Disponível em: https://docs.cypress.io/. Acesso em: 5 nov. 2026.

CYPRESS. **Launching Browsers**. Disponível em: https://docs.cypress.io/app/references/launching-browsers. Acesso em: 5 nov. 2026.

CYPRESS. **Cypress Studio AI**. Disponível em: https://docs.cypress.io/app/guides/cypress-studio. Acesso em: 5 nov. 2026.

LMC. **Steward: PHP libraries that makes Selenium WebDriver + PHPUnit functional testing easy and robust**. GitHub. Disponível em: https://github.com/lmc-eu/steward. Acesso em: 5 nov. 2026.

MICROSOFT. **Playwright Documentation — Supported Languages**. Disponível em: https://playwright.dev/docs/languages. Acesso em: 5 nov. 2026.

MICROSOFT. **Playwright Documentation — Parallelism**. Disponível em: https://playwright.dev/docs/test-parallel. Acesso em: 5 nov. 2026.

MICROSOFT. **Playwright Documentation — Continuous Integration**. Disponível em: https://playwright.dev/docs/ci. Acesso em: 5 nov. 2026.

MICROSOFT. **Playwright — Package Information**. GitHub. Disponível em: https://github.com/microsoft/playwright. Acesso em: 5 nov. 2026.

PHP-WEBDRIVER. **php-webdriver: Selenium WebDriver bindings for PHP**. GitHub. Disponível em: https://github.com/php-webdriver/php-webdriver. Acesso em: 5 nov. 2026.

PHP-WEBDRIVER. **php-webdriver/webdriver**. Packagist. Disponível em: https://packagist.org/packages/php-webdriver/webdriver. Acesso em: 5 nov. 2026.

SELENIUM. **Selenium WebDriver Documentation**. Disponível em: https://www.selenium.dev/documentation/webdriver/. Acesso em: 5 nov. 2026.

SELENIUM. **Selenium Grid Documentation**. Disponível em: https://www.selenium.dev/documentation/grid/. Acesso em: 5 nov. 2026.

SELENIUMHQ. **docker-selenium**. GitHub. Disponível em: https://github.com/SeleniumHQ/docker-selenium. Acesso em: 5 nov. 2026.