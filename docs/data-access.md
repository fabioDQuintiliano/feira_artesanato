# Persistência e modelo de dados

## Banco e configuração

O sistema usa MySQL por PDO. Host, banco e credenciais são definidos em `config.php`; não há `.env`. Não reproduza esses valores em documentação ou testes.

Não foram encontradas migrations formais. `migrate_event_fields.php` é um script pontual, não um histórico completo do esquema. O modelo abaixo foi inferido do código e dos artefatos gerados.

## Camadas de acesso

### `Model`

Local: `functions/mainFunction.php`.

É a camada CRUD antiga e usa montagem manual de SQL. Aparece em código global e administrativo. Entradas precisam ser revisadas cuidadosamente porque identificadores e valores podem ser concatenados.

### `DB` e `DB_Classe`

Local: `functions/queryFunction.php`.

Implementam um padrão Active Record legado. Também constroem consultas por concatenação e continuam presentes em módulos antigos.

### `DAO`, `DB_Functions` e `DB_Class`

Local: `functions/auto_db.php`.

É a camada mais recente. Permite chamadas dinâmicas semelhantes a:

```php
DAO::Projeto()->_created_by($id)->_loadAll();
```

Filtros simples usam prepared statements, mas os seguintes pontos ainda aceitam SQL ou identificadores dinâmicos:

- `_where()`;
- ordenação;
- limites;
- nomes de tabelas e campos;
- `DAO::doQuery()`.

Não passe entrada do cliente a esses pontos sem validação estrita ou lista permitida.

## Convenções de dados

Convenções frequentes, mas não universais:

- `id`: chave primária interna;
- `txtid`: identificador público;
- `created_on`: data de criação;
- `edited_on`: data de edição;
- `created_by`: autor;
- `last_edit_by`: último editor;
- nomes em `snake_case`;
- relações N:N em tabelas de ligação.

## Entidades da agenda cultural

### `eventos`

Campos usados pelo código incluem:

- `id`, `txtid`;
- `nome`, `descricao`, `imagem`;
- `data_evento`, `local`, `link`;
- `valor`, `faixa_etaria`;
- `created_on`, `created_by`, `last_edit_by`.

Implementação principal:

- `classes/Sistema/Eventos.php`;
- `pages/home_v3/`;
- `pages/evento/`;
- `action/cadastrar_evento.php`.

### `tags` e `evento_tags`

`tags` mantém classificações do evento. `evento_tags` representa a relação N:N.

Criação ou atualização de evento, tags e vínculos precisa ser atômica. O fluxo atual não garante transação e pode deixar dados parciais.

### `links_config` e `estabelecimentos`

`links_config` define URLs usadas pela importação automática, além de estabelecimento, autor e data de atualização. `estabelecimentos` é referenciada por essa configuração.

### Entidades administrativas

- `system_admin`: usuários administrativos;
- `system_perfil`: perfis e permissões;
- `system_config`: configurações;
- `system_form` e `system_inputs`: metadados do CRUD;
- `system_block`: bloqueios de login.

### Sessões da API

A tabela `session` persiste token, usuário, dispositivo, criação e expiração das sessões da API.

## Limitações conhecidas

- Não há transação central para operações compostas.
- Uma conexão PDO pode ser aberta para cada DAO.
- Consultas livres e filtros brutos podem causar SQL injection.
- Exceções podem expor SQL e detalhes do banco.
- Não há garantia confirmada de unicidade de `txtid` ou tags.
- O uso de `rowCount()` após `SELECT` pode produzir resultados dependentes do driver.
- Código gerado consulta metadados do banco e pode divergir do filesystem.

## Orientação para código novo

1. Prefira a camada DAO apenas quando ela realmente parametrizar valores.
2. Não adicione novos usos de `Model`, `DB_Classe`, `_where()` com entrada externa ou SQL concatenado.
3. Use transação para gravações em mais de uma tabela.
4. Valide nomes de coluna, direção de ordenação e limites por lista permitida.
5. Preserve `txtid` quando ele já é usado em URL ou integração.
6. Confirme o esquema real antes de criar migration ou assumir constraints.
7. Não edite definições em `tables/`; consulte [Administração e arquivos gerados](generated-admin.md).
