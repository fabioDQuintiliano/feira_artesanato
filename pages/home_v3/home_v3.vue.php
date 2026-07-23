<?php
use Sistema\Eventos;

$limiteHome = 12;
$limiteSelecionados = 8;
$tagParam = $_GET['tag'] ?? null;
$filtroTags = [];
if (is_string($tagParam) && $tagParam !== '') {
    $filtroTags = array_values(array_filter(array_map('trim', explode(',', $tagParam))));
}
$dataInicial = $_GET['data'] ?? null;
if (!is_string($dataInicial) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataInicial)) {
    $dataInicial = null;
}

$eventos = Eventos::getEventos(
    $limiteHome,
    $filtroTags ?: null,
    0,
    $dataInicial,
    null,
    Eventos::TIPO_BARES_RESTAURANTES
);
$eventosBares = Eventos::getEventos(
    $limiteHome,
    null,
    0,
    null,
    Eventos::TIPO_BARES_RESTAURANTES
);
$tagsPopulares = Eventos::getTagsPopulares(12);
$tagsInteresses = Eventos::getTagsPopulares(24);
$totalEventosEncontrados = Eventos::contarEventos(
    $filtroTags ?: null,
    $dataInicial,
    null,
    Eventos::TIPO_BARES_RESTAURANTES
);
?>
<script>
    var app = new Vue({
        el: '#app_home',
        data: {
            eventos: <?= json_encode($eventos) ?>,
            eventosBares: <?= json_encode($eventosBares) ?>,
            eventosSelecionados: [],
            tagsPopulares: <?= json_encode($tagsPopulares) ?>,
            tagsInteresses: <?= json_encode($tagsInteresses) ?>,
            filtroTags: <?= json_encode($filtroTags) ?>,
            totalEventosEncontrados: <?= (int) $totalEventosEncontrados ?>,
            limite: <?= (int) $limiteHome ?>,
            limiteSelecionados: <?= (int) $limiteSelecionados ?>,
            carregando: false,
            carregandoSelecionados: false,
            temMaisEventos: <?= count($eventos) >= $limiteHome ? 'true' : 'false' ?>,
            temMaisBares: <?= count($eventosBares) >= $limiteHome ? 'true' : 'false' ?>,
            consultaConcluida: true,
            erroCarregamento: false,
            carregado: false,
            tipoBaresRestaurantes: <?= (int) Eventos::TIPO_BARES_RESTAURANTES ?>,

            // Calendário
            dataSelecionada: <?= $dataInicial ? json_encode($dataInicial) : 'null' ?>,
            mesAtual: new Date().getMonth(),
            anoAtual: new Date().getFullYear(),
            diasSemana: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            datasComEventos: [],

            // Cookies / interesses
            cookieConsent: false,
            mostrarBannerCookies: false,
            mostrarModalInteresses: false,
            interesses: [],
            interessesTemp: [],
            erroInteresses: '',
            cookiesRecusados: false,

            // Chat IA
            modoChatAtivo: false,
            chatMensagens: [],
            chatInput: '',
            chatCarregando: false,
            eventosSugeridosIA: [],
            chatPlaceholders: [
                'Quero um show de rock com entrada gratuita...',
                'Tem teatro para este fim de semana?',
                'Algo legal para ir com crianças...',
                'Sugira um evento de cinema amanhã...',
                'Onde tem música ao vivo hoje?'
            ],
            chatPlaceholderIndex: 0,
            chatPlaceholderTimer: null
        },
        computed: {
            chatPlaceholderAtual() {
                return this.chatPlaceholders[this.chatPlaceholderIndex] || 'Pergunte ao Guia Cultural...';
            },
            nomeMesAtual() {
                const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                return meses[this.mesAtual];
            },
            diasVazios() {
                const primeiroDia = new Date(this.anoAtual, this.mesAtual, 1).getDay();
                return primeiroDia;
            },
            diasNoMes() {
                const ultimoDia = new Date(this.anoAtual, this.mesAtual + 1, 0).getDate();
                const dias = [];
                const hojeStr = new Date().toISOString().split('T')[0];

                for (let i = 1; i <= ultimoDia; i++) {
                    const dataObj = new Date(this.anoAtual, this.mesAtual, i);
                    const dataStr = dataObj.toISOString().split('T')[0];

                    dias.push({
                        dia: i,
                        dataFull: dataStr,
                        hoje: dataStr === hojeStr,
                        temEvento: this.datasComEventos.includes(dataStr)
                    });
                }
                return dias;
            },
            tituloFiltroTags() {
                if (!this.filtroTags.length) return 'Próximos Eventos';
                const nomes = this.filtroTags.map(tag =>
                    tag.charAt(0).toUpperCase() + tag.slice(1)
                );
                if (nomes.length === 1) {
                    return 'Eventos em "' + nomes[0] + '"';
                }
                return 'Eventos em "' + nomes.join('", "') + '"';
            },
            urlVerMaisEventos() {
                const params = new URLSearchParams();
                if (this.filtroTags.length) {
                    params.set('tag', this.filtroTags.join(','));
                }
                if (this.dataSelecionada) {
                    params.set('data', this.dataSelecionada);
                }
                const query = params.toString();
                return '<?= ROOT ?>eventos' + (query ? '?' + query : '');
            },
            urlVerMaisBares() {
                return '<?= ROOT ?>eventos?tipo=bares';
            },
            temFiltrosAtivos() {
                return this.filtroTags.length > 0 || !!this.dataSelecionada;
            },
            interessesExibicao() {
                return this.interesses
                    .map(tag => tag.charAt(0).toUpperCase() + tag.slice(1))
                    .join(', ');
            }
        },
        mounted: function () {
            this.carregado = true;
            this.carregarDatasComEventos();
            this.inicializarPreferencias();
            this.iniciarPlaceholderRotativo();
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },
        beforeDestroy: function () {
            this.pararPlaceholderRotativo();
        },
        methods: {
            // --- Chat IA ---
            iniciarPlaceholderRotativo: function () {
                this.pararPlaceholderRotativo();
                this.chatPlaceholderTimer = setInterval(() => {
                    if (this.chatInput || this.modoChatAtivo) return;
                    this.chatPlaceholderIndex = (this.chatPlaceholderIndex + 1) % this.chatPlaceholders.length;
                }, 3200);
            },
            pararPlaceholderRotativo: function () {
                if (this.chatPlaceholderTimer) {
                    clearInterval(this.chatPlaceholderTimer);
                    this.chatPlaceholderTimer = null;
                }
            },
            fecharModoChat: function () {
                this.modoChatAtivo = false;
                this.iniciarPlaceholderRotativo();
                this.$nextTick(() => {
                    this.atualizarIcones();
                });
            },
            enviarMensagemChat: function () {
                if (this.chatCarregando || !this.chatInput.trim()) return;

                const texto = this.chatInput.trim();
                this.modoChatAtivo = true;
                this.pararPlaceholderRotativo();

                this.chatMensagens.push({
                    role: 'user',
                    text: texto
                });
                this.chatInput = '';
                this.chatCarregando = true;
                this.scrollHeroChat();
                this.atualizarIcones();

                ajax_load_class("\\Sistema\\ChatIA", "getRespostaComEventos", {
                    mensagens: this.chatMensagens
                })
                    .then((ret) => {
                        let resposta = '';
                        let eventos = [];

                        if (ret && typeof ret === 'object') {
                            resposta = ret.resposta || '';
                            eventos = Array.isArray(ret.eventos) ? ret.eventos : [];
                        } else if (typeof ret === 'string') {
                            resposta = ret;
                        }

                        this.chatMensagens.push({
                            role: 'assistant',
                            text: resposta || 'Não consegui montar uma resposta agora. Tente de novo em instantes.'
                        });

                        this.eventosSugeridosIA = eventos;

                        this.chatCarregando = false;
                        this.scrollHeroChat();
                        this.$nextTick(() => {
                            if (this.$refs.sidebarChatInput) this.$refs.sidebarChatInput.focus();
                            this.atualizarIcones();
                        });
                    })
                    .catch((e) => {
                        console.error("Erro no chat:", e);
                        this.chatMensagens.push({
                            role: 'assistant',
                            text: 'Desculpe, tive um erro ao processar sua mensagem. Tente novamente em alguns instantes.'
                        });
                        this.chatCarregando = false;
                        this.scrollHeroChat();
                        this.atualizarIcones();
                    });
            },
            scrollHeroChat: function () {
                this.$nextTick(() => {
                    const el = this.$refs.heroChatScroll;
                    if (el) {
                        el.scrollTop = el.scrollHeight;
                    }
                });
            },
            formatarTextoChat: function (texto) {
                if (!texto) return '';
                let html = String(texto)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\n/g, '<br>')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

                const urlRegex = /(https?:\/\/[^\s<]+)/g;
                html = html.replace(urlRegex, function (url) {
                    return '<a href="' + url + '" class="underline font-semibold" target="_blank" rel="noopener noreferrer">' + url + '</a>';
                });

                return html;
            },

            // --- Cookies ---
            getCookie: function (nome) {
                const match = document.cookie.match(new RegExp('(?:^|; )' + nome.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
                return match ? decodeURIComponent(match[1]) : null;
            },
            setCookie: function (nome, valor, dias) {
                const maxAge = Math.floor((dias || 365) * 24 * 60 * 60);
                document.cookie = nome + '=' + encodeURIComponent(valor)
                    + '; path=/; max-age=' + maxAge + '; SameSite=Lax';
            },
            removeCookie: function (nome) {
                document.cookie = nome + '=; path=/; max-age=0; SameSite=Lax';
            },
            inicializarPreferencias: function () {
                const consent = this.getCookie('ac_cookie_consent');
                if (consent === '1') {
                    this.cookieConsent = true;
                    this.mostrarBannerCookies = false;
                    this.carregarInteressesDoCookie();
                    if (this.interesses.length) {
                        this.carregarEventosSelecionados();
                    } else {
                        this.$nextTick(() => {
                            this.mostrarModalInteresses = true;
                            this.interessesTemp = [];
                            this.atualizarIcones();
                        });
                    }
                } else if (consent === '0') {
                    this.cookieConsent = false;
                    this.cookiesRecusados = true;
                    this.mostrarBannerCookies = false;
                } else {
                    this.mostrarBannerCookies = true;
                }
            },
            carregarInteressesDoCookie: function () {
                const raw = this.getCookie('ac_user_interests');
                if (!raw) {
                    this.interesses = [];
                    return;
                }
                this.interesses = raw.split(',').map(t => t.trim()).filter(Boolean);
            },
            aceitarCookies: function () {
                this.setCookie('ac_cookie_consent', '1', 365);
                this.cookieConsent = true;
                this.cookiesRecusados = false;
                this.mostrarBannerCookies = false;
                this.carregarInteressesDoCookie();
                if (this.interesses.length) {
                    this.carregarEventosSelecionados();
                } else {
                    this.abrirModalInteresses();
                }
            },
            recusarCookies: function () {
                this.setCookie('ac_cookie_consent', '0', 365);
                this.removeCookie('ac_user_interests');
                this.cookieConsent = false;
                this.cookiesRecusados = true;
                this.mostrarBannerCookies = false;
                this.interesses = [];
                this.eventosSelecionados = [];
                this.mostrarModalInteresses = false;
            },
            abrirModalInteresses: function () {
                if (!this.cookieConsent) {
                    this.mostrarBannerCookies = true;
                    return;
                }
                this.erroInteresses = '';
                this.interessesTemp = this.interesses.slice();
                this.mostrarModalInteresses = true;
                this.atualizarIcones();
            },
            fecharModalInteresses: function () {
                this.mostrarModalInteresses = false;
                this.erroInteresses = '';
            },
            interesseTempAtivo: function (tag) {
                return this.interessesTemp.indexOf(tag) !== -1;
            },
            alternarInteresseTemp: function (tag) {
                const idx = this.interessesTemp.indexOf(tag);
                if (idx !== -1) {
                    this.interessesTemp.splice(idx, 1);
                } else {
                    this.interessesTemp.push(tag);
                }
                this.erroInteresses = '';
            },
            salvarInteresses: function () {
                if (!this.cookieConsent) {
                    this.erroInteresses = 'Aceite os cookies para salvar seus interesses.';
                    this.mostrarBannerCookies = true;
                    return;
                }
                if (!this.interessesTemp.length) {
                    this.erroInteresses = 'Selecione pelo menos um interesse.';
                    return;
                }
                this.interesses = this.interessesTemp.slice();
                this.setCookie('ac_user_interests', this.interesses.join(','), 365);
                this.mostrarModalInteresses = false;
                this.erroInteresses = '';
                this.carregarEventosSelecionados();
            },
            carregarEventosSelecionados: function () {
                if (!this.interesses.length) {
                    this.eventosSelecionados = [];
                    return;
                }

                this.carregandoSelecionados = true;
                ajax_load_class("\\Sistema\\Eventos", "getEventosPorInteresses", {
                    interesses: this.interesses,
                    limite: this.limiteSelecionados,
                    excluirTipoEstabelecimento: this.tipoBaresRestaurantes
                }).then((lista) => {
                    this.eventosSelecionados = Array.isArray(lista) ? lista : [];
                    this.carregandoSelecionados = false;
                    this.atualizarIcones();
                }).catch((e) => {
                    console.error("Erro ao carregar selecionados:", e);
                    this.eventosSelecionados = [];
                    this.carregandoSelecionados = false;
                });
            },
            rolarSelecionados: function (direcao) {
                const track = this.$refs.sliderSelecionados;
                if (!track) return;
                const card = track.querySelector('.selecionados-card');
                const passo = card ? card.offsetWidth + 24 : Math.max(track.clientWidth * 0.8, 320);
                track.scrollBy({ left: direcao * passo, behavior: 'smooth' });
            },
            atualizarIcones: function () {
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
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
            carregarDatasComEventos: function () {
                ajax_load_class("\\Sistema\\Eventos", "getDatasComEventos", {
                    mes: this.mesAtual + 1,
                    ano: this.anoAtual
                }).then(datas => {
                    this.datasComEventos = datas || [];
                });
            },
            selecionarData: function (dia) {
                if (this.dataSelecionada === dia.dataFull) {
                    this.limparFiltroData();
                } else {
                    this.dataSelecionada = dia.dataFull;
                    this.filtrarTudo();
                }
            },
            limparFiltroData: function () {
                this.dataSelecionada = null;
                this.filtrarTudo();
            },
            limparTodosFiltros: function () {
                if (this.carregando) return;
                this.filtroTags = [];
                this.dataSelecionada = null;
                this.filtrarTudo();
            },
            mesAnterior: function () {
                if (this.mesAtual === 0) {
                    this.mesAtual = 11;
                    this.anoAtual--;
                } else {
                    this.mesAtual--;
                }
                this.carregarDatasComEventos();
            },
            mesProximo: function () {
                if (this.mesAtual === 11) {
                    this.mesAtual = 0;
                    this.anoAtual++;
                } else {
                    this.mesAtual++;
                }
                this.carregarDatasComEventos();
            },
            formatarDataBR: function (dataStr) {
                if (!dataStr) return '';
                const parts = dataStr.split('-');
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            },
            tagAtiva: function (tag) {
                return this.filtroTags.indexOf(tag) !== -1;
            },
            filtrarTag: function (tag) {
                if (this.carregando) return;
                if (tag === '') {
                    this.filtroTags = [];
                } else {
                    const idx = this.filtroTags.indexOf(tag);
                    if (idx !== -1) {
                        this.filtroTags.splice(idx, 1);
                    } else {
                        this.filtroTags.push(tag);
                    }
                }
                this.filtrarTudo();
            },
            filtrarTudo: function () {
                this.eventos = [];
                this.temMaisEventos = false;
                this.consultaConcluida = false;
                this.erroCarregamento = false;

                let query = '?pg=home_v3';
                if (this.filtroTags.length) {
                    query += '&tag=' + encodeURIComponent(this.filtroTags.join(','));
                }
                if (this.dataSelecionada) query += '&data=' + encodeURIComponent(this.dataSelecionada);

                window.history.pushState({ path: query }, '', query);
                this.carregarTotalFiltros();
                this.carregarEventos();
            },
            carregarTotalFiltros: function () {
                ajax_load_class("\\Sistema\\Eventos", "contarEventos", {
                    tag: this.filtroTags.length ? this.filtroTags : null,
                    data: this.dataSelecionada,
                    tipoEstabelecimento: null,
                    excluirTipoEstabelecimento: this.tipoBaresRestaurantes
                }).then((total) => {
                    if (typeof total === 'number') {
                        this.totalEventosEncontrados = total;
                    }
                    this.atualizarIcones();
                });
            },
            carregarEventos: function () {
                if (this.carregando) return;

                this.carregando = true;
                this.erroCarregamento = false;

                const params = {
                    limite: this.limite,
                    tag: this.filtroTags.length ? this.filtroTags : null,
                    offset: 0,
                    data: this.dataSelecionada,
                    tipoEstabelecimento: null,
                    excluirTipoEstabelecimento: this.tipoBaresRestaurantes
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
                        this.eventos = novosEventos;
                        this.temMaisEventos = novosEventos.length >= this.limite;
                        this.carregando = false;
                        this.atualizarIcones();
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
