# Convenções do frontend

## Stack

O frontend é servido diretamente pelo PHP. Não há `package.json` nem pipeline de build na raiz.

Tecnologias encontradas:

- Vue 2.6.14;
- jQuery e jQuery Migrate;
- Tailwind CSS via CDN no frontend atual;
- Bootstrap e Soft UI Dashboard no administrativo;
- Lucide, Chart.js, SortableJS, Vue.Draggable, SweetAlert2, v-mask, Bootbox, Froala e jQuery UI.

## Estrutura de uma página

Para uma página chamada `evento`, procure:

```text
pages/evento/evento.php
pages/evento/evento.css.php
pages/evento/evento.vue.php
```

- `.php`: HTML e dados renderizados pelo servidor;
- `.css.php`: estilos opcionais;
- `.vue.php`: JavaScript e instância Vue;
- comentário `<!--[CONTAINER-nome]-->`: escolhe o layout.

Também existem páginas em arquivo único, como `pages/termos.php`.

## Containers

`containers/` guarda fontes de layout. `containers/exe_system/` contém cabeçalhos e rodapés gerados usados por `page.php`.

O container atual `containers/layout_react.php`, apesar do nome, usa Vue 2, Tailwind, jQuery e Lucide; não usa React.

Antes de alterar um arquivo em `containers/exe_system/`, localize sua fonte ou gerador. Alterações diretas podem ser sobrescritas.

## Objetos reutilizáveis

Fragmentos reutilizáveis aparecem em `pages/obj_*` e podem ser carregados pela infraestrutura de objetos, incluindo rotas com prefixo `obj-` e helpers como `loadObj()`.

Objetos seguem a mesma composição opcional de PHP, CSS e Vue.

## Comunicação com o backend

Padrão frequente:

```text
Vue/jQuery
  -> ajax_load_class()
  -> /fn-ajax_load_class_function
  -> classe PHP em classes/
  -> método escolhido pelo cliente
```

O código cliente está principalmente em:

- arquivos `*.vue.php`;
- `script/script_admin.js`;
- `js/` e `script/`.

Uma mudança no nome de classe, método ou formato do retorno deve ser sincronizada entre PHP e JavaScript.

## Estado e renderização

Vue é instanciado diretamente na página, sem módulos ES e sem componentes compilados. Dados podem ser:

- impressos pelo PHP no HTML/JavaScript;
- buscados por AJAX;
- mantidos somente no navegador.

Respostas de IA e descrições podem chegar a diretivas como `v-html`. Conteúdo HTML externo ou gerado deve ser sanitizado antes da renderização.

## Recursos estáticos e uploads

Recursos estão espalhados por `assets/`, `css/`, `js/`, `script/`, `images/`, `img/`, `fonts/` e `theme/`.

Uploads são gravados dentro da árvore pública, principalmente em `images/upload/` e `arquivos/`. Não confie apenas na extensão informada pelo nome do arquivo.

## Checklist para uma página

1. Localizar os três arquivos da página e seu container.
2. Procurar objetos `obj_*` incluídos.
3. Identificar chamadas AJAX e a classe/método PHP de destino.
4. Confirmar se o arquivo de destino é fonte ou gerado.
5. Verificar HTML inserido por `v-html`.
6. Testar com bibliotecas carregadas pelo container real.
7. Evitar sintaxe que dependa de bundler, módulos ou Vue 3.
