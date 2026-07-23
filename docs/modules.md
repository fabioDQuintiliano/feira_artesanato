# Catálogo de módulos

Este catálogo separa o produto atual, a infraestrutura compartilhada e os subsistemas legados. A existência de um arquivo não garante que o módulo esteja publicado; confirme rotas, menu administrativo e chamadas atuais.

## Agenda cultural

### Home e descoberta

Rotas:

- `/` redireciona para `/home_v3`;
- `/home_v3`;
- filtros por tag e data.

Arquivos:

- `index.php`;
- `pages/home_v3/home_v3.php`;
- `pages/home_v3/home_v3.vue.php`;
- `classes/Sistema/Eventos.php`.

Funcionamento:

- lista eventos futuros;
- filtra por tag e data;
- pagina por scroll;
- marca datas com eventos no calendário;
- calcula tags populares;
- usa `eventos`, `tags` e `evento_tags`.

O frontend chama `Sistema\Eventos::getEventos()` e `getDatasComEventos()` pelo dispatcher AJAX.

### Detalhe e compartilhamento

Rota: `/evento/{txtid}`.

Arquivos:

- `pages/evento/evento.php`;
- `pages/evento/evento.vue.php`.

Busca o evento por `txtid`, agrega tags e apresenta data, local, valor, faixa etária e link. O compartilhamento usa WhatsApp, X/Twitter ou cópia de URL.

### Cadastro público

Rotas:

- `/cadastrar_evento`;
- `/action-cadastrar_evento`.

Arquivos:

- `pages/cadastrar_evento/cadastrar_evento.php`;
- `pages/cadastrar_evento/cadastrar_evento.vue.php`;
- `action/cadastrar_evento.php`.

Fluxo:

1. recebe JSON;
2. valida nome e data;
3. grava imagem Base64;
4. cria o evento;
5. cria ou reutiliza tags;
6. grava `evento_tags`.

Não há autenticação, rate limit ou moderação efetiva observada. A mensagem de “aguardando revisão” não corresponde à persistência e à consulta pública atuais.

## Importação automática de eventos

Arquivos:

- `action/cron_get_eventos.php`;
- `classes/Backend/v1/ExtratorEventosGemini.php`.

Fluxo:

1. lê `links_config`;
2. baixa cada URL;
3. prioriza JSON-LD `schema.org/Event`;
4. usa Gemini como fallback;
5. normaliza os campos;
6. faz upsert por `txtid`;
7. mantém tags e vínculos;
8. atualiza a data da fonte;
9. devolve relatório JSON.

O disparo depende de cron externo. A proteção HTTP só ocorre quando `CRON_TOKEN` está configurado. O módulo precisa de proteção SSRF, transação, lock, retries e histórico de execução.

## Guia cultural com IA

Rota: `/chat`.

Arquivos:

- `pages/chat/chat.php`;
- `pages/chat/chat.vue.php`;
- `classes/Sistema/ChatIA.php`.

O navegador mantém o histórico e chama `ChatIA::getRespostaIA()`. O backend carrega até 50 eventos futuros, monta o contexto e consulta o Gemini. O histórico do chat não é persistido no banco.

Sanitize a resposta antes de convertê-la em HTML.

## Administração

### Login e permissões

Rotas principais:

- `/admin`;
- `/adm-home?item={form}`;
- `/adm-logout`.

Arquivos:

- `admin/loginSystem.php`;
- `functions/systemFunctions.php`;
- `classes/Sistema/Admin/Menu.php`;
- `admin/menu.php`;
- `functions/auto_perfil.php`;
- `functions/auto_usuario.php`.

Usuários ficam em `system_admin`; permissões em `system_perfil`; bloqueios em `system_block`. Permissões incluem menu, inclusão, edição, exclusão, visualização, listagem e botões adicionais.

### CRUD por metadados

Arquivos:

- `functions/systemFunctions_formsNew.php`;
- `action/insert_global.php`;
- `action/edit_global.php`;
- `action/delete_global.php`;
- `admin/admin_content.php`.

Fluxo:

```text
/adm-home?item=N
  -> definição gerada
  -> lista/filtro/formulário
  -> ação global
  -> hooks pre*/pos*
```

Módulos registrados no menu observado:

- Configurações — `system_config`;
- Perfis — `system_perfil`;
- Pessoas — `system_admin`;
- Clientes — `system_admin` com perfil específico;
- Links — `links_config`;
- Estabelecimentos — `estabelecimentos`.

Links alimentam a importação de eventos. Estabelecimentos dependem de estado e cidade. Pessoas dependem de perfis.

### Construtor de formulários

Rotas:

- `/system-form`;
- `/system-addform`;
- `/system-formInput/{id}`;
- `/system-addinput`;
- `/system-delform`;
- `/system-rebuild` (regenera artefatos; disponível mesmo com IDE desligada).

Arquivos:

- `system/pages/form.php`;
- `system/pages/addForm.php`;
- `system/pages/formInput.php`;
- `system/gera_definicoes_de_tabelas.php`;
- `system/gera_arquivos_de_listagem.php`.

O construtor altera metadados e gera o código consumido pelo CRUD. Exige acesso administrativo. Consulte [system-admin.md](system-admin.md) e [generated-admin.md](generated-admin.md) antes de qualquer mudança.

## Projetos, tarefas e cronogramas

Este subsistema legado possui implementação completa, mas seus formulários antigos não aparecem no registro administrativo atual.

Rotas:

- `/adm-cronograma/{txtid}`;
- `/adm-compartilhar/{txtid}`;
- `/share/{txtid}`.

Arquivos:

- `classes/Sistema/Projetos.php`;
- `classes/Sistema/Tarefas.php`;
- `classes/Sistema/Cronograma.php`;
- `pages/cronograma/`;
- `pages/compartilhar/`;
- `pages/share/`.

Projetos podem pertencer ao criador ou ser associados por `projeto_pessoa`. Tarefas pertencem a projetos e podem compor cronogramas hierárquicos. A interface Vue funciona como editor Gantt e salva mudanças por AJAX. O compartilhamento público depende do campo `cronograma.publico`.

## Chatbot de apoio via WhatsApp

Webhook: `/action-get_whatsapp`.

Arquivos:

- `action/get_whatsapp.php`;
- `classes/Sistema/Whatsapp.php`;
- `classes/Sistema/OpenAiClass.php`;
- `classes/Sistema/Chatbot.php`;
- `functions/auto_informacoes.php`.

Fluxo:

1. valida o challenge do webhook;
2. marca a mensagem como lida;
3. recupera histórico recente;
4. persiste contato e mensagem;
5. usa OpenAI para classificar assunto e entendimento;
6. busca conteúdo de apoio;
7. gera e envia resposta pela Graph API.

Tabelas principais: `contato`, `mensagem`, `informacao` e `system_config`.

Este chatbot é independente do Guia Cultural.

## API móvel legada

Arquivos:

- `rest/rest.php`;
- `rest/api.php`;
- `rest/auto_server.php`;
- `classes/Backend/v0/`;
- `classes/Backend/v1/`.

Rotas:

- `GET /rest/token`;
- `POST /rest/v1/rfc`.

Operações encontradas:

- login, cadastro, perfil, sessão e recuperação;
- feed, pesquisa, recomendações, denúncias e compras;
- dados de loja;
- contato, banners e termos;
- token Firebase.

O despacho RFC resolve dinamicamente classe e método. A autenticação usa registros na tabela `session`; confirme em cada método se a falha de `requireAuth()` encerra o processamento.

## Conteúdo institucional legado

Rotas e páginas:

- `/quem_somos`;
- `/projetos`;
- `/projeto`;
- `/blog`;
- `/p/{slug}`;
- objetos `pages/obj_*`.

Esses módulos usam páginas PHP, CSS e Vue, além de componentes reutilizáveis. Confirme se ainda estão ligados por menus ou links antes de alterá-los.

## Utilitários e módulos secundários

- Contato: `action/contato.php`.
- Recuperação de senha web: `action/recuperar_senha.php`.
- Upload autenticado: `action/upload_image.php`.
- Baixa de faturas: `action/baixa_faturas.php`.
- E-mail legado: `functions/auto_email_smtp.php`.
- Push/GCM legado: `functions/auto_push.php`.
- Instagram/Facebook: `functions/auto_instagram.php`.
- Imagens/Asido: `functions/auto_image_asido.php`.
- Pagar.me: `functions/auto_pagarme.php`, observado como comentado/inativo.
- PDF: `pdf.php`.
- SOAP: `ws.php`.

## Como decidir se um módulo está ativo

1. Procure a rota no código e no `.htaccess`.
2. Procure links em páginas, containers e menu administrativo.
3. Procure chamadas à classe, função ou ação.
4. Confirme tabelas e metadados no banco.
5. Verifique cron, webhook ou callback externo.
6. Não conclua atividade apenas pela existência de arquivos.
