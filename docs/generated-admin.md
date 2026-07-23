# Administração e arquivos gerados

## Modelo administrativo

A administração é um CRUD genérico orientado por metadados do banco. Em vez de um controller específico para cada entidade, tabelas internas descrevem:

- tabela real;
- campos e tipos de input;
- validações e máscaras;
- joins e relações;
- campos de cadastro, edição, listagem e visualização;
- hooks PHP;
- menus e permissões.

As principais fontes de metadados são `system_form`, `system_inputs`, `system_perfil` e tabelas relacionadas.

## Geração

Os geradores principais são:

- `system/gera_definicoes_de_tabelas.php`;
- `system/gera_arquivos_de_listagem.php`.

Eles produzem ou atualizam:

- `tables/def_*.php`;
- `tables/_admin_def_tables.php`;
- `tables/_admin_menu.php`;
- `tables/_admin_permissoes.php`;
- `admin/exe_system/`;
- `containers/exe_system/`;
- `functions/__list_functions.php`.

`controle-includes.php` pode executar geradores durante requisições normais quando a pasta `system/` está disponível. Isso significa que iniciar o sistema com banco ou metadados incorretos pode sobrescrever os artefatos derivados.

## Regra de edição

Nunca edite `tables/` para implementar uma mudança permanente.

Para alterar um formulário administrativo:

1. identifique o registro correspondente nos metadados;
2. confirme qual gerador produz o arquivo;
3. altere a fonte autoritativa — metadado ou gerador;
4. regenere em ambiente controlado;
5. revise todos os arquivos derivados;
6. teste cadastro, edição, listagem, visualização, permissões e hooks.

O mesmo cuidado se aplica a `admin/exe_system/`, `containers/exe_system/` e `functions/__list_functions.php`.

## CRUD genérico

Ações centrais:

- `action/insert_global.php`;
- `action/edit_global.php`;
- `action/delete_global.php`;
- `functions/systemFunctions_formsNew.php`.

Essas ações leem definições geradas e podem executar hooks. Uma mudança em um metadado pode afetar diversas telas e fluxos, não apenas uma entidade.

## Autenticação e permissões

O administrativo usa:

- `admin/loginSystem.php` para o formulário de login;
- funções de `functions/systemFunctions.php`;
- `system_admin` para usuários;
- `system_perfil` para permissões;
- `system_block` para tentativas bloqueadas.

As permissões podem estar serializadas e são transformadas em arquivos gerados. Consulte [Autenticação e sessões](authentication-and-sessions.md).

## Riscos operacionais

- Geração em runtime exige permissão de escrita sobre código PHP.
- Requisições concorrentes podem ler arquivos parcialmente gerados.
- Trechos PHP podem vir codificados dos metadados do banco.
- Um banco errado pode regenerar menus, permissões, formulários e containers incorretos.
- Alterações manuais em derivados serão perdidas.
- Nem todas as entidades atuais da agenda aparecem no gerador; `eventos`, `tags` e `evento_tags` são tratadas diretamente pelo código observado.

## Checklist de investigação

Ao receber uma tarefa administrativa:

- localizar o nome da tabela em `tables/` apenas para consulta;
- encontrar a origem correspondente em `system_form`/`system_inputs`;
- procurar hooks e callbacks citados pela definição;
- localizar a ação genérica acionada pela tela;
- verificar permissões em menu e perfil;
- avaliar se a mudança também exige ajuste no gerador;
- não executar a aplicação com configuração de banco improvisada.
