<style>
:root {
    --cer-clay: #E96B35;
    --cer-clay-deep: #D95D2B;
    --cer-ink: #3A271C;
    --cer-paper: #FFF9F1;
    --cer-gold: #F6C85A;
    --cer-terra: #C47A3A;
    --cer-sand: #FFE8C8;
    --cer-peach: #FFE0C8;
    --cer-sky: #FFF3D6;
    --cer-mist: rgba(233, 107, 53, 0.12);
    --cer-font-display: "Playfair Display", Georgia, serif;
    --cer-font-hand: "Caveat", cursive;
    --cer-font-body: "Source Sans 3", "Segoe UI", sans-serif;
    --cer-ease: cubic-bezier(0.22, 1, 0.36, 1);
    --cer-header-h: 5.25rem;
}

*, *::before, *::after { box-sizing: border-box; }

html { scroll-behavior: smooth; }

body.body-ceramistas {
    margin: 0;
    color: var(--cer-ink);
    background:
        radial-gradient(1000px 520px at 8% -8%, rgba(246, 200, 90, 0.45), transparent 55%),
        radial-gradient(900px 480px at 95% 12%, rgba(233, 107, 53, 0.18), transparent 50%),
        radial-gradient(700px 400px at 50% 60%, rgba(255, 224, 200, 0.55), transparent 60%),
        linear-gradient(180deg, #FFF6E8 0%, var(--cer-paper) 45%, #FFEFD9 100%);
    font-family: var(--cer-font-body);
    font-weight: 400;
    font-size: 1.05rem;
    line-height: 1.65;
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

.skip-link {
    position: absolute;
    left: 1rem;
    top: -3rem;
    background: var(--cer-ink);
    color: #fff;
    padding: 0.6rem 1rem;
    z-index: 100;
    transition: top 0.2s;
}
.skip-link:focus { top: 1rem; }

.cer-wrap {
    width: min(1120px, calc(100% - 2.5rem));
    margin-inline: auto;
}

.cer-eyebrow {
    margin: 0 0 0.6rem;
    font-family: var(--cer-font-hand);
    font-size: 1.55rem;
    line-height: 1;
    color: var(--cer-clay);
}

h1, h2, h3 {
    font-family: var(--cer-font-display);
    font-weight: 600;
    line-height: 1.15;
    letter-spacing: -0.01em;
    color: var(--cer-ink);
}

h2 {
    margin: 0 0 0.85rem;
    font-size: clamp(2rem, 4vw, 3rem);
}

h3 {
    margin: 0 0 0.5rem;
    font-size: 1.35rem;
}

.cer-section {
    padding: clamp(4rem, 8vw, 6.5rem) 0;
}

.cer-section__head {
    max-width: 38rem;
    margin-bottom: 2.5rem;
}

.cer-section__head p:last-child {
    margin: 0;
    color: rgba(44, 30, 21, 0.78);
}

.cer-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    min-height: 3rem;
    padding: 0.75rem 1.45rem;
    border-radius: 999px;
    border: 1px solid transparent;
    background: linear-gradient(135deg, var(--cer-clay) 0%, #F08A4B 100%);
    color: #fff;
    font-family: var(--cer-font-body);
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.25s var(--cer-ease), background 0.25s, box-shadow 0.25s;
    box-shadow: 0 10px 28px rgba(233, 107, 53, 0.32);
}
.cer-btn:hover {
    background: linear-gradient(135deg, var(--cer-clay-deep) 0%, var(--cer-clay) 100%);
    transform: translateY(-2px);
}
.cer-btn--ghost {
    background: rgba(255, 255, 255, 0.16);
    color: #fff;
    border-color: rgba(255, 255, 255, 0.65);
    box-shadow: none;
    backdrop-filter: blur(6px);
}
.cer-btn--ghost:hover {
    background: rgba(255, 255, 255, 0.28);
}
.cer-btn--compact {
    min-height: 2.4rem;
    padding: 0.45rem 1rem;
    font-size: 0.85rem;
    box-shadow: 0 6px 16px rgba(233, 107, 53, 0.22);
}

/* Header */
.cer-header {
    position: fixed;
    inset: 0 0 auto;
    z-index: 40;
    transition: background 0.3s, box-shadow 0.3s, backdrop-filter 0.3s;
}
.cer-header.is-scrolled,
.cer-header.is-open {
    background: rgba(255, 249, 241, 0.94);
    backdrop-filter: blur(12px);
    box-shadow: 0 1px 0 var(--cer-mist);
}
.cer-header__inner {
    width: min(1120px, calc(100% - 2rem));
    margin: 0 auto;
    min-height: var(--cer-header-h);
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
    height: 3.6rem;
    width: auto;
    display: block;
    border-radius: 0.4rem;
}
.cer-nav {
    display: flex;
    align-items: center;
    gap: 1.15rem;
}
.cer-nav a {
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 500;
    color: var(--cer-ink);
    opacity: 0.82;
}
.cer-nav a:hover { opacity: 1; color: var(--cer-clay); }
.cer-nav-toggle {
    display: none;
    width: 2.6rem;
    height: 2.6rem;
    border: 0;
    background: transparent;
    padding: 0;
    cursor: pointer;
}
.cer-nav-toggle span {
    display: block;
    width: 1.35rem;
    height: 2px;
    margin: 5px auto;
    background: var(--cer-ink);
    transition: 0.25s;
}

/* Hero */
.cer-hero {
    position: relative;
    min-height: 100vh;
    min-height: 100svh;
    display: grid;
    align-items: end;
    color: #fff;
    overflow: hidden;
    background: linear-gradient(165deg, #F6C85A 0%, #E96B35 48%, #C47A3A 100%);
}
.cer-hero__media {
    position: absolute;
    inset: 0;
}
.cer-hero__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.28;
    transform: scale(1.04);
    animation: cer-ken 18s ease-in-out infinite alternate;
}
.cer-hero__veil {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(180deg, rgba(58, 39, 28, 0.18) 0%, rgba(58, 39, 28, 0.28) 45%, rgba(58, 39, 28, 0.55) 100%),
        radial-gradient(circle at 70% 20%, rgba(255, 243, 214, 0.35), transparent 50%);
}
.cer-hero__content {
    position: relative;
    z-index: 1;
    width: min(1120px, calc(100% - 2.5rem));
    margin: 0 auto;
    padding: calc(var(--cer-header-h) + 2.5rem) 0 4.5rem;
    max-width: 48rem;
}
.cer-hero__logo {
    display: block;
    width: min(100%, 40rem);
    height: auto;
    margin: 0 0 1.5rem;
}
.cer-hero h1 {
    margin: 0 0 1rem;
    color: #fff;
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    text-wrap: balance;
}
.cer-hero__lead {
    margin: 0 0 1.4rem;
    font-size: 1.12rem;
    font-weight: 300;
    max-width: 34rem;
    color: rgba(255, 255, 255, 0.9);
}
.cer-hero__meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem 1.5rem;
    margin: 0 0 1.9rem;
    max-width: 36rem;
}
.cer-hero__when,
.cer-hero__where {
    display: grid;
    gap: 0.2rem;
    padding: 0.15rem 0 0.15rem 1rem;
    border-left: 3px solid var(--cer-gold);
}
.cer-hero__meta-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-family: var(--cer-font-hand);
    font-size: 1.35rem;
    line-height: 1;
    color: var(--cer-gold);
}
.cer-hero__meta-label svg {
    width: 1.05rem;
    height: 1.05rem;
}
.cer-hero__when strong,
.cer-hero__where strong {
    font-family: var(--cer-font-display);
    font-size: clamp(1.45rem, 3.2vw, 2rem);
    font-weight: 700;
    line-height: 1.15;
    color: #fff;
    letter-spacing: -0.01em;
}
.cer-hero__meta-note {
    font-size: 0.92rem;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.82);
}
.cer-hero__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

@media (max-width: 560px) {
    .cer-hero__meta {
        grid-template-columns: 1fr;
    }
}

/* Sobre */
.cer-sobre__grid,
.cer-musica__grid,
.cer-kids__grid,
.cer-contato__grid {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: clamp(1.5rem, 4vw, 3.5rem);
    align-items: center;
}
.cer-sobre__figure,
.cer-musica__figure,
.cer-kids figure {
    margin: 0;
    overflow: hidden;
    border-radius: 1.5rem 0.4rem 1.5rem 0.4rem;
    box-shadow: 0 24px 50px rgba(233, 107, 53, 0.14);
}
.cer-sobre__figure img,
.cer-musica__figure img,
.cer-kids figure img {
    width: 100%;
    height: min(520px, 70vw);
    object-fit: cover;
}

/* Programação */
.cer-programacao {
    background:
        linear-gradient(180deg, rgba(246, 200, 90, 0.22), transparent 40%),
        linear-gradient(0deg, rgba(255, 224, 200, 0.55), transparent 45%);
}
.cer-tabs {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.cer-tabs__btn {
    appearance: none;
    border: 1px solid rgba(233, 107, 53, 0.22);
    background: rgba(255, 255, 255, 0.78);
    border-radius: 1rem;
    padding: 0.85rem 1.15rem;
    text-align: left;
    cursor: pointer;
    min-width: 10rem;
    transition: 0.25s var(--cer-ease);
    font-family: inherit;
    color: var(--cer-ink);
}
.cer-tabs__btn strong {
    display: block;
    font-family: var(--cer-font-display);
    font-size: 1.05rem;
}
.cer-tabs__btn span {
    font-size: 0.88rem;
    opacity: 0.7;
}
.cer-tabs__btn.is-active,
.cer-tabs__btn:hover {
    border-color: var(--cer-clay);
    background: #fff;
    box-shadow: 0 10px 24px rgba(233, 107, 53, 0.16);
}
.cer-timeline {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 1rem;
}
.cer-timeline__item {
    display: grid;
    grid-template-columns: 9.5rem 1fr;
    gap: 1rem;
    padding: 1.15rem 1.25rem;
    border-left: 3px solid rgba(246, 200, 90, 0.7);
    background: rgba(255, 255, 255, 0.72);
    border-radius: 0 0.85rem 0.85rem 0;
    transition: border-color 0.25s, transform 0.25s var(--cer-ease);
}
.cer-timeline__item.is-destaque {
    border-left-color: var(--cer-clay);
    background: #fff;
}
.cer-timeline__item:hover { transform: translateX(4px); }
.cer-timeline__time {
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
}
.cer-timeline__hour {
    font-family: var(--cer-font-display);
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--cer-terra);
}
.cer-timeline__icon {
    width: 2.1rem;
    height: 2.1rem;
    color: var(--cer-clay);
}
.cer-timeline__icon svg { width: 100%; height: 100%; }
.cer-timeline__body h3 { margin-bottom: 0.35rem; }
.cer-timeline__body p {
    margin: 0 0 0.55rem;
    color: rgba(58, 39, 28, 0.78);
}
.cer-timeline__local {
    font-size: 0.86rem;
    color: var(--cer-terra);
    font-weight: 600;
}

/* Expositores */
.cer-expo-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}
.cer-expo {
    appearance: none;
    border: 0;
    padding: 0;
    text-align: left;
    background: transparent;
    cursor: pointer;
    display: grid;
    gap: 0.9rem;
    font: inherit;
    color: inherit;
    transition: transform 0.35s var(--cer-ease);
}
.cer-expo:hover { transform: translateY(-6px); }
.cer-expo:focus-visible {
    outline: 2px solid var(--cer-clay);
    outline-offset: 4px;
}
.cer-expo__media {
    display: block;
    overflow: hidden;
    border-radius: 1.25rem 0.35rem;
    aspect-ratio: 4 / 5;
    background: var(--cer-sand);
}
.cer-expo__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s var(--cer-ease);
}
.cer-expo:hover .cer-expo__media img { transform: scale(1.06); }
.cer-expo__cat {
    display: block;
    font-family: var(--cer-font-hand);
    font-size: 1.25rem;
    color: var(--cer-clay);
    margin-bottom: 0.15rem;
}
.cer-expo__meta strong {
    display: block;
    font-family: var(--cer-font-display);
    font-size: 1.35rem;
    margin-bottom: 0.35rem;
}
.cer-expo__resumo {
    display: block;
    color: rgba(44, 30, 21, 0.75);
    font-size: 0.95rem;
}
.cer-expo__cta {
    display: inline-block;
    margin-top: 0.7rem;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--cer-clay);
}

/* Sabores / alimentação */
.cer-sabores {
    background:
        radial-gradient(700px 360px at 15% 20%, rgba(246, 200, 90, 0.55), transparent 55%),
        radial-gradient(600px 320px at 90% 80%, rgba(233, 107, 53, 0.18), transparent 50%),
        linear-gradient(160deg, #FFF1D4 0%, #FFE0C2 50%, #FFD7B0 100%);
    color: var(--cer-ink);
}
.cer-sabores .cer-eyebrow { color: var(--cer-clay); }
.cer-sabores h2 { color: var(--cer-ink); }
.cer-sabores__intro {
    display: flex;
    justify-content: space-between;
    gap: 1.5rem;
    align-items: end;
    margin-bottom: 2.25rem;
}
.cer-sabores__intro > div:first-child { max-width: 38rem; }
.cer-sabores__intro p:last-child {
    margin: 0;
    color: rgba(58, 39, 28, 0.78);
}
.cer-sabores__badges {
    display: flex;
    gap: 0.75rem;
}
.cer-sabores__badges span {
    width: 2.75rem;
    height: 2.75rem;
    color: var(--cer-clay);
    opacity: 0.95;
}
.cer-sabores__badges svg { width: 100%; height: 100%; }
.cer-sabores-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.15rem;
}
.cer-sabor {
    appearance: none;
    border: 1px solid rgba(233, 107, 53, 0.18);
    background: rgba(255, 255, 255, 0.78);
    padding: 0;
    text-align: left;
    cursor: pointer;
    display: grid;
    grid-template-rows: auto 1fr;
    font: inherit;
    color: inherit;
    overflow: hidden;
    border-radius: 1rem 0.35rem;
    transition: transform 0.35s var(--cer-ease), border-color 0.25s, background 0.25s, box-shadow 0.25s;
}
.cer-sabor:hover {
    transform: translateY(-5px);
    border-color: rgba(233, 107, 53, 0.45);
    background: #fff;
    box-shadow: 0 16px 36px rgba(233, 107, 53, 0.16);
}
.cer-sabor:focus-visible {
    outline: 2px solid var(--cer-clay);
    outline-offset: 3px;
}
.cer-sabor__media {
    position: relative;
    display: block;
    aspect-ratio: 16 / 11;
    overflow: hidden;
    background: var(--cer-sand);
}
.cer-sabor__media > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.7s var(--cer-ease);
}
.cer-sabor:hover .cer-sabor__media > img { transform: scale(1.05); }
.cer-sabor__logo {
    position: absolute;
    left: 0.85rem;
    bottom: 0.85rem;
    width: 3.4rem;
    height: 3.4rem;
    border-radius: 0.45rem;
    overflow: hidden;
    background: #fff;
    border: 2px solid #fff;
    box-shadow: 0 6px 16px rgba(58, 39, 28, 0.15);
}
.cer-sabor__logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cer-sabor__body {
    display: grid;
    gap: 0.35rem;
    padding: 1.1rem 1.15rem 1.25rem;
}
.cer-sabor__cat {
    font-family: var(--cer-font-hand);
    font-size: 1.2rem;
    color: var(--cer-clay);
}
.cer-sabor__body strong {
    font-family: var(--cer-font-display);
    font-size: 1.3rem;
    color: var(--cer-ink);
}
.cer-sabor__resumo {
    color: rgba(58, 39, 28, 0.72);
    font-size: 0.94rem;
}
.cer-sabor__cta {
    margin-top: 0.45rem;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: var(--cer-clay);
}

/* Atrações */
.cer-pillars {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}
.cer-pillar {
    position: relative;
    padding: 1.4rem 1.4rem 1.2rem;
    background: rgba(255, 255, 255, 0.78);
    border: 1px solid rgba(233, 107, 53, 0.14);
    border-radius: 1rem 0.35rem;
    overflow: hidden;
}
.cer-pillar__icon {
    width: 2.4rem;
    height: 2.4rem;
    color: var(--cer-clay);
    margin-bottom: 0.75rem;
}
.cer-pillar__icon svg { width: 100%; height: 100%; }
.cer-pillar p {
    margin: 0 0 1rem;
    color: rgba(44, 30, 21, 0.78);
}
.cer-pillar img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 0.75rem;
}

.cer-musica,
.cer-kids {
    background:
        radial-gradient(600px 280px at 80% 30%, rgba(246, 200, 90, 0.35), transparent 60%),
        linear-gradient(120deg, rgba(255, 224, 200, 0.7), rgba(255, 243, 214, 0.85));
}

.cer-map {
    border-radius: 1rem;
    overflow: hidden;
    min-height: 280px;
    box-shadow: 0 18px 40px rgba(233, 107, 53, 0.12);
    border: 1px solid rgba(233, 107, 53, 0.16);
}
.cer-map iframe {
    width: 100%;
    height: 320px;
    border: 0;
}
.cer-contato__place {
    margin-top: 1.25rem;
    max-width: 28rem;
    color: rgba(58, 39, 28, 0.75);
}

.cer-footer {
    background: linear-gradient(135deg, #E96B35 0%, #F08A4B 45%, #E07A2F 100%);
    color: rgba(255, 249, 241, 0.92);
    padding: 2.5rem 0;
}
.cer-footer__inner {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: end;
}
.cer-footer__brand img {
    height: 4.25rem;
    width: auto;
    border-radius: 0.45rem;
    margin-bottom: 0.85rem;
}
.cer-footer p { margin: 0; }

.cer-empty {
    padding: 2rem;
    background: rgba(255, 255, 255, 0.7);
    border: 1px dashed rgba(233, 107, 53, 0.35);
    color: rgba(58, 39, 28, 0.7);
    border-radius: 0.85rem;
}

/* Modal */
.cer-modal {
    position: fixed;
    inset: 0;
    z-index: 60;
    background: rgba(44, 30, 21, 0.55);
    display: grid;
    place-items: center;
    padding: 1rem;
    animation: cer-fade 0.25s ease;
}
.cer-modal__dialog {
    width: min(760px, 100%);
    max-height: min(92vh, 900px);
    overflow: auto;
    background: var(--cer-paper);
    border-radius: 1.25rem;
    position: relative;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
    animation: cer-rise 0.35s var(--cer-ease);
}
.cer-modal__close {
    position: absolute;
    top: 0.7rem;
    right: 0.8rem;
    z-index: 2;
    width: 2.4rem;
    height: 2.4rem;
    border: 0;
    border-radius: 999px;
    background: rgba(250, 246, 240, 0.9);
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    color: var(--cer-ink);
}
.cer-modal__hero {
    aspect-ratio: 16 / 10;
    background: var(--cer-sand);
}
.cer-modal__hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cer-modal__body { padding: 1.4rem 1.5rem 1.8rem; }
.cer-modal__desc p {
    margin: 0 0 0.85rem;
    white-space: pre-line;
}
.cer-modal__gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(72px, 1fr));
    gap: 0.45rem;
    margin: 1.1rem 0;
}
.cer-modal__gallery button {
    border: 2px solid transparent;
    padding: 0;
    border-radius: 0.45rem;
    overflow: hidden;
    cursor: pointer;
    background: none;
    aspect-ratio: 1;
}
.cer-modal__gallery button.is-active { border-color: var(--cer-clay); }
.cer-modal__gallery img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cer-modal__links {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.cer-modal__links a {
    color: var(--cer-clay);
    font-weight: 700;
    text-decoration: none;
}

/* Reveal */
.reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.8s var(--cer-ease), transform 0.8s var(--cer-ease);
}
.reveal.is-visible {
    opacity: 1;
    transform: none;
}

@keyframes cer-ken {
    from { transform: scale(1.04) translateY(0); }
    to { transform: scale(1.1) translateY(-1.5%); }
}
@keyframes cer-fade {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes cer-rise {
    from { opacity: 0; transform: translateY(16px) scale(0.98); }
    to { opacity: 1; transform: none; }
}

@media (max-width: 960px) {
    .cer-sobre__grid,
    .cer-musica__grid,
    .cer-kids__grid,
    .cer-contato__grid,
    .cer-pillars,
    .cer-expo-grid,
    .cer-sabores-grid {
        grid-template-columns: 1fr;
    }
    .cer-expo-grid,
    .cer-sabores-grid { grid-template-columns: repeat(2, 1fr); }
    .cer-musica__grid { direction: ltr; }
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
        padding: 0.75rem 1rem 1.25rem;
        background: rgba(255, 249, 241, 0.97);
        border-bottom: 1px solid var(--cer-mist);
    }
    .cer-nav.is-open { display: flex; }
    .cer-nav a {
        padding: 0.85rem 0.25rem;
        border-bottom: 1px solid rgba(44, 30, 21, 0.06);
    }
    .cer-nav .cer-btn { margin-top: 0.75rem; justify-content: center; }
    .cer-timeline__item { grid-template-columns: 1fr; }
    .cer-expo-grid,
    .cer-sabores-grid { grid-template-columns: 1fr; }
    .cer-hero__content { padding-bottom: 3rem; }
    .cer-brand img { height: 2.9rem; }
    .cer-hero__logo { width: min(100%, 22rem); }
    .cer-sabores__intro { flex-direction: column; align-items: start; }
}
</style>
