# Páginas, Vue e objetos

Guia prático de como o frontend PHP monta páginas públicas, scripts Vue e fragmentos reutilizáveis (`obj_*` / `cp_*`).

Complementa [Convenções do frontend](frontend-conventions.md) e [Ciclo das requisições](request-lifecycle.md).

## Visão geral

```text
URL amigável  →  controle-includes.php  →  page.php
                                              │
                    containers/exe_system/<container>_head.php
                                              │
                         pages/<nome>/<nome>.css.php   (opcional)
                         pages/<nome>/<nome>.php       (HTML)
                         pages/<nome>/<nome>.vue.php   (Vue)
                                              │
                    containers/exe_system/<container>_foot.php
                                              │
                         formataFullPageRet()  →  HTML final
```

Há três peças distintas:

| Peça | Prefixo / pasta | Papel |
| --- | --- | --- |
| **Página** | `pages/<nome>/` ou `pages/<nome>.php` | Rota pública completa |
| **Objeto** | `pages/obj_*` | Fragmento reutilizável via `loadObj()` |
| **Componente Vue** | `pages/cp_*.php` | `Vue.component` + `x-template` |

Stack atual: **Vue 3** (CDN) + ponte `script/vue3-bridge.js`, que mantém `new Vue({ el })`, `Vue.component` e `Vue.use`.

---

## Regra: reutilizar via componente Vue

Qualquer trecho de interface ou comportamento que se **repita** (ou possa se repetir) ao longo do funcionamento principal do sistema deve ser extraído para um **componente Vue** (`pages/cp_*.php`), e não copiado entre páginas.

Isso inclui, por exemplo:

- cards de evento, listas, badges, filtros e botões de ação recorrentes;
- blocos de formulário compartilhados (upload, máscaras, confirmação);
- trechos de template com a mesma estrutura HTML + lógica Vue.

**Como aplicar**

1. Criar `pages/cp_<nome>.php` com `x-template` + `Vue.component('cp_<nome>', ...)`.
2. Carregar com `loadObj('cp_<nome>')` **antes** do `new Vue` da página.
3. Usar a tag `<cp_<nome> ...>` no HTML das páginas.

**Quando não usar `cp_*`**

- Conteúdo exclusivo de uma única página, sem perspectiva de reuso → pode ficar no `.php` / `.vue.php` da própria página.
- Fragmento de layout de servidor sem estado Vue (só HTML estático compartilhado) → preferir objeto `obj_*` via `loadObj()`.

**Objeto (`obj_*`) vs componente (`cp_*`)**

| | `obj_*` | `cp_*` |
| --- | --- | --- |
| Papel | Bloco de página/layout incluído pelo PHP | Peça reutilizável **dentro** de uma app Vue |
| Estado Vue | Pode ter app própria (`new Vue`) | Vive na app da página (props / eventos) |
| Uso típico | Topbar, rodapé, seções montadas no servidor | Card, item de lista, controle repetido |

No fluxo principal do produto (agenda, listagens, home), prefira **uma app Vue por página** + componentes `cp_*` reutilizáveis, em vez de duplicar markup.

---

## Convenções de nomes

| Tipo | Padrão | Exemplo |
| --- | --- | --- |
| Página em pasta | `pages/<nome>/<nome>.php` (+ `.css.php`, `.vue.php`) | `pages/home_v3/home_v3.php` |
| Página avulsa | `pages/<nome>.php` | `pages/termos.php`, `pages/404.php` |
| Objeto em pasta | `pages/obj_<nome>/obj_<nome>.php` (+ css/vue) | `obj_topbar/` |
| Objeto avulso | `pages/obj_<nome>.php` | `obj_cabecalho.php` |
| Componente Vue | `pages/cp_<nome>.php` | `cp_projeto.php` |
| Container (fonte) | `containers/<nome>.php` com `[CONTENT-PLACE]` | `layout_react.php` |
| Container (gerado) | `containers/exe_system/<nome>_head.php` + `_foot.php` | — |
| Marcador de layout | `<!--[CONTAINER-<nome>]-->` na página | `<!--[CONTAINER-layout_react]-->` |

O nome da pasta/arquivo **é** a rota: `pages/eventos/` → `/eventos`.

---

## Ciclo de uma página

1. `.htaccess` reescreve a URL para `controle-includes.php`.
2. Sem prefixo (`fn-`, `action-`, `adm-`, `obj-`…), cai em `page.php`.
3. `page.php` resolve:
   - `pages/<pg>/<pg>.php` (preferência), ou
   - `pages/<pg>.php`, ou
   - `pages/404.php`.
4. Lê o comentário `<!--[CONTAINER-...]-->` do arquivo da página.
5. Monta a resposta na ordem:
   1. `containers/exe_system/<container>_head.php`
   2. `<pg>.css.php` (somente se a página estiver em pasta)
   3. o `.php` da página
   4. `<pg>.vue.php` (somente se a página estiver em pasta)
   5. `containers/exe_system/<container>_foot.php`
6. `formataFullPageRet()` troca `ROOT/` pela URL base e injeta includes de head.

A raiz (`index.php`) redireciona para `home_v3`.

Arquivos-chave: `page.php`, `controle-includes.php`, `functions/systemFunctions.php` (`formataFullPageRet`, `loadObj`).

---

## Como criar uma página

### 1. Pasta e arquivos

```text
pages/minha_pagina/
  minha_pagina.php       # obrigatório
  minha_pagina.css.php   # opcional
  minha_pagina.vue.php   # opcional
```

### 2. HTML da página

```php
<!--[CONTAINER-layout_react]-->

<div id="app_minha_pagina" v-cloak>
  <h1>{{ titulo }}</h1>
  <button type="button" @click="salvar">Salvar</button>
</div>
```

Regras:

- O comentário `<!--[CONTAINER-...]>`` deve existir; sem ele o layout não resolve.
- Use um container que exista em `containers/` (ex.: `layout_react`, `padrao`, `master-site`).
- Se houver Vue, o `id` do root precisa bater com o `el` do `.vue.php`.

### 3. CSS opcional (`*.css.php`)

```php
<style>
  #app_minha_pagina { /* ... */ }
</style>
```

Só é incluído automaticamente para páginas em pasta.

### 4. Vue opcional (`*.vue.php`)

```php
<?php
// dados do servidor (opcional)
$titulo = 'Olá';
?>
<script>
  var app = new Vue({
    el: '#app_minha_pagina',
    data: {
      titulo: <?= json_encode($titulo) ?>
    },
    methods: {
      salvar: function () {
        // fetch(ROOT + 'action-...') ou ajax_load_class(...)
      }
    },
    mounted: function () {
      if (window.lucide) lucide.createIcons();
    }
  });
</script>
```

A ponte Vue 3 aceita:

- `data: { ... }` ou `data() { return { ... } }`
- `beforeDestroy` / `destroyed` (alias para `beforeUnmount` / `unmounted`)

Ordem importa: o HTML do root é impresso **antes** do script Vue.

### 5. Publicar a rota

Acesse `https://seu-dominio/minha_pagina`. Não há registro de rotas aparte do nome do arquivo/pasta.

---

## Como criar um objeto (`obj_*`)

Objetos são fragmentos reutilizáveis (cabeçalho, rodapé, blocos da home legada), carregados com `loadObj()`.

### Estrutura recomendada

```text
pages/obj_meu_bloco/
  obj_meu_bloco.php
  obj_meu_bloco.css.php   # opcional
  obj_meu_bloco.vue.php   # opcional
```

Exemplo de HTML:

```php
<div id="obj_meu_bloco" v-cloak>
  <p>{{ mensagem }}</p>
</div>
```

### Uso

```php
<?= loadObj('obj_meu_bloco') ?>

<?php
// parâmetros opcionais viram $_GET durante o include
loadObj('obj_meu_bloco', ['modo' => 'compacto']);
?>
```

Comportamento de `loadObj()`:

- Se existir pasta `pages/<obj>/<obj>.php` → inclui CSS → PHP → Vue.
- Senão → inclui só `pages/<obj>.php` (arquivo único).

Objetos costumam ser chamados:

- de dentro de containers (ex.: `padrao` com topbar/rodapé);
- de páginas compostas (ex.: `inicio` encadeando vários `loadObj`).

### Rota `obj-`

Existe prefixo `obj-` em `controle-includes.php` apontando para `obj.php`, mas esse caminho **não** replica o fluxo completo de `loadObj` (não carrega pasta/`*.vue.php` de forma confiável). Prefira `loadObj()` no servidor.

---

## Como criar um componente Vue (`cp_*`)

Componentes são registrados globalmente e usados como tags customizadas dentro de uma app Vue já montada.

### Arquivo

`pages/cp_exemplo.php`:

```php
<script type="text/x-template" id="cp_exemplo">
  <div class="cp-exemplo">
    <strong>{{ item.nome }}</strong>
    <button type="button" @click="acao">OK</button>
  </div>
</script>

<script>
  Vue.component('cp_exemplo', {
    template: '#cp_exemplo',
    props: {
      item: { type: Object, default: function () { return {}; } }
    },
    methods: {
      acao: function () {
        this.$emit('feito', this.item);
      }
    }
  });
</script>
```

### Carregar e usar

Na página ou objeto, **antes** do `new Vue` da app pai:

```php
<?php loadObj('cp_exemplo'); ?>
```

No HTML da app:

```html
<cp_exemplo :item="linha" @feito="onFeito"></cp_exemplo>
```

A ponte registra o componente no registry global e o aplica em cada `new Vue` / `createApp`.

---

## Containers

| Papel | Onde editar | Onde não editar |
| --- | --- | --- |
| Fonte | `containers/<nome>.php` | — |
| Gerado | — | `containers/exe_system/*` |

O arquivo-fonte tem o marcador `[CONTENT-PLACE]`. Com a pasta `system/` presente, `system/gera_arquivos_de_listagem.php` gera `_head.php` / `_foot.php` a partir desse split.

Containers observados: `layout_react`, `padrao`, `padrao-simples`, `master-site`, `admin-loja`.

`layout_react` **não usa React** — Vue 3 + Tailwind CDN + Lucide. É o layout das páginas modernas (`home_v3`, `eventos`, `cadastrar_evento`).

---

## Comunicação com o backend

Padrões frequentes nas páginas Vue:

1. **SSR + JSON** — PHP no `.vue.php` busca dados e injeta com `json_encode`.
2. **`fetch` para actions** — `fetch(ROOT + 'action-cadastrar_evento', { method: 'POST', ... })`.
3. **`ajax_load_class`** — legado/admin, via `/fn-ajax_load_class_function`.

Novas páginas da agenda cultural devem preferir (1) + (2).

---

## Checklist rápido

### Nova página

- [ ] Pasta `pages/<nome>/` com `<nome>.php`
- [ ] Comentário `<!--[CONTAINER-...]-->`
- [ ] Root Vue com `id` estável (se houver interatividade)
- [ ] `.vue.php` com `el` correspondente
- [ ] Testar a URL `/<nome>`

### Novo objeto

- [ ] Prefixo `obj_`
- [ ] Preferir pasta com trio opcional
- [ ] Root com `id` único se tiver Vue próprio
- [ ] Carregar com `loadObj('obj_...')`

### Novo componente

- [ ] Prefixo `cp_`
- [ ] `x-template` + `Vue.component`
- [ ] `loadObj('cp_...')` antes do `new Vue` pai
- [ ] Props explícitas; evitar depender de estado global
- [ ] Usado em vez de copiar o mesmo HTML/Vue em mais de uma página

---

## Melhorias sugeridas

1. **Corrigir ou descontinuar `obj.php`**  
   A rota `obj-` não espelha `loadObj()` e ainda usa `ob_end_clean()` de forma inadequada. Ou alinhar ao fluxo completo, ou documentar como legado e não usar.

2. **Unificar o “trio” também para páginas avulsas**  
   Hoje `.css.php` / `.vue.php` só entram automaticamente se a página estiver em pasta. Páginas em arquivo único ficam inconsistentes.

3. **Renomear `layout_react`**  
   O nome sugere React; a stack é Vue/Tailwind. Um alias (`layout_vue` / `layout_site`) reduz confusão.

4. **Uma app Vue por página**  
   Vários `obj_*` com `new Vue({ el })` criam apps isoladas (sem estado compartilhado). Preferir um root na página + componentes `cp_*`, ou Composition API / Pinia se no futuro houver bundler.

5. **Não gerar `exe_system` em toda request de produção**  
   Com `system/` presente, a geração em runtime pode sobrescrever arquivos e gerar race conditions. Em deploy, gerar no build/release e servir artefatos estáveis.

6. **Padronizar IDs e nomes**  
   Mistura de `#app_home`, `#app-cadastro`, `#app_eventos`. Adotar um padrão (`#app-<rota>`) facilita busca e docs.

7. **Atualizar docs legados**  
   Alguns arquivos ainda citam Vue 2; a runtime atual é Vue 3 + bridge.

8. **Separar legado de produto**  
   Coexistem `inicio` / `home_v3`, containers Bootstrap antigos e layout Tailwind. Marcar páginas legado vs ativas evita editar o arquivo errado.

9. **Sanitizar `v-html`**  
   Onde a IA ou conteúdo externo entra no DOM, sanitizar no servidor antes de expor ao Vue.

10. **Tipar contratos AJAX**  
    Documentar por action o JSON esperado/retornado; reduz quebra entre `.vue.php` e `action/*.php`.

---

## Referências rápidas

| Tema | Arquivo |
| --- | --- |
| Resolução de página e includes | `page.php` |
| Roteamento por prefixo | `controle-includes.php` |
| `loadObj` | `functions/systemFunctions.php` |
| Substituição `ROOT/` | `formataFullPageRet()` em `functions/systemFunctions.php` |
| Ponte Vue 3 | `script/vue3-bridge.js` |
| Geração de containers | `system/gera_arquivos_de_listagem.php` |
| Página moderna de referência | `pages/home_v3/` |
| Listagem com scroll | `pages/eventos/` |
| Formulário + `fetch` | `pages/cadastrar_evento/` |
| Componente de exemplo | `pages/cp_projeto.php` |
