<!--[CONTAINER-layout_react]-->
<?php
$ehBares = isset($_GET['tipo']) && $_GET['tipo'] === 'bares';
$tagParam = $_GET['tag'] ?? null;
$filtroTags = [];
if (is_string($tagParam) && $tagParam !== '') {
    $filtroTags = array_values(array_filter(array_map('trim', explode(',', $tagParam))));
}

$tituloPagina = $ehBares ? 'Bares e Restaurantes' : 'Todos os Eventos';
if (!$ehBares && count($filtroTags) === 1) {
    $tituloPagina = 'Eventos em "' . ucfirst($filtroTags[0]) . '"';
} elseif (!$ehBares && count($filtroTags) > 1) {
    $nomes = array_map(function ($tag) {
        return ucfirst($tag);
    }, $filtroTags);
    $tituloPagina = 'Eventos em "' . implode('", "', $nomes) . '"';
}

$MASTER_PAGETITLE = $tituloPagina . ' - Agenda Cultural';
?>

<div id="app_eventos" v-cloak>
    <header class="fixed top-0 left-0 right-0 z-50 bg-background/95 backdrop-blur-md border-b border-border/50">
        <div class="container px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                <a href="<?= ROOT ?>home_v3" class="flex items-center group" aria-label="Agenda Cultural">
                    <img src="<?= ROOT ?>images/logo_horizontal.png" alt="Agenda Cultural Ribeirão Preto"
                        class="h-14 md:h-[72px] w-auto object-contain" />
                </a>
                <a href="<?= ROOT ?>home_v3"
                    class="text-sm font-medium text-muted-foreground hover:text-primary transition-colors flex items-center gap-2">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Voltar para a inicial
                </a>
            </div>
        </div>
    </header>

    <main class="pt-28 pb-20 min-h-screen bg-background">
        <div class="container px-4">
            <div class="mb-12">
                <p class="text-xs font-bold text-primary uppercase tracking-[0.2em] mb-2">
                    <?= $ehBares ? 'Gastronomia & noite' : 'Agenda cultural' ?>
                </p>
                <h1 class="text-3xl md:text-5xl font-display font-bold text-foreground mb-4">
                    {{ tituloPagina }}
                </h1>
                <p class="text-muted-foreground max-w-2xl">
                    <?= $ehBares
                        ? 'Confira a programação completa dos bares e restaurantes.'
                        : 'Explore a listagem completa dos próximos eventos.' ?>
                </p>
                <p v-if="dataSelecionada" class="text-sm text-primary font-medium mt-3">
                    Filtrando por data: {{ formatarDataBR(dataSelecionada) }}
                </p>
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
                <p class="text-lg text-muted-foreground">Nenhum evento encontrado.</p>
                <a href="<?= ROOT ?>home_v3" class="text-primary hover:underline mt-2 inline-block">Voltar para a inicial</a>
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
                <button @click="carregarMais(true)" class="text-primary hover:underline mt-2 inline-block">
                    Tentar novamente
                </button>
            </div>

            <div v-else-if="eventos.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <a v-for="ev in eventos" :key="ev.id" :href="'<?= ROOT ?>evento/' + ev.txtid"
                    class="group bg-card rounded-xl border border-border/50 overflow-hidden hover:border-primary/50 transition-all duration-300 flex flex-col h-full hover:shadow-xl hover:shadow-primary/5">
                    <div class="relative aspect-video overflow-hidden">
                        <img :src="imagemEvento(ev)" :alt="ev.titulo"
                            @error="usarPlaceholder($event, ev)"
                            class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
                        <div v-if="ehBares" class="absolute top-3 left-3">
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
                        <h2
                            class="text-lg font-heading font-bold text-foreground mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                            {{ ev.titulo }}
                        </h2>
                        <div class="text-sm text-muted-foreground line-clamp-3 mb-4 flex-1" v-html="ev.descricao"></div>
                        <div class="mt-auto inline-flex items-center gap-2 text-sm font-bold text-foreground group/link">
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

            <div v-if="fimEventos && eventos.length > 0" class="text-center py-12 text-muted-foreground italic">
                Você chegou ao fim da listagem.
            </div>
        </div>
    </main>
</div>
