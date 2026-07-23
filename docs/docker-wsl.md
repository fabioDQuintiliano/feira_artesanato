# Docker no WSL

## Requisitos

- WSL 2;
- Docker Desktop com integração para a distribuição WSL, ou Docker Engine instalado dentro do WSL;
- portas `8080` e `3307` livres, ou valores alternativos no `.env`.

Para melhor desempenho, mantenha o projeto no filesystem Linux do WSL, por exemplo em `~/projetos/feira`. Bind mounts executados diretamente de unidade de rede ou de `/mnt/c` podem ser lentos e apresentar problemas de permissão.

## Primeiro start

Dentro da pasta do projeto no terminal WSL:

```bash
cp .env.example .env
./up
```

O script `./up` sobe os containers (`docker compose up -d`) e imprime os endereços do site e do phpMyAdmin.

Equivalente manual:

```bash
docker compose up --build -d
./docker/show-urls.sh
```

Se outra stack local já estiver usando `8080`/`3307`/`8081`, ajuste as portas no `.env` (ex.: `8090`, `3308`, `8091`).

O MySQL fica disponível para ferramentas locais em:

```text
host: 127.0.0.1
porta: 3308
banco: admin_feira
usuário: feira
senha: feira
```

Altere essas credenciais no `.env` quando necessário.

## Importação do banco

O arquivo `dump/dump.sql` é montado em `/docker-entrypoint-initdb.d/` e importado automaticamente somente quando o volume `mysql_data` é criado pela primeira vez.

Para apagar o banco Docker e importar novamente o dump:

```bash
docker compose down -v
docker compose up --build -d
```

O comando `down -v` apaga permanentemente os dados armazenados nos volumes desse ambiente.

## Comandos úteis

Ver o estado:

```bash
docker compose ps
```

Ver logs:

```bash
docker compose logs -f app
docker compose logs -f db
```

Abrir um shell no container PHP:

```bash
docker compose exec app bash
```

Executar o importador de eventos:

```bash
docker compose exec app php action/cron_get_eventos.php
```

Validar a configuração do Compose:

```bash
docker compose config
```

Parar os serviços sem apagar o banco:

```bash
docker compose down
```

## Configuração

O Compose fornece à aplicação:

- `APP_URL`;
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`;
- `GEMINI_API_KEY`;
- `OPENAI_API_KEY`;
- `CRON_TOKEN`.

`config.php` usa essas variáveis quando `APP_URL` está definida e mantém o comportamento legado fora do Docker.

Não versionar `.env`. Chaves de serviços externos devem ser preenchidas localmente e rotacionadas caso tenham sido expostas anteriormente.

## Código e dependências

O diretório do projeto é montado em `/var/www/html`, permitindo editar arquivos no WSL e atualizar a aplicação sem reconstruir a imagem. O diretório `vendor/` usa um volume separado e é preenchido pelo Composer.

Se `composer.json` ou `composer.lock` mudar:

```bash
docker compose exec app composer install
```

## Arquivos gerados e uploads

O entrypoint prepara permissão de escrita para:

- `arquivos/`;
- `images/upload/`;
- `tables/`;
- `admin/exe_system/`;
- `containers/exe_system/`;
- `functions/__list_functions.php`.

`tables/` continua sendo gerada automaticamente e não deve ser editada manualmente.

## Solução de problemas

Se a aplicação iniciar antes da conclusão do primeiro import, acompanhe:

```bash
docker compose logs -f db
```

Se a porta estiver ocupada, altere `APP_PORT` ou `DB_PORT_FORWARD` no `.env`.

Se o código montado de uma pasta Windows ou de rede apresentar erro de permissão, copie o projeto para o filesystem do WSL e recrie os containers.
