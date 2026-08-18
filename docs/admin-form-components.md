# Componentes de formulário do admin (`componente/`)

Campos do CRUD administrativo podem carregar um **widget PHP** da pasta `componente/` em vez do input HTML padrão.

Isso é **distinto** de:

| Pasta / padrão | Escopo |
| --- | --- |
| `componente/` | Widgets de campo do **painel admin** (CRUD) |
| `pages/cp_*` | Componentes Vue do **frontend** (`loadObj`) |
| `pages/obj_*` | Fragmentos PHP do frontend (`loadObj`) |
| `[obj=...]` em `admin/pages` | Include de páginas admin → `admin/exe_system/` |

Ver também [system-admin.md](system-admin.md), [generated-admin.md](generated-admin.md) e [pages-vue-objetos.md](pages-vue-objetos.md).

---

## Visão geral

```text
system_inputs.mapear_componente / parametros_componente
        │
        │  (SYSTEM_CODEGEN ou /system-rebuild)
        ▼
tables/def_*.php  →  $TABLE_DEF_INPUT[campo]["mapear_componente"]
        │
        ▼
ComponenteLoader::render / runAfterSave
        │
        ▼
include componente/{slug}.php
new Componente__{slug}
        │
        ├── exibe()           → formulário (add/edit)
        ├── listagem()        → célula da grid (+ $PARAM)
        ├── view()            → detalhe (+ $PARAM)
        └── afterInsert|afterUpdate|save|salvar|update
```

**Não há gerador que copie `componente/`.** Os arquivos são fonte viva. O gerador só grava o **nome** e os parâmetros em `tables/def_*.php`.

---

## Loader centralizado

| Classe | Papel |
| --- | --- |
| `Sistema\Admin\ComponenteLoader` | parse de params, validação de slug, include, render, pós-save |
| `Sistema\Admin\ComponenteCampo` | base opcional para widgets novos |

Uso no CRUD:

```php
\Sistema\Admin\ComponenteLoader::render($li, 'exibe'|'listagem'|'view', $tabela, $valor, $id);
\Sistema\Admin\ComponenteLoader::runAfterSave($_POST['componente__mapear'], $id, $tabela, 'insert'|'update');
```

- Slug só `[a-zA-Z0-9_]+` (bloqueia path traversal).
- `$PARAM` sempre montado (mesmo sem `parametros_componente`).
- Hidden `componente__mapear[]` emitido **por campo** (não só no 1º include da classe).
- Aridade dos métodos legados respeitada via `ReflectionMethod` (PHP 8+).

---

## Metadado (como se configura)

Na IDE `system-addinput`:

| Campo DB | UI | Função |
| --- | --- | --- |
| `mapear_componente` | “Componente de campo” | basename em `componente/` (sem `.php`) |
| `parametros_componente` | “Parâmetros do componente” | linhas `chave=valor` |

O select lista só `.php` na raiz com slug válido. Valor inválido é descartado no save.

O `type` do input continua no metadado; com `mapear_componente` o render padrão é substituído.

---

## Contrato da classe

Arquivo: `componente/{slug}.php`  
Classe: `Componente__{slug}`  
Novos widgets podem estender `\Sistema\Admin\ComponenteCampo`.

### Métodos

| Método | Quando | Assinatura |
| --- | --- | --- |
| `exibe` | Form add/edit | `exibe($tabela, $valor, $PARAM)` |
| `listagem` | Grid | `listagem($tabela, $id, $valor, $PARAM = null)` |
| `view` | Detalhe | `view($tabela, $valor, $PARAM = null)` |
| `afterInsert` | Pós-insert (preferido) | `afterInsert($id, $tabela, $campo, $PARAM = null)` |
| `afterUpdate` | Pós-update (preferido) | `afterUpdate($id, $tabela, $campo, $PARAM = null)` |
| `save` / `salvar` / `update` | Legado | ainda despachados pelo loader |

Ordem no insert/edit global: **pre\* → DB → componentes → pos\***.

### Parâmetros (`$PARAM`)

1. Parse de `parametros_componente` (primeiro `=` por linha; trim de `\r`).
2. Sempre: `nome_campo`, `campo_tabela`.
3. Globals paralelas (legado): `$MAP['campo_tabela']`, `$MAP['linha_input']`.

---

## Inventário atual (`componente/`)

| Arquivo | Uso típico |
| --- | --- |
| `auto_jcrop.php` | Upload/crop de imagem |
| `upload_imagem.php` / `_padrao` / `_garotas` | Upload de imagem. Em `_padrao`: dropzone (arrastar, clicar, colar), prévia centralizada e ações Trocar/Remover |
| `upload_arquivo.php` | Upload de arquivo |
| `galeria_de_imagens.php` | Galeria |
| `editor_de_texto.php` | Rich text (Froala) |
| `coordenadas_endereco.php` | Mapa / coordenadas |
| `url_amigavel.php` | Gera `friendly_url` no pós-save |
| `auto_items_permissoes.php` | UI de permissões de perfil |
| `gerador_de_parcelas.php` | Parcelas (legado) |

---

## Como criar um componente novo

1. Criar `componente/meu_widget.php` com `class Componente__meu_widget` (opcional: `extends \Sistema\Admin\ComponenteCampo`).
2. Implementar `exibe()`; opcionalmente `listagem`, `view`, `afterInsert`/`afterUpdate`.
3. Na IDE, escolher o componente e parâmetros `chave=valor`.
4. Regenerar defs (`SYSTEM_CODEGEN` ou `/system-rebuild`).
5. Testar add, edit, list, view e pós-save.

**Diretriz:** campo especial do painel → `componente/`. UI repetível do site → `pages/cp_*`.

---

## Melhorias aplicadas

- Loader + base class em `classes/Sistema/Admin/`
- Bug `viewForm` (aspas) corrigido
- Hidden por campo; parse de params robusto; validação de slug
- Insert/edit unificados via `runAfterSave` (save/salvar/update + after*)
- Ordem: componentes antes de `posinsert`/`posupdate`
- IDE: labels e validação de slug
- Soft UI: labels/inputs do form no `css-admin-soft.css`

### Pendências (não bloqueantes)

- Migrar widgets legados para `extends ComponenteCampo` / `afterInsert|afterUpdate`
- Transação DB se `afterSave` falhar
- Validação MIME compartilhada para uploads
- Deprecar de vez `systemFunctions_forms.php` (legado)

---

## Checklist rápido

- [ ] Confirmar `mapear_componente` no metadado / `def_*` (consulta)
- [ ] Abrir `componente/{slug}.php` e a classe `Componente__{slug}`
- [ ] Ver se usa `$PARAM`, `$MAP` ou ambos
- [ ] Testar add, edit, list, view
- [ ] Pós-save: hidden `componente__mapear[]` + método after*/save/update
- [ ] Não editar só o `def_*`

---

## Referências

| Tema | Onde |
| --- | --- |
| Loader / contrato | `classes/Sistema/Admin/ComponenteLoader.php`, `ComponenteCampo.php` |
| Render form/lista/view | `functions/systemFunctions_formsNew.php` |
| Actions | `action/insert_global.php`, `action/edit_global.php` |
| IDE do campo | `system/pages/addinput.php` |
| Widgets | `componente/*.php` |
| Soft UI forms | `admin/css-admin-soft.css` |
| Confirms / toasts / loading | `admin/js/admin-ui.js` (`conf`, `wait`, `AdminUI`) |
