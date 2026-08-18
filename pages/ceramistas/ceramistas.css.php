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

.skip-link {
    position: absolute;
    left: 1rem;
    top: -3rem;
    background: var(--cer-ink);
    color: #fff;
    padding: 0.6rem 1rem;
    z-index: 100;
}
.skip-link:focus { top: 1rem; }

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
}

.cer-section__head p:last-child,
.cer-hero__lead,
.cer-hero__place-sub,
.cer-timeline__body p,
.cer-expo__resumo,
.cer-sabor__resumo,
.cer-pillar p,
.cer-contato__place,
.cer-sabores__intro p:last-child,
.cer-sobre__copy p,
.cer-kids__copy p,
.cer-kids-day p,
.cer-kids__close {
    font-family: var(--cer-font-body);
}

h1, h2, h3 {
    font-family: var(--cer-font-main);
    font-weight: 400;
    line-height: 1.15;
    letter-spacing: 0;
    color: var(--cer-ink);
}

h2 {
    margin: 0 0 0.75rem;
    font-size: clamp(1.9rem, 3.5vw, 2.6rem);
}

h3 {
    margin: 0 0 0.45rem;
    font-size: 1.25rem;
}

.cer-section {
    padding: clamp(3.5rem, 7vw, 5.5rem) 0;
}

.cer-section__head {
    max-width: 34rem;
    margin-bottom: 2.25rem;
}

.cer-section__head p:last-child {
    margin: 0;
    color: var(--cer-muted);
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
.cer-header .cer-btn--compact {
    border-color: var(--cer-cream);
    background: var(--cer-cream);
    color: var(--cer-clay-deep);
}
.cer-header .cer-btn--compact:hover {
    background: #fff;
    border-color: #fff;
}
.cer-hero .cer-btn {
    border-color: var(--cer-cream);
    background: var(--cer-cream);
    color: var(--cer-clay-deep);
}
.cer-hero .cer-btn:hover {
    background: #fff;
    border-color: #fff;
}

/* Header */
.cer-header {
    position: fixed;
    inset: 0 0 auto;
    z-index: 40;
    background: transparent;
    border-bottom: 1px solid transparent;
    transition: background 0.2s, border-color 0.2s;
}
.cer-header.is-scrolled,
.cer-header.is-open {
    background: var(--cer-clay);
    border-bottom-color: rgba(255, 255, 255, 0.12);
}
.cer-header__inner {
    width: min(1080px, calc(100% - 2rem));
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

/* Hero terracota + ornamentos de canto */
.cer-hero {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    min-height: 100vh;
    min-height: 100svh;
    display: grid;
    align-items: center;
    background: var(--cer-clay);
    color: var(--cer-on-clay);
}
.cer-hero__ornament {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}
.cer-hero__corner {
    position: absolute;
    display: block;
    width: clamp(21rem, 66vw, 45rem);
    height: auto;
    opacity: 1;
    user-select: none;
}
.cer-hero__corner--tr {
    top: -6%;
    right: -8%;
}
.cer-hero__corner--bl {
    bottom: -8%;
    left: -10%;
}
@media (max-width: 760px) {
    .cer-hero__corner {
        width: clamp(16rem, 85vw, 28rem);
        opacity: 0.92;
    }
}
.cer-hero__stage {
    position: relative;
    z-index: 1;
    width: min(1080px, calc(100% - 2.5rem));
    margin: 0 auto;
    padding: calc(var(--cer-header-h) + 2.5rem) 0 3.5rem;
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: clamp(2rem, 5vw, 4rem);
    align-items: end;
}

.cer-hero__logo {
    width: min(100%, 17rem);
    height: auto;
    margin: 0 0 1.5rem;
}
.cer-hero h1 {
    margin: 0 0 0.85rem;
    font-size: clamp(2rem, 4vw, 3rem);
    max-width: 16ch;
    color: #fff;
    line-height: 1.2;
}
.cer-hero__typewriter-box {
    display: grid;
    align-items: start;
}
.cer-hero__typewriter-sizer,
.cer-hero__typewriter {
    grid-area: 1 / 1;
}
.cer-hero__typewriter-sizer {
    visibility: hidden;
    display: grid;
    pointer-events: none;
}
.cer-hero__typewriter-sizer > span {
    grid-area: 1 / 1;
}
.cer-hero__typewriter {
    min-height: 0;
}
.cer-hero__caret {
    display: inline-block;
    width: 0.08em;
    height: 0.92em;
    margin-left: 0.12em;
    background: currentColor;
    vertical-align: -0.08em;
    animation: cer-caret 0.85s steps(1) infinite;
}
.cer-hero__caret.is-waiting {
    animation-duration: 1s;
}
@keyframes cer-caret {
    50% { opacity: 0; }
}
@media (prefers-reduced-motion: reduce) {
    .cer-hero__caret {
        animation: none;
        opacity: 1;
    }
}
.cer-hero__lead {
    margin: 0 0 1.6rem;
    max-width: 32rem;
    color: rgba(255, 247, 240, 0.84);
    font-size: 1.08rem;
}
.cer-hero__actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.9rem 1.25rem;
}
.cer-hero__link {
    font-size: 0.92rem;
    font-weight: 400;
    color: #fff;
    text-decoration: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.45);
    padding-bottom: 0.1rem;
}
.cer-hero__link:hover {
    border-bottom-color: #fff;
}
.cer-hero__type {
    padding: 0 0 0.25rem;
    border-left: 2px solid rgba(255, 247, 240, 0.55);
    padding-left: 1.25rem;
}
.cer-hero__label {
    margin: 0;
    font-size: 0.75rem;
    font-weight: 400;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 247, 240, 0.7);
}
.cer-hero__label--space { margin-top: 1.5rem; }
.cer-hero__days {
    margin: 0.35rem 0 0;
    font-family: var(--cer-font-main);
    font-size: clamp(3.4rem, 8vw, 5.5rem);
    font-weight: 400;
    line-height: 0.95;
    letter-spacing: -0.04em;
    color: #fff;
}
.cer-hero__month {
    margin: 0.4rem 0 0;
    font-size: 1.15rem;
    font-weight: 500;
    color: var(--cer-cream);
}
.cer-hero__city {
    margin: 0.25rem 0 0;
    font-family: var(--cer-font-main);
    font-size: clamp(2.1rem, 3.8vw, 2.7rem);
    font-weight: 400;
    line-height: 1.05;
    color: #fff;
}
.cer-hero__city-uf {
    display: inline-block;
    margin-right: 0.15em;
    font-size: 0.62em;
    font-weight: 500;
    letter-spacing: 0.08em;
    vertical-align: 0.12em;
    color: var(--cer-cream);
}
.cer-hero__place {
    margin: 0.35rem 0 0;
    font-size: 1.12rem;
    font-weight: 500;
    color: var(--cer-cream);
}
.cer-hero__place-sub {
    margin: 0.2rem 0 0;
    color: rgba(255, 247, 240, 0.75);
    font-size: 0.95rem;
}

/* Sections */
.cer-sobre__grid,
.cer-kids__grid,
.cer-contato__grid {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: clamp(1.5rem, 4vw, 3rem);
    align-items: center;
}
.cer-sobre__figure,
.cer-kids figure {
    margin: 0;
    overflow: hidden;
    border-radius: 0.35rem;
}
.cer-sobre__figure img,
.cer-kids figure img {
    width: 100%;
    height: min(480px, 68vw);
    object-fit: cover;
}

.cer-kids__grid {
    align-items: start;
}
.cer-kids__copy p {
    margin: 0;
    color: var(--cer-muted);
    max-width: 36rem;
}
.cer-kids__days {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.15rem;
    margin-top: clamp(1.75rem, 4vw, 2.5rem);
}
.cer-kids-day {
    background: var(--cer-paper);
    border: 1px solid var(--cer-line);
    border-radius: 0.35rem;
    padding: 1.35rem 1.4rem 1.45rem;
}
.cer-kids-day__when {
    margin: 0 0 0.55rem;
    font-family: var(--cer-font-main);
    font-size: 0.78rem;
    font-weight: 400;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--cer-clay);
}
.cer-kids-day h3 {
    margin: 0 0 0.35rem;
    font-size: 1.35rem;
}
.cer-kids-day__meta {
    margin: 0 0 0.75rem;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--cer-ink);
}
.cer-kids-day p:last-child {
    margin: 0;
    color: var(--cer-muted);
}
.cer-kids__close {
    margin: 1.5rem 0 0;
    max-width: 40rem;
    font-family: var(--cer-font-main);
    font-style: italic;
    font-size: 1.2rem;
    line-height: 1.4;
    color: var(--cer-ink);
}

.cer-programacao,
.cer-sabores,
.cer-musica,
.cer-kids {
    background: var(--cer-clay-soft);
    border-top: 1px solid var(--cer-line);
    border-bottom: 1px solid var(--cer-line);
}

.cer-shows {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
    border-top: 1px solid var(--cer-line);
}
.cer-show {
    display: grid;
    grid-template-columns: minmax(9.5rem, 0.55fr) minmax(0, 1.4fr);
    gap: 1.25rem clamp(1.5rem, 4vw, 3rem);
    align-items: center;
    padding: clamp(1.4rem, 3vw, 2rem) 0;
    border-bottom: 1px solid var(--cer-line);
}
@media (max-width: 640px) {
    .cer-show {
        grid-template-columns: 1fr;
        gap: 0.9rem;
    }
    .cer-show__poster {
        width: min(100%, 14rem);
    }
}
.cer-show__poster {
    margin: 0;
    overflow: hidden;
    border-radius: 0.25rem;
    background: transparent;
    width: 100%;
    max-width: 18rem;
    justify-self: start;
    opacity: 0.9;
}
.cer-show__poster img {
    width: 100%;
    height: auto;
    display: block;
    aspect-ratio: 1;
    object-fit: cover;
}
.cer-show__meta {
    padding: 0;
    min-width: 0;
}
.cer-show__meta h3 {
    margin: 0 0 0.45rem;
    font-size: clamp(1.75rem, 3.2vw, 2.35rem);
}
.cer-show__when {
    margin: 0 0 0.65rem;
    font-family: var(--cer-font-main);
    font-size: clamp(1.35rem, 2.6vw, 1.75rem);
    font-weight: 400;
    line-height: 1.3;
    color: var(--cer-clay-deep);
}
.cer-show__meta > p:not(.cer-show__when) {
    margin: 0;
    max-width: 40rem;
    font-family: var(--cer-font-body);
    color: var(--cer-muted);
    font-size: 1.05rem;
    line-height: 1.55;
}
.cer-show__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.55rem;
    margin-top: 0.9rem;
}
.cer-show__actions .cer-btn {
    gap: 0.45rem;
}
.cer-show__action-icon {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
}
.cer-btn--ghost-show {
    background: transparent;
    color: var(--cer-clay-deep);
    border-color: var(--cer-clay);
}
.cer-btn--ghost-show:hover {
    background: var(--cer-paper);
    border-color: var(--cer-clay-deep);
    color: var(--cer-clay-deep);
}
.cer-section__head .cer-musica__note {
    margin: 0.65rem 0 0;
    max-width: 36rem;
    text-align: left;
    font-family: var(--cer-font-body);
    font-size: 0.98rem;
    color: var(--cer-muted);
}

.cer-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
}
.cer-tabs__btn {
    appearance: none;
    border: 1px solid var(--cer-line);
    background: var(--cer-paper);
    border-radius: 0.35rem;
    padding: 0.75rem 1rem;
    text-align: left;
    cursor: pointer;
    min-width: 9.5rem;
    font-family: inherit;
    color: var(--cer-ink);
    transition: border-color 0.2s, color 0.2s;
}
.cer-tabs__btn strong {
    display: block;
    font-family: var(--cer-font-main);
    font-size: 1rem;
}
.cer-tabs__btn span {
    font-size: 0.85rem;
    color: var(--cer-muted);
}
.cer-tabs__btn.is-active,
.cer-tabs__btn:hover {
    border-color: var(--cer-clay);
}

.cer-timeline {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: 0;
    border-top: 1px solid var(--cer-line);
}
.cer-timeline__item {
    display: grid;
    grid-template-columns: 8.5rem 1fr;
    gap: 1rem;
    padding: 1.15rem 0;
    border-bottom: 1px solid var(--cer-line);
    background: transparent;
}
.cer-timeline__item.is-destaque .cer-timeline__hour {
    color: var(--cer-clay);
}
.cer-timeline__time {
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
}
.cer-timeline__hour {
    font-family: var(--cer-font-main);
    font-size: 1rem;
    font-weight: 400;
}
.cer-timeline__icon {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--cer-clay);
}
.cer-timeline__icon svg { width: 100%; height: 100%; }
.cer-timeline__body h3 { margin-bottom: 0.25rem; }
.cer-timeline__body p {
    margin: 0 0 0.4rem;
    color: var(--cer-muted);
}
.cer-timeline__local {
    font-size: 0.84rem;
    color: var(--cer-clay);
    font-weight: 400;
}

.cer-agenda {
    list-style: none;
    margin: 0.5rem 0 0;
    padding: 0;
    display: grid;
    gap: 0.9rem;
}
.cer-agenda__item {
    margin: 0;
    padding: 1.15rem 1.25rem;
    background: var(--cer-paper);
    border: 1px solid var(--cer-line);
    border-radius: 0.35rem;
    display: grid;
    grid-template-columns: 2rem 1fr;
    gap: 0.9rem 1.1rem;
    align-items: start;
}
.cer-agenda__icon {
    width: 1.85rem;
    height: 1.85rem;
    color: var(--cer-clay);
    margin-top: 0.12rem;
}
.cer-agenda__icon svg {
    width: 100%;
    height: 100%;
    display: block;
}
.cer-agenda__when {
    margin: 0 0 0.45rem;
    color: var(--cer-clay-deep);
    font-size: 1.02rem;
    line-height: 1.35;
}
.cer-agenda__item p:last-child {
    margin: 0;
    color: var(--cer-muted);
    font-family: var(--cer-font-body);
    font-size: 1rem;
}
.cer-agenda__close {
    margin: 1.5rem 0 0;
    max-width: 40rem;
    font-family: var(--cer-font-body);
    color: var(--cer-ink);
}

.cer-expo-grid,
.cer-sabores-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}
.cer-expo,
.cer-sabor {
    appearance: none;
    border: 0;
    padding: 0;
    text-align: left;
    background: transparent;
    display: grid;
    gap: 0.85rem;
    font: inherit;
    color: inherit;
    text-decoration: none;
}
.cer-expo:focus-visible,
.cer-sabor:focus-visible {
    outline: 2px solid var(--cer-clay);
    outline-offset: 3px;
}
.cer-expo__media,
.cer-sabor__media {
    display: block;
    position: relative;
    overflow: hidden;
    border-radius: 0.35rem;
    background: var(--cer-clay-soft);
    border: 1px solid var(--cer-line);
}
.cer-expo__media {
    aspect-ratio: 1 / 1;
    width: 100%;
}
.cer-sabor__media {
    aspect-ratio: 1 / 1;
    width: 100%;
}
.cer-expo__media img,
.cer-sabor__media > img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.cer-sabor__logo {
    position: absolute;
    left: 0.7rem;
    bottom: 0.7rem;
    width: 2.8rem;
    height: 2.8rem;
    border-radius: 0.25rem;
    overflow: hidden;
    background: #fff;
    border: 1px solid var(--cer-line);
}
.cer-sabor__logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.cer-expo__cat,
.cer-sabor__cat {
    display: block;
    font-size: 0.75rem;
    font-weight: 400;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--cer-clay);
    margin-bottom: 0.2rem;
}
.cer-expo__meta strong,
.cer-sabor__body strong {
    display: block;
    font-family: var(--cer-font-main);
    font-size: 1.25rem;
    margin-bottom: 0.3rem;
}
.cer-expo__resumo,
.cer-sabor__resumo {
    display: block;
    color: var(--cer-muted);
    font-size: 0.94rem;
}
.cer-expo__cta,
.cer-sabor__cta {
    display: inline-block;
    margin-top: 0.55rem;
    font-size: 0.8rem;
    font-weight: 400;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--cer-ink);
    border-bottom: 1px solid var(--cer-line);
}
.cer-sabor__body {
    display: grid;
    gap: 0.2rem;
}
.cer-sabores__intro {
    display: flex;
    justify-content: space-between;
    gap: 1.5rem;
    align-items: end;
    margin-bottom: 2rem;
}
.cer-sabores__intro > div:first-child { max-width: 36rem; }
.cer-sabores__intro p:last-child {
    margin: 0;
    color: var(--cer-muted);
}
.cer-sabores__badges {
    display: flex;
    gap: 0.75rem;
}
.cer-sabores__badges span {
    width: 2rem;
    height: 2rem;
    color: var(--cer-clay);
}
.cer-sabores__badges svg { width: 100%; height: 100%; }

.cer-pillars {
    list-style: none;
    margin: 0;
    padding: 0;
    max-width: 42rem;
    border-top: 1px solid var(--cer-line);
}
.cer-pillar {
    display: grid;
    grid-template-columns: 1.75rem 1fr;
    gap: 1rem 1.15rem;
    align-items: start;
    padding: 1.35rem 0;
    border-bottom: 1px solid var(--cer-line);
    background: none;
}
.cer-pillar__icon {
    width: 1.75rem;
    height: 1.75rem;
    margin-top: 0.2rem;
    color: var(--cer-clay);
    display: block;
}
.cer-pillar__icon svg { width: 100%; height: 100%; display: block; }
.cer-pillar__copy h3 {
    margin: 0 0 0.35rem;
    font-size: 1.35rem;
}
.cer-pillar__copy p {
    margin: 0;
    color: var(--cer-muted);
    max-width: 36rem;
}

.cer-map {
    border-radius: 0.35rem;
    overflow: hidden;
    min-height: 280px;
    border: 1px solid var(--cer-line);
}
.cer-map iframe {
    width: 100%;
    height: 320px;
    border: 0;
}
.cer-contato__place {
    margin-top: 1.15rem;
    max-width: 28rem;
    color: var(--cer-muted);
}

.cer-footer {
    background: var(--cer-clay);
    color: rgba(255, 247, 240, 0.82);
    padding: 2.25rem 0;
}
.cer-footer__inner {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: end;
}
.cer-footer__brand img {
    height: 3rem;
    width: auto;
    margin-bottom: 0.7rem;
    border-radius: 0.25rem;
}
.cer-footer p { margin: 0; }
.cer-footer p.cer-footer__credit {
    display: block;
    width: 100%;
    margin: 1.25rem 0 0;
    padding: 1.1rem 1.25rem 0;
    border-top: 1px solid rgba(255, 247, 240, 0.2);
    font-size: 0.82rem;
    font-family: var(--cer-font-body);
    color: rgba(255, 247, 240, 0.78);
    text-align: center;
}
.cer-footer__credit a {
    color: inherit;
    text-decoration: none;
}

.cer-empty {
    padding: 1.5rem;
    background: var(--cer-paper);
    border: 1px solid var(--cer-line);
    color: var(--cer-muted);
    border-radius: 0.35rem;
}

.reveal {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.55s var(--cer-ease), transform 0.55s var(--cer-ease);
}
.reveal.is-visible {
    opacity: 1;
    transform: none;
}

@media (max-width: 960px) {
    .cer-hero__stage,
    .cer-sobre__grid,
    .cer-shows,
    .cer-kids__grid,
    .cer-kids__days,
    .cer-contato__grid,
    .cer-expo-grid,
    .cer-sabores-grid {
        grid-template-columns: 1fr;
    }
    .cer-expo-grid,
    .cer-sabores-grid {
        grid-template-columns: repeat(2, 1fr);
    }
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
    .cer-timeline__item,
    .cer-expo-grid,
    .cer-sabores-grid {
        grid-template-columns: 1fr;
    }
    .cer-brand img { height: 2.35rem; }
    .cer-sabores__intro {
        flex-direction: column;
        align-items: start;
    }
}
</style>
