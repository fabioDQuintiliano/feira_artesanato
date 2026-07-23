<!--[CONTAINER-layout_react]-->
<?php
use Sistema\Eventos;

$slug = $params[1] ?? ''; // $params vem de controle-includes.php -> page.php
$evento = Eventos::getEventoByTxtId($slug);

if (!$evento) {
    echo "<script>window.location.href = '" . ROOT . "';</script>";
    exit;
}

$MASTER_PAGETITLE = $evento['titulo'] . " - Agenda Cultural";
$MASTER_DESCRIPTION = strip_tags($evento['descricao']);
$MASTER_IMAGE = $evento['imagem'];
?>

<div id="app_evento" v-cloak>
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
                    Voltar para Eventos
                </a>
            </div>
        </div>
    </header>

    <main class="pt-24 pb-20">
        <div class="container px-4">
            <div class="max-w-5xl mx-auto">
                <!-- Hero Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start mb-16">
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden border border-border/50 shadow-2xl">
                        <img :src="imagemEvento(evento)" :alt="evento.titulo"
                            @error="usarPlaceholder($event)"
                            class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent lg:hidden">
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <?php foreach ($evento['tags'] as $tag): ?>
                                <a href="<?= ROOT ?>home_v3?tag=<?= urlencode($tag) ?>"
                                    class="px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-xs font-bold text-primary uppercase tracking-wider hover:bg-primary hover:text-primary-foreground transition-all">
                                    <?= $tag ?>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <h1
                            class="text-4xl md:text-5xl lg:text-6xl font-display font-bold text-foreground mb-8 leading-tight">
                            <?= $evento['titulo'] ?>
                        </h1>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-secondary/50 border border-border/50">
                                <div
                                    class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary shrink-0">
                                    <i data-lucide="calendar" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider mb-1">
                                        Data</p>
                                    <p class="text-lg font-semibold text-foreground">
                                        <?= $evento['data'] ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-secondary/50 border border-border/50">
                                <div
                                    class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary shrink-0">
                                    <i data-lucide="clock" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider mb-1">
                                        Horário</p>
                                    <p class="text-lg font-semibold text-foreground">
                                        <?= $evento['hora'] ?>
                                    </p>
                                </div>
                            </div>
                            <?php if ($evento['local']): ?>
                                <div
                                    class="sm:col-span-2 flex items-start gap-4 p-4 rounded-xl bg-secondary/50 border border-border/50">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary shrink-0">
                                        <i data-lucide="map-pin" class="h-5 w-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider mb-1">
                                            Local</p>
                                        <p class="text-lg font-semibold text-foreground">
                                            <?= $evento['local'] ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="flex items-start gap-4 p-4 rounded-xl bg-secondary/50 border border-border/50">
                                <div
                                    class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary shrink-0">
                                    <i data-lucide="dollar-sign" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider mb-1">
                                        Valor</p>
                                    <p class="text-lg font-semibold text-foreground">
                                        <?= $evento['valor'] ?: 'Sob consulta' ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-secondary/50 border border-border/50">
                                <div
                                    class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary shrink-0">
                                    <i data-lucide="users" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-muted-foreground font-medium uppercase tracking-wider mb-1">
                                        Classificação</p>
                                    <p class="text-lg font-semibold text-foreground">
                                        <?= $evento['faixa_etaria'] ?: 'Não informada' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <?php if ($evento['link']): ?>
                            <a href="<?= $evento['link'] ?>" target="_blank"
                                class="w-full sm:w-max bg-primary hover:bg-primary/90 text-primary-foreground font-bold px-10 py-4 rounded-xl flex items-center justify-center gap-3 transition-all shadow-lg shadow-primary/20 group">
                                Garantir Ingresso
                                <i data-lucide="external-link"
                                    class="h-5 w-5 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-display font-bold text-foreground mb-6 flex items-center gap-2">
                            <i data-lucide="info" class="h-6 w-6 text-primary"></i>
                            Sobre o Evento
                        </h2>
                        <div class="prose prose-invert max-w-none text-muted-foreground leading-relaxed text-lg">
                            <?= $evento['descricao'] ?>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="sticky top-24 p-8 rounded-2xl bg-card border border-border/50 glass-effect">
                            <h3 class="text-xl font-display font-bold text-foreground mb-6">Compartilhar</h3>
                            <div class="flex gap-4">
                                <button @click="share('whatsapp')"
                                    class="w-12 h-12 rounded-lg bg-secondary hover:bg-primary/20 hover:text-primary flex items-center justify-center transition-all border border-border/50">
                                    <i data-lucide="message-circle" class="h-6 w-6"></i>
                                </button>
                                <button @click="share('x')" aria-label="Compartilhar no X"
                                    class="w-12 h-12 rounded-lg bg-secondary hover:bg-primary/20 hover:text-primary flex items-center justify-center transition-all border border-border/50">
                                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 fill-current">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.657l-5.214-6.817-5.967 6.817H1.68l7.73-8.835L1.254 2.25h6.826l4.713 6.231 5.451-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z" />
                                    </svg>
                                </button>
                                <button @click="share('copy')"
                                    class="w-12 h-12 rounded-lg bg-secondary hover:bg-primary/20 hover:text-primary flex items-center justify-center transition-all border border-border/50">
                                    <i data-lucide="copy" class="h-6 w-6"></i>
                                </button>
                            </div>

                            <hr class="my-8 border-border/50" />

                            <div class="space-y-4">
                                <p class="text-sm text-muted-foreground flex items-center gap-2">
                                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                                    Evento Verificado
                                </p>
                                <p class="text-sm text-muted-foreground flex items-center gap-2">
                                    <i data-lucide="info" class="h-4 w-4 text-primary"></i>
                                    Sujeito a lotação
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-secondary border-t border-border py-12">
        <div class="container px-4 text-center">
            <p class="text-sm text-muted-foreground">
                ©
                <?= date('Y') ?> Agenda Cultural. Todos os direitos reservados.
            </p>
        </div>
    </footer>

    <transition enter-active-class="transition duration-300 ease-out"
        enter-class="translate-y-3 opacity-0" enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-class="translate-y-0 opacity-100" leave-to-class="translate-y-3 opacity-0">
        <div v-if="toastVisivel"
            class="fixed bottom-6 left-1/2 z-[100] -translate-x-1/2 flex items-center gap-3 rounded-xl bg-foreground px-5 py-3 text-background shadow-2xl"
            role="status" aria-live="polite">
            <i data-lucide="check-circle" class="h-5 w-5 text-primary"></i>
            <span class="font-semibold">Link copiado!</span>
        </div>
    </transition>
</div>