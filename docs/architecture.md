# Arquitetura

## Estilo geral

O sistema é um monólito PHP com arquitetura própria. Não existe separação rígida entre controllers, serviços e repositórios. Rotas incluem arquivos diretamente, funções globais convivem com classes e regras de negócio podem acessar o banco sem uma camada intermediária.

O nome do diretório nem sempre representa um módulo isolado. A aplicação atual de agenda cultural foi construída sobre uma base reutilizada, que ainda contém código de outros produtos.

## Camadas observadas

### Entrada e bootstrap

- `.htaccess`: encaminha URLs amigáveis.
- `index.php`: redireciona a raiz para `home_v3`.
- `controle-includes.php`: interpreta a rota e escolhe página, ação, função, administração, objeto ou gerador.
- `front_includes.php`: carrega configuração, Composer, bibliotecas e funções.
- `config.php`: inicia sessão e define ambiente, banco, URL e integrações.
- `autoload.php`: resolve namespaces dentro de `classes/`.

### Apresentação

- `pages/`: páginas e objetos públicos.
- `containers/`: layouts-fonte.
- `containers/exe_system/`: layouts gerados.
- `admin/`: interface administrativa.
- `script/`, `js/`, `css/`, `assets/`: recursos de frontend.

Uma página moderna costuma ter:

- `<nome>.php`: markup e dados iniciais;
- `<nome>.css.php`: estilos opcionais;
- `<nome>.vue.php`: instância Vue 2 e comportamento no navegador.

### Aplicação e domínio

- `classes/Sistema/`: regras de negócio usadas pela aplicação atual e por módulos legados.
- `action/`: endpoints PHP incluídos diretamente pelo roteador.
- `functions/`: funções globais, infraestrutura e camadas de banco.
- `classes/Backend/`: implementação das operações expostas pela API.

### Persistência

O projeto possui três gerações de acesso a MySQL:

- `Model`, em `functions/mainFunction.php`;
- `DB`/`DB_Classe`, em `functions/queryFunction.php`;
- `DAO`/`DB_Functions`/`DB_Class`, em `functions/auto_db.php`.

Não há migrations formais nem uma unidade de trabalho central. Operações compostas frequentemente não usam transações.

### Administração orientada por metadados

As tabelas `system_form`, `system_inputs`, `system_perfil` e estruturas relacionadas descrevem formulários, campos, menus e permissões. Os geradores de `system/` transformam esses metadados em arquivos PHP.

Arquivos derivados incluem:

- `tables/*.php`;
- `tables/_admin_*.php`;
- `admin/exe_system/`;
- `containers/exe_system/`;
- `functions/__list_functions.php`.

## Dependências principais

O Composer da raiz é usado principalmente para clientes de IA e componentes HTTP:

- cliente Gemini;
- cliente OpenAI;
- Symfony HttpClient;
- implementação PSR-7 Nyholm.

Também existem bibliotecas copiadas para dentro do projeto, entre elas Slim 2, PHPMailer legado, NuSOAP, mPDF e SDKs antigos.

Não há pipeline frontend na raiz. Vue 2, jQuery, Tailwind e outras bibliotecas são carregados diretamente pelos containers.

## Domínio atual e código legado

O domínio atual concentra-se em:

- agenda e detalhe de eventos;
- tags e filtros;
- cadastro público de eventos;
- importação de eventos por URL com IA;
- chat de recomendação cultural;
- administração genérica;
- projetos, tarefas e compartilhamento.

Há ainda código de lojas, pagamentos, acompanhantes, rede protetiva, hotelaria, WhatsApp, Facebook e outros produtos. Antes de reutilizar uma classe, confirme chamadas ativas a ela e as tabelas que espera encontrar.

## Mapa de dependências

```text
Apache/.htaccess
    -> controle-includes.php
        -> front_includes.php
            -> config.php
            -> autoload.php
            -> vendor/autoload.php
            -> functions/*.php
        -> page.php | action/*.php | admin/*.php | system/*.php
            -> pages/* e containers/*
            -> classes/Sistema/*
            -> Model/DB/DAO
                -> MySQL

rest.php
    -> front_includes.php
    -> rest/rest.php e rest/api.php
        -> Slim 2
        -> classes/Backend/*
            -> DAO/MySQL e integrações externas
```

## Restrições arquiteturais importantes

- Requisições normais podem acionar regeneração de código quando `system/` está presente.
- A configuração de ambiente é definida em código, não por `.env`.
- O autoload próprio e o Composer coexistem.
- Código moderno exige PHP recente, enquanto partes legadas dependem de comportamentos antigos.
- Funções e métodos podem ser selecionados dinamicamente pela entrada HTTP; não presuma que toda chamada possui validação central.
