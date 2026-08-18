<?php
global $PERFIL_PERMISSOES;

if (empty($PERFIL_PERMISSOES) && function_exists('perfilUser')) {
	$PERFIL_PERMISSOES = perfilUser();
}

$menuAdmin = new \Sistema\Admin\Menu();
$secoesMenu = $menuAdmin->getMenu();

$textosAtalho = array(
	'expositores' => 'Artesãos, alimentação e fotos do encontro.',
	'programacao' => 'Oficinas, palco e horários dos dois dias.',
	'atracoes_musicais' => 'Shows e artistas da programação.',
	'configuracao' => 'Datas, local, WhatsApp e mapa.',
	'configuracoes' => 'Ajustes gerais do sistema.',
);

$ordemAtalho = array('expositores', 'programacao', 'atracoes_musicais', 'configuracao');
$semNovo = array('configuracao' => true, 'configuracoes' => true);
$ocultarAtalho = array('pessoas' => true, 'perfis' => true);

$atalhos = array();
foreach ($secoesMenu as $secao) {
	$subs = isset($secao->subitens) ? $secao->subitens : array();
	foreach ($subs as $sub) {
		$link = isset($sub['link']) ? (string) $sub['link'] : '';
		if ($link === '' || !empty($ocultarAtalho[$link])) {
			continue;
		}
		$permAdd = array($link, removeCaracteres(isset($sub['item']) ? $sub['item'] : ''));
		$podeAdd = false;
		if (!empty($PERFIL_PERMISSOES['add']) && is_array($PERFIL_PERMISSOES['add'])) {
			foreach ($permAdd as $chave) {
				if ($chave !== '' && in_array($chave, $PERFIL_PERMISSOES['add'], true)) {
					$podeAdd = true;
					break;
				}
			}
		}
		$atalhos[$link] = array(
			'nome' => isset($sub['item']) ? $sub['item'] : $link,
			'form' => isset($sub['form']) ? $sub['form'] : '',
			'icone' => !empty($sub['icon']) ? $sub['icon'] : $menuAdmin->getIcon($link),
			'texto' => isset($textosAtalho[$link]) ? $textosAtalho[$link] : 'Abrir o cadastro no painel.',
			'novo' => $podeAdd && empty($semNovo[$link]),
			'secao' => isset($secao->item->nome) ? $secao->item->nome : '',
		);
	}
}

$principais = array();
foreach ($ordemAtalho as $link) {
	if (isset($atalhos[$link])) {
		$principais[$link] = $atalhos[$link];
		unset($atalhos[$link]);
	}
}
$outros = $atalhos;
?>

<section class="adm-home">
	<header class="adm-home__intro">
		<p class="adm-home__kicker">Painel</p>
		<h2 class="adm-home__title">Atalhos dos cadastros</h2>
		<p class="adm-home__lead">Abra os módulos principais do encontro sem passar pelo menu.</p>
	</header>

	<?php if (empty($principais) && empty($outros)): ?>
		<p class="adm-home__vazio">Nenhum cadastro disponível para o seu perfil.</p>
	<?php else: ?>
		<div class="adm-home__grid">
			<?php foreach ($principais as $card): ?>
				<?php $href = ROOT.'adm-home?item='.rawurlencode((string) $card['form']); ?>
				<article class="adm-home-card">
					<span class="adm-home-card__icon" aria-hidden="true"><i class="<?php echo htmlspecialchars($card['icone'], ENT_QUOTES, 'UTF-8'); ?>"></i></span>
					<h3><?php echo htmlspecialchars($card['nome'], ENT_QUOTES, 'UTF-8'); ?></h3>
					<p><?php echo htmlspecialchars($card['texto'], ENT_QUOTES, 'UTF-8'); ?></p>
					<div class="adm-home-card__acoes">
						<a class="adm-home-card__btn" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">Abrir</a>
						<?php if ($card['novo']): ?>
							<a class="adm-home-card__btn adm-home-card__btn--ghost" href="<?php echo htmlspecialchars($href.'&new=1', ENT_QUOTES, 'UTF-8'); ?>">Adicionar</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if (!empty($outros)): ?>
			<h3 class="adm-home__sub">Outros cadastros</h3>
			<div class="adm-home__grid adm-home__grid--compact">
				<?php foreach ($outros as $card): ?>
					<?php $href = ROOT.'adm-home?item='.rawurlencode((string) $card['form']); ?>
					<a class="adm-home-mini" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
						<i class="<?php echo htmlspecialchars($card['icone'], ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
						<span><?php echo htmlspecialchars($card['nome'], ENT_QUOTES, 'UTF-8'); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<p class="adm-home__site">
		<a href="<?php echo ROOT; ?>ceramistas" target="_blank" rel="noopener">Ver o site do encontro</a>
	</p>
</section>
