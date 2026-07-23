<script>
    new Vue({
        el: '#app_evento',
        data: {
            evento: <?= json_encode(
                $evento,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            toastVisivel: false,
            toastTimer: null
        },
        methods: {
            categoriaPrincipal: function () {
                if (Array.isArray(this.evento.tags) && this.evento.tags.length > 0) {
                    return String(this.evento.tags[0]);
                }

                return 'Evento cultural';
            },
            configuracaoPlaceholder: function () {
                const categoriaOriginal = this.categoriaPrincipal();
                const categoria = categoriaOriginal
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase();

                const configuracoes = [
                    { termos: ['musica', 'show', 'concerto', 'rock', 'samba'], titulo: 'Música', simbolo: '♫', cor1: '#fe3403', cor2: '#e67000' },
                    { termos: ['teatro', 'espetaculo'], titulo: 'Teatro', simbolo: '🎭', cor1: '#e67000', cor2: '#fec400' },
                    { termos: ['cinema', 'filme', 'audiovisual'], titulo: 'Cinema', simbolo: '▶', cor1: '#007dc4', cor2: '#2e1717' },
                    { termos: ['arte', 'exposicao', 'museu', 'galeria'], titulo: 'Arte e exposição', simbolo: '◆', cor1: '#007dc4', cor2: '#fec400' },
                    { termos: ['danca', 'baile'], titulo: 'Dança', simbolo: '♪', cor1: '#fe3403', cor2: '#007dc4' },
                    { termos: ['literatura', 'livro', 'leitura'], titulo: 'Literatura', simbolo: 'Aa', cor1: '#e67000', cor2: '#2e1717' },
                    { termos: ['infantil', 'crianca', 'familia'], titulo: 'Infantil', simbolo: '★', cor1: '#fec400', cor2: '#007dc4' },
                    { termos: ['gastronomia', 'comida', 'culinaria'], titulo: 'Gastronomia', simbolo: '✦', cor1: '#e67000', cor2: '#fe3403' },
                    { termos: ['festival', 'feira'], titulo: 'Festival', simbolo: '✺', cor1: '#fe3403', cor2: '#fec400' }
                ];

                return configuracoes.find(configuracao =>
                    configuracao.termos.some(termo => categoria.includes(termo))
                ) || {
                    titulo: categoriaOriginal.slice(0, 32),
                    simbolo: '✦',
                    cor1: '#fe3403',
                    cor2: '#007dc4'
                };
            },
            placeholderEvento: function () {
                const config = this.configuracaoPlaceholder();
                const tituloSeguro = String(config.titulo)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&apos;');
                const svg = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450">
                        <defs>
                            <linearGradient id="fundo" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="${config.cor1}"/>
                                <stop offset="100%" stop-color="${config.cor2}"/>
                            </linearGradient>
                        </defs>
                        <rect width="800" height="450" fill="url(#fundo)"/>
                        <circle cx="675" cy="70" r="190" fill="#ffffff" opacity=".10"/>
                        <circle cx="90" cy="420" r="230" fill="#ffffff" opacity=".08"/>
                        <text x="400" y="205" fill="#ffffff" text-anchor="middle"
                            font-family="Roboto Slab, Georgia, serif" font-size="96" font-weight="700">${config.simbolo}</text>
                        <text x="400" y="300" fill="#ffffff" text-anchor="middle"
                            font-family="Roboto Slab, Georgia, serif" font-size="42" font-weight="700">${tituloSeguro}</text>
                        <text x="400" y="350" fill="#ffffff" opacity=".78" text-anchor="middle"
                            font-family="Inter, Arial, sans-serif" font-size="18" font-weight="600" letter-spacing="5">AGENDA CULTURAL</text>
                    </svg>
                `;

                return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
            },
            imagemEvento: function () {
                const imagem = typeof this.evento.imagem === 'string' ? this.evento.imagem.trim() : '';
                const imagemNormalizada = imagem.toLowerCase();

                if (imagem && imagemNormalizada !== 'null' && imagemNormalizada !== 'undefined') {
                    return imagem;
                }

                return this.placeholderEvento();
            },
            usarPlaceholder: function (event) {
                if (event.target.dataset.placeholderAplicado === 'true') return;

                event.target.dataset.placeholderAplicado = 'true';
                event.target.src = this.placeholderEvento();
            },
            copiarLink: function (url) {
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(url);
                }

                return new Promise((resolve, reject) => {
                    const campoTemporario = document.createElement('textarea');
                    campoTemporario.value = url;
                    campoTemporario.setAttribute('readonly', '');
                    campoTemporario.style.position = 'fixed';
                    campoTemporario.style.opacity = '0';
                    campoTemporario.style.pointerEvents = 'none';
                    document.body.appendChild(campoTemporario);
                    campoTemporario.select();
                    campoTemporario.setSelectionRange(0, campoTemporario.value.length);

                    try {
                        const copiado = document.execCommand('copy');
                        document.body.removeChild(campoTemporario);
                        copiado ? resolve() : reject(new Error('Cópia não permitida pelo navegador.'));
                    } catch (erro) {
                        document.body.removeChild(campoTemporario);
                        reject(erro);
                    }
                });
            },
            mostrarToast: function () {
                clearTimeout(this.toastTimer);
                this.toastVisivel = true;

                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });

                this.toastTimer = setTimeout(() => {
                    this.toastVisivel = false;
                }, 3000);
            },
            share: function (platform) {
                const url = window.location.href;
                const text = "Confira esse evento na Agenda Cultural: " + document.title;

                if (platform === 'whatsapp') {
                    window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(text + " " + url)}`, '_blank');
                } else if (platform === 'x') {
                    window.open(`https://x.com/intent/post?text=${encodeURIComponent(text + " " + url)}`, '_blank');
                } else if (platform === 'copy') {
                    this.copiarLink(url)
                        .then(() => {
                            this.mostrarToast();
                        })
                        .catch(() => {
                            window.prompt('Copie o link do evento:', url);
                        });
                }
            }
        },
        mounted: function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    });
</script>