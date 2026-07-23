# Autenticação e sessões

## Sessão PHP

`config.php` inicia a sessão e configura sua duração lógica. Como o bootstrap é compartilhado, páginas públicas, administração e parte da API podem depender de estado global de sessão.

## Login administrativo

Arquivos principais:

- `admin/loginSystem.php`;
- `functions/systemFunctions.php`;
- `adm.php`;
- `admsite.php`.

Dados relacionados:

- `system_admin`: usuário e hash da senha;
- `system_perfil`: perfil e permissões;
- `system_block`: controle de tentativas por IP.

Após o login, a sessão mantém identificador do usuário, registro administrativo e hash. Áreas protegidas usam `checa_acesso_system()` para consultar o banco e revalidar o estado.

O sistema bloqueia o IP após várias tentativas. Senhas administrativas ainda usam SHA-1 e não foi observada regeneração do ID da sessão no login.

## Permissões

Permissões administrativas vêm de perfis e podem estar serializadas. Parte delas é convertida em arquivos em `tables/`.

Ao adicionar uma função administrativa:

1. defina a permissão no metadado correto;
2. regenere os artefatos;
3. valide a permissão no servidor;
4. não use apenas ocultação de menu ou botão como autorização.

## Token de página

`page.php` cria um token aleatório em `$_SESSION['token']` a cada página. Não foi identificada validação central correspondente nas ações CRUD examinadas. Portanto, a simples presença desse token não deve ser tratada como proteção CSRF efetiva.

## Sessões da API

A API persiste sessões na tabela `session`, com:

- token;
- usuário;
- dispositivo;
- data de criação;
- expiração.

Arquivos centrais:

- `rest/auto__base.php`;
- `classes/Backend/Base.php`;
- `classes/Backend/v0/PhpLogin.php`.

O token pode chegar no corpo ou no header `Authorization`, como token simples ou no formato `uuid;token`. A validade configurada é longa.

Alguns métodos chamam `requireAuth()`. Confirme que a falha realmente encerra a execução antes de acessar ou alterar dados.

## Chamadas dinâmicas

Os seguintes fluxos permitem selecionar código por entrada HTTP:

- prefixo `fn-`, para funções globais;
- `ajax_load_class_function`, para classe e método;
- `/rest/v1/rfc`, para operação de backend.

Autenticação no roteador não substitui autorização dentro da função ou método. Métodos públicos devem recusar explicitamente chamadas não autorizadas.

## Requisitos para alterações

- Regenerar o ID da sessão após login.
- Migrar novas senhas para `password_hash()` e `password_verify()`.
- Aplicar autorização no servidor em cada operação sensível.
- Implementar CSRF real para ações baseadas em cookie.
- Encerrar processamento imediatamente quando autenticação falhar.
- Reduzir validade e permitir revogação dos tokens da API.
- Não registrar tokens, hashes ou credenciais em logs.
