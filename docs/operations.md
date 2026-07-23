# Operação e ambiente

## Requisitos inferidos

- Apache com `mod_rewrite` e suporte a `.htaccess`;
- PHP 8.2 ou superior para as dependências Composer atuais;
- MySQL com PDO;
- extensões PHP: PDO MySQL, cURL, mbstring, JSON, GD e, preferencialmente, DOM/XML;
- diretório `vendor/` instalado;
- escrita habilitada nos diretórios usados por uploads e geração.

Existe tensão de compatibilidade: dependências recentes exigem PHP moderno, enquanto partes legadas usam short tags, propriedades dinâmicas e padrões antigos. Teste cada rota afetada na versão real do servidor.

## Configuração

`config.php`:

- inicia sessão;
- define timezone;
- escolhe produção ou desenvolvimento;
- define `ROOT`, banco e integrações;
- mantém o modo atual selecionado em código.

Não há `.env`. Segredos estão misturados à configuração e devem ser migrados para variáveis de ambiente ou cofre externo.

## Instalação

Procedimento mínimo inferido:

1. configurar Apache para servir a raiz do projeto;
2. habilitar `mod_rewrite`;
3. selecionar o ambiente sem versionar segredos;
4. criar ou apontar para o banco correto;
5. executar `composer install`;
6. garantir extensões PHP;
7. conceder escrita apenas aos diretórios necessários;
8. validar a raiz `/feira/` ou ajustar `ROOT`;
9. acessar primeiro uma rota controlada e revisar geração de arquivos.

Não execute a aplicação contra um banco vazio ou incorreto sem desabilitar ou compreender a geração automática.

## Diretórios com escrita

- `images/upload/`;
- `arquivos/`;
- diretório de sessão do PHP;
- `tables/`;
- `admin/exe_system/`;
- `containers/exe_system/`;
- `functions/__list_functions.php`.

Permissão de escrita sobre PHP gerado aumenta o risco operacional. Em uma evolução futura, gere esses artefatos no deploy e mantenha o runtime somente leitura.

## Tarefas agendadas

- `action/cron_get_eventos.php`: ingestão de eventos.
- `cron-casos.php`: notificações legadas.

O cron de eventos precisa de autenticação efetiva, lock contra concorrência, timeout, retries e armazenamento do relatório. A execução CLI deve carregar explicitamente a configuração e o autoload exigidos.

## Logs e observabilidade

O projeto não possui logging central, métricas ou health check confiável.

Locais observados:

- `dblog.txt`;
- `arquivos/cron.txt`;
- `error_log()` em alguns módulos;
- respostas HTTP com erros ou relatórios.

Evite habilitar detalhes de erro em produção. Nunca grave credenciais, tokens, HTML completo de terceiros ou dados pessoais.

## Deploy

Existem mecanismos legados de publicação por ZIP/FTP, além de arquivos de atualização e teste. Eles não formam um pipeline seguro ou reproduzível.

Antes de produção:

- restringir ou remover endpoints de publish, teste e diagnóstico;
- instalar dependências com lock;
- separar configuração do código;
- gerar artefatos de forma controlada;
- aplicar permissões mínimas;
- criar backup de banco e arquivos;
- validar callbacks e crons;
- confirmar que `_public/` e `_publish/` permanecem fora do escopo de manutenção.

## Ausências importantes

Não foram encontrados na aplicação:

- Docker/Compose;
- pipeline de CI;
- testes automatizados próprios;
- migrations versionadas;
- configuração por `.env`;
- deploy automatizado moderno;
- monitoramento estruturado.
