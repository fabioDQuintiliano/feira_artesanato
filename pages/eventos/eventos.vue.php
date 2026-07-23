<?php
use Sistema\Eventos;

$ehBares = isset($_GET['tipo']) && $_GET['tipo'] === 'bares';
$tagParam = $_GET['tag'] ?? null;
$filtroTags = [];
if (is_string($tagParam) && $tagParam !== '') {
    $filtroTags = array_values(array_filter(array_map('trim', explode(',', $tagParam))));
}
$dataFiltro = $_GET['data'] ?? null;
if (!is_string($dataFiltro) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataFiltro)) {
    $dataFiltro = null;
}

$limite = 12;
$tipoEstabelecimento = $ehBares ? Eventos::TIPO_BARES_RESTAURANTES : null;
$excluirTipo = $ehBares ? null : Eventos::TIPO_BARES_RESTAURANTES;

$eventos = Eventos::getEventos(
    $limite,
    $filtroTags ?: null,
    0,
    $dataFiltro,
    $tipoEstabelecimento,
    $excluirTipo
);

$tituloPagina = $ehBares ? 'Bares e Restaurantes' : 'Todos os Eventos';
if (!$ehBares && count($filtroTags) === 1) {
    $tituloPagina = 'Eventos em "' . ucfirst($filtroTags[0]) . '"';
} elseif (!$ehBares && count($filtroTags) > 1) {
    $nomes = array_map(function ($tag) {
        return ucfirst($tag);
    }, $filtroTags);
    $tituloPagina = 'Eventos em "' . implode('", "', $nomes) . '"';
}
?>
<script>
    var app = new Vue({
        el: '#app_eventos',
        data: {
            eventos: <?= json_encode($eventos) ?>,
            filtroTags: <?= json_encode($filtroTags) ?>,
            dataSelecionada: <?= $dataFiltro ? json_encode($dataFiltro) : 'null' ?>,
            ehBares: <?= $ehBares ? 'true' : 'false' ?>,
            tituloPagina: <?= json_encode($tituloPagina) ?>,
            offset: <?= count($eventos) ?>,
            limite: <?= (int) $limite ?>,
            carregando: false,
            fimEventos: <?= count($eventos) < $limite ? 'true' : 'false' ?>,
            consultaConcluida: true,
            erroCarregamento: false,
            tipoBaresRestaurantes: <?= (int) Eventos::TIPO_BARES_RESTAURANTES ?>
        },
        mounted: function () {
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed: function () {
            window.removeEventListener('scroll', this.handleScroll);
        },
        methods: {
            formatarDataBR: function (dataStr) {
                if (!dataStr) return '';
                const parts = dataStr.split('-');
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            },
            handleScroll: function () {
                if (this.carregando || this.fimEventos) return;

                const scrollHeight = document.documentElement.scrollHeight;
                const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                const clientHeight = document.documentElement.clientHeight;

                if (scrollTop + clientHeight >= scrollHeight - 300) {
                    this.carregarMais();
                }
            },
            categoriaPrincipal: function (evento) {
                if (Array.isArray(evento.tags) && evento.tags.length > 0) {
                    return String(evento.tags[0]);
                }
                return 'Evento cultural';
            },
            configuracaoPlaceholder: function (evento) {
                const categoriaOriginal = this.categoriaPrincipal(evento);
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
            placeholderEvento: function (evento) {
                const config = this.configuracaoPlaceholder(evento);
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
            imagemEvento: function (evento) {
                const imagem = typeof evento.imagem === 'string' ? evento.imagem.trim() : '';
                const imagemNormalizada = imagem.toLowerCase();

                if (imagem && imagemNormalizada !== 'null' && imagemNormalizada !== 'undefined') {
                    return imagem;
                }

                return this.placeholderEvento(evento);
            },
            usarPlaceholder: function (event, evento) {
                if (event.target.dataset.placeholderAplicado === 'true') return;
                event.target.dataset.placeholderAplicado = 'true';
                event.target.src = this.placeholderEvento(evento);
            },
            carregarMais: function (novoFiltro = false) {
                if (this.carregando || (this.fimEventos && !novoFiltro)) return;

                this.carregando = true;
                this.erroCarregamento = false;

                if (novoFiltro) {
                    this.offset = 0;
                    this.fimEventos = false;
                }

                const params = {
                    limite: this.limite,
                    tag: this.filtroTags.length ? this.filtroTags : null,
                    offset: this.offset,
                    data: this.dataSelecionada,
                    tipoEstabelecimento: this.ehBares ? this.tipoBaresRestaurantes : null,
                    excluirTipoEstabelecimento: this.ehBares ? null : this.tipoBaresRestaurantes
                };

                ajax_load_class("\\Sistema\\Eventos", "getEventos", params)
                    .then((novosEventos) => {
                        if (!Array.isArray(novosEventos)) {
                            this.consultaConcluida = false;
                            this.erroCarregamento = true;
                            this.carregando = false;
                            return;
                        }

                        this.consultaConcluida = true;

                        if (novosEventos.length > 0) {
                            if (novoFiltro) {
                                this.eventos = novosEventos;
                            } else {
                                this.eventos = [...this.eventos, ...novosEventos];
                            }
                            this.offset += novosEventos.length;

                            if (novosEventos.length < this.limite) {
                                this.fimEventos = true;
                            }

                            this.$nextTick(() => {
                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons();
                                }
                            });
                        } else {
                            this.fimEventos = true;
                        }
                        this.carregando = false;
                    })
                    .catch((e) => {
                        console.error("Erro ao carregar eventos:", e);
                        this.consultaConcluida = false;
                        this.erroCarregamento = true;
                        this.carregando = false;
                    });
            }
        }
    });
</script>
