# Guia rápido para agentes de IA

## Antes de começar

1. Leia `docs/README.md` e o documento do módulo.
2. Não pesquise nem altere `_public/` e `_publish/`.
3. Não edite `tables/`.
4. Identifique se o arquivo é fonte ou gerado.
5. Não execute a aplicação contra banco alternativo sem controlar a geração.
6. Não exponha valores de credenciais encontrados no código.

## Como localizar uma funcionalidade

### Partindo de uma URL pública

1. Leia `.htaccess` e `controle-includes.php`.
2. Resolva o prefixo da rota.
3. Para página, procure `pages/<nome>/` e `pages/<nome>.php`.
4. Leia o container declarado no comentário da página.
5. Procure chamadas AJAX no arquivo `.vue.php`.
6. Siga classe e método até `classes/Sistema/` ou `classes/Backend/`.
7. Siga o acesso a dados até `functions/auto_db.php` ou camada legada.

### Partindo de uma tela administrativa

1. Consulte a definição em `tables/`, sem editar.
2. Identifique `system_form`, `system_inputs`, hooks e permissões.
3. Localize a ação genérica em `action/`.
4. Descubra o gerador responsável.
5. Altere a fonte autoritativa e regenere de modo controlado.

### Partindo de uma chamada da API

1. Leia `rest.php`, `rest/rest.php` e `rest/api.php`.
2. Localize a rota em `rest/auto_*.php`.
3. Resolva classe e método em `classes/Backend/<versao>/`.
4. Confirme que a autenticação interrompe falhas.
5. Revise SQL, saída e integração externa.

## Arquivos fonte e derivados

Trate como derivados:

- `tables/`;
- `admin/exe_system/`;
- `containers/exe_system/`;
- `functions/__list_functions.php`.

Fontes frequentes:

- `system/` para geração;
- metadados no MySQL;
- `containers/` para layouts;
- `functions/auto_*.php` para funções incluídas no índice;
- `classes/`, `pages/` e `action/` para regras e fluxos.

## Escolha da camada de banco

O projeto possui três camadas. Não amplie o legado por conveniência.

- Prefira consultas parametrizadas.
- Não passe entrada para `_where()`, SQL livre, nomes de campo, ordenação ou limite.
- Use transação em operação com várias tabelas.
- Confirme schema e constraints no banco real.

## Compatibilidade

- Frontend usa Vue 2 sem build; não escrever como Vue 3 ou módulos compilados.
- O sistema depende de globais criadas pelo bootstrap.
- Autoload próprio e Composer coexistem.
- Dependências modernas exigem PHP recente, mas código legado pode depender de short tags e comportamentos antigos.
- O nome de uma classe ou diretório pode pertencer a produto legado e não indicar uso atual.

## Verificação mínima

Depois de uma mudança:

1. executar `php -l` nos PHP alterados;
2. verificar includes e caminhos em Windows/Linux;
3. testar a rota direta e o fluxo pela interface;
4. validar usuário autorizado e não autorizado;
5. revisar SQL e transações;
6. confirmar que nenhum artefato gerado foi alterado por engano;
7. revisar erros do PHP e console do navegador;
8. testar entrada vazia, inválida, excessiva e maliciosa;
9. conferir o estado do versionamento.

## Pare e peça confirmação quando

- a tarefa exigir alterar metadados diretamente no banco;
- houver risco de regenerar grande parte do sistema;
- o schema real contradizer os artefatos;
- for necessária rotação de segredos;
- uma alteração puder publicar dados sem moderação;
- não estiver claro se um módulo legado ainda está em produção.
