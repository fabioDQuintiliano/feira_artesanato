<!--[CONTAINER-layout_react]-->

<div id="app_home" v-cloak :class="{ 'ia-split-active': modoChatAtivo }">
    <div class="ia-split-main">
    <header class="fixed top-0 left-0 right-0 z-50 bg-background/95 backdrop-blur-md border-b border-border/50 ia-split-header">
        <div class="container px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <a href="<?= ROOT ?>" class="flex items-center group" aria-label="Agenda Cultural">
                    <img src="<?= ROOT ?>images/logo_horizontal.png" alt="Agenda Cultural Ribeirão Preto"
                        class="h-14 md:h-[72px] w-auto object-contain" />
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-8" v-if="!modoChatAtivo">
                    <a href="<?= ROOT ?>eventos"
                        class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors">
                        Eventos
                    </a>
                    <a href="<?= ROOT ?>eventos?tipo=bares"
                        class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors">
                        Bares e Restaurantes
                    </a>
                    <a href="#categorias"
                        class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors">
                        Categorias
                    </a>
                </nav>

                <button v-if="modoChatAtivo" type="button" @click="fecharModoChat"
                    class="hidden md:inline-flex items-center gap-2 text-sm font-medium text-muted-foreground hover:text-primary transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Voltar à agenda
                </button>

                <!-- CTA Button -->
                <div class="hidden md:block" v-if="!modoChatAtivo">
                    <a href="<?= ROOT ?>cadastrar_evento"
                        class="bg-primary hover:bg-primary/90 text-primary-foreground text-sm font-medium px-4 py-2 rounded-md transition-colors">
                        Adicionar Evento
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="md:hidden p-2 text-foreground hover:text-primary transition-colors"
                    v-if="!modoChatAtivo">
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>
                <button v-else type="button" @click="fecharModoChat"
                    class="md:hidden p-2 text-foreground hover:text-primary transition-colors"
                    aria-label="Voltar à agenda">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero: Guia Cultural -->
        <section v-if="!modoChatAtivo"
            class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-gradient-to-b from-background via-background to-secondary/30">
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-primary/10 blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-accent/10 blur-3xl"></div>
            </div>

            <div class="container relative z-10 px-4 py-16 md:py-24 lg:py-28">
                <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-10 lg:gap-14">
                    <div class="flex items-center justify-center lg:justify-start">
                        <img src="<?= ROOT ?>images/logo_cm.png"
                            alt="Agenda Cultural Ribeirão Preto"
                            class="w-full max-w-[460px] xl:max-w-[540px] object-contain drop-shadow-2xl" />
                    </div>

                    <div class="text-center lg:text-left w-full max-w-xl mx-auto lg:mx-0">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/25 mb-5">
                            <i data-lucide="bot" class="h-4 w-4 text-primary"></i>
                            <span class="text-[11px] font-bold text-primary uppercase tracking-[0.18em]">
                                Guia Cultural IA
                            </span>
                        </div>

                        <h1 class="font-display text-4xl sm:text-5xl md:text-6xl mb-4 tracking-tight text-foreground leading-[1.05]">
                            O que você
                            <span class="text-gradient font-semibold">quer viver</span>
                            hoje?
                        </h1>

                        <p class="text-base md:text-lg text-muted-foreground mb-8 leading-relaxed">
                            Pergunte ao Guia Cultural e descubra eventos reais da agenda, no clima certo para você.
                        </p>

                        <div class="hero-ia-input flex items-center gap-2 rounded-2xl border border-border/70 bg-card/90 shadow-xl shadow-primary/10 p-2 pl-4">
                            <i data-lucide="sparkles" class="h-5 w-5 text-primary shrink-0 hidden sm:block"></i>
                            <input type="text"
                                v-model="chatInput"
                                @keyup.enter="enviarMensagemChat"
                                ref="heroChatInput"
                                :placeholder="chatPlaceholderAtual"
                                class="hero-ia-input__field flex-1 bg-transparent border-none focus:ring-0 text-base text-foreground placeholder:text-muted-foreground py-3 outline-none min-w-0"
                                :disabled="chatCarregando"
                                autocomplete="off" />
                            <button type="button" @click="enviarMensagemChat"
                                class="shrink-0 inline-flex items-center justify-center gap-2 rounded-xl bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-4 sm:px-5 py-3 transition-all disabled:opacity-50"
                                :disabled="chatCarregando || !chatInput.trim()"
                                aria-label="Enviar pergunta">
                                <span class="hidden sm:inline">Perguntar</span>
                                <i data-lucide="send" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Resultados da IA (modo split) -->
        <section v-if="modoChatAtivo" class="pt-24 md:pt-28 pb-10 md:pb-16 bg-background min-h-screen">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <p class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-2">Sugestões do Guia</p>
                    <h2 class="text-2xl md:text-3xl font-display font-bold text-foreground mb-2">
                        Eventos para você
                    </h2>
                    <p class="text-sm text-muted-foreground max-w-2xl">
                        A lista abaixo acompanha a conversa com a IA. Continue perguntando na barra lateral para refinar.
                    </p>
                </div>

                <div v-if="chatCarregando && !eventosSugeridosIA.length" class="flex justify-center py-20">
                    <div class="h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
                </div>

                <div v-else-if="!eventosSugeridosIA.length" class="py-16 text-center border border-dashed border-border rounded-2xl">
                    <i data-lucide="search" class="h-8 w-8 text-muted-foreground mx-auto mb-3"></i>
                    <p class="text-muted-foreground">
                        Ainda não encontramos eventos para esta pergunta. Tente detalhar um pouco mais no chat.
                    </p>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 md:gap-6">
                    <a v-for="ev in eventosSugeridosIA" :key="'ia-' + ev.id"
                        :href="'<?= ROOT ?>evento/' + ev.txtid"
                        class="group bg-card rounded-xl border border-border/50 overflow-hidden hover:border-primary/50 transition-all duration-300 flex flex-col h-full hover:shadow-xl hover:shadow-primary/5">
                        <div class="relative aspect-video overflow-hidden">
                            <img :src="imagemEvento(ev)" :alt="ev.titulo"
                                @error="usarPlaceholder($event, ev)"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 rounded-md bg-primary text-primary-foreground text-[10px] font-bold uppercase tracking-wider">
                                    Sugerido
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-primary text-xs font-semibold mb-3">
                                <i data-lucide="calendar" class="h-3 w-3"></i>
                                <span>{{ ev.data }}</span>
                                <span class="text-muted-foreground/30">•</span>
                                <i data-lucide="clock" class="h-3 w-3"></i>
                                <span>{{ ev.hora }}</span>
                            </div>
                            <h3 class="text-lg font-heading font-bold text-foreground mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                {{ ev.titulo }}
                            </h3>
                            <div class="text-sm text-muted-foreground line-clamp-3 mb-4 flex-1" v-html="ev.descricao"></div>
                            <div class="mt-auto inline-flex items-center gap-2 text-sm font-bold text-foreground">
                                Saiba Mais
                                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <template v-if="!modoChatAtivo">
        <!-- Divisor animado entre o hero e a agenda -->
        <div class="wave-divider" aria-hidden="true">
            <?php
            $waveLayers = [
                ['#fe3403', 'M0,4 C38,0 112,0 150,4 C188,8 262,8 300,4 C338,0 412,0 450,4 C488,8 562,8 600,4 C638,0 712,0 750,4 C788,8 862,8 900,4 C938,0 1012,0 1050,4 C1088,8 1162,8 1200,4 L1200,75 L0,75 Z'],
                ['#007dc4', 'M0,24 C30,20 90,20 120,24 C150,28 210,28 240,24 C270,20 330,20 360,24 C390,28 450,28 480,24 C510,20 570,20 600,24 C630,28 690,28 720,24 C750,20 810,20 840,24 C870,28 930,28 960,24 C990,20 1050,20 1080,24 C1110,28 1170,28 1200,24 L1200,75 L0,75 Z'],
                ['#fec400', 'M0,32 C50,28 150,28 200,32 C250,36 350,36 400,32 C450,28 550,28 600,32 C650,36 750,36 800,32 C850,28 950,28 1000,32 C1050,36 1150,36 1200,32 L1200,75 L0,75 Z'],
                ['#e67000', 'M0,39 C38,33 112,33 150,39 C188,45 262,45 300,39 C338,35 412,35 450,39 C488,43 562,43 600,39 C638,32 712,32 750,39 C788,46 862,46 900,39 C938,36 1012,36 1050,39 C1088,42 1162,42 1200,39 L1200,75 L0,75 Z'],
                ['#ecd0d0', 'M0,57 C50,51 150,51 200,57 C250,63 350,63 400,57 C450,54 550,54 600,57 C650,60 750,60 800,57 C850,50 950,50 1000,57 C1050,64 1150,64 1200,57 L1200,75 L0,75 Z'],
            ];
            ?>
            <?php foreach ($waveLayers as $index => [$color, $path]): ?>
                <div class="wave-divider__layer wave-divider__layer--<?= $index + 1 ?>">
                    <div class="wave-divider__track">
                        <?php for ($copy = 0; $copy < 2; $copy++): ?>
                            <svg viewBox="0 0 1200 75" preserveAspectRatio="none">
                                <path fill="<?= $color ?>" d="<?= $path ?>" />
                            </svg>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Selecionados para você -->
        <section id="selecionados" class="selecionados-section relative"
            :class="interesses.length ? 'py-16 md:py-24' : 'py-10 md:py-12'">
            <div class="container relative z-10 px-4">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4"
                    :class="interesses.length ? 'mb-8 md:mb-10' : 'mb-0'">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl sm:text-4xl md:text-5xl font-display font-bold text-foreground leading-[1.05] mb-3">
                            Selecionados
                            <span class="text-gradient block sm:inline">para você</span>
                        </h2>
                        <div class="flex flex-col gap-4">
                            <p class="flex items-start gap-2.5 text-sm md:text-base text-muted-foreground max-w-xl leading-relaxed">
                                <i data-lucide="sparkles" class="h-4 w-4 text-primary shrink-0 mt-0.5"></i>
                                <span>
                                    <template v-if="interesses.length">
                                        Sugestões com base nos seus interesses:
                                        <span class="text-foreground font-semibold">{{ interessesExibicao }}</span>
                                    </template>
                                    <template v-else>
                                        Escolha suas categorias favoritas e montamos uma seleção de eventos sob medida para o seu perfil.
                                    </template>
                                </span>
                            </p>
                            <div v-if="!interesses.length && !carregandoSelecionados"
                                class="selecionados-anim" aria-hidden="true">
                                <div class="selecionados-anim__tags">
                                    <span class="selecionados-anim__tag selecionados-anim__tag--1">Música</span>
                                    <span class="selecionados-anim__tag selecionados-anim__tag--2">Teatro</span>
                                    <span class="selecionados-anim__tag selecionados-anim__tag--3">Cinema</span>
                                    <span class="selecionados-anim__tag selecionados-anim__tag--4">Arte</span>
                                </div>
                                <div class="selecionados-anim__flow">
                                    <span class="selecionados-anim__dot"></span>
                                    <span class="selecionados-anim__dot"></span>
                                    <span class="selecionados-anim__dot"></span>
                                    <i data-lucide="sparkles" class="selecionados-anim__spark h-4 w-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                        <template v-if="eventosSelecionados.length">
                            <button type="button" @click="rolarSelecionados(-1)"
                                class="selecionados-slider__nav" aria-label="Eventos anteriores">
                                <i data-lucide="chevron-left" class="h-5 w-5"></i>
                            </button>
                            <button type="button" @click="rolarSelecionados(1)"
                                class="selecionados-slider__nav" aria-label="Próximos eventos">
                                <i data-lucide="chevron-right" class="h-5 w-5"></i>
                            </button>
                        </template>
                        <button type="button" @click="abrirModalInteresses"
                            class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-5 py-2.5 rounded-lg text-sm shadow-md shadow-primary/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <i data-lucide="sliders-horizontal" class="h-4 w-4"></i>
                            {{ interesses.length ? 'Editar interesses' : 'Personalizar interesses' }}
                        </button>
                    </div>
                </div>

                <div v-if="carregandoSelecionados" class="flex justify-center py-12">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
                </div>

                <div v-else-if="eventosSelecionados.length === 0 && interesses.length"
                    class="py-8 text-center">
                    <p class="text-sm text-muted-foreground">
                        Não encontramos eventos para esses interesses no momento.
                    </p>
                    <button type="button" @click="abrirModalInteresses"
                        class="text-primary hover:underline mt-2 inline-block text-sm font-medium">
                        Ajustar interesses
                    </button>
                </div>
            </div>

            <div v-if="eventosSelecionados.length && !carregandoSelecionados"
                class="selecionados-slider relative mt-2">
                <div ref="sliderSelecionados"
                    class="selecionados-slider__track flex gap-5 md:gap-6 overflow-x-auto pb-3 snap-x snap-mandatory custom-scrollbar">
                    <a v-for="(ev, idx) in eventosSelecionados" :key="'sel-' + ev.id"
                        :href="'<?= ROOT ?>evento/' + ev.txtid"
                        class="selecionados-card snap-start shrink-0 w-[min(280px,82vw)] sm:w-[300px] md:w-[280px] lg:w-[300px] group bg-background rounded-xl border border-border/60 overflow-hidden transition-all duration-300 flex flex-col hover:border-primary/60 hover:shadow-xl hover:shadow-primary/10"
                        :style="{ animationDelay: (idx * 60) + 'ms' }">
                        <div class="relative aspect-video overflow-hidden">
                            <img :src="imagemEvento(ev)" :alt="ev.titulo"
                                @error="usarPlaceholder($event, ev)"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
                            <div class="absolute inset-0 bg-gradient-to-t from-foreground/30 via-transparent to-transparent opacity-70"></div>
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-2 py-1 rounded-md bg-primary text-primary-foreground text-[10px] font-bold uppercase tracking-wider shadow-md shadow-primary/30">
                                    Para você
                                </span>
                            </div>
                            <div class="absolute top-3 right-3 flex gap-1.5">
                                <span v-for="tag in ev.tags.slice(0, 2)" :key="tag"
                                    class="px-2 py-1 rounded-md bg-background/85 backdrop-blur-sm text-[10px] font-bold text-primary uppercase tracking-wider">
                                    {{ tag }}
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-primary text-xs font-semibold mb-3">
                                <i data-lucide="calendar" class="h-3 w-3"></i>
                                <span>{{ ev.data }}</span>
                                <span class="text-muted-foreground/30">•</span>
                                <i data-lucide="clock" class="h-3 w-3"></i>
                                <span>{{ ev.hora }}</span>
                            </div>
                            <h3
                                class="text-lg font-heading font-bold text-foreground mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                {{ ev.titulo }}
                            </h3>
                            <div class="text-sm text-muted-foreground line-clamp-3 mb-4 flex-1" v-html="ev.descricao">
                            </div>
                            <div
                                class="mt-auto inline-flex items-center gap-2 text-sm font-bold text-foreground group/link">
                                Saiba Mais
                                <i data-lucide="arrow-up-right"
                                    class="h-4 w-4 transform group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- Calendário de Eventos -->
        <section id="discovery" class="py-12 md:py-20 bg-background relative overflow-hidden">
            <!-- Background Decoration Amplo -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-accent/5 to-background -z-10"></div>

            <div class="container px-4">
                <div class="grid lg:grid-cols-12 gap-8 items-stretch">

                    <!-- Lado Esquerdo: Calendário -->
                    <div class="lg:col-span-5">
                        <div
                            class="glass-effect rounded-[2.5rem] border border-white/10 p-6 md:p-8 shadow-2xl relative h-full flex flex-col">
                            <!-- Cabeçalho do Calendário -->
                            <div class="flex items-center justify-between mb-8 px-2">
                                <div>
                                    <h3 class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mb-1">
                                        Agenda Cultural</h3>
                                    <h4
                                        class="text-xl font-display font-bold text-foreground capitalize flex items-center gap-2">
                                        {{ nomeMesAtual }} <span class="opacity-40 font-normal">{{ anoAtual }}</span>
                                    </h4>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="mesAnterior"
                                        class="p-2 hover:bg-white/5 rounded-full border border-white/5 transition-colors">
                                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="mesProximo"
                                        class="p-2 hover:bg-white/5 rounded-full border border-white/5 transition-colors">
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Grade do Calendário -->
                            <div class="grid grid-cols-7 gap-2 mb-2 flex-grow">
                                <div v-for="dia in diasSemana" :key="dia"
                                    class="text-center text-[9px] font-bold text-muted-foreground uppercase tracking-widest pb-1">
                                    {{ dia }}
                                </div>
                                <div v-for="n in diasVazios" :key="'vazio-'+n" class="aspect-square"></div>
                                <div v-for="dia in diasNoMes" :key="dia.dataFull" @click="selecionarData(dia)"
                                    class="relative aspect-square rounded-xl border transition-all cursor-pointer flex flex-col items-center justify-center group text-xs sm:text-sm"
                                    :class="[
                                        dia.hoje ? 'border-primary/40' : 'border-white/5',
                                        dia.temEvento ? 'bg-primary/5 border-primary/10 hover:bg-primary/10' : 'hover:bg-white/5',
                                        dataSelecionada === dia.dataFull ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20 scale-105 z-10' : ''
                                     ]">
                                    <span class="font-bold"
                                        :class="dataSelecionada === dia.dataFull ? 'text-primary-foreground' : 'text-foreground'">{{
                                        dia.dia }}</span>
                                    <div v-if="dia.temEvento" class="absolute bottom-2 w-1 h-1 rounded-full"
                                        :class="dataSelecionada === dia.dataFull ? 'bg-primary-foreground' : 'bg-primary'">
                                    </div>
                                </div>
                            </div>

                            <!-- Feedback Filtro -->
                            <div v-if="dataSelecionada" class="mt-6 flex justify-center">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary animate-in fade-in zoom-in duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="w-3.5 h-3.5">
                                        <path d="M8 2v4" />
                                        <path d="M16 2v4" />
                                        <rect width="18" height="18" x="3" y="4" rx="2" />
                                        <path d="M3 10h18" />
                                        <path d="m9 16 2 2 4-4" />
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase">Filtrando: {{
                                        formatarDataBR(dataSelecionada) }}</span>
                                    <button @click="limparFiltroData"
                                        class="hover:text-foreground transition-colors ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="w-3.5 h-3.5">
                                            <path d="M18 6 6 18" />
                                            <path d="m6 6 12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lado Direito: Categorias -->
                    <div id="categorias" class="lg:col-span-7 flex">
                        <div
                            class="glass-effect rounded-[2.5rem] border border-white/10 p-6 md:p-8 shadow-2xl relative h-full w-full flex flex-col">
                            <div class="mb-8 px-2">
                                <h3 class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mb-1 flex items-center gap-2">
                                    <i data-lucide="tag" class="h-3 w-3"></i>
                                    Filtros
                                </h3>
                                <h4 class="text-xl font-display font-bold text-foreground">
                                    Principais Categorias
                                </h4>
                                <p class="text-sm text-muted-foreground mt-2">
                                    Selecione uma ou mais tags para combinar os filtros.
                                </p>
                            </div>

                            <div class="flex flex-wrap content-start gap-3 flex-grow">
                                <button @click="filtrarTag('')"
                                    class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all border"
                                    :class="!filtroTags.length ? 'bg-primary text-primary-foreground border-primary shadow-lg shadow-primary/20' : 'bg-secondary text-muted-foreground border-border/50 hover:bg-secondary/80'">
                                    Todos
                                </button>
                                <button v-for="tag in tagsPopulares" :key="tag" @click="filtrarTag(tag)"
                                    class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-all border"
                                    :class="tagAtiva(tag) ? 'bg-primary text-primary-foreground border-primary shadow-lg shadow-primary/20' : 'bg-secondary text-muted-foreground border-border/50 hover:bg-secondary/80'">
                                    {{ tag.charAt(0).toUpperCase() + tag.slice(1) }}
                                </button>
                            </div>

                            <div v-if="temFiltrosAtivos" class="mt-6 px-2">
                                <div
                                    class="inline-flex flex-wrap items-center gap-3 rounded-2xl border border-primary/20 bg-primary/5 px-4 py-3 text-sm text-foreground">
                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="h-4 w-4">
                                            <circle cx="11" cy="11" r="8" />
                                            <path d="m21 21-4.3-4.3" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold leading-tight">
                                            {{ totalEventosEncontrados }}
                                            {{ totalEventosEncontrados === 1 ? 'evento encontrado' : 'eventos encontrados' }}
                                        </p>
                                        <p class="text-xs text-muted-foreground mt-0.5">
                                            <template v-if="filtroTags.length && dataSelecionada">
                                                para as categorias selecionadas em {{ formatarDataBR(dataSelecionada) }}
                                            </template>
                                            <template v-else-if="dataSelecionada">
                                                para a data {{ formatarDataBR(dataSelecionada) }}
                                            </template>
                                            <template v-else>
                                                para os filtros selecionados
                                            </template>
                                        </p>
                                    </div>
                                    <button @click="limparTodosFiltros"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-border/60 bg-background/80 px-3 py-2 text-xs font-semibold text-muted-foreground transition-colors hover:border-primary/30 hover:text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="h-3.5 w-3.5">
                                            <path d="M18 6 6 18" />
                                            <path d="m6 6 12 12" />
                                        </svg>
                                        Limpar filtros
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Listagem de Eventos -->
        <section id="eventos" class="py-20 bg-background">
            <div class="container px-4">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-display font-bold text-foreground mb-4">
                            {{ tituloFiltroTags }}
                        </h2>
                        <p class="text-muted-foreground max-w-xl">
                            Confira a seleção dos melhores eventos culturais programados para os próximos dias.
                        </p>
                    </div>
                </div>

                <div v-if="!carregando && consultaConcluida && !erroCarregamento && eventos.length === 0"
                    class="py-20 text-center border-2 border-dashed border-border rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="h-12 w-12 text-muted-foreground mx-auto mb-4">
                        <path d="M8 2v4" />
                        <path d="M16 2v4" />
                        <rect width="18" height="18" x="3" y="4" rx="2" />
                        <path d="M3 10h18" />
                        <path d="m14 14-4 4" />
                        <path d="m10 14 4 4" />
                    </svg>
                    <p class="text-lg text-muted-foreground">Nenhum evento encontrado para este filtro.</p>
                    <button @click="limparTodosFiltros" class="text-primary hover:underline mt-2 inline-block">Ver todos os
                        eventos</button>
                </div>

                <div v-else-if="!carregando && erroCarregamento && eventos.length === 0"
                    class="py-20 text-center border-2 border-dashed border-border rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="h-12 w-12 text-muted-foreground mx-auto mb-4">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
                        <path d="M21 3v5h-5" />
                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
                        <path d="M8 16H3v5" />
                    </svg>
                    <p class="text-lg text-muted-foreground">Não foi possível carregar os eventos.</p>
                    <button @click="carregarEventos" class="text-primary hover:underline mt-2 inline-block">
                        Tentar novamente
                    </button>
                </div>

                <div v-else-if="eventos.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <a v-for="ev in eventos" :key="ev.id" :href="'<?= ROOT ?>evento/' + ev.txtid"
                        class="group bg-card rounded-xl border border-border/50 overflow-hidden hover:border-primary/50 transition-all duration-300 flex flex-col h-full hover:shadow-xl hover:shadow-primary/5">
                        <!-- Imagem -->
                        <div class="relative aspect-video overflow-hidden">
                            <img :src="imagemEvento(ev)" :alt="ev.titulo"
                                @error="usarPlaceholder($event, ev)"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
                            <div class="absolute top-3 right-3 flex gap-2">
                                <span v-for="tag in ev.tags.slice(0, 2)" :key="tag"
                                    class="px-2 py-1 rounded-md bg-background/80 backdrop-blur-sm text-[10px] font-bold text-primary uppercase tracking-wider">
                                    {{ tag }}
                                </span>
                            </div>
                        </div>

                        <!-- Conteúdo -->
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-primary text-xs font-semibold mb-3">
                                <i data-lucide="calendar" class="h-3 w-3"></i>
                                <span>{{ ev.data }}</span>
                                <span class="text-muted-foreground/30">•</span>
                                <i data-lucide="clock" class="h-3 w-3"></i>
                                <span>{{ ev.hora }}</span>
                            </div>
                            <h3
                                class="text-lg font-heading font-bold text-foreground mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                {{ ev.titulo }}
                            </h3>
                            <div class="text-sm text-muted-foreground line-clamp-3 mb-4 flex-1" v-html="ev.descricao">
                            </div>
                            <div
                                class="mt-auto inline-flex items-center gap-2 text-sm font-bold text-foreground group/link">
                                Saiba Mais
                                <i data-lucide="arrow-up-right"
                                    class="h-4 w-4 transform group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div v-if="carregando" class="flex justify-center py-12">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
                </div>

                <div v-if="!carregando && eventos.length > 0 && temMaisEventos" class="flex justify-center mt-12">
                    <a :href="urlVerMaisEventos"
                        class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-8 py-3 rounded-md transition-colors">
                        Ver mais eventos
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Bares e Restaurantes -->
        <section id="bares" v-if="eventosBares.length" class="py-20 bg-secondary/30 border-t border-border/50">
            <div class="container px-4">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-display font-bold text-foreground mb-4">
                            Bares e Restaurantes
                        </h2>
                        <p class="text-muted-foreground max-w-xl">
                            Programação dos bares e restaurantes da cidade para os próximos dias.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <a v-for="ev in eventosBares" :key="'bar-' + ev.id" :href="'<?= ROOT ?>evento/' + ev.txtid"
                        class="group bg-card rounded-xl border border-border/50 overflow-hidden hover:border-primary/50 transition-all duration-300 flex flex-col h-full hover:shadow-xl hover:shadow-primary/5">
                        <div class="relative aspect-video overflow-hidden">
                            <img :src="imagemEvento(ev)" :alt="ev.titulo"
                                @error="usarPlaceholder($event, ev)"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-2 py-1 rounded-md bg-background/80 backdrop-blur-sm text-[10px] font-bold text-primary uppercase tracking-wider">
                                    Bar & Restaurante
                                </span>
                            </div>
                            <div class="absolute top-3 right-3 flex gap-2">
                                <span v-for="tag in ev.tags.slice(0, 2)" :key="tag"
                                    class="px-2 py-1 rounded-md bg-background/80 backdrop-blur-sm text-[10px] font-bold text-primary uppercase tracking-wider">
                                    {{ tag }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center gap-2 text-primary text-xs font-semibold mb-3">
                                <i data-lucide="calendar" class="h-3 w-3"></i>
                                <span>{{ ev.data }}</span>
                                <span class="text-muted-foreground/30">•</span>
                                <i data-lucide="clock" class="h-3 w-3"></i>
                                <span>{{ ev.hora }}</span>
                            </div>
                            <h3
                                class="text-lg font-heading font-bold text-foreground mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                {{ ev.titulo }}
                            </h3>
                            <div class="text-sm text-muted-foreground line-clamp-3 mb-4 flex-1" v-html="ev.descricao">
                            </div>
                            <div
                                class="mt-auto inline-flex items-center gap-2 text-sm font-bold text-foreground group/link">
                                Saiba Mais
                                <i data-lucide="arrow-up-right"
                                    class="h-4 w-4 transform group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div v-if="temMaisBares" class="flex justify-center mt-12">
                    <a :href="urlVerMaisBares"
                        class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-8 py-3 rounded-md transition-colors">
                        Ver mais bares e restaurantes
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </a>
                </div>
            </div>
        </section>
        </template>
</main>

<footer v-if="!modoChatAtivo" class="bg-secondary border-t border-border py-12 md:py-16">
    <div class="container px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12 text-left">
            <!-- Brand -->
            <div class="md:col-span-2">
                <a href="<?= ROOT ?>" class="inline-flex items-center mb-4" aria-label="Agenda Cultural">
                    <img src="<?= ROOT ?>images/logo_cm.png" alt="Agenda Cultural Ribeirão Preto"
                        class="h-28 w-auto object-contain" />
                </a>
                <p class="text-sm text-muted-foreground max-w-sm">
                    Sua plataforma para descobrir os melhores eventos culturais da cidade.
                    Shows, exposições, teatro e muito mais.
                </p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-heading font-semibold text-foreground mb-4">Navegação</h4>
                <ul class="space-y-2">
                    <li><a href="<?= ROOT ?>eventos"
                            class="text-sm text-muted-foreground hover:text-primary transition-colors">Eventos</a></li>
                    <li><a href="<?= ROOT ?>eventos?tipo=bares"
                            class="text-sm text-muted-foreground hover:text-primary transition-colors">Bares e Restaurantes</a>
                    </li>
                    <li><a href="#categorias"
                            class="text-sm text-muted-foreground hover:text-primary transition-colors">Categorias</a>
                    </li>
                    <li><a href="<?= ROOT ?>cadastrar_evento"
                            class="text-sm text-muted-foreground hover:text-primary transition-colors">Cadastrar
                            Evento</a>
                    </li>
                </ul>
            </div>

            <!-- Social -->
            <div>
                <h4 class="font-heading font-semibold text-foreground mb-4">Redes Sociais</h4>
                <div class="flex gap-3">
                    <a href="https://www.instagram.com/cultura_me_/" target="_blank" rel="noopener noreferrer"
                        aria-label="Instagram"
                        class="w-10 h-10 rounded-lg bg-muted flex items-center justify-center text-muted-foreground hover:text-primary hover:bg-primary/10 transition-all">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 fill-none stroke-current"
                            stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                            <circle cx="12" cy="12" r="4"></circle>
                            <circle cx="17.5" cy="6.5" r="1" class="fill-current stroke-none"></circle>
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook"
                        class="w-10 h-10 rounded-lg bg-muted flex items-center justify-center text-muted-foreground hover:text-primary hover:bg-primary/10 transition-all">
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 fill-current">
                            <path
                                d="M13.5 21v-8h2.75l.41-3H13.5V8.08c0-.87.24-1.46 1.58-1.46h1.72V3.94c-.3-.04-1.32-.13-2.51-.13-2.49 0-4.19 1.52-4.19 4.31V10H7.3v3h2.8v8h3.4Z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-border mt-12 pt-8 text-center">
            <p class="text-sm text-muted-foreground">
                © <?= date('Y') ?> Agenda Cultural. Todos os direitos reservados.
            </p>
        </div>
    </div>
</footer>
    </div><!-- /.ia-split-main -->

    <!-- Sidebar conversa IA -->
    <aside v-if="modoChatAtivo" class="ia-split-sidebar">
        <div class="ia-split-sidebar__inner flex flex-col h-full">
            <div class="flex items-center justify-between gap-3 px-4 py-4 border-b border-border/50">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <i data-lucide="bot" class="h-5 w-5 text-primary"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-display font-semibold text-foreground leading-tight">Guia Cultural</p>
                        <p class="text-[11px] text-muted-foreground">Conversando agora</p>
                    </div>
                </div>
                <button type="button" @click="fecharModoChat"
                    class="p-2 rounded-lg text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
                    aria-label="Fechar conversa">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div ref="heroChatScroll" class="flex-1 overflow-y-auto px-4 py-4 space-y-4 custom-scrollbar">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <i data-lucide="bot" class="h-4 w-4 text-primary"></i>
                    </div>
                    <div class="bg-card border border-border/50 rounded-2xl rounded-tl-none px-3.5 py-3 text-sm text-foreground leading-relaxed shadow-sm">
                        Me conte o que você procura e eu vou sugerindo eventos reais da agenda.
                    </div>
                </div>

                <div v-for="(msg, index) in chatMensagens" :key="'chat-' + index"
                    class="flex gap-3"
                    :class="{ 'flex-row-reverse': msg.role === 'user' }">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                        :class="msg.role === 'user' ? 'bg-secondary border border-border' : 'bg-primary/10 border border-primary/20'">
                        <i v-if="msg.role === 'user'" data-lucide="user" class="h-4 w-4 text-muted-foreground"></i>
                        <i v-else data-lucide="bot" class="h-4 w-4 text-primary"></i>
                    </div>
                    <div class="max-w-[85%] px-3.5 py-3 text-sm leading-relaxed shadow-sm"
                        :class="msg.role === 'user'
                            ? 'bg-primary text-primary-foreground rounded-2xl rounded-tr-none'
                            : 'bg-card border border-border/50 text-foreground rounded-2xl rounded-tl-none'">
                        <div v-html="formatarTextoChat(msg.text)"></div>
                    </div>
                </div>

                <div v-if="chatCarregando" class="flex gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <i data-lucide="bot" class="h-4 w-4 text-primary"></i>
                    </div>
                    <div class="bg-card border border-border/50 rounded-2xl rounded-tl-none px-3.5 py-3 shadow-sm">
                        <div class="flex gap-1">
                            <span class="w-1.5 h-1.5 bg-primary/40 rounded-full animate-bounce"></span>
                            <span class="w-1.5 h-1.5 bg-primary/40 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                            <span class="w-1.5 h-1.5 bg-primary/40 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 border-t border-border/50">
                <div class="flex items-center gap-2 rounded-xl border border-border/60 bg-background/80 p-1.5">
                    <input type="text" v-model="chatInput" @keyup.enter="enviarMensagemChat"
                        ref="sidebarChatInput"
                        placeholder="Continue a conversa..."
                        class="flex-1 bg-transparent border-none focus:ring-0 text-sm text-foreground placeholder:text-muted-foreground px-3 py-2.5 outline-none"
                        :disabled="chatCarregando" />
                    <button type="button" @click="enviarMensagemChat"
                        class="w-10 h-10 rounded-lg bg-primary hover:bg-primary/90 text-primary-foreground flex items-center justify-center transition-all disabled:opacity-50 shrink-0"
                        :disabled="chatCarregando || !chatInput.trim()"
                        aria-label="Enviar">
                        <i data-lucide="send" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Modal de interesses -->
    <div v-if="mostrarModalInteresses" class="fixed inset-0 z-[70] flex items-end sm:items-center justify-center p-0 sm:p-4"
        role="dialog" aria-modal="true" aria-labelledby="modal-interesses-titulo">
        <div class="absolute inset-0 bg-foreground/50 backdrop-blur-sm" @click="fecharModalInteresses"></div>
        <div
            class="relative z-10 w-full sm:max-w-xl max-h-[90vh] overflow-y-auto rounded-t-2xl sm:rounded-2xl border border-border bg-card shadow-2xl p-6 md:p-8">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mb-2">Personalização</p>
                    <h3 id="modal-interesses-titulo" class="text-2xl font-display font-bold text-foreground">
                        Quais são seus interesses?
                    </h3>
                    <p class="text-sm text-muted-foreground mt-2">
                        Escolha as categorias que mais combinam com você. Usaremos isso para sugerir até 8 eventos.
                    </p>
                </div>
                <button type="button" @click="fecharModalInteresses"
                    class="p-2 rounded-lg text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
                    aria-label="Fechar">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <button v-for="tag in tagsInteresses" :key="'interesse-' + tag" type="button"
                    @click="alternarInteresseTemp(tag)"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all border"
                    :class="interesseTempAtivo(tag)
                        ? 'bg-primary text-primary-foreground border-primary shadow-md shadow-primary/20'
                        : 'bg-secondary text-muted-foreground border-border/50 hover:bg-secondary/80'">
                    {{ tag.charAt(0).toUpperCase() + tag.slice(1) }}
                </button>
            </div>

            <p v-if="erroInteresses" class="text-sm text-primary mb-4">{{ erroInteresses }}</p>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" @click="fecharModalInteresses"
                    class="px-5 py-2.5 rounded-md text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors">
                    Agora não
                </button>
                <button type="button" @click="salvarInteresses"
                    class="bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-6 py-2.5 rounded-md transition-colors">
                    Salvar interesses
                </button>
            </div>
        </div>
    </div>

    <!-- Banner de consentimento de cookies -->
    <div v-if="mostrarBannerCookies"
        class="fixed bottom-0 left-0 right-0 z-[60] p-4 md:p-6 pointer-events-none">
        <div
            class="pointer-events-auto mx-auto max-w-3xl rounded-2xl border border-border bg-card shadow-2xl p-5 md:p-6 flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-1">
                <p class="font-heading font-semibold text-foreground mb-1">Usamos cookies</p>
                <p class="text-sm text-muted-foreground">
                    Salvamos seu consentimento e seus interesses culturais em cookies para personalizar a seção
                    “Selecionados para você”. Ao continuar, você concorda com esse uso.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 shrink-0">
                <button type="button" @click="recusarCookies"
                    class="px-4 py-2.5 rounded-md text-sm font-semibold border border-border text-muted-foreground hover:text-foreground transition-colors">
                    Recusar
                </button>
                <button type="button" @click="aceitarCookies"
                    class="bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-5 py-2.5 rounded-md text-sm transition-colors">
                    Aceitar cookies
                </button>
            </div>
        </div>
    </div>
</div>