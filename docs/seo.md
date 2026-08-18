# SEO e compartilhamento (Open Graph)

As tags do `<head>` das páginas públicas passam por `classes/Sistema/Seo.php`, chamada em `page.php` depois de a página definir `$MASTER_SEO` e/ou as variáveis legadas `$MASTER_PAGETITLE`, `$MASTER_DESCRIPTION`, `$MASTER_KEYWORDS`, `$MASTER_IMAGE` e `$MASTER_ADD_TO_HEADER`.

O HTML gerado inclui:

- `title`, `description`, `keywords`, `robots`, `canonical`, `hreflang`;
- Open Graph (`og:title`, `og:description`, `og:url`, `og:image` com width/height/type/alt) — usado pelo **Facebook**, **WhatsApp**, Instagram e LinkedIn;
- Twitter Cards (`summary_large_image`);
- microdados `itemprop` (nome, descrição, imagem);
- JSON-LD (`application/ld+json`) quando a página informa `json_ld`.

A URL canônica e o `og:url` são absolutos (`https://…`). O `og:url` antigo (só host + path, sem esquema) quebrava a prévia no WhatsApp e no Facebook.

## Como uma página informa o SEO

Opção preferida: array `$MASTER_SEO`.

```php
$MASTER_SEO = [
    'title' => 'Título da página',
    'description' => 'Até ~160 caracteres.',
    'keywords' => 'termo um, termo dois',
    'image' => rtrim(ROOT, '/') . '/images/exemplo.jpg',
    'image_alt' => 'Texto alternativo da imagem',
    'type' => 'website', // ou profile, article
    'site_name' => 'Nome do site',
    'canonical' => rtrim(ROOT, '/') . '/rota',
    'author' => 'Nome',
    'theme_color' => '#D95D2B',
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'geo_placename' => 'Arceburgo',
    'geo_region' => 'BR-MG',
    'geo_position' => '-21.36;-46.93',
    'json_ld' => [/* array Schema.org */],
];
```

Se `$MASTER_SEO` omitir um campo, `page.php` completa com `$MASTER_*`.

## Encontro de ceramistas

| Página | Imagem de compartilhamento | JSON-LD |
| --- | --- | --- |
| `/ceramistas` | `images/ceramistas/arceburgo.jpg` | `Event` + `WebSite` |
| `/expositor/{slug}` | foto de destaque (ou a mesma foto do encontro) | `ProfilePage` |
| expositor 404 | foto do encontro | sem indexação (`noindex, follow`) |

Favicon e apple-touch-icon: `images/ceramistas/favicon.png` (ícone quadrado mão + vaso). Layout: `containers/layout_ceramistas.php`. Regenerar `containers/exe_system/layout_ceramistas_head.php` se o codegen estiver desligado.

## Prévia no Facebook e no WhatsApp

1. A imagem precisa ser URL **pública e absoluta** (https), de preferência JPEG/PNG com pelo menos 200×200 px (ideal ~1200×630).
2. Depois de publicar, atualize o cache do Facebook em [Sharing Debugger](https://developers.facebook.com/tools/debug/).
3. No WhatsApp, a prévia usa as mesmas tags `og:*`. Se a imagem não aparecer, confirme https, tamanho e que o arquivo não está bloqueado por `robots`/`login`.

Não coloque tokens, App ID ou chaves de API nas meta tags sem necessidade. `fb:app_id` só deve existir se houver um app Meta configurado para o domínio.
