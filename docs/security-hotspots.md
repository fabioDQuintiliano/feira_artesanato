# Pontos críticos de segurança

Este documento é um inventário de manutenção, não uma confirmação de que cada item já foi explorado. Não reproduz valores secretos.

## Prioridade crítica

### Segredos no código

Foram observadas credenciais e chaves em arquivos PHP e JSON. Considere-as comprometidas:

1. rotacione os valores nos provedores;
2. remova-os do código e do histórico;
3. carregue-os de variáveis de ambiente ou cofre;
4. limite permissões por serviço e ambiente.

Locais de atenção incluem `config.php`, classes de IA, WhatsApp, Firebase e scripts de publicação.

### Invocação dinâmica

Superfícies:

- rotas `fn-*`;
- `ajax_load_class_function`;
- `/rest/v1/rfc`.

Funções, classes e métodos podem ser escolhidos pelo cliente. Existe validação de login ineficaz no fluxo AJAX observado. Use listas permitidas, autenticação obrigatória e autorização dentro de cada operação.

### SQL injection

`Model`, `DB_Classe`, `_where()`, ordenação, limites e consultas livres podem interpolar entrada. Migre valores para prepared statements e restrinja identificadores por lista permitida.

### SSRF e IA

A importação acessa URLs configuradas e envia conteúdo externo ao Gemini. Bloqueie IPs locais/privados, esquemas não HTTP, redirecionamentos perigosos e respostas excessivas. Trate o conteúdo como prompt injection.

### Código gerado em runtime

Requisições podem reescrever arquivos PHP a partir de metadados do banco. Isso permite corrida, arquivo parcial e propagação de código armazenado no banco. Prefira geração controlada no deploy.

## Aplicação web

- CORS está aberto globalmente.
- Não há proteção CSRF central confirmada.
- O token criado por `page.php` não é validado de forma consistente.
- Login administrativo usa SHA-1.
- O ID da sessão não parece ser regenerado após login.
- Erros e stack traces podem revelar SQL e infraestrutura.
- `oca-check.php` expõe informações do PHP.
- Endpoints de teste, publish e atualização podem estar públicos.

## API

- Tokens possuem validade longa.
- O header de autorização aceita formatos distintos.
- Alguns fluxos podem continuar após falha de `requireAuth()`.
- A resolução de operação RFC é dinâmica.
- O modo de desenvolvimento da API foi observado habilitado.

## Conteúdo e XSS

- Respostas da IA podem ser convertidas para HTML.
- Descrições importadas podem conter conteúdo externo.
- Vue usa `v-html` em fluxos relevantes.
- HTML armazenado precisa de sanitização por lista permitida no servidor.

## Cadastro público

O cadastro de eventos:

- não exige autenticação;
- não possui CAPTCHA ou rate limit observado;
- não grava moderação efetiva, apesar da mensagem da interface;
- pode publicar evento futuro imediatamente;
- grava evento e tags sem transação;
- aceita imagem Base64 sem limite e validação robusta.

## Uploads

- extensão pode ser determinada pelo nome;
- MIME, tamanho e conteúdo nem sempre são validados;
- arquivos ficam na árvore web;
- caminhos relativos podem produzir destinos inesperados;
- anexos antigos podem permanecer após atualização.

Use armazenamento não executável, nomes aleatórios, limites, verificação MIME e processamento seguro de imagem.

## Checklist antes de expor uma rota

- autenticação e autorização explícitas;
- CSRF para sessão baseada em cookie;
- rate limit;
- validação de tipos e limites;
- SQL parametrizado;
- saída escapada ou sanitizada;
- upload restrito;
- mensagens de erro genéricas;
- logs sem dados sensíveis;
- teste de chamada direta, não apenas pela interface.
