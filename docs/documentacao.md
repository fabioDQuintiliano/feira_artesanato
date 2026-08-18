# Documentação de implementações

Toda implementação nova neste repositório precisa de um arquivo em `docs/`.

## O que documentar

- Módulo de painel, tabela, endpoint, página pública, hook, integração ou fluxo operacional novo.
- SQL de schema e de carga de conteúdo (em `.md`, não só no chat).
- Como instalar, testar e o que não editar (artefatos gerados).

## Como fazer

1. Criar ou atualizar `docs/{tema}.md` em português.
2. Incluir o arquivo no índice de [README.md](README.md).
3. Ligar o texto aos guias já existentes (`system-admin.md`, `criar-modulo-admin.md`, etc.) em vez de copiar o sistema inteiro.
4. Não colocar senhas, tokens, chaves ou connection strings.

## Nomeação

| Tipo | Arquivo típico |
| --- | --- |
| Fluxo reutilizável | `docs/criar-modulo-admin.md` |
| SQL / conteúdo de produto | `docs/sql-ceramistas.md` |
| Comportamento de um módulo | `docs/{modulo}.md` |

Se a mudança for pequena, atualize o `.md` que já cobre o tema. Não espalhe a mesma regra em três arquivos sem um índice.
