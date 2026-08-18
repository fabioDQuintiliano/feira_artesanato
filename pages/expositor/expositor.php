<!--[CONTAINER-layout_ceramistas]-->
<?php
$slug = isset($url[1]) ? (string) $url[1] : '';
$expositor = \Sistema\Expositores::getPorSlug($slug);
$config = \Sistema\CeramistasConfig::get();
$waUrl = $config['whatsapp_url'];

if (!$expositor) {
	http_response_code(404);
	$MASTER_PAGETITLE = 'Expositor não encontrado · 2º Encontro de Ceramistas';
	$MASTER_DESCRIPTION = 'Este expositor não foi encontrado ou não está mais disponível.';
	$MASTER_IMAGE = rtrim(ROOT, '/') . '/images/logo.png';
} else {
	$voltarHash = ($expositor['grupo'] ?? '') === \Sistema\Expositores::GRUPO_ALIMENTACAO
		? '#sabores'
		: '#expositores';
	$MASTER_PAGETITLE = $expositor['nome'] . ' · 2º Encontro de Ceramistas';
	$MASTER_DESCRIPTION = $expositor['resumo']
		?: ('Conheça ' . $expositor['nome'] . ' no 2º Encontro de Ceramistas, Aprendizes e Artesãos.');
	$MASTER_KEYWORDS = $expositor['nome'] . ', expositor, ceramistas, Arceburgo, ' . ($expositor['categoria'] ?: 'artesanato');
	$MASTER_IMAGE = $expositor['foto_destaque'] ?: (rtrim(ROOT, '/') . '/images/logo.png');
}

?>
<?php if (!$expositor): ?>
<div id="app_expositor" class="cer-page">
	<header class="cer-header is-scrolled">
		<div class="cer-header__inner">
			<a class="cer-brand" href="<?= ROOT ?>ceramistas">
				<img src="<?= ROOT ?>images/logo.png" alt="2º Encontro de Ceramistas, Aprendizes e Artesãos" width="280" height="90">
			</a>
			<a class="cer-btn cer-btn--compact" href="<?= ROOT ?>ceramistas">Voltar ao encontro</a>
		</div>
	</header>
	<main class="cer-profile cer-profile--empty">
		<div class="cer-wrap">
			<p class="cer-eyebrow">404</p>
			<h1>Expositor não encontrado</h1>
			<p>Este perfil não existe ou não está mais disponível.</p>
			<a class="cer-btn" href="<?= ROOT ?>ceramistas">Voltar ao encontro</a>
		</div>
	</main>
</div>
<?php else: ?>
<script>
window.__EXPOSITOR__ = <?= json_encode([
	'root' => ROOT,
	'whatsapp' => $waUrl,
	'voltar' => ROOT . 'ceramistas' . $voltarHash,
	'expositor' => $expositor,
], JSON_UNESCAPED_UNICODE) ?>;
</script>
<div id="app_expositor" class="cer-page" v-cloak>
	<header class="cer-header" :class="{ 'is-scrolled': scrolled, 'is-open': menuAberto }">
		<div class="cer-header__inner">
			<a class="cer-brand" href="<?= ROOT ?>ceramistas">
				<img src="<?= ROOT ?>images/logo.png" alt="2º Encontro de Ceramistas, Aprendizes e Artesãos" width="280" height="90">
			</a>
			<button class="cer-nav-toggle" type="button" :aria-expanded="menuAberto ? 'true' : 'false'" aria-controls="cer-nav-exp" @click="menuAberto = !menuAberto">
				<span></span><span></span><span></span>
				<span class="sr-only">Menu</span>
			</button>
			<nav id="cer-nav-exp" class="cer-nav" :class="{ 'is-open': menuAberto }">
				<a :href="voltar" @click="fecharMenu">Voltar</a>
				<a href="<?= ROOT ?>ceramistas#musica" @click="fecharMenu">Música</a>
				<a href="<?= ROOT ?>ceramistas#programacao" @click="fecharMenu">Programação</a>
				<a href="<?= ROOT ?>ceramistas#contato" @click="fecharMenu">Contato</a>
				<a class="cer-btn cer-btn--compact" href="<?= htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>" :href="whatsapp" target="_blank" rel="noopener">WhatsApp</a>
			</nav>
		</div>
	</header>

	<main class="cer-profile">
		<div class="cer-wrap cer-profile__layout">
			<div class="cer-profile__visual">
				<figure class="cer-profile__hero">
					<img :src="fotoAtiva" :alt="expositor.nome">
				</figure>
				<div class="cer-profile__thumbs" v-if="expositor.fotos && expositor.fotos.length > 1">
					<button
						v-for="(foto, i) in expositor.fotos"
						:key="foto.id"
						type="button"
						class="cer-profile__thumb"
						:class="{ 'is-active': fotoIndex === i }"
						@click="fotoIndex = i"
					>
						<img :src="foto.url" :alt="foto.legenda || expositor.nome" loading="lazy">
					</button>
				</div>
			</div>

			<article class="cer-profile__body">
				<p class="cer-eyebrow">{{ expositor.categoria || (expositor.grupo === 'alimentacao' ? 'Alimentação' : 'Expositor') }}</p>
				<h1>{{ expositor.nome }}</h1>
				<p class="cer-profile__resumo" v-if="expositor.resumo">{{ expositor.resumo }}</p>
				<div class="cer-profile__desc" v-html="descricaoHtml"></div>
				<div class="cer-profile__links">
					<a v-if="expositor.instagram_url" class="cer-btn" :href="expositor.instagram_url" target="_blank" rel="noopener">Instagram</a>
					<a v-if="expositor.whatsapp_url" class="cer-btn cer-btn--ghost" :href="expositor.whatsapp_url" target="_blank" rel="noopener">WhatsApp</a>
					<a class="cer-profile__back" :href="voltar">← Voltar ao encontro</a>
				</div>
			</article>
		</div>
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
<?php endif; ?>
