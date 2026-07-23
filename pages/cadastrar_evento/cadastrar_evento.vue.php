<script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    Vue.use(VueMask.VueMaskPlugin);

    new Vue({
        el: '#app-cadastro',
        data() {
            return {
                enviando: false,
                previewImagem: null,
                form: {
                    nome: '',
                    descricao: '',
                    data: '',
                    hora: '',
                    local: '',
                    valor: '',
                    faixa_etaria: 'Livre',
                    tags: '',
                    imagem: ''
                }
            }
        },
        methods: {
            onImagemChange(e) {
                const file = e.target.files[0];
                if (!file) return;

                // Preview
                this.previewImagem = URL.createObjectURL(file);

                // Base64 para envio
                const reader = new FileReader();
                reader.onload = (f) => {
                    this.form.imagem = f.target.result;
                };
                reader.readAsDataURL(file);
            },

            async submeter() {
                if (!this.form.nome || !this.form.data) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos obrigatórios',
                        text: 'Por favor, preencha pelo menos o nome e a data do evento.',
                        background: '#1a1a1a',
                        color: '#fff'
                    });
                    return;
                }

                this.enviando = true;

                try {
                    const payload = Object.assign({}, this.form, {
                        hora: this.form.hora || '00:00'
                    });

                    const response = await fetch(ROOT + 'action-cadastrar_evento', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const raw = await response.text();
                    let result;
                    try {
                        result = JSON.parse(raw);
                    } catch (e) {
                        throw new Error('Resposta inválida do servidor ao cadastrar o evento.');
                    }

                    if (!response.ok || result.error) {
                        throw new Error(result.error_msg || 'Não foi possível cadastrar o evento.');
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Excelente!',
                        text: result.msg || 'Seu evento foi cadastrado com sucesso.',
                        background: '#1a1a1a',
                        color: '#fff',
                        confirmButtonColor: 'hsl(24 95% 53%)'
                    });

                    window.location.href = ROOT;

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: error.message || 'Ocorreu um erro ao cadastrar o evento. Tente novamente mais tarde.',
                        background: '#1a1a1a',
                        color: '#fff'
                    });
                } finally {
                    this.enviando = false;
                }
            }
        },
        mounted() {
            // Re-initializa ícones lucide caso necessário
            if (window.lucide) {
                lucide.createIcons();
            }
        }
    });
</script>

<style>
    .glass-effect {
        background: rgba(26, 26, 26, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Estilização para o SweetAlert2 combinar com o tema escuro */
    .swal2-popup {
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 1.5rem !important;
    }
</style>