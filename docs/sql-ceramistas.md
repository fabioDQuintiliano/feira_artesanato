# SQL — conteúdo do site de ceramistas

Arquivo de acumulação dos SQLs para criar e popular as tabelas usadas pelo **2º Encontro de Ceramistas** (`/ceramistas` e `/expositor/{slug}`).

Como usar:

1. Executar os blocos na ordem em que aparecem.
2. Imagens de expositores ficam em `images/upload/` (caminhos relativos nas colunas, sem a pasta `images/upload/`). Cartazes musicais podem usar `ceramistas/...` (`images/ceramistas/`) ou o mesmo `images/upload/`.
3. `grupo = 'artesao'` alimenta a seção **Expositores**; `grupo = 'alimentacao'` alimenta **Sabores**. Atrações musicais vêm da tabela `atracoes_musicais`. Data, local e WhatsApp do site vêm de `ceramistas_config`.

Não editar `tables/` nem `_public/` / `_publish/` para aplicar estes SQLs.

---

## 1. Expositores

Tabelas usadas por `classes/Sistema/Expositores.php`.

Campos lidos no site:

| Coluna | Uso no site |
| --- | --- |
| `nome` | Título do card e da página de perfil |
| `slug` | URL pública `/expositor/{slug}` |
| `resumo` | Texto curto no card |
| `descricao` | Texto longo do perfil (aceita quebras de linha) |
| `categoria` | Rótulo acima do nome (ex.: Cerâmica, Pizza artesanal) |
| `grupo` | `artesao` ou `alimentacao` |
| `logo` | Miniatura / logo no card de sabores |
| `foto_destaque` | Foto principal do card e do perfil |
| `instagram` | Handle sem `@` (o site monta `https://instagram.com/{handle}`) |
| `whatsapp` | DDD + número, só dígitos (o site monta `https://wa.me/55{numero}`) |
| `ordem` | Ordenação na listagem |
| `ativo` | `1` = visível no site |

`txtid` é identificador interno único (não aparece na URL). `slug` precisa ser único, minúsculo, só `a-z`, `0-9`, `_` e `-`.

### 1.1 Criar tabelas

```sql
CREATE TABLE IF NOT EXISTS `expositores_grupos` (
  `id` VARCHAR(40) NOT NULL,
  `nome` VARCHAR(80) NOT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `expositores_grupos` (`id`, `nome`, `ordem`) VALUES
  ('artesao', 'Artesãos / cerâmica', 1),
  ('alimentacao', 'Alimentação e cerveja', 2);

CREATE TABLE IF NOT EXISTS `expositores` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `txtid` VARCHAR(80) NOT NULL,
  `nome` VARCHAR(180) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `resumo` VARCHAR(400) DEFAULT NULL,
  `descricao` TEXT NOT NULL,
  `categoria` VARCHAR(80) DEFAULT NULL,
  `grupo` VARCHAR(40) NOT NULL DEFAULT 'artesao',
  `logo` VARCHAR(255) DEFAULT NULL,
  `foto_destaque` VARCHAR(255) DEFAULT NULL,
  `instagram` VARCHAR(120) DEFAULT NULL,
  `whatsapp` VARCHAR(40) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `edited_on` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_expositores_txtid` (`txtid`),
  UNIQUE KEY `uk_expositores_slug` (`slug`),
  KEY `idx_expositores_ativo_ordem` (`ativo`, `ordem`),
  KEY `idx_expositores_grupo` (`grupo`, `ativo`, `ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `expositores_fotos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `expositor_id` INT UNSIGNED NOT NULL,
  `arquivo` VARCHAR(255) NOT NULL,
  `legenda` VARCHAR(255) DEFAULT NULL,
  `ordem` INT NOT NULL DEFAULT 0,
  `destaque` TINYINT(1) NOT NULL DEFAULT 0,
  `created_on` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expositores_fotos_expositor` (`expositor_id`, `ordem`),
  CONSTRAINT `fk_expositores_fotos_expositor`
    FOREIGN KEY (`expositor_id`) REFERENCES `expositores` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 1.2 Modelo para cadastrar um expositor

Troque os valores e rode. O `id` gerado entra nas fotos.

```sql
INSERT INTO `expositores` (
  `txtid`, `nome`, `slug`, `resumo`, `descricao`,
  `categoria`, `grupo`, `logo`, `foto_destaque`,
  `instagram`, `whatsapp`, `ordem`, `ativo`
) VALUES (
  MD5(CONCAT('expositor-', 'slug_do_expositor')),
  'Nome do ateliê ou marca',
  'slug_do_expositor',
  'Frase curta para o card (até ~400 caracteres).',
  'Texto longo do perfil.\n\nPode ter vários parágrafos.',
  'Cerâmica',
  'artesao',                          -- artesao | alimentacao
  'expositores/slug_do_expositor/logo.jpeg',
  'expositores/slug_do_expositor/foto_01.jpeg',
  'handle_instagram',                 -- sem @
  '35999999999',                      -- DDD + número, só dígitos; NULL se não houver
  10,                                 -- menor número aparece primeiro
  1
);

SET @expositor_id = LAST_INSERT_ID();

INSERT INTO `expositores_fotos` (`expositor_id`, `arquivo`, `legenda`, `ordem`, `destaque`) VALUES
  (@expositor_id, 'expositores/slug_do_expositor/foto_01.jpeg', 'Foto principal', 0, 1),
  (@expositor_id, 'expositores/slug_do_expositor/foto_02.jpeg', 'Peça', 1, 0);
```

A foto com `destaque = 1` deve coincidir com `foto_destaque` do expositor. Coloque os arquivos em:

```text
images/upload/expositores/{slug}/logo.jpeg
images/upload/expositores/{slug}/foto_01.jpeg
```

### 1.3 Atualizar um expositor já cadastrado

```sql
UPDATE `expositores`
SET
  `nome` = 'Nome atualizado',
  `resumo` = 'Novo resumo',
  `descricao` = 'Nova descrição',
  `categoria` = 'Cerâmica',
  `grupo` = 'artesao',
  `instagram` = 'handle_instagram',
  `whatsapp` = '35999999999',
  `ordem` = 10,
  `ativo` = 1
WHERE `slug` = 'slug_do_expositor';
```

Para esconder do site sem apagar:

```sql
UPDATE `expositores` SET `ativo` = 0 WHERE `slug` = 'slug_do_expositor';
```

### 1.4 Lote `tmp/pt2`

Importação das pastas em `tmp/pt2` (descrição, Instagram, WhatsApp e fotos). Modelos (`_modelo`, cópias) foram ignorados. O Atelier Iara Nicola já existia (`slug` `iara`).

```bash
docker compose exec -u www-data app php migrate_ceramistas_pt2.php
```

O script gera o `slug` com `url_amigavel(nome)`, copia as imagens para `images/upload/expositores/{slug}/` (`logo.jpeg` + `foto_01.jpeg`…) e grava galeria em `expositores_fotos`. Idempotente: se o slug já existe, não duplica.

| Nome | Slug (URL) | Grupo | Instagram | WhatsApp |
| --- | --- | --- | --- | --- |
| Nino Cerâmica | `nino_ceramica` | artesão | nino.ceramica | — |
| Telma Lopes | `telma_lopes` | artesão | telmalopes_croche | — |
| Mãos de Carolina | `maos_de_carolina` | artesão | maosdecarolinaceramica | — |
| PRYA Cerâmicas | `prya_ceramicas` | artesão | pryaceramicas | — |
| Lume Saboaria Artesanal | `lume_saboaria_artesanal` | artesão | lume.saboaria.artesanal | — |
| Artesanal Tropical | `artesanal_tropical` | artesão | atelieartesanaltropical | — |
| Msytic Artesanatos | `msytic_artesanatos` | artesão | mystic.artesanatos | — |
| Marici Cerâmica | `marici_ceramica` | artesão | — | — |
| Vânia | `vania` | artesão | — | — |
| Atelie Rita Nogueira | `atelie_rita_nogueira` | artesão | atelie_ritanogueira2 | — |
| Empório Quem Comeu o Meu Queijo | `emporio_quem_comeu_o_meu_queijo` | alimentação | — | — |
| Flor de Trigo | `flor_de_trigo` | alimentação | florde_trigo | 16991283335 |
| Café JC - Elizabeth Rezende | `cafe_jc_elizabeth_rezende` | alimentação | — | — |
| Art Sabor Confeitaria | `art_sabor_confeitaria` | alimentação | janaina_artsabor_confeitaria | — |

Cerâmica, crochê, costura e saboaria foram para **Expositores**. Confeitaria, pães, queijo e café foram para **Sabores**.

---

## 2. Módulo admin — cadastro de expositores

O painel não tem um controller por entidade. Cada módulo é metadado em `system_form` + `system_inputs`, menu em `admin_menu*` e permissões em `system_perfil`. O gerador em `system/` cria `tables/def_*.php`. **Não editar `tables/`.**

Este módulo cria:

| Peça | Função |
| --- | --- |
| Menu **Encontro → Expositores** | entrada no Soft UI (`/adm-home?item={id}`) |
| Form `expositores` | CRUD do cadastro |
| Form `expositores_fotos` | galeria, aberta pelo botão **Galeria** na listagem |
| Hooks `functions/auto_expositores.php` | slug, Instagram, WhatsApp, foto de destaque |
| Grupos `expositores_grupos` | select Artesãos / Alimentação |

Forma recomendada de aplicar (idempotente; também atualiza permissões do administrador e regenera o painel):

```bash
docker compose exec -u www-data app php system/instalar_modulo.php ceramistas_expositores
```

Definição: `system/modulos/ceramistas_expositores.php`. Fluxo genérico: [criar-modulo-admin.md](criar-modulo-admin.md).

Depois, no perfil **Administrador**, confirme as permissões de Expositores (menu, listar, incluir, editar, excluir) e o botão extra **Galeria**. Faça logout/login se o menu não aparecer.

### 2.1 Formulários e menu

Os IDs abaixo usam variáveis. Rode depois das tabelas da seção 1.

```sql
-- Form de fotos (sem item de menu; acesso pelo botão Galeria)
INSERT INTO `system_form` (
  `menu`, `nome`, `legenda`, `id_form`, `class`, `method`, `action`, `url_retorno`,
  `tabela`, `arquivo_def`, `preinsert`, `preupdate`, `predelete`,
  `posinsert`, `posupdate`, `posdelete`, `item_menu`, `botoes_adicionais`, `join_n_n`,
  `inserir`, `editar`, `deletar`, `visualizar`, `pdf`,
  `sql_adicional`, `sql_ordem`, `pre_listagem`, `pos_listagem`,
  `checkbox`, `condicao_checkbox`, `listar_pagina`
) VALUES (
  '', 'Fotos dos expositores', 'Galeria de fotos do expositor', '', '', 'post', '', '',
  'expositores_fotos', 'expositores_fotos', '', '', '',
  'auto_posinsert_expositores_fotos', 'auto_posupdate_expositores_fotos', '', 0,
  'a:1:{i:0;s:0:\"\";}',
  'a:4:{s:7:\"nome_bt\";a:1:{i:0;s:0:\"\";}s:14:\"chave_primaria\";a:1:{i:0;s:0:\"\";}s:11:\"tabela_join\";a:1:{i:0;s:0:\"\";}s:17:\"chave_estrangeira\";a:1:{i:0;s:0:\"\";}}',
  0, 0, 0, 0, 0,
  '',
  'ZWNobyAnb3JkZW0gQVNDLCBpZCBBU0MnOw==',
  '', '', 0, '', ''
);

SET @form_fotos = LAST_INSERT_ID();

-- Form principal (join_n_n aponta para o form de fotos)
INSERT INTO `system_form` (
  `menu`, `nome`, `legenda`, `id_form`, `class`, `method`, `action`, `url_retorno`,
  `tabela`, `arquivo_def`, `preinsert`, `preupdate`, `predelete`,
  `posinsert`, `posupdate`, `posdelete`, `item_menu`, `botoes_adicionais`, `join_n_n`,
  `inserir`, `editar`, `deletar`, `visualizar`, `pdf`,
  `sql_adicional`, `sql_ordem`, `pre_listagem`, `pos_listagem`,
  `checkbox`, `condicao_checkbox`, `listar_pagina`
) VALUES (
  'Encontro', 'Expositores', 'Cadastro dos expositores do encontro de ceramistas', '', '', 'post', '', '',
  'expositores', 'expositores',
  'auto_preinsert_expositores', 'auto_preupdate_expositores', '',
  '', '', '', 0,
  'a:1:{i:0;s:0:\"\";}',
  CONCAT(
    'a:4:{s:7:\"nome_bt\";a:1:{i:0;s:7:\"Galeria\";}s:14:\"chave_primaria\";a:1:{i:0;s:2:\"id\";}s:11:\"tabela_join\";a:1:{i:0;s:',
    CHAR_LENGTH(@form_fotos), ':"', @form_fotos,
    '";}s:17:\"chave_estrangeira\";a:1:{i:0;s:12:\"expositor_id\";}}'
  ),
  0, 0, 0, 0, 0,
  '',
  'ZWNobyAnZ3J1cG8gQVNDLCBvcmRlbSBBU0MsIG5vbWUgQVNDJzs=',
  '', '', 0, '', ''
);

SET @form_expo = LAST_INSERT_ID();
```

### 2.2 Campos do form Expositores

```sql
INSERT INTO `system_inputs` (
  `system_form`, `nome`, `id_input`, `class`, `campo_tabela`, `type`, `caracteristica`, `valor`,
  `join_tabela`, `join_chave_extrangeira`, `join_campo_exibido`, `sql_adicional`,
  `mascara`, `mascara_personalizada`, `exb_cadastro`, `exb_edicao`, `exb_listagem`,
  `exb_filtro`, `exb_view`, `edicao_restrita`, `validacao`, `aba`, `mapear_componente`,
  `parametros_componente`, `funcao_exibicao`, `linha_separadora`, `secao`, `ordem`
) VALUES
(@form_expo, 'Nome', '', ' gWidth', 'nome', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 1, 1, 0, 'text', '', '', '', '', 0, 'Identificação', 1),
(@form_expo, 'Slug (URL)', '', ' gWidth', 'slug', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 0, 1, 0, '', '', '', '', '', 0, 'Identificação', 2),
(@form_expo, 'Grupo', '', ' ', 'grupo', 'select', 2, '', 'expositores_grupos', 'id', 'nome', '', '', '', 1, 1, 1, 1, 1, 0, 'select', '', '', '', '', 0, 'Identificação', 3),
(@form_expo, 'Categoria', '', ' gWidth', 'categoria', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 1, 1, 0, '', '', '', '', '', 0, 'Identificação', 4),
(@form_expo, 'Resumo', '', ' ggWidth', 'resumo', 'textarea', 1, '', '', '', '', '', '', '', 1, 1, 0, 0, 1, 0, '', '', '', '', '', 0, 'Textos', 5),
(@form_expo, 'Descrição', '', ' ggWidth', 'descricao', 'textarea', 1, '', '', '', '', '', '', '', 1, 1, 0, 0, 1, 0, 'text', '', '', '', '', 0, 'Textos', 6),
(@form_expo, 'Logo', '', ' ', 'logo', 'text', 1, '', '', '', '', '', '', '', 1, 1, 0, 0, 1, 0, '', '', 'upload_imagem_padrao', 'w=800\r\nh=800\r\nview=160', '', 0, 'Imagens', 7),
(@form_expo, 'Foto de destaque', '', ' ', 'foto_destaque', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 0, 1, 0, '', '', 'upload_imagem_padrao', 'w=1200\r\nh=1200\r\nview=200', '', 0, 'Imagens', 8),
(@form_expo, 'Instagram', '', ' gWidth', 'instagram', 'text', 1, '', '', '', '', '', '', '', 1, 1, 0, 0, 1, 0, '', '', '', '', '', 0, 'Contato', 9),
(@form_expo, 'WhatsApp', '', ' ', 'whatsapp', 'text', 1, '', '', '', '', '', '', '', 1, 1, 0, 0, 1, 0, '', '', '', '', '', 0, 'Contato', 10),
(@form_expo, 'Ordem', '', ' ', 'ordem', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 0, 1, 0, '', '', '', '', '', 0, 'Publicação', 11),
(@form_expo, 'Ativo no site', '', ' ', 'ativo', 'select', 1, 'Não,Sim', '', '', '', '', '', '', 1, 1, 1, 1, 1, 0, '', '', '', '', '', 0, 'Publicação', 12);
```

### 2.3 Campos do form Fotos

```sql
INSERT INTO `system_inputs` (
  `system_form`, `nome`, `id_input`, `class`, `campo_tabela`, `type`, `caracteristica`, `valor`,
  `join_tabela`, `join_chave_extrangeira`, `join_campo_exibido`, `sql_adicional`,
  `mascara`, `mascara_personalizada`, `exb_cadastro`, `exb_edicao`, `exb_listagem`,
  `exb_filtro`, `exb_view`, `edicao_restrita`, `validacao`, `aba`, `mapear_componente`,
  `parametros_componente`, `funcao_exibicao`, `linha_separadora`, `secao`, `ordem`
) VALUES
(@form_fotos, 'Expositor', '', ' ', 'expositor_id', 'select', 2, '', 'expositores', 'id', 'nome', '', '', '', 1, 1, 1, 0, 1, 0, 'select', '', '', '', '', 0, 'Foto', 1),
(@form_fotos, 'Arquivo', '', ' ', 'arquivo', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 0, 1, 0, '', '', 'upload_imagem_padrao', 'w=1200\r\nh=1200\r\nview=200', '', 0, 'Foto', 2),
(@form_fotos, 'Legenda', '', ' gWidth', 'legenda', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 0, 1, 0, '', '', '', '', '', 0, 'Foto', 3),
(@form_fotos, 'Ordem', '', ' ', 'ordem', 'text', 1, '', '', '', '', '', '', '', 1, 1, 1, 0, 1, 0, '', '', '', '', '', 0, 'Foto', 4),
(@form_fotos, 'Destaque', '', ' ', 'destaque', 'select', 1, 'Não,Sim', '', '', '', '', '', '', 1, 1, 1, 0, 1, 0, '', '', '', '', '', 0, 'Foto', 5);
```

### 2.4 Menu Encontro

```sql
INSERT INTO `admin_menu` (`item`, `cor`, `order_by`)
SELECT 'Encontro', '', COALESCE(MAX(`order_by`), 0) + 1
FROM `admin_menu`
WHERE NOT EXISTS (SELECT 1 FROM `admin_menu` WHERE `item` = 'Encontro');

SET @menu_encontro = (SELECT `id` FROM `admin_menu` WHERE `item` = 'Encontro' LIMIT 1);

INSERT INTO `admin_submenu` (`item`, `link`, `form`, `tabela`, `order_by`)
SELECT 'Expositores', 'expositores', @form_expo, 'expositores', COALESCE(MAX(`order_by`), 0) + 1
FROM `admin_submenu`
WHERE NOT EXISTS (SELECT 1 FROM `admin_submenu` WHERE `form` = @form_expo);

SET @sub_expo = (SELECT `id` FROM `admin_submenu` WHERE `form` = @form_expo LIMIT 1);

INSERT IGNORE INTO `admin_menu_submenu` (`menu`, `submenu`)
VALUES (@menu_encontro, @sub_expo);
```

### 2.5 Depois do SQL

1. Conferir `functions/auto_expositores.php` (hooks de slug/contato).
2. Regenerar o painel: abrir `/system-rebuild` autenticado, ou recarregar qualquer URL com `SYSTEM_CODEGEN=1`.
3. Atualizar permissões do perfil Administrador (o script PHP faz isso; o SQL puro não altera `system_perfil`).
4. Testar listagem, cadastro, edição, exclusão e o botão Galeria.

---

## 3. Programação

Tabela usada por `classes/Sistema/Programacao.php` e pela seção **Programação** em `/ceramistas`.

| Coluna | Uso no site |
| --- | --- |
| `titulo` | Título na timeline |
| `descricao` | Texto do item |
| `dia` | Agrupa as abas (sábado / domingo) |
| `hora_inicio` / `hora_fim` | Horário exibido |
| `local` | Linha de local |
| `categoria` | Classificação interna (`abertura`, `oficina`, `feira`, `musica`, `kids`, `sabores`) |
| `icone` | SVG no frontend (`sun`, `pottery`, `music`, `kids`, `taste`, `beer`, `market`) |
| `ordem` | Desempate no mesmo horário |
| `destaque` | Destaque visual na timeline |
| `ativo` | `1` = visível no site |

`txtid` é gerado no insert do admin. Ícones e categorias são tabelas de apoio para o select do painel.

### 3.1 Tabelas de apoio e agenda

Já estão em `sql/ceramistas_schema.sql` (`programacao_categorias`, `programacao_icones`, `programacao`).

### 3.2 Módulo admin

```bash
docker compose exec -u www-data app php system/instalar_modulo.php ceramistas_programacao
```

Definição: `system/modulos/ceramistas_programacao.php`. Hooks: `functions/auto_programacao.php`. Menu: **Encontro → Programação**.

A seção **Espaço Kids** da página `/ceramistas` é texto estático em `pages/ceramistas/ceramistas.php`. A agenda deve repetir os mesmos blocos:

```sql
UPDATE `programacao`
SET
  `titulo` = 'Oficina de massinhas e pintura',
  `descricao` = 'Oficina à tarde, preparada por crianças, para crianças: cores, formas e criar com as mãos.',
  `hora_inicio` = '14:00:00',
  `hora_fim` = NULL,
  `local` = 'Espaço Kids'
WHERE `id` = 5 AND `categoria` = 'kids';

INSERT INTO `programacao` (
  `txtid`, `titulo`, `descricao`, `dia`, `hora_inicio`, `hora_fim`,
  `local`, `categoria`, `icone`, `ordem`, `destaque`, `ativo`, `created_on`
)
SELECT
  MD5('kids-brinquedos-2026-09-06'),
  'Brinquedos na praça',
  'Cantinho especial com brinquedos para os pequenos brincarem e aproveitarem a tarde.',
  '2026-09-06',
  '14:00:00',
  '18:00:00',
  'Espaço Kids',
  'kids',
  'kids',
  4,
  0,
  1,
  NOW()
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `programacao`
  WHERE `dia` = '2026-09-06' AND `categoria` = 'kids' AND `titulo` = 'Brinquedos na praça'
);
```

---

## 4. Atrações musicais

Tabela usada por `classes/Sistema/AtracoesMusicais.php` e pela seção **Música** em `/ceramistas`.

| Coluna | Uso no site |
| --- | --- |
| `nome` | Nome da banda / artista |
| `slug` | Identificador interno único |
| `resumo` | Texto curto abaixo da data |
| `cartaz` | Imagem do cartaz. Prefixo `ceramistas/` lê `images/ceramistas/`; qualquer outro caminho relativo lê `images/upload/` |
| `cartaz_alt` | Texto alternativo da imagem |
| `dia` / `hora` | Montam a linha “05 de setembro · sábado · a partir das 15h” |
| `local` | Local do show (padrão no admin: Calçadão Pedro Furlan) |
| `instagram` | Handle sem `@` (o site monta `https://instagram.com/{handle}`) |
| `site` | URL completa do site do artista |
| `ordem` | Desempate no mesmo horário |
| `ativo` | `1` = visível no site |

`txtid` é gerado no insert do admin.

### 4.1 Tabela

Já está em `sql/ceramistas_schema.sql` (`atracoes_musicais`), com carga inicial de Trinca Ferro e João Ferreira (`INSERT IGNORE` pela `slug`).

### 4.2 Módulo admin

```bash
docker compose exec -u www-data app php system/instalar_modulo.php ceramistas_atracoes_musicais
```

Definição: `system/modulos/ceramistas_atracoes_musicais.php`. Hooks: `functions/auto_atracoes_musicais.php`. Menu: **Encontro → Atrações musicais**.

Depois, logout/login no admin se o item não aparecer.

### 4.3 Modelo para cadastrar um show

```sql
INSERT INTO `atracoes_musicais` (
  `txtid`, `nome`, `slug`, `resumo`, `cartaz`, `cartaz_alt`,
  `dia`, `hora`, `local`, `instagram`, `site`, `ordem`, `ativo`
) VALUES (
  MD5(CONCAT('atracao-', 'slug_do_artista')),
  'Nome da banda',
  'slug_do_artista',
  'Frase curta para o card.',
  'ceramistas/cartaz-do-artista.jpg',  -- ou caminho em images/upload/
  'Cartaz: Nome da banda',
  '2026-09-05',
  '15:00:00',
  'Calçadão Pedro Furlan',
  'handle_instagram',                  -- sem @
  'https://site-do-artista.com.br/',   -- NULL se não houver
  10,
  1
);
```

Para esconder do site sem apagar:

```sql
UPDATE `atracoes_musicais` SET `ativo` = 0 WHERE `slug` = 'slug_do_artista';
```

---

## 5. Configuração geral

Tabela usada por `classes/Sistema/CeramistasConfig.php`. Há **um único registro** (id `1`). O painel abre direto a edição (sem incluir/excluir).

| Coluna | Uso no site |
| --- | --- |
| `data_inicio` / `data_fim` | Hero (05–06 / setembro 2026), rodapé e meta description |
| `local` | Nome do lugar no hero (“Praça da Matriz”) |
| `local_complemento` | Linha menor do hero e nota da seção Música |
| `cidade` / `uf` | Kicker, rodapé e textos compostos |
| `endereco` | Parágrafo da seção Contato |
| `mapa_query` | Ponto do iframe do Google Maps. Use `lat,lng` (ex.: `-21.3644363,-46.938418`) para centralizar o mapa e exibir um marcador com o nome de `local`. Texto livre (endereço) continua válido. |
| `whatsapp` | Dígitos (com ou sem 55). O site monta `wa.me` e o rótulo `(35) 99701-0196` |
| `mensagem_whatsapp` | Texto pré-preenchido do `wa.me` |

### 5.1 Tabela

Já está em `sql/ceramistas_schema.sql` (`ceramistas_config`), com a carga inicial do encontro 2026.

Ponto do evento (Praça da Matriz / Calçadão Pedro Furlan), zoom equivalente a ~406 m:

```sql
UPDATE `ceramistas_config`
SET `mapa_query` = '-21.3644363,-46.938418'
WHERE `id` = 1;
```

### 5.2 Módulo admin

```bash
docker compose exec -u www-data app php system/instalar_modulo.php ceramistas_config
```

Definição: `system/modulos/ceramistas_config.php`. Hooks: `functions/auto_ceramistas_config.php`. Menu: **Encontro → Configuração**.

O H1 do hero em `/ceramistas` alterna três frases com efeito de digitar/apagar (`pages/ceramistas/ceramistas.php` + `.vue.php`). A primeira permanece no `sr-only` para leitores de tela. As frases extras são: “Tradição moldada em comunidade!” e “Arte que transforma, música que encanta!”. Com `prefers-reduced-motion` fica só a primeira, sem animação.

Depois, logout/login no admin se o item não aparecer.

---

## Próximos SQLs

- (outros conteúdos ainda estáticos na página, se forem para o banco)
