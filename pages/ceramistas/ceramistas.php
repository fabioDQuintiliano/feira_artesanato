<!--[CONTAINER-layout_ceramistas]-->
<?php
$MASTER_PAGETITLE = '2º Encontro de Ceramistas, Aprendizes e Artesãos · Arceburgo';
$MASTER_DESCRIPTION = 'Arte que conecta, tradição que transforma! 5 e 6 de Setembro na Praça da Matriz, Arceburgo - MG. Programação, expositores, oficinas, música e espaço kids.';
$MASTER_KEYWORDS = 'ceramistas, artesãos, Arceburgo, feira, cerâmica, encontro, artesanato';
$MASTER_IMAGE = rtrim(ROOT, '/') . '/images/logo.png';

$expositores = \Sistema\Expositores::listarArtesaos();
$alimentacao = \Sistema\Expositores::listarAlimentacao();
$programacao = \Sistema\Programacao::listarAgrupada();
$waNumero = '5535997010196';
$waUrl = 'https://wa.me/' . $waNumero . '?text=' . rawurlencode('Olá! Quero saber mais sobre o 2º Encontro de Ceramistas em Arceburgo.');
$img = rtrim(ROOT, '/') . '/images/ceramistas';
?>
<script>
window.__CERAMISTAS__ = {
    root: <?= json_encode(ROOT) ?>,
    whatsapp: <?= json_encode($waUrl) ?>,
    expositores: <?= json_encode($expositores, JSON_UNESCAPED_UNICODE) ?>,
    alimentacao: <?= json_encode($alimentacao, JSON_UNESCAPED_UNICODE) ?>,
    programacao: <?= json_encode($programacao, JSON_UNESCAPED_UNICODE) ?>
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
                <a href="#programacao" @click="fecharMenu">Programação</a>
                <a href="#expositores" @click="fecharMenu">Expositores</a>
                <a href="#sabores" @click="fecharMenu">Sabores</a>
                <a href="#atracoes" @click="fecharMenu">Atrações</a>
                <a href="#kids" @click="fecharMenu">Espaço Kids</a>
                <a href="#contato" @click="fecharMenu">Contato</a>
                <a class="cer-btn cer-btn--compact" :href="whatsapp" target="_blank" rel="noopener">WhatsApp</a>
            </nav>
        </div>
    </header>

    <main id="conteudo">
        <section id="topo" class="cer-hero" aria-label="Destaque">
            <div class="cer-hero__media" aria-hidden="true">
                <img src="<?= $img ?>/hero-argila.jpg" alt="" width="1600" height="1067">
                <div class="cer-hero__veil"></div>
            </div>
            <div class="cer-hero__content reveal is-visible">
                <img class="cer-hero__logo" src="<?= ROOT ?>images/logo.png" alt="2º Encontro de Ceramistas, Aprendizes e Artesãos" width="720" height="232">
                <h1>Arte que conecta, tradição que transforma!</h1>
                <p class="cer-hero__lead">Venha viver momentos que transformam! O encontro está de volta em Arceburgo, MG.</p>
                <div class="cer-hero__meta">
                    <div class="cer-hero__when">
                        <span class="cer-hero__meta-label">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3v2M17 3v2M4 9h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                            Quando
                        </span>
                        <strong>5 e 6 de Setembro</strong>
                        <span class="cer-hero__meta-note">Sábado e domingo</span>
                    </div>
                    <div class="cer-hero__where">
                        <span class="cer-hero__meta-label">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="10" r="2.4" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                            Onde
                        </span>
                        <strong>Praça da Matriz</strong>
                        <span class="cer-hero__meta-note">Calçadão Pedro Furlan · Arceburgo - MG</span>
                    </div>
                </div>
                <div class="cer-hero__actions">
                    <a class="cer-btn" :href="whatsapp" target="_blank" rel="noopener">Quero Fazer Parte</a>
                    <a class="cer-btn cer-btn--ghost" href="#programacao">Ver programação</a>
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
                    <img src="<?= $img ?>/sobre-atelier.jpg" alt="Vitrine artesanal com peças de cerâmica e artesanato" width="900" height="700" loading="lazy">
                </figure>
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
                    <button
                        v-for="exp in expositores"
                        :key="exp.id"
                        type="button"
                        class="cer-expo"
                        @click="abrirExpositor(exp)"
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
                    </button>
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
                    <button
                        v-for="exp in alimentacao"
                        :key="'food-' + exp.id"
                        type="button"
                        class="cer-sabor"
                        @click="abrirExpositor(exp)"
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
                    </button>
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

                <div class="cer-pillars">
                    <article class="cer-pillar">
                        <div class="cer-pillar__icon" v-html="iconeSvg('pottery')"></div>
                        <h3>Artesanato de Cerâmica</h3>
                        <p>Peças autorais únicas com história, cultura e identidade.</p>
                        <img src="<?= $img ?>/atracoes-artesanato.jpg" alt="Artesanato regional" loading="lazy">
                    </article>
                    <article class="cer-pillar">
                        <div class="cer-pillar__icon" v-html="iconeSvg('market')"></div>
                        <h3>Artesanato de Arceburgo e Região</h3>
                        <p>Valorização das raízes e dos talentos que nascem aqui.</p>
                        <img src="<?= $img ?>/textura-argila.jpg" alt="Textura de argila e ofício" loading="lazy">
                    </article>
                    <article class="cer-pillar">
                        <div class="cer-pillar__icon" v-html="iconeSvg('pottery')"></div>
                        <h3>Oficina ao Vivo</h3>
                        <p>Experiência criativa para todas as idades — participe, aprenda e encante-se!</p>
                        <img src="<?= $img ?>/oficina-maos.jpg" alt="Mãos trabalhando em ofício artesanal" loading="lazy">
                    </article>
                    <article class="cer-pillar">
                        <div class="cer-pillar__icon" v-html="iconeSvg('music')"></div>
                        <h3>Música e Sabores</h3>
                        <p>Rock vintage, o melhor da MPB, cerveja artesanal e delícias locais.</p>
                        <img src="<?= $img ?>/sabores.jpg" alt="Ambiente de sabores e encontro" loading="lazy">
                    </article>
                </div>
            </div>
        </section>

        <section id="musica" class="cer-section cer-musica reveal">
            <div class="cer-wrap cer-musica__grid">
                <figure class="cer-musica__figure">
                    <img src="<?= $img ?>/musica-ambiente.jpg" alt="Atmosfera acolhedora do encontro" loading="lazy">
                </figure>
                <div>
                    <p class="cer-eyebrow">Trilha sonora</p>
                    <h2>Boa música, boa conversa e muita arte</h2>
                    <p>No 2º Encontro de Ceramistas, a trilha sonora também será especial. Vamos curtir um rock and roll vintage, daqueles clássicos leves, agradáveis e cheios de boas lembranças.</p>
                    <p>Teremos também o melhor da Música Popular Brasileira: canções que acolhem, emocionam, aquecem o coração e celebram a riqueza da nossa cultura.</p>
                </div>
            </div>
        </section>

        <section id="kids" class="cer-section cer-kids reveal">
            <div class="cer-wrap cer-kids__grid">
                <div>
                    <p class="cer-eyebrow">Famílias</p>
                    <h2>Espaço Kids</h2>
                    <p>Diversão garantida para os pequenos! Enquanto você aprecia a arte, o artesanato, a música e os sabores da nossa terra, as crianças terão um espaço especial cheio de alegria e segurança.</p>
                    <p>Pula-pula com muita energia e brincadeiras incríveis preparadas com muito amor para a sua família.</p>
                </div>
                <figure>
                    <img src="<?= $img ?>/espaco-kids.jpg" alt="Ambiente alegre para famílias" loading="lazy">
                </figure>
            </div>
        </section>

        <section id="contato" class="cer-section cer-contato reveal">
            <div class="cer-wrap cer-contato__grid">
                <div>
                    <p class="cer-eyebrow">Contato</p>
                    <h2>Venha fazer parte</h2>
                    <p>Dúvidas, inscrições ou interesse em expor? Fale conosco pelo WhatsApp.</p>
                    <a class="cer-btn" :href="whatsapp" target="_blank" rel="noopener">(35) 99701-0196</a>
                    <p class="cer-contato__place">Entorno do Caramanchão da Praça da Matriz e Calçadão Pedro Furlan · Arceburgo - MG</p>
                </div>
                <div class="cer-map">
                    <iframe
                        title="Mapa da Praça da Matriz em Arceburgo"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://maps.google.com/maps?q=Pra%C3%A7a%20da%20Matriz%2C%20Arceburgo%20-%20MG&t=&z=16&ie=UTF8&iwloc=&output=embed"
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
            <p>Arceburgo · 5 e 6 de Setembro</p>
        </div>
    </footer>

    <div class="cer-modal" v-if="expositorAtivo" @click.self="fecharExpositor">
        <div class="cer-modal__dialog" role="dialog" aria-modal="true" :aria-labelledby="'expo-title-' + expositorAtivo.id">
            <button class="cer-modal__close" type="button" @click="fecharExpositor" aria-label="Fechar">×</button>
            <div class="cer-modal__hero">
                <img :src="fotoModal" :alt="expositorAtivo.nome">
            </div>
            <div class="cer-modal__body">
                <p class="cer-eyebrow">{{ expositorAtivo.categoria }}</p>
                <h2 :id="'expo-title-' + expositorAtivo.id">{{ expositorAtivo.nome }}</h2>
                <div class="cer-modal__desc" v-html="descricaoHtml(expositorAtivo.descricao)"></div>
                <div class="cer-modal__gallery" v-if="expositorAtivo.fotos && expositorAtivo.fotos.length">
                    <button
                        v-for="(foto, i) in expositorAtivo.fotos"
                        :key="foto.id"
                        type="button"
                        :class="{ 'is-active': fotoModalIndex === i }"
                        @click="fotoModalIndex = i"
                    >
                        <img :src="foto.url" :alt="foto.legenda || expositorAtivo.nome" loading="lazy">
                    </button>
                </div>
                <div class="cer-modal__links">
                    <a v-if="expositorAtivo.instagram_url" :href="expositorAtivo.instagram_url" target="_blank" rel="noopener">Instagram</a>
                    <a v-if="expositorAtivo.whatsapp_url" :href="expositorAtivo.whatsapp_url" target="_blank" rel="noopener">WhatsApp</a>
                </div>
            </div>
        </div>
    </div>
</div>
