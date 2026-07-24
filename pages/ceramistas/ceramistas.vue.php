<script>
(function () {
    var data = window.__CERAMISTAS__ || {};
    var icones = {
        sun: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>',
        pottery: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M8 4c0 2 1.5 3 4 3s4-1 4-3"/><path d="M7 8c-1.5 2-2 4.5-1.2 7.2C6.5 18.5 8.8 21 12 21s5.5-2.5 6.2-5.8C19 12.5 18.5 10 17 8"/><path d="M9 11h6"/></svg>',
        music: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M9 18V6l12-2v12"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>',
        kids: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 20c1.2-3.2 3.5-4.8 6.5-4.8S17.3 16.8 18.5 20"/><path d="M16 4.5c1.2-.8 2.7-.8 3.8.2M8 4.5C6.8 3.7 5.3 3.7 4.2 4.7"/></svg>',
        taste: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 9c0-3.3 2.7-6 6-6s6 2.7 6 6v2H6V9z"/><path d="M5 11h14v2a7 7 0 0 1-14 0v-2z"/><path d="M12 20v2"/></svg>',
        beer: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 4h9l1 14H5L6 4z"/><path d="M15 7h3.5a2.5 2.5 0 0 1 0 5H15"/><path d="M8 8v6M11 8v6"/></svg>',
        market: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 10l2-5h12l2 5"/><path d="M4 10h16v9a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9z"/><path d="M9 20v-6h6v6"/></svg>'
    };

    new Vue({
        el: '#app_ceramistas',
        data: function () {
            var dias = (data.programacao && data.programacao.dias) ? data.programacao.dias : [];
            return {
                scrolled: false,
                menuAberto: false,
                whatsapp: data.whatsapp || '#',
                expositores: data.expositores || [],
                alimentacao: data.alimentacao || [],
                dias: dias,
                diaAtivo: dias.length ? dias[0].dia_iso : null,
                expositorAtivo: null,
                fotoModalIndex: 0
            };
        },
        computed: {
            itensDoDia: function () {
                var self = this;
                var dia = this.dias.find(function (d) { return d.dia_iso === self.diaAtivo; });
                return dia ? dia.itens : [];
            },
            fotoModal: function () {
                if (!this.expositorAtivo) return '';
                var fotos = this.expositorAtivo.fotos || [];
                if (fotos[this.fotoModalIndex]) return fotos[this.fotoModalIndex].url;
                return this.expositorAtivo.foto_destaque || this.expositorAtivo.logo || '';
            }
        },
        mounted: function () {
            var self = this;
            this.onScroll = function () {
                self.scrolled = window.scrollY > 24;
            };
            window.addEventListener('scroll', this.onScroll, { passive: true });
            this.onScroll();
            this.initReveal();
            document.addEventListener('keydown', this.onKey);
        },
        beforeDestroy: function () {
            window.removeEventListener('scroll', this.onScroll);
            document.removeEventListener('keydown', this.onKey);
            if (this._observer) this._observer.disconnect();
        },
        methods: {
            fecharMenu: function () {
                this.menuAberto = false;
            },
            iconeSvg: function (nome) {
                return icones[nome] || icones.sun;
            },
            abrirExpositor: function (exp) {
                this.expositorAtivo = exp;
                this.fotoModalIndex = 0;
                document.body.style.overflow = 'hidden';
            },
            fecharExpositor: function () {
                this.expositorAtivo = null;
                document.body.style.overflow = '';
            },
            onKey: function (e) {
                if (e.key === 'Escape') this.fecharExpositor();
            },
            descricaoHtml: function (texto) {
                if (!texto) return '';
                var escaped = String(texto)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
                return '<p>' + escaped.replace(/\n\n+/g, '</p><p>').replace(/\n/g, '<br>') + '</p>';
            },
            initReveal: function () {
                var nodes = document.querySelectorAll('#app_ceramistas .reveal');
                if (!('IntersectionObserver' in window)) {
                    nodes.forEach(function (el) { el.classList.add('is-visible'); });
                    return;
                }
                this._observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                        }
                    });
                }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });
                nodes.forEach(function (el) { this._observer.observe(el); }.bind(this));
            }
        }
    });
})();
</script>
