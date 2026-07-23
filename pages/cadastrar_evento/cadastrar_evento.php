<!--[CONTAINER-layout_react]-->
<div id="app-cadastro" class="min-h-screen bg-background text-foreground pb-20">
    <!-- Header -->
    <header
        class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div class="container flex h-16 items-center justify-between px-4">
            <div class="flex items-center gap-4">
                <a href="<?= ROOT ?>" class="p-2 hover:bg-accent rounded-full transition-colors">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h1 class="text-lg font-semibold flex items-center gap-2">
                    <i data-lucide="calendar-plus" class="w-5 h-5 text-primary"></i>
                    Cadastrar Evento
                </h1>
            </div>
        </div>
    </header>

    <main class="container max-w-2xl px-4 py-8">
        <div class="glass-effect rounded-3xl p-6 md:p-8 shadow-2xl border border-white/10">
            <div class="space-y-8">
                <!-- Banner Preview -->
                <div class="relative group cursor-pointer overflow-hidden rounded-2xl bg-muted aspect-video flex flex-col items-center justify-center border-2 border-dashed border-border hover:border-primary/50 transition-all"
                    @click="$refs.inputImagem.click()">
                    <img v-if="previewImagem" :src="previewImagem" class="absolute inset-0 w-full h-full object-cover">
                    <div v-if="!previewImagem" class="text-center p-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="image-plus" class="w-6 h-6 text-primary"></i>
                        </div>
                        <p class="font-medium text-sm">Adicionar imagem do evento</p>
                        <p class="text-xs text-muted-foreground mt-1">Clique para fazer upload (PNG, JPG)</p>
                    </div>
                    <div v-else
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                        <p class="text-white font-medium flex items-center gap-2">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            Trocar imagem
                        </p>
                    </div>
                    <input type="file" ref="inputImagem" class="hidden" accept="image/*" @change="onImagemChange">
                </div>

                <!-- Form Fields -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Nome do Evento -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Nome do Evento</label>
                        <div class="relative">
                            <i data-lucide="type" class="absolute left-3 top-3 w-4 h-4 text-muted-foreground"></i>
                            <input type="text" v-model="form.nome" placeholder="Ex: Show de Rock no Parque"
                                class="flex h-10 w-full rounded-md border border-input bg-background/50 pl-10 px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 border-white/10 focus:border-primary/50 transition-all">
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Descrição</label>
                        <textarea v-model="form.descricao" rows="4" placeholder="Conte mais sobre o evento..."
                            class="flex min-h-[100px] w-full rounded-md border border-input bg-background/50 px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 border-white/10 focus:border-primary/50 transition-all"></textarea>
                    </div>

                    <!-- Data e Hora -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Data</label>
                            <div class="relative">
                                <i data-lucide="calendar"
                                    class="absolute left-3 top-3 w-4 h-4 text-muted-foreground"></i>
                                <input type="text" v-model="form.data" v-mask="'##/##/####'" placeholder="DD/MM/AAAA"
                                    class="flex h-10 w-full rounded-md border border-input bg-background/50 pl-10 px-3 py-2 text-sm ring-offset-background border-white/10 focus:border-primary/50 transition-all">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Hora</label>
                            <div class="relative">
                                <i data-lucide="clock" class="absolute left-3 top-3 w-4 h-4 text-muted-foreground"></i>
                                <input type="text" v-model="form.hora" v-mask="'##:##'" placeholder="HH:MM"
                                    class="flex h-10 w-full rounded-md border border-input bg-background/50 pl-10 px-3 py-2 text-sm ring-offset-background border-white/10 focus:border-primary/50 transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Local -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Localização</label>
                        <div class="relative">
                            <i data-lucide="map-pin" class="absolute left-3 top-3 w-4 h-4 text-muted-foreground"></i>
                            <input type="text" v-model="form.local" placeholder="Ex: Teatro Municipal"
                                class="flex h-10 w-full rounded-md border border-input bg-background/50 pl-10 px-3 py-2 text-sm ring-offset-background border-white/10 focus:border-primary/50 transition-all">
                        </div>
                    </div>

                    <!-- Valor e Faixa Etária -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Valor ingresso</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-sm text-muted-foreground">R$</span>
                                <input type="text" v-model="form.valor" placeholder="Grátis"
                                    class="flex h-10 w-full rounded-md border border-input bg-background/50 pl-10 px-3 py-2 text-sm ring-offset-background border-white/10 focus:border-primary/50 transition-all">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Classificação</label>
                            <div class="relative">
                                <i data-lucide="users" class="absolute left-3 top-3 w-4 h-4 text-muted-foreground"></i>
                                <select v-model="form.faixa_etaria"
                                    class="flex h-10 w-full rounded-md border border-input bg-background/50 pl-10 px-3 py-2 text-sm ring-offset-background border-white/10 focus:border-primary/50 transition-all appearance-none">
                                    <option value="Livre">Livre</option>
                                    <option value="10 anos">10 anos</option>
                                    <option value="12 anos">12 anos</option>
                                    <option value="14 anos">14 anos</option>
                                    <option value="16 anos">16 anos</option>
                                    <option value="18 anos">18 anos</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none">Tags (separadas por vírgula)</label>
                        <div class="relative">
                            <i data-lucide="tag" class="absolute left-3 top-3 w-4 h-4 text-muted-foreground"></i>
                            <input type="text" v-model="form.tags" placeholder="Ex: show, rock, gratuito, familia"
                                class="flex h-10 w-full rounded-md border border-input bg-background/50 pl-10 px-3 py-2 text-sm ring-offset-background border-white/10 focus:border-primary/50 transition-all">
                        </div>
                    </div>
                </div>

                <!-- Footer / Submit -->
                <div class="pt-4 flex flex-col gap-4">
                    <button @click="submeter" :disabled="enviando"
                        class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-8 py-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/20 disabled:opacity-50">
                        <template v-if="!enviando">
                            <i data-lucide="send" class="w-5 h-5"></i>
                            Publicar Evento
                        </template>
                        <template v-else>
                            <i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>
                            Publicando...
                        </template>
                    </button>
                    <p class="text-center text-xs text-muted-foreground">
                        Ao publicar, o evento fica disponível na agenda cultural.
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>