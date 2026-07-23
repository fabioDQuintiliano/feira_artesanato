<?php
// Estilos específicos para a home_v3 (Opcional, pois usamos Tailwind via CDN no container)
?>
<style>
    .wave-divider {
        position: relative;
        width: 100%;
        height: 75px;
        overflow: hidden;
        background: #ecd0d0;
        line-height: 0;
    }

    .wave-divider__track {
        display: flex;
        width: 200%;
        height: 100%;
        will-change: transform;
    }

    .wave-divider__layer {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }

    .wave-divider__layer--1 .wave-divider__track {
        animation: wave-flow 14s linear infinite;
    }

    .wave-divider__layer--2 .wave-divider__track {
        animation: wave-flow 19s linear infinite reverse;
    }

    .wave-divider__layer--3 .wave-divider__track {
        animation: wave-flow 11s linear infinite;
    }

    .wave-divider__layer--4 .wave-divider__track {
        animation: wave-flow 23s linear infinite reverse;
    }

    .wave-divider__layer--5 .wave-divider__track {
        animation: wave-flow 29s linear infinite reverse;
    }

    .wave-divider__track svg {
        width: 50%;
        height: 100%;
        flex: 0 0 50%;
    }

    @keyframes wave-flow {
        from {
            transform: translate3d(0, 0, 0);
        }

        to {
            transform: translate3d(-50%, 0, 0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .wave-divider__track {
            animation: none;
        }
    }

    [v-cloak] {
        display: none !important;
    }

    /* Split IA: site + sidebar */
    .ia-split-active {
        display: flex;
        align-items: flex-start;
        min-height: 100vh;
    }

    .ia-split-main {
        flex: 1 1 auto;
        min-width: 0;
        width: 100%;
        transition: padding-right 0.35s ease;
    }

    .ia-split-active .ia-split-main {
        padding-right: min(420px, 42vw);
        box-sizing: border-box;
    }

    .ia-split-active .ia-split-header {
        right: min(420px, 42vw);
    }

    .ia-split-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: min(420px, 42vw);
        z-index: 55;
        background: #fff8f8;
        border-left: 1px solid #ddbcbc;
        box-shadow: -12px 0 40px rgba(46, 23, 23, 0.08);
        animation: ia-sidebar-in 0.35s ease-out;
    }

    .ia-split-sidebar__inner {
        height: 100%;
        padding-top: 0;
    }

    @keyframes ia-sidebar-in {
        from {
            transform: translateX(24px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .hero-ia-input__field::placeholder {
        transition: opacity 0.35s ease;
    }

    @media (max-width: 900px) {
        .ia-split-active {
            flex-direction: column;
        }

        .ia-split-active .ia-split-main {
            width: 100%;
            padding-right: 0;
            padding-bottom: 48vh;
        }

        .ia-split-active .ia-split-header {
            right: 0;
        }

        .ia-split-sidebar {
            top: auto;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 46vh;
            border-left: none;
            border-top: 1px solid #ddbcbc;
            box-shadow: 0 -8px 30px rgba(46, 23, 23, 0.1);
        }
    }

    /* Selecionados para você */
    .selecionados-section {
        background: #ecd0d0;
    }

    .selecionados-card {
        animation: selecionados-card-in 0.55s ease-out both;
    }

    .selecionados-slider {
        width: 100%;
    }

    .selecionados-slider__track {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: rgba(254, 52, 3, 0.45) transparent;
        padding-left: 30px;
        padding-right: 30px;
    }

    .selecionados-slider__track::-webkit-scrollbar {
        height: 6px;
    }

    .selecionados-slider__track::-webkit-scrollbar-track {
        background: transparent;
    }

    .selecionados-slider__track::-webkit-scrollbar-thumb {
        background: rgba(254, 52, 3, 0.4);
        border-radius: 9999px;
    }

    .selecionados-slider__nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 9999px;
        border: 1px solid rgba(221, 188, 188, 0.8);
        background: #fff8f8;
        color: #2e1717;
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .selecionados-slider__nav:hover {
        background: #fe3403;
        border-color: #fe3403;
        color: #fff;
        transform: scale(1.05);
    }

    .selecionados-anim {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .selecionados-anim__tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .selecionados-anim__tag {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.7rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid rgba(46, 23, 23, 0.12);
        background: rgba(255, 248, 248, 0.7);
        color: #745959;
        opacity: 0.45;
        transform: scale(0.96);
        animation: selecionados-tag-pick 4.8s ease-in-out infinite;
    }

    .selecionados-anim__tag--1 { animation-delay: 0s; }
    .selecionados-anim__tag--2 { animation-delay: 0.35s; }
    .selecionados-anim__tag--3 { animation-delay: 0.7s; }
    .selecionados-anim__tag--4 { animation-delay: 1.05s; }

    .selecionados-anim__flow {
        display: flex;
        align-items: center;
        gap: 0.28rem;
        color: #fe3403;
        opacity: 0.7;
    }

    .selecionados-anim__dot {
        width: 0.28rem;
        height: 0.28rem;
        border-radius: 9999px;
        background: currentColor;
        animation: selecionados-dot-pulse 1.2s ease-in-out infinite;
    }

    .selecionados-anim__dot:nth-child(2) { animation-delay: 0.15s; }
    .selecionados-anim__dot:nth-child(3) { animation-delay: 0.3s; }

    .selecionados-anim__spark {
        animation: selecionados-spark 1.6s ease-in-out infinite;
    }

    @keyframes selecionados-tag-pick {
        0%, 12% {
            opacity: 0.4;
            transform: scale(0.96);
            background: rgba(255, 248, 248, 0.7);
            color: #745959;
            border-color: rgba(46, 23, 23, 0.12);
        }
        22%, 70% {
            opacity: 1;
            transform: scale(1);
            background: #fe3403;
            color: #fff;
            border-color: #fe3403;
        }
        82%, 100% {
            opacity: 0.4;
            transform: scale(0.96);
            background: rgba(255, 248, 248, 0.7);
            color: #745959;
            border-color: rgba(46, 23, 23, 0.12);
        }
    }

    @keyframes selecionados-dot-pulse {
        0%, 100% { opacity: 0.25; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1.15); }
    }

    @keyframes selecionados-spark {
        0%, 100% { opacity: 0.35; transform: rotate(-8deg) scale(0.9); }
        50% { opacity: 1; transform: rotate(8deg) scale(1.1); }
    }

    @keyframes selecionados-card-in {
        from {
            opacity: 0;
            transform: translateY(14px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .selecionados-card,
        .selecionados-anim__tag,
        .selecionados-anim__dot,
        .selecionados-anim__spark {
            animation: none;
        }

        .selecionados-anim__tag {
            opacity: 1;
            background: #fe3403;
            color: #fff;
            border-color: #fe3403;
            transform: none;
        }
    }
</style>