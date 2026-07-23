# Pasta `system/` — geração do painel administrativo

A pasta `system/` é responsável por modelar e **gerar os módulos da área restrita** (`adm-*`): formulários CRUD, menus, permissões e páginas administrativas derivadas.

Complementa [Administração e arquivos gerados](generated-admin.md) e [Autenticação e sessões](authentication-and-sessions.md).

## Dois papéis distintos

| Papel | O quê | Rota / entrada |
| --- | --- | --- |
| **IDE de metadados** | UI legada para cadastrar formulários, campos e menus | prefixo `system-*` (ex.: `system-form`) |
| **Geradores** | Transformam metadados MySQL em PHP em disco | `SYSTEM_CODEGEN=1` ou `/system-rebuild` |

O painel do dia a dia do usuário admin é o Soft UI (`adm-*` → `adm.php`).  
`system-*` é a ferramenta de configuração que alimenta os geradores.

```text
[MySQL: system_form / system_inputs / admin_menu*]
              │
              │  (SYSTEM_CODEGEN=1 ou /system-rebuild)
              ▼
┌─────────────────────────────────────┐
│ gera_definicoes_de_tabelas.php      │ → tables/def_*.php + _admin_*
│ gera_arquivos_de_listagem.php       │ → __list_functions + exe_system
└─────────────────────────────────────┘
              │
              ▼
[adm.php → Soft UI → Menu + admin_content CRUD]
              │
              ▼
[action/*_global + hooks em functions/auto_*.php]
```

---

## Mapa de arquivos em `system/`

### Geradores

| Arquivo | Função |
| --- | --- |
| `gera_definicoes_de_tabelas.php` | Lê `system_form` + `system_inputs` + menus → `tables/def_*.php`, `_admin_def_tables.php`, `_admin_menu.php`, `_admin_permissoes.php` |
| `gera_arquivos_de_listagem.php` | (1) `functions/auto_*.php` → `__list_functions.php`; (2) `admin/pages/*` → `admin/exe_system/*`; (3) `containers/*.php` → `containers/exe_system/*_head/_foot` |

### IDE (modelagem)

| Arquivo | Função |
| --- | --- |
| `index.php` | Redirect para `system-inicio` |
| `head.php` / `footer.php` | Shell HTML da IDE |
| `css-system.css` / `script.js` | Estilo e JS legados (jQuery / prettyPhoto) |
| `page_auxiliar_sistema.php` | Barra flutuante de atalhos (Ctrl+Espaço); também pode aparecer no fluxo `adm` |
| `atualiza_posicao.php` | AJAX: atualiza ordem de `system_inputs` |
| `copia_formulario.php` | Stub incompleto |
| `pages/inicio.php` | Home da IDE |
| `pages/form.php` | Lista formulários |
| `pages/addForm.php` | Cria/edita formulário + sync de menu |
| `pages/delform.php` | Remove form, inputs e submenu |
| `pages/formInput.php` | Lista/ordena campos |
| `pages/addinput.php` | CRUD de campo |
| `pages/formGraph.php` | Editor gráfico de layout do form |
| `pages/page_icons.php` | Placeholder de ícones |
| `pages/rebuild.php` | Regenera artefatos (`/system-rebuild`) |

---

## Quando a geração roda

Flags em `config.php` (via `.env` / compose):

| Flag | Padrão em `development` | Padrão em `production` | Efeito |
| --- | --- | --- | --- |
| `SYSTEM_CODEGEN` | 1 | 0 | regenera artefatos em **toda** request |
| `SYSTEM_IDE_ENABLED` | 1 | 0 | libera rotas `system-*` (exceto rebuild) |
| `SYSTEM_AUX_BAR` | 1 | 0 | barra auxiliar no `adm` / `system` |

Em `controle-includes.php`, se a pasta `system/` existir:

1. carrega `system/codegen_helpers.php`;
2. se `SYSTEM_CODEGEN=1`, executa os geradores;
3. define `IS_OCA`.

Regeneração sob demanda (sempre autenticada, mesmo com IDE/codegen desligados):

- URL: `/system-rebuild` → `system/pages/rebuild.php`

Em produção, preferir `APP_ENV=production` (codegen off) + rebuild no release/CI ou via endpoint.

Helpers: `system_atomic_write()` grava com arquivo temporário + `rename` (flock) em `tables/`, `exe_system/` e `__list_functions.php`.

---

## Pipeline detalhado

### A — Definições de tabela

`gera_definicoes_de_tabelas.php`:

1. Lê todos os `system_form`.
2. Para cada form, monta `$TABLE_DEF[...]` (flags CRUD, hooks, SQL, joins, botões extras, filtros).
3. Lê `system_inputs` ordenados → `$TABLE_DEF_INPUT[campo][...]`.
4. Se houver SQL embutido no metadado (base64), gera funções PHP no `def_*.php`.
5. Grava `tables/def_{arquivo_def}.php` via `system_atomic_write()`.
6. Gera índice `tables/_admin_def_tables.php` (id do form → arquivo).
7. Gera catálogo `tables/_admin_permissoes.php`.
8. Lê `admin_menu` / `admin_submenu` / `admin_menu_submenu` → `tables/_admin_menu.php`.

Hooks típicos no def (nomes de funções em `functions/auto_*.php`):

- `preinsert`, `preupdate`, `predelete`
- `posinsert`, `posupdate`, `posdelete`
- `pre_listagem`, `pos_listagem`

### B — Listagens, páginas admin e containers

`gera_arquivos_de_listagem.php`:

1. **auto_*** — varre `functions/`, inclui só arquivos `auto_*.php` → `functions/__list_functions.php`.
2. **admin/pages** — copia para `admin/exe_system/`, resolvendo `[obj=nome]` para includes.
3. **containers** — split em `[CONTENT-PLACE]` → `containers/exe_system/{nome}_head.php` e `_foot.php`.

### C — Metadados via IDE

Ao salvar em `system/pages/addForm.php`:

- upsert em `system_form`;
- cria/atualiza `admin_menu`, `admin_submenu` e vínculo N:N;
- na **próxima request**, o gerador A regenera menu e defs.

---

## Mapa fonte → derivado

| Fonte (editar isto) | Saída (não editar) |
| --- | --- |
| `system_form` / `system_inputs` | `tables/def_*.php` |
| ids dos forms | `tables/_admin_def_tables.php` |
| forms + botões extras | `tables/_admin_permissoes.php` |
| `admin_menu*` | `tables/_admin_menu.php` |
| `functions/auto_*.php` | `functions/__list_functions.php` |
| `admin/pages/*.php` | `admin/exe_system/*.php` |
| `containers/*.php` | `containers/exe_system/*_head/_foot.php` |
| `admin/filtro/{form}__*.php` | referenciado no `$TABLE_DEF["filtro_adicional"]` |

**Não gerados por `system/`, mas usados no painel:** Soft UI (`admin/template/...`), `adm.php`, `admin/inicio.php`, `admin_header.php`, `menu.php`, `admin_content.php`, `action/*_global.php`, `functions/systemFunctions*.php`.

---

## Runtime do painel Soft UI

```text
URL adm-* → controle-includes.php → adm.php
  → checa_acesso_system()
  → getInfoItem(?item=) carrega def_*.php
  → admin/inicio.php (sidenav Soft UI + navbar + body)
  → admin_content.php (CRUD genérico ou página custom)
```

Prioridade de conteúdo em `admin_body` / `admin_content`:

1. Página custom `pages/{slug}/` (casos híbridos).
2. `admin/exe_system/{pagina}.php` (página admin gerada).
3. `?item={id}` → CRUD genérico (`listFormNew` / `loadFormNew` / `viewForm`) com checagem de `$PERFIL_PERMISSOES`.

Menu: `_admin_menu.php` → `\Sistema\Admin\Menu::getMenu()` → Vue no sidenav, filtrado por permissões do perfil.

Links típicos: `ROOT/adm-home?item={form_id}`.

---

## Hooks, menus e permissões

### Hooks

- Declarados no metadado (`system_form`) como nomes de função.
- Implementados em `functions/auto_*.php`.
- Disparados por `action/insert_global.php`, `edit_global.php`, `delete_global.php` e listagem.

### Menus

- Persistidos em `admin_menu` / `admin_submenu` / `admin_menu_submenu`.
- Artefato: `_admin_menu.php`.
- Ícones: mapa em `classes/Sistema/Admin/Menu.php` (nem todos os itens têm ícone).

### Permissões

1. Perfil serializa `menu/add/edit/del/view/list/...` (`functions/auto_perfil.php`).
2. Guarda em `system_perfil.permissoes`.
3. Runtime: `$PERFIL_PERMISSOES` filtra menu e botões CRUD.
4. `_admin_permissoes.php` é catálogo para montar a UI de perfil, não a ACL em si.

---

## Como criar / alterar um módulo admin

1. Preferir a IDE `system-*` **ou** editar metadados no MySQL com cuidado.
2. Definir tabela real + campos em `system_form` / `system_inputs`.
3. Se precisar de lógica (senha, validação, side-effects): `functions/auto_<assunto>.php` + nome do hook no form.
4. Se precisar de tela custom (não CRUD genérico): fonte em `admin/pages/<pagina>.php` (não em `exe_system/`).
5. Recarregar qualquer URL do sistema (com `system/` presente) para regenerar, ou documentar um rebuild controlado.
6. Testar listagem, cadastro, edição, exclusão, permissões e hooks.

**Nunca** implementar mudança permanente só em `tables/` ou `admin/exe_system/` — será sobrescrita.

---

## Riscos operacionais

- Geração em runtime exige escrita sobre PHP.
- Requests concorrentes podem ler artefatos pela metade (`_admin_menu` / vários `exe_system` sem flock completo).
- Trechos PHP via base64 no metadado = superfície de injeção se o banco for comprometido.
- Deploy com `system/` ativo + banco errado regenera o painel incorreto.
- `page_auxiliar_sistema` no fluxo `adm` expõe atalhos de “ferramentas de sistema” no painel operacional.
- Nem todas as entidades do produto (ex.: agenda `eventos`/`tags`) passam pelo gerador — algumas vivem só em `classes/Sistema/`.

---

## Layout e UX observados

1. **Dois design systems:** Soft UI no `adm-*` vs CSS legado na IDE `system-*`.
2. **CSS empilhado:** Soft UI + `css-admin.css` + jQuery UI + prettyPhoto — peso e conflitos.
3. **CRUD híbrido:** classes Soft UI misturadas com botões/loaders/confirm legado.
4. **Menu:** ícones incompletos, pouco uso de collapse, ruído de debug possível.
5. **Kit Soft UI vendor** completo em `admin/template/` (docs, scss, gulp) — ruído em deploy.
6. **Placeholder `ROOT/`** depende de `formataFullPageRet`; fácil quebrar fora desse pipeline.

---

## Entidades fora do gerador

Estas partes da agenda cultural **não** passam por `system_form` / CRUD gerado — vivem em `classes/Sistema/`, páginas públicas e actions:

- `eventos`, `tags`, `evento_tags`
- fluxos de home, detalhe de evento, chat IA, cron de importação

Não tente modelá-las na IDE `system-*` sem alinhar o domínio. Módulos admin tipicamente gerados: configurações, perfis, pessoas, clientes, links, estabelecimentos (ver [modules.md](modules.md)).

---

## Melhorias aplicadas

### Arquitetura / operação

1. **Codegen condicional** — `SYSTEM_CODEGEN` (off em production); endpoint `/system-rebuild`.
2. **Atomicidade** — `system_atomic_write()` (tmp + rename + flock).
3. **Hooks** — diretriz: lógica em `functions/auto_*.php`; evitar PHP via base64 no MySQL.
4. **IDE separada** — `SYSTEM_AUX_BAR` / `SYSTEM_IDE_ENABLED` off em production; barra auxiliar não entra no `adm` operacional.
5. **Contrato** — mudança permanente = metadado + `auto_*` + `admin/pages` fonte.

### Layout Soft UI

1. **`css-admin-soft.css`** — overrides alinhados ao Soft UI sobre o legado.
2. **CRUD** — botão Adicionar e submit no padrão Soft UI (`btn bg-gradient-info`).
3. **Menu** — seções colapsáveis, ícones ampliados, sem `console.log`.
4. **Deploy** — `.dockerignore` exclui docs/demos/scss/gulp do template Soft UI.
5. **IDE** — restrita por flag em production; `system-rebuild` permanece disponível autenticado.

---

## Checklist rápido

Ao trabalhar em um módulo do painel:

- [ ] Identificar form/tabela em metadados (não só em `tables/`)
- [ ] Confirmar se a tela é CRUD genérico (`?item=`) ou página custom (`admin/pages`)
- [ ] Localizar hooks em `functions/auto_*.php`
- [ ] Verificar permissões de perfil / menu
- [ ] Editar fonte, não derivado
- [ ] Testar CRUD + permissões após regenerar

---

## Referências

| Tema | Onde |
| --- | --- |
| Entrada admin | `adm.php`, `admin/inicio.php` |
| Conteúdo CRUD | `admin/admin_content.php`, `admin/admin_body.php` |
| Menu | `admin/menu.php`, `classes/Sistema/Admin/Menu.php` |
| Geradores | `system/gera_definicoes_de_tabelas.php`, `system/gera_arquivos_de_listagem.php` |
| Bootstrap codegen | `controle-includes.php` + flags `SYSTEM_*` / `/system-rebuild` |
| Helpers | `system/codegen_helpers.php` (`system_atomic_write`, `system_run_codegen`) |
| Actions genéricas | `action/insert_global.php`, `edit_global.php`, `delete_global.php` |
| Docs relacionados | [generated-admin.md](generated-admin.md), [admin-form-components.md](admin-form-components.md), [authentication-and-sessions.md](authentication-and-sessions.md) |
