<!--[CONTAINER-layout_ceramistas]-->
<?php
$MASTER_PAGETITLE = '2º Encontro de Ceramistas, Aprendizes e Artesãos · Arceburgo';
$MASTER_KEYWORDS = 'ceramistas, artesãos, Arceburgo, feira, cerâmica, encontro, artesanato';
$MASTER_IMAGE = rtrim(ROOT, '/') . '/images/logo.png';

$expositores = \Sistema\Expositores::listarArtesaos();
$alimentacao = \Sistema\Expositores::listarAlimentacao();
$programacao = \Sistema\Programacao::listarAgrupada();
try {
    $atracoesMusicais = \Sistema\AtracoesMusicais::listar();
} catch (\Throwable $e) {
    $atracoesMusicais = [];
}
$config = \Sistema\CeramistasConfig::get();
$MASTER_DESCRIPTION = $config['meta_description'];
$waUrl = $config['whatsapp_url'];
$img = rtrim(ROOT, '/') . '/images/ceramistas';
$frasesHero = [
    'Arte que conecta, tradição que transforma!',
    'Tradição moldada em comunidade!',
    'Arte que transforma, música que encanta!',
];
?>
<script>
window.__CERAMISTAS__ = {
    root: <?= json_encode(ROOT) ?>,
    whatsapp: <?= json_encode($waUrl) ?>,
    frasesHero: <?= json_encode($frasesHero, JSON_UNESCAPED_UNICODE) ?>,
    expositores: <?= json_encode($expositores, JSON_UNESCAPED_UNICODE) ?>,
    alimentacao: <?= json_encode($alimentacao, JSON_UNESCAPED_UNICODE) ?>,
    programacao: <?= json_encode($programacao, JSON_UNESCAPED_UNICODE) ?>,
    atracoesMusicais: <?= json_encode($atracoesMusicais, JSON_UNESCAPED_UNICODE) ?>
};
</script>

<div id="app_ceramistas" v-cloak>
    <a class="skip-link" href="#conteudo">Ir para o conteúdo</a>

    <header class="cer-header" :class="{ 'is-scrolled': scrolled, 'is-open': menuAberto }">
        <div class="cer-header__inner">
            <a class="cer-brand" href="#topo" @click="fecharMenu">
                <img src="<?= ROOT ?>images/logo.png" alt="2º Encontro de Ceramistas, Aprendizes e Artesãos" width="280" height="90">
            </a>

            <button class="cer-nav-toggle" type="button" :aria-expanded="menuAberto ? 'true' : 'false'" aria-controls="cer-nav" @click="menuAberto = !menuAberto">
                <span></span><span></span><span></span>
                <span class="sr-only">Menu</span>
            </button>

            <nav id="cer-nav" class="cer-nav" :class="{ 'is-open': menuAberto }">
                <a href="#sobre" @click="fecharMenu">Sobre</a>
                <a href="#musica" @click="fecharMenu">Música</a>
                <a href="#programacao" @click="fecharMenu">Programação</a>
                <a href="#expositores" @click="fecharMenu">Expositores</a>
                <a href="#sabores" @click="fecharMenu">Sabores</a>
                <a href="#atracoes" @click="fecharMenu">Atrações</a>
                <a href="#kids" @click="fecharMenu">Espaço Kids</a>
                <a href="#contato" @click="fecharMenu">Contato</a>
                <a class="cer-btn cer-btn--compact" href="<?= htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>" :href="whatsapp" target="_blank" rel="noopener">WhatsApp</a>
            </nav>
        </div>
    </header>

    <main id="conteudo">
        <section id="topo" class="cer-hero" aria-label="Destaque">
            <div class="cer-hero__ornament" aria-hidden="true">
                <img class="cer-hero__corner cer-hero__corner--tr" src="<?= $img ?>/ornamentos/canto-topo.svg" alt="" width="420" height="420" decoding="async">
                <img class="cer-hero__corner cer-hero__corner--bl" src="<?= $img ?>/ornamentos/canto-baixo.svg" alt="" width="420" height="420" decoding="async">
            </div>
            <div class="cer-hero__stage">
                <div class="cer-hero__brand">
                    <img class="cer-hero__logo" src="<?= ROOT ?>images/logo.png" alt="2º Encontro de Ceramistas, Aprendizes e Artesãos" width="640" height="206">
                    <h1>
                        <span class="sr-only"><?= htmlspecialchars($frasesHero[0], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="cer-hero__typewriter" aria-hidden="true">{{ fraseHero }}<span class="cer-hero__caret" :class="{ 'is-waiting': fraseHeroPausa }"></span></span>
                    </h1>
                    <p class="cer-hero__lead"><?= htmlspecialchars($config['lead'], ENT_QUOTES, 'UTF-8') ?></p>
                    <div class="cer-hero__actions">
                        <a class="cer-btn" href="<?= htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>" :href="whatsapp" target="_blank" rel="noopener">Quero Fazer Parte</a>
                        <a class="cer-hero__link" href="#musica">Música</a>
                    </div>
                </div>

                <div class="cer-hero__type">
                    <p class="cer-hero__label">Quando</p>
                    <p class="cer-hero__days" aria-label="<?= htmlspecialchars($config['quando_aria'], ENT_QUOTES, 'UTF-8') ?>">
                        <span><?= htmlspecialchars($config['dias_hero'], ENT_QUOTES, 'UTF-8') ?></span>
                    </p>
                    <p class="cer-hero__month"><?= htmlspecialchars($config['mes_ano'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="cer-hero__label cer-hero__label--space">Onde</p>
                    <p class="cer-hero__city"><?= htmlspecialchars($config['cidade'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($config['uf'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="cer-hero__place"><?= htmlspecialchars($config['local'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php if (!empty($config['local_complemento'])): ?>
                    <p class="cer-hero__place-sub"><?= htmlspecialchars($config['local_complemento'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section id="sobre" class="cer-section cer-sobre reveal">
            <div class="cer-wrap cer-sobre__grid">
                <div class="cer-sobre__copy">
                    <p class="cer-eyebrow">O Encontro</p>
                    <h2>Tradição moldada em comunidade</h2>
                    <p>O 2º Encontro de Ceramistas, Aprendizes e Artesãos celebra o artesanato de Arceburgo e região: troca de saberes, peças com história e o poder da arte de aproximar pessoas.</p>
                    <p>No entorno do Caramanchão da Praça da Matriz e no Calçadão Pedro Furlan, ceramistas, artesãos, famílias e visitantes compartilham dois dias de criação, música e sabores.</p>
                </div>
                <figure class="cer-sobre__figure">
                    <img src="<?= $img ?>/arceburgo.jpg" alt="Vitrine artesanal com peças de cerâmica e artesanato" width="900" height="700" loading="lazy">
                </figure>
            </div>
        </section>

        <section id="musica" class="cer-section cer-musica reveal">
            <div class="cer-wrap">
                <div class="cer-section__head">
                    <p class="cer-eyebrow">Shows</p>
                    <h2>Atrações musicais</h2>
                    <p>Arte que transforma, música que encanta — dois shows gratuitos no Calçadão Pedro Furlan.</p>
                    <p class="cer-musica__note"><?= htmlspecialchars($config['nota_musica'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="cer-shows" v-if="atracoesMusicais.length">
                    <article class="cer-show" v-for="show in atracoesMusicais" :key="show.id">
                        <figure class="cer-show__poster" v-if="show.cartaz">
                            <img
                                :src="show.cartaz"
                                :alt="show.cartaz_alt"
                                width="902"
                                height="899"
                                loading="lazy"
                            >
                        </figure>
                        <div class="cer-show__meta">
                            <h3>{{ show.nome }}</h3>
                            <p class="cer-show__when">{{ show.quando }}</p>
                            <p v-if="show.resumo">{{ show.resumo }}</p>
                            <div class="cer-show__actions" v-if="show.instagram_url || show.site">
                                <a v-if="show.instagram_url" class="cer-btn cer-btn--compact" :href="show.instagram_url" target="_blank" rel="noopener">
                                    <svg class="cer-show__action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <rect x="3.5" y="3.5" width="17" height="17" rx="5" fill="none" stroke="currentColor" stroke-width="1.7"/>
                                        <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.7"/>
                                        <circle cx="17.2" cy="6.8" r="1.1" fill="currentColor"/>
                                    </svg>
                                    Instagram
                                </a>
                                <a v-if="show.site" class="cer-btn cer-btn--compact cer-btn--ghost-show" :href="show.site" target="_blank" rel="noopener">
                                    <svg class="cer-show__action-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                        <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.7"/>
                                        <path d="M3.5 12h17M12 3.5c2.4 2.6 3.6 5.5 3.6 8.5s-1.2 5.9-3.6 8.5c-2.4-2.6-3.6-5.5-3.6-8.5s1.2-5.9 3.6-8.5z" fill="none" stroke="currentColor" stroke-width="1.7"/>
                                    </svg>
                                    Site
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
                <p class="cer-empty" v-else>Em breve as atrações musicais serão anunciadas.</p>
            </div>
        </section>

        <section id="programacao" class="cer-section cer-programacao reveal">
            <div class="cer-wrap">
                <div class="cer-section__head">
                    <p class="cer-eyebrow">Agenda</p>
                    <h2>Programação</h2>
                    <p>Dois dias para aprender, apreciar e celebrar — oficinas, shows e a feira em ritmo de encontro.</p>
                </div>

                <div class="cer-tabs" role="tablist" aria-label="Dias do evento" v-if="dias.length">
                    <button
                        v-for="(dia, idx) in dias"
                        :key="dia.dia_iso"
                        type="button"
                        role="tab"
                        class="cer-tabs__btn"
                        :class="{ 'is-active': diaAtivo === dia.dia_iso }"
                        :aria-selected="diaAtivo === dia.dia_iso ? 'true' : 'false'"
                        @click="diaAtivo = dia.dia_iso"
                    >
                        <strong>{{ dia.semana }}</strong>
                        <span>{{ dia.rotulo }}</span>
                    </button>
                </div>

                <ol class="cer-timeline" v-if="itensDoDia.length">
                    <li v-for="item in itensDoDia" :key="item.id" class="cer-timeline__item" :class="{ 'is-destaque': item.destaque }">
                        <div class="cer-timeline__time">
                            <span class="cer-timeline__hour">{{ item.horario }}</span>
                            <span class="cer-timeline__icon" v-html="iconeSvg(item.icone)"></span>
                        </div>
                        <div class="cer-timeline__body">
                            <h3>{{ item.titulo }}</h3>
                            <p>{{ item.descricao }}</p>
                            <span class="cer-timeline__local" v-if="item.local">{{ item.local }}</span>
                        </div>
                    </li>
                </ol>
                <p class="cer-empty" v-else>A programação será publicada em breve.</p>
            </div>
        </section>

        <section id="expositores" class="cer-section cer-expositores reveal">
            <div class="cer-wrap">
                <div class="cer-section__head">
                    <p class="cer-eyebrow">Feira</p>
                    <h2>Expositores</h2>
                    <p>Conheça quem traz a alma do encontro: cerâmica, concreto, arte e sabores feitos à mão.</p>
                </div>

                <div class="cer-expo-grid" v-if="expositores.length">
                    <a
                        v-for="exp in expositores"
                        :key="exp.id"
                        class="cer-expo"
                        :href="root + 'expositor/' + exp.slug"
                    >
                        <span class="cer-expo__media">
                            <img :src="exp.foto_destaque || exp.logo" :alt="'Foto de ' + exp.nome" loading="lazy">
                        </span>
                        <span class="cer-expo__meta">
                            <span class="cer-expo__cat">{{ exp.categoria }}</span>
                            <strong>{{ exp.nome }}</strong>
                            <span class="cer-expo__resumo">{{ exp.resumo }}</span>
                            <span class="cer-expo__cta">Ver perfil</span>
                        </span>
                    </a>
                </div>
                <p class="cer-empty" v-else>Em breve novos expositores serão anunciados.</p>
            </div>
        </section>

        <section id="sabores" class="cer-section cer-sabores reveal">
            <div class="cer-wrap">
                <div class="cer-sabores__intro">
                    <div>
                        <p class="cer-eyebrow">Gastronomia</p>
                        <h2>Alimentação &amp; cerveja artesanal</h2>
                        <p>Do forno ao chopp: sabores feitos com o mesmo espírito do encontro — tempo, mão e afeto.</p>
                    </div>
                    <div class="cer-sabores__badges" aria-hidden="true">
                        <span v-html="iconeSvg('taste')"></span>
                        <span v-html="iconeSvg('beer')"></span>
                    </div>
                </div>

                <div class="cer-sabores-grid" v-if="alimentacao.length">
                    <a
                        v-for="exp in alimentacao"
                        :key="'food-' + exp.id"
                        class="cer-sabor"
                        :href="root + 'expositor/' + exp.slug"
                    >
                        <span class="cer-sabor__media">
                            <img :src="exp.foto_destaque || exp.logo" :alt="'Foto de ' + exp.nome" loading="lazy">
                            <span class="cer-sabor__logo" v-if="exp.logo">
                                <img :src="exp.logo" :alt="'Logo ' + exp.nome">
                            </span>
                        </span>
                        <span class="cer-sabor__body">
                            <span class="cer-sabor__cat">{{ exp.categoria }}</span>
                            <strong>{{ exp.nome }}</strong>
                            <span class="cer-sabor__resumo">{{ exp.resumo }}</span>
                            <span class="cer-sabor__cta">Conhecer</span>
                        </span>
                    </a>
                </div>
                <p class="cer-empty" v-else>Em breve anunciamos os sabores do encontro.</p>
            </div>
        </section>

        <section id="atracoes" class="cer-section cer-atracoes reveal">
            <div class="cer-wrap">
                <div class="cer-section__head">
                    <p class="cer-eyebrow">Experiências</p>
                    <h2>Atrações</h2>
                    <p>Pilares que fazem do encontro um fim de semana completo.</p>
                </div>

                <ul class="cer-pillars">
                    <li class="cer-pillar">
                        <span class="cer-pillar__icon" aria-hidden="true" v-html="iconeSvg('pottery')"></span>
                        <div class="cer-pillar__copy">
                            <h3>Artesanato de Cerâmica</h3>
                            <p>Peças autorais únicas com história, cultura e identidade.</p>
                        </div>
                    </li>
                    <li class="cer-pillar">
                        <span class="cer-pillar__icon" aria-hidden="true" v-html="iconeSvg('market')"></span>
                        <div class="cer-pillar__copy">
                            <h3>Artesanato de Arceburgo e Região</h3>
                            <p>Valorização das raízes e dos talentos que nascem aqui.</p>
                        </div>
                    </li>
                    <li class="cer-pillar">
                        <span class="cer-pillar__icon" aria-hidden="true" v-html="iconeSvg('pottery')"></span>
                        <div class="cer-pillar__copy">
                            <h3>Oficina ao Vivo</h3>
                            <p>Experiência criativa para todas as idades — participe, aprenda e encante-se!</p>
                        </div>
                    </li>
                    <li class="cer-pillar">
                        <span class="cer-pillar__icon" aria-hidden="true" v-html="iconeSvg('music')"></span>
                        <div class="cer-pillar__copy">
                            <h3>Música e Sabores</h3>
                            <p>Rock vintage, o melhor da MPB, cerveja artesanal e delícias locais.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <section id="kids" class="cer-section cer-kids reveal">
            <div class="cer-wrap">
                <div class="cer-kids__grid">
                    <div class="cer-kids__copy">
                        <p class="cer-eyebrow">Famílias</p>
                        <h2>Espaço Kids</h2>
                        <p>No sábado a criançada também coloca a criatividade para brincar. Enquanto os adultos passeiam pelo encontro, conhecem os artesãos, apreciam a cerâmica e curtem a programação, as crianças terão o próprio espaço — um cantinho de infância no meio da praça.</p>
                    </div>
                    <figure>
                        <img src="<?= $img ?>/espaco-kids.jpg" alt="Crianças brincando no Espaço Kids do encontro" width="900" height="700" loading="lazy">
                    </figure>
                </div>

                <div class="cer-kids__days">
                    <article class="cer-kids-day">
                        <p class="cer-kids-day__when">Sábado · 5 de setembro</p>
                        <h3>Oficina de massinhas e pintura</h3>
                        <p class="cer-kids-day__meta">À tarde</p>
                        <p>Oficina preparada com carinho por crianças, para crianças. Um momento gostoso de experimentar cores, formas, criar e se divertir com as mãos.</p>
                    </article>
                    <article class="cer-kids-day">
                        <p class="cer-kids-day__when">Domingo · 6 de setembro</p>
                        <h3>Brinquedos na praça</h3>
                        <p class="cer-kids-day__meta">14h às 18h</p>
                        <p>A diversão continua: brinquedos na praça, num cantinho especial para os pequenos brincarem e aproveitarem a tarde.</p>
                    </article>
                </div>

                <p class="cer-kids__close">Porque o nosso encontro também é lugar de infância, criatividade e boas memórias.</p>
            </div>
        </section>

        <section id="contato" class="cer-section cer-contato reveal">
            <div class="cer-wrap cer-contato__grid">
                <div>
                    <p class="cer-eyebrow">Contato</p>
                    <h2>Venha fazer parte</h2>
                    <p>Dúvidas, inscrições ou interesse em expor? Fale conosco pelo WhatsApp.</p>
                    <a class="cer-btn" href="<?= htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>" :href="whatsapp" target="_blank" rel="noopener"><?= htmlspecialchars($config['whatsapp_rotulo'], ENT_QUOTES, 'UTF-8') ?></a>
                    <p class="cer-contato__place"><?= htmlspecialchars($config['endereco'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="cer-map">
                    <iframe
                        title="<?= htmlspecialchars($config['mapa_titulo'], ENT_QUOTES, 'UTF-8') ?>"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="<?= htmlspecialchars($config['mapa_url'], ENT_QUOTES, 'UTF-8') ?>"
                    ></iframe>
                </div>
            </div>
        </section>
    </main>

    <footer class="cer-footer">
        <div class="cer-wrap cer-footer__inner">
            <div class="cer-footer__brand">
                <img src="<?= ROOT ?>images/logo.png" alt="2º Encontro de Ceramistas" width="220" height="72">
                <p>Arte que conecta, tradição que transforma!</p>
            </div>
            <p><?= htmlspecialchars($config['rodape'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </footer>

</div>
