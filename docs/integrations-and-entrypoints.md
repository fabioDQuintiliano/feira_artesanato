# Integrações e pontos de entrada

## Pontos de entrada principais

- `index.php`: redireciona para `home_v3`.
- `controle-includes.php`: roteador web central.
- `rest.php`: entrada da API.
- `ws.php`: serviço SOAP/NuSOAP.
- `cron-casos.php`: notificações legadas.
- `action/cron_get_eventos.php`: importação cultural.
- `retorno_pagarme.php`: callback de pagamento legado.
- `pdf.php`: geração de PDF.
- `uploadifive.php` e `imagem_usuario.php`: upload e imagens.

Há ainda arquivos de teste, diagnóstico e publicação que podem ser acessíveis diretamente pelo servidor web. Antes de implantar, inventarie regras do Apache e remova ou restrinja os endpoints desnecessários.

## Gemini e importação de eventos

Implementação:

- `classes/Backend/v1/ExtratorEventosGemini.php`;
- `action/cron_get_eventos.php`;
- `classes/Sistema/ChatIA.php`.

A importação:

1. lê URLs de `links_config`;
2. baixa o HTML com Symfony HttpClient;
3. tenta extrair eventos de JSON-LD;
4. quando necessário, envia parte do conteúdo ao Gemini;
5. grava eventos, tags e vínculos.

Cuidados:

- validar e restringir URLs para impedir SSRF;
- bloquear destinos locais, privados e redirecionamentos perigosos;
- tratar o texto remoto como entrada não confiável e possível prompt injection;
- usar transação para evento e tags;
- adicionar lock, idempotência, retries e histórico da execução;
- sanitizar conteúdo antes de persistir ou renderizar.

O chat cultural envia eventos e o histórico recebido do navegador ao Gemini. A resposta não deve ser inserida como HTML sem sanitização.

## OpenAI

O cliente Composer e uma integração em `classes/Sistema/OpenAiClass.php` coexistem com Gemini. Confirme se a classe possui chamadas ativas antes de expandi-la. Chaves não devem permanecer em arquivos PHP.

## WhatsApp e Meta

Arquivos centrais:

- `classes/Sistema/Whatsapp.php`;
- `pages/get_whatsapp.php`;
- integrações antigas com Facebook/Instagram.

`pages/get_whatsapp.php` pode registrar requisições em arquivos de texto. Evite persistir payloads completos com dados pessoais ou tokens.

## Firebase e push

Arquivos:

- `classes/Firebase/NotificationFirebase.php`;
- `classes/Firebase/firebase_credentials.json`;
- `functions/auto_push.php`.

Credenciais de serviço não devem ficar versionadas. Use segredo externo, restrinja permissões e faça rotação das chaves existentes.

## E-mail

Implementação observada:

- `functions/auto_email_smtp.php`;
- `email/recuperar_senha.htm`.

O código usa uma versão antiga do PHPMailer e sua configuração não garante SMTP autenticado. As constantes Mailjet de `config.php` não apareceram em uso no fluxo analisado.

Antes de depender desse módulo, teste entrega, TLS, autenticação, remetente, erros e codificação em ambiente controlado.

## PDF

`pdf.php` usa mPDF legado e HTML armazenado em `$_SESSION['save_pdf']`. O nome do arquivo pode vir da requisição. Normalize o nome e não aceite caminhos ou caracteres de controle.

## Uploads

Locais e endpoints incluem:

- `images/upload/`;
- `arquivos/`;
- `action/upload_image.php`;
- `componente/upload_arquivo.php`;
- cadastro público de evento em Base64.

Validar MIME real, extensão permitida, tamanho, dimensões, nome aleatório e destino fora da árvore executável. A extensão enviada pelo cliente não é suficiente.

## Pagamentos e serviços legados

Há código de Pagar.me, PagSeguro, Google Maps, GCM, Facebook e outros serviços antigos. Parte pode estar inativa. Para cada alteração:

1. procurar chamadas atuais;
2. confirmar credenciais e versão da API;
3. identificar callback público;
4. verificar assinatura e idempotência;
5. não reativar código somente porque o arquivo existe.
