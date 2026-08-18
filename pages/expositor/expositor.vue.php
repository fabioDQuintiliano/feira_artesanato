<script>
(function () {
    var data = window.__EXPOSITOR__ || null;
    if (!data || !data.expositor) return;

    new Vue({
        el: '#app_expositor',
        data: function () {
            return {
                scrolled: false,
                menuAberto: false,
                whatsapp: data.whatsapp || '#',
                voltar: data.voltar || (data.root + 'ceramistas'),
                expositor: data.expositor,
                fotoIndex: 0
            };
        },
        computed: {
            fotoAtiva: function () {
                var fotos = this.expositor.fotos || [];
                if (fotos[this.fotoIndex]) return fotos[this.fotoIndex].url;
                return this.expositor.foto_destaque || this.expositor.logo || '';
            },
            descricaoHtml: function () {
                var texto = this.expositor.descricao;
                if (!texto) return '';
                var escaped = String(texto)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
                return '<p>' + escaped.replace(/\n\n+/g, '</p><p>').replace(/\n/g, '<br>') + '</p>';
            }
        },
        mounted: function () {
            var self = this;
            this.onScroll = function () {
                self.scrolled = window.scrollY > 24;
            };
            window.addEventListener('scroll', this.onScroll, { passive: true });
            this.onScroll();
        },
        beforeDestroy: function () {
            window.removeEventListener('scroll', this.onScroll);
        },
        methods: {
            fecharMenu: function () {
                this.menuAberto = false;
            }
        }
    });
})();
</script>
