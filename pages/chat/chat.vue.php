<script>
    var appChat = new Vue({
        el: '#app_chat',
        data: {
            mensagens: [],
            inputUsuario: '',
            carregando: false
        },
        mounted: function () {
            // Auto-focus no input ao carregar
            this.$nextTick(() => {
                const input = document.querySelector('input');
                if (input) input.focus();
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        },
        methods: {
            enviarMensagem: function () {
                if (this.carregando || !this.inputUsuario.trim()) return;

                const texto = this.inputUsuario.trim();
                this.mensagens.push({
                    role: 'user',
                    text: texto
                });

                this.inputUsuario = '';
                this.carregando = true;

                // Scroll para o fim
                this.scrollToBottom();

                // Chamada AJAX para a IA
                ajax_load_class("\\Sistema\\ChatIA", "getRespostaIA", {
                    mensagens: this.mensagens
                })
                    .then((resposta) => {
                        this.mensagens.push({
                            role: 'assistant',
                            text: resposta
                        });
                        this.carregando = false;
                        this.scrollToBottom();

                        // Foca novamente no input
                        this.$nextTick(() => {
                            if (this.$refs.inputMensagem) this.$refs.inputMensagem.focus();
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        });
                    })
                    .catch((e) => {
                        console.error("Erro no chat:", e);
                        this.mensagens.push({
                            role: 'assistant',
                            text: "Desculpe, tive um erro ao processar sua mensagem. Tente novamente em alguns instantes."
                        });
                        this.carregando = false;
                        this.$nextTick(() => {
                            if (this.$refs.inputMensagem) this.$refs.inputMensagem.focus();
                        });
                    });
            },
            scrollToBottom: function () {
                this.$nextTick(() => {
                    window.scrollTo({
                        top: document.documentElement.scrollHeight,
                        behavior: 'smooth'
                    });
                });
            },
            formatarTexto: function (texto) {
                if (!texto) return '';
                // Transformar quebras de linha em <br> e bold markdown em <strong>
                let html = texto
                    .replace(/\n/g, '<br>')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

                // Tenta linkar URLs que não estejam em tags anchor
                const urlRegex = /(https?:\/\/[^\s]+)/g;
                html = html.replace(urlRegex, function (url) {
                    return '<a href="' + url + '" class="text-primary hover:underline font-bold" target="_blank">' + url + '</a>';
                });

                return html;
            }
        }
    });
</script>