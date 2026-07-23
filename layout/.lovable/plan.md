
# Substituir Playfair Display por Syne

## Objetivo
Trocar a fonte serifada **Playfair Display** pela fonte sans-serif **Syne** nos títulos de destaque do site, mantendo o visual marcante e moderno.

---

## Onde a fonte aparece atualmente

Os elementos que usam `font-display` (Playfair Display):
- **Header**: "Agenda Cultural" no logo
- **Hero**: "Sua Agenda Cultural em um só lugar"
- **Footer**: "Agenda Cultural" no logo

---

## Alterações necessárias

### 1. Atualizar importação da fonte

**Arquivo**: `src/index.css`

Substituir a importação do Google Fonts:
- Remover: `Playfair+Display`
- Adicionar: `Syne`

### 2. Atualizar configuração do Tailwind

**Arquivo**: `tailwind.config.ts`

Alterar a família de fontes `display`:
- De: `['Playfair Display', 'serif']`
- Para: `['Syne', 'sans-serif']`

---

## Impacto visual

A fonte **Syne** traz:
- Visual geométrico e bold
- Estética contemporânea que combina com o tema dark
- Maior impacto em títulos grandes
- Consistência com a identidade sans-serif do resto do site (Inter)

---

## Arquivos a modificar

| Arquivo | Alteração |
|---------|-----------|
| `src/index.css` | Atualizar URL do Google Fonts |
| `tailwind.config.ts` | Alterar `font-display` para Syne |
