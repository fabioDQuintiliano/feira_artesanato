# Documentação técnica do Eventos

Este diretório resume o funcionamento do sistema para agentes de IA e pessoas que precisem localizar, compreender ou alterar um módulo com segurança.

## Regras obrigatórias

- Não pesquisar nem alterar `_public/` e `_publish/`.
- Não editar `tables/`: ela contém artefatos gerados automaticamente a partir de metadados do banco.
- Tratar `admin/exe_system/`, `containers/exe_system/` e `functions/__list_functions.php` como arquivos derivados antes de modificá-los.
- Não copiar credenciais, tokens ou chaves encontradas no código para documentação, logs, testes ou respostas.
- Evitar executar a aplicação contra outro banco sem avaliar a geração automática de arquivos.
- Confirmar o estado do repositório antes de alterar arquivos: parte do código atual pode não estar versionada.

## Índice

- [Visão da arquitetura](architecture.md)
- [Ciclo das requisições](request-lifecycle.md)
- [Convenções do frontend](frontend-conventions.md)
- [Persistência e modelo de dados](data-access.md)
- [Administração e arquivos gerados](generated-admin.md)
- [Autenticação e sessões](authentication-and-sessions.md)
- [Módulos funcionais](modules.md)
- [Integrações e pontos de entrada](integrations-and-entrypoints.md)
- [Operação e ambiente](operations.md)
- [Docker no WSL](docker-wsl.md)
- [Pontos críticos de segurança](security-hotspots.md)
- [Guia rápido para agentes de IA](ai-agent-guide.md)

## Resumo do sistema

O projeto é um monólito PHP customizado, sem framework MVC principal. O núcleo atual é uma agenda cultural, mas a base também contém módulos legados de projetos anteriores. O sistema combina:

- Apache e `mod_rewrite`;
- roteamento próprio por prefixos;
- páginas PHP renderizadas no servidor;
- Vue 2 e jQuery carregados sem pipeline de build;
- administração CRUD orientada por metadados do MySQL;
- API REST construída sobre Slim 2;
- múltiplas camadas de acesso ao banco;
- integrações com serviços de IA, mensageria, push e serviços legados.

## Arquivos iniciais para investigação

| Objetivo | Arquivos |
| --- | --- |
| Bootstrap e configuração | `config.php`, `front_includes.php`, `autoload.php` |
| Roteamento web | `.htaccess`, `controle-includes.php`, `page.php` |
| Páginas públicas | `pages/`, `containers/` |
| Regras de negócio atuais | `classes/Sistema/` |
| Ações HTTP | `action/` |
| Funções compartilhadas | `functions/` |
| API | `rest.php`, `rest/`, `classes/Backend/` |
| Administração | `adm.php`, `admsite.php`, `admin/`, `system/` |
| Código gerado | `tables/`, `admin/exe_system/`, `containers/exe_system/` |

## Limites desta documentação

O esquema do banco foi inferido do código e dos arquivos gerados; índices, constraints, triggers e dados reais não foram consultados diretamente. Esta documentação descreve o estado observado no filesystem e deve ser atualizada quando o comportamento do banco for confirmado.
