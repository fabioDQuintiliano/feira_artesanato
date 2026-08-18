# Ciclo das requisições

## Rota pública

Para um caminho que não corresponde a arquivo ou diretório físico:

```text
Apache
  -> .htaccess
  -> controle-includes.php?pg=<rota>
  -> front_includes.php
  -> page.php
  -> pages/<pagina>...
  -> containers/exe_system/<container>_head.php
  -> containers/exe_system/<container>_foot.php
```

`controle-includes.php` separa a rota e expõe parâmetros com a convenção interna usada pelo sistema. Em uma rota sem prefixo, `page.php` procura:

1. `pages/<pagina>.php`;
2. `pages/<pagina>/<pagina>.php`;
3. `pages/404.php`, se nada for encontrado.

A página informa o container por um comentário no próprio arquivo:

```html
<!--[CONTAINER-layout_react]-->
```

`page.php` inclui, nesta ordem:

1. cabeçalho gerado do container;
2. `<pagina>.css.php`, quando existe;
3. arquivo principal da página;
4. `<pagina>.vue.php`, quando existe;
5. rodapé gerado do container.

Ao final, `formataFullPageRet()` monta a resposta e substitui os marcadores internos. O placeholder `(-((--HEAD_INCLUDES--))-)` recebe título, Open Graph, Twitter Card e JSON-LD gerados por `\Sistema\Seo::head()` (ver [seo.md](seo.md)).

## Prefixos de rota

- sem prefixo: página pública;
- `action-`: inclui um arquivo de `action/`;
- `fn-`: chama uma função PHP;
- `adm-`: administração principal;
- `admsite-`: variante administrativa;
- `blank-`: resposta administrativa sem layout completo;
- `obj-`: fragmento reutilizável;
- `system-`: configuração ou geração interna.

Os prefixos dinâmicos são parte da superfície de segurança. Não crie novas funções ou métodos públicos supondo que só serão chamados pela interface esperada.

## Chamadas AJAX para classes

O frontend usa o fluxo:

```text
ajax_load_class(classe, metodo, parametros)
  -> POST /fn-ajax_load_class_function
  -> functions/functions.php
  -> instancia a classe solicitada
  -> chama o método solicitado
  -> devolve o resultado ao navegador
```

Arquivos centrais:

- `script/script_admin.js`;
- `functions/functions.php`;
- classes resolvidas por `autoload.php`.

Esta chamada escolhe classe e método dinamicamente. Todo método alcançável precisa validar autenticação, autorização, tipos, limites e formato dos parâmetros por conta própria.

## Ações

As rotas `action-<nome>` incluem `action/<nome>.php`. Há ações genéricas de CRUD e ações específicas, como cadastro e importação de eventos.

Antes de alterar uma ação:

1. identifique se ela é chamada por formulário tradicional, AJAX ou cron;
2. verifique se depende de variáveis globais criadas pelo bootstrap;
3. procure hooks nos metadados administrativos;
4. confirme se modifica mais de uma tabela e se precisa de transação;
5. valide upload, autorização e proteção contra CSRF.

## API REST

Fluxo:

```text
/rest/...
  -> rest.php
  -> front_includes.php
  -> rest/rest.php
  -> rest/api.php
  -> rotas auto_*.php
  -> classes/Backend/<versao>/*
```

A API usa Slim 2 copiado dentro de `rest/Slim/`.

Rotas centrais observadas:

- `GET /rest/token`;
- `POST /rest/v1/rfc`.

O endpoint RFC transforma um caminho lógico recebido no corpo em uma chamada de classe e método sob `classes/Backend/`. Ao incluir uma nova operação, implemente autorização dentro da operação e não confie apenas no roteamento.

## Rotas diretas

Alguns arquivos PHP na raiz ou em subpastas podem ser acessados diretamente, sem passar pelo roteador principal. Entre eles estão callbacks, crons, SOAP, PDF, upload, diagnóstico, publicação e testes. Consulte [Integrações e pontos de entrada](integrations-and-entrypoints.md) antes de considerar uma alteração isolada.
