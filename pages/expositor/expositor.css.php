<style>
@font-face {
    font-family: "Bulings Nature";
    src: url("<?= ROOT ?>fonts/bulings-nature/bulings-nature.otf") format("opentype");
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}
@font-face {
    font-family: "Bulings Nature";
    src: url("<?= ROOT ?>fonts/bulings-nature/bulings-nature-italic.otf") format("opentype");
    font-weight: 400;
    font-style: italic;
    font-display: swap;
}

:root {
    --cer-clay: #D95D2B;
    --cer-clay-deep: #B84A1F;
    --cer-clay-soft: #F4E4D8;
    --cer-ink: #2C1E15;
    --cer-muted: #6B5346;
    --cer-line: #E8D5C6;
    --cer-paper: #FFFAF6;
    --cer-cream: #FFF4EA;
    --cer-on-clay: #FFF7F0;
    --cer-font-main: "Bulings Nature", "Source Sans 3", Georgia, serif;
    --cer-font-body: "Source Sans 3", "Segoe UI", sans-serif;
    --cer-ease: cubic-bezier(0.22, 1, 0.36, 1);
    --cer-header-h: 4.5rem;
}

*, *::before, *::after { box-sizing: border-box; }
html { scroll-behavior: smooth; }

body.body-ceramistas {
    margin: 0;
    color: var(--cer-ink);
    background: var(--cer-paper);
    font-family: var(--cer-font-main);
    font-weight: 400;
    font-size: 1.12rem;
    line-height: 1.55;
    -webkit-font-smoothing: antialiased;
}

[v-cloak] { display: none; }
img { max-width: 100%; display: block; }
a { color: inherit; }

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}

.cer-wrap {
    width: min(1080px, calc(100% - 2.5rem));
    margin-inline: auto;
}

.cer-eyebrow {
    margin: 0 0 0.5rem;
    font-size: 0.78rem;
    font-weight: 400;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--cer-clay);
    font-family: var(--cer-font-body);
}

h1 {
    font-family: var(--cer-font-main);
    font-weight: 400;
    line-height: 1.15;
    margin: 0 0 0.75rem;
    font-size: clamp(2rem, 4vw, 2.8rem);
    color: var(--cer-ink);
}

.cer-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 2.85rem;
    padding: 0.7rem 1.3rem;
    border-radius: 0.35rem;
    border: 1px solid var(--cer-clay);
    background: var(--cer-clay);
    color: #fff;
    font-family: inherit;
    font-size: 0.92rem;
    font-weight: 400;
    text-decoration: none;
    transition: background 0.2s, border-color 0.2s, color 0.2s;
}
.cer-btn:hover {
    background: var(--cer-clay-deep);
    border-color: var(--cer-clay-deep);
}
.cer-btn--compact {
    min-height: 2.3rem;
    padding: 0.4rem 0.9rem;
    font-size: 0.85rem;
}
.cer-btn--ghost {
    background: transparent;
    color: var(--cer-clay-deep);
}
.cer-btn--ghost:hover {
    background: var(--cer-clay-soft);
    border-color: var(--cer-clay);
    color: var(--cer-clay-deep);
}
.cer-header .cer-btn--compact {
    border-color: var(--cer-cream);
    background: var(--cer-cream);
    color: var(--cer-clay-deep);
}
.cer-header .cer-btn--compact:hover {
    background: #fff;
    border-color: #fff;
}

.cer-header {
    position: fixed;
    inset: 0 0 auto;
    z-index: 40;
    height: var(--cer-header-h);
    display: flex;
    align-items: center;
    background: var(--cer-clay);
    color: var(--cer-on-clay);
    transition: box-shadow 0.25s var(--cer-ease);
}
.cer-header.is-scrolled {
    box-shadow: 0 8px 24px rgba(44, 30, 21, 0.18);
}
.cer-header__inner {
    width: min(1080px, calc(100% - 2.5rem));
    margin-inline: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.cer-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    flex-shrink: 0;
}
.cer-brand img {
    height: 2.75rem;
    width: auto;
}
.cer-nav {
    display: flex;
    align-items: center;
    gap: 1.2rem;
}
.cer-nav a {
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    font-family: var(--cer-font-body);
    color: rgba(255, 247, 240, 0.88);
}
.cer-nav a:hover { color: #fff; }
.cer-nav-toggle {
    display: none;
    width: 2.4rem;
    height: 2.4rem;
    border: 0;
    background: transparent;
    padding: 0;
    cursor: pointer;
}
.cer-nav-toggle span {
    display: block;
    width: 1.25rem;
    height: 1.5px;
    margin: 5px auto;
    background: var(--cer-on-clay);
}

.cer-profile {
    padding: calc(var(--cer-header-h) + 2.5rem) 0 4rem;
    min-height: 70vh;
}
.cer-profile--empty {
    text-align: left;
}
.cer-profile--empty p {
    font-family: var(--cer-font-body);
    color: var(--cer-muted);
    max-width: 28rem;
}
.cer-profile--empty .cer-btn { margin-top: 1rem; }

.cer-profile__layout {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: clamp(1.5rem, 4vw, 3rem);
    align-items: start;
}
.cer-profile__hero {
    margin: 0;
    overflow: hidden;
    border-radius: 0.35rem;
    background: var(--cer-clay-soft);
    border: 1px solid var(--cer-line);
    aspect-ratio: 4 / 5;
}
.cer-profile__hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cer-profile__thumbs {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(72px, 1fr));
    gap: 0.5rem;
    margin-top: 0.75rem;
}
.cer-profile__thumb {
    appearance: none;
    border: 1px solid var(--cer-line);
    padding: 0;
    border-radius: 0.25rem;
    overflow: hidden;
    cursor: pointer;
    background: none;
    aspect-ratio: 1;
}
.cer-profile__thumb.is-active,
.cer-profile__thumb:hover {
    border-color: var(--cer-clay);
}
.cer-profile__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cer-profile__resumo {
    margin: 0 0 1rem;
    font-family: var(--cer-font-body);
    font-size: 1.15rem;
    color: var(--cer-ink);
}
.cer-profile__desc {
    font-family: var(--cer-font-body);
    color: var(--cer-muted);
}
.cer-profile__desc p {
    margin: 0 0 0.9rem;
}
.cer-profile__links {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem 1rem;
    margin-top: 1.75rem;
}
.cer-profile__back {
    font-family: var(--cer-font-body);
    color: var(--cer-clay);
    text-decoration: none;
}
.cer-profile__back:hover { text-decoration: underline; }

.cer-footer {
    background: var(--cer-clay);
    color: var(--cer-on-clay);
    padding: 2rem 0;
}
.cer-footer__inner {
    display: flex;
    justify-content: space-between;
    align-items: end;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.cer-footer__brand img {
    height: 2.4rem;
    width: auto;
    margin-bottom: 0.5rem;
}
.cer-footer p { margin: 0; font-family: var(--cer-font-body); }
.cer-footer p.cer-footer__credit {
    display: block;
    width: 100%;
    margin: 1.25rem 0 0;
    padding: 1.1rem 1.25rem 0;
    border-top: 1px solid rgba(255, 247, 240, 0.2);
    font-size: 0.82rem;
    color: rgba(255, 247, 240, 0.78);
    text-align: center;
}
.cer-footer__credit a {
    color: inherit;
    text-decoration: none;
}

@media (max-width: 960px) {
    .cer-profile__layout { grid-template-columns: 1fr; }
    .cer-profile__hero { aspect-ratio: 5 / 4; max-height: 480px; }
}

@media (max-width: 760px) {
    .cer-nav-toggle { display: block; }
    .cer-nav {
        position: fixed;
        inset: var(--cer-header-h) 0 auto;
        display: none;
        flex-direction: column;
        align-items: stretch;
        gap: 0;
        padding: 0.75rem 1rem 1.15rem;
        background: var(--cer-clay);
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }
    .cer-nav.is-open { display: flex; }
    .cer-nav a {
        padding: 0.8rem 0.15rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        color: var(--cer-on-clay);
    }
    .cer-nav .cer-btn {
        margin-top: 0.75rem;
        justify-content: center;
    }
    .cer-brand img { height: 2.35rem; }
    .cer-footer__inner { align-items: start; }
}
</style>
