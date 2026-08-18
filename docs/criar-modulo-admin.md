# Como criar um módulo do painel admin

Fluxo para cadastrar uma entidade no CRUD genérico (`adm-home?item={id}`) **sem** editar `tables/` e **sem** repetir o script de expositores.

Complementa [system-admin.md](system-admin.md) e [generated-admin.md](generated-admin.md).

## Ideia

O painel não tem um controller por tabela. Um módulo é:

1. tabela MySQL real;
2. metadados em `system_form` + `system_inputs`;
3. item de menu (`admin_menu*`);
4. permissões em `system_perfil`;
5. hooks opcionais em `functions/auto_*.php`;
6. artefatos gerados (`tables/def_*.php`).

A definição declarativa fica em `system/modulos/{id}.php`. O instalador idempotente é:

```bash
docker compose exec -u www-data app php system/instalar_modulo.php
docker compose exec -u www-data app php system/instalar_modulo.php {id}
```

`-u www-data` evita gravar artefatos como root (isso quebra o codegen na request seguinte).

Exemplo já existente: `system/modulos/ceramistas_expositores.php`, `ceramistas_programacao.php`, `ceramistas_atracoes_musicais.php`, `ceramistas_config.php`.

## Passo a passo

### 1. Tabela

Crie o `CREATE TABLE` (utf8mb4, `id`, convenções `txtid` / `created_on` se fizer sentido).

Salve o SQL no `.md` da funcionalidade em `docs/` (obrigatório). Se for conteúdo do encontro, use [sql-ceramistas.md](sql-ceramistas.md).

Opcional: coloque o schema também em `sql/*.sql` e aponte `schema_sql` na definição.

### 2. Definição do módulo

Crie `system/modulos/{id}.php` que **retorna um array**:

```php
<?php
return array(
	'id' => 'meu_modulo',
	'titulo' => 'Nome humano',
	'doc' => 'docs/meu-modulo.md',
	'schema_sql' => dirname(__DIR__, 2).'/sql/meu_schema.sql', // opcional
	'sql' => array(), // CREATE/INSERT extra, opcional
	'forms' => array(
		array(
			'arquivo_def' => 'minha_tabela', // único; vira tables/def_minha_tabela.php
			'tabela' => 'minha_tabela',
			'nome' => 'Meu cadastro',         // rótulo do menu e permissão
			'legenda' => 'Texto de ajuda',
			'menu' => 'Encontro',            // seção do sidenav; vazio = sem menu
			'link' => 'meu_cadastro',        // opcional; default = removeCaracteres(nome)
			'preinsert' => 'auto_preinsert_meu_modulo',
			'preupdate' => 'auto_preupdate_meu_modulo',
			'sql_ordem' => "echo 'ordem ASC, nome ASC';",
			'bt_adicional' => 'meucadastrogaleria', // permissão do botão join_n_n
			'relacionados' => array(
				array(
					'botao' => 'Galeria',
					'chave_primaria' => 'id',
					'form' => 'minha_tabela_fotos', // arquivo_def do form filho
					'chave_estrangeira' => 'pai_id',
				),
			),
			'campos' => array(
				array(
					'nome' => 'Nome',
					'campo_tabela' => 'nome',
					'type' => 'text',
					'class' => ' gWidth',
					'exb_listagem' => 1,
					'exb_filtro' => 1,
					'validacao' => 'text',
					'secao' => 'Identificação',
					'ordem' => 1,
				),
			),
		),
	),
);
```

Coloque **forms filhos antes** do form pai quando houver `relacionados`.

### 3. Campos (atalhos)

| Objetivo | Como configurar |
| --- | --- |
| Texto obrigatório | `type=text`, `validacao=text` |
| Texto longo | `type=textarea`, `class=' ggWidth'` |
| Select fixo 0/1 | `type=select`, `valor='Não,Sim'` (índice 0 e 1) |
| Select de outra tabela | `type=select`, `caracteristica=2`, `join_tabela`, `join_chave_extrangeira`, `join_campo_exibido`, `validacao=select` |
| Data | `type=text`, `mascara=data` (grava `Y-m-d`, exibe `d/m/Y`) |
| Hora | `type=text`, `mascara=hora` (`99:99`; acrescente `:00` no hook se a coluna for `TIME`) |
| Só na listagem | `exb_listagem=1` (cadastro/edição/view já vêm ligados por padrão) |

Repita `secao` em todos os campos do grupo. O formulário (inclusão, edição e visualização) agrupa esses campos num cartão com a classe `linhaSeparaSessao`, aberto só quando o nome da seção muda.

### 4. Hooks

Lógica (slug, senha, side-effects) em `functions/auto_{assunto}.php`. O nome da função entra em `preinsert` / `preupdate` / `posinsert` / etc.

Não colocar PHP via base64 no metadado.

### 5. Ícone do menu

Inclua a chave em `classes/Sistema/Admin/Menu.php` (`$icons`):

- seção: `removeCaracteres` do nome do menu (`encontro`);
- item: `link` do submenu (`expositores`). **O `link` precisa ser igual a `removeCaracteres(nome)`** (espaço vira `_`, acento some). A listagem e os botões Editar/Excluir conferem o **nome**, não um `link` inventado — se não bater, o menu aparece mas o clique volta para o dashboard.

### 6. Instalar

```bash
docker compose exec -u www-data app php system/instalar_modulo.php {id}
```

O instalador:

- cria/atualiza form e campos (não duplica `campo_tabela` no mesmo form);
- cria seção de menu e submenu se `menu` não for vazio;
- concede permissões ao perfil Administrador (e a perfis que já tenham `pessoas`);
- regenera `tables/` e `__list_functions.php`;
- ajusta dono/permissão dos artefatos.

Atalho legado dos expositores (mesmo efeito):

```bash
docker compose exec -u www-data app php migrate_ceramistas_admin.php
```

### 7. Verificar

1. Logout/login no admin (permissões ficam na sessão).
2. Abrir a seção no sidenav.
3. Testar listar, incluir, editar, excluir.
4. Se houver `relacionados`, testar o botão na listagem.
5. Conferir o perfil Administrador se o item não aparecer.

## O que não fazer

- Não editar `tables/def_*.php`, `admin/exe_system/` nem `functions/__list_functions.php` como fonte.
- Não rodar o instalador como root (`docker compose exec app php ...` sem `-u www-data`).
- Não apontar o codegen para outro banco.
- Não copiar credenciais para o `.md`.

## Arquivos do fluxo

| Peça | Onde |
| --- | --- |
| Instalador | `classes/Sistema/Admin/Modulo.php` |
| CLI | `system/instalar_modulo.php` |
| Definições | `system/modulos/{id}.php` |
| Hooks | `functions/auto_*.php` |
| Ícones | `classes/Sistema/Admin/Menu.php` |
| Documentação da feature | `docs/{tema}.md` (obrigatório) |
