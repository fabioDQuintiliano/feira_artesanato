<?php
/**
 * Importa expositores de tmp/pt2 (descrição, instagram, whatsapp e fotos).
 * Uso: docker compose exec -u www-data app php migrate_ceramistas_pt2.php
 */
require_once __DIR__ . '/front_includes.php';

function ceramistas_txtid($seed)
{
	return substr(md5($seed . microtime(true) . mt_rand()), 0, 12);
}

function ceramistas_pt2_parse($texto)
{
	$texto = str_replace("\r\n", "\n", (string) $texto);
	$nome = '';
	if (preg_match('/^Nome:\s*(.+)$/miu', $texto, $m)) {
		$nome = trim($m[1], " \t.。");
	}

	$instagram = '';
	if (preg_match('/@([A-Za-z0-9._]+)/u', $texto, $m)) {
		$instagram = strtolower(rtrim($m[1], '.'));
	}

	$whatsapp = null;
	if (preg_match('/whatsapp\s*[:\-]?\s*([\d\s\-\(\)]+)/iu', $texto, $m)) {
		$digitos = preg_replace('/\D+/', '', $m[1]);
		$whatsapp = $digitos !== '' ? $digitos : null;
	}

	$corpo = preg_replace('/^Nome:\s*.+$/miu', '', $texto);
	$corpo = preg_replace('/^@\S+$/mu', '', $corpo);
	$corpo = preg_replace('/^whatsapp\s*[:\-]?\s*[\d\s\-\(\)]+$/miu', '', $corpo);
	$corpo = trim(preg_replace("/\n{3,}/", "\n\n", $corpo));

	$resumo = trim(preg_split("/\n\s*\n/u", $corpo)[0]);
	$resumo = preg_replace('/\s+/u', ' ', $resumo);
	if (function_exists('mb_substr')) {
		if (mb_strlen($resumo, 'UTF-8') > 280) {
			$resumo = rtrim(mb_substr($resumo, 0, 277, 'UTF-8')).'...';
		}
	} elseif (strlen($resumo) > 280) {
		$resumo = rtrim(substr($resumo, 0, 277)).'...';
	}

	return array(
		'nome' => $nome,
		'instagram' => $instagram !== '' ? $instagram : null,
		'whatsapp' => $whatsapp,
		'descricao' => $corpo !== '' ? $corpo : ' ',
		'resumo' => $resumo !== '' ? $resumo : $nome,
	);
}

function ceramistas_pt2_copiar_fotos($origem, $slug)
{
	$destRel = 'expositores/'.$slug;
	$destAbs = dirname(__FILE__).'/images/upload/'.$destRel;
	if (!is_dir($destAbs) && !mkdir($destAbs, 0775, true) && !is_dir($destAbs)) {
		throw new Exception('Não foi possível criar '.$destAbs);
	}

	$itens = scandir($origem);
	$imagens = array();
	$logoSrc = null;
	foreach ($itens as $arq) {
		if ($arq[0] === '.') {
			continue;
		}
		$ext = strtolower(pathinfo($arq, PATHINFO_EXTENSION));
		if (!in_array($ext, array('jpg', 'jpeg', 'png', 'webp'), true)) {
			continue;
		}
		$caminho = $origem.DIRECTORY_SEPARATOR.$arq;
		if (strtolower($arq) === 'logo.jpeg' || strtolower($arq) === 'logo.jpg') {
			$logoSrc = $caminho;
			continue;
		}
		$imagens[] = $caminho;
	}
	natcasesort($imagens);
	$imagens = array_values($imagens);

	$logoRel = null;
	if ($logoSrc) {
		$logoRel = $destRel.'/logo.jpeg';
		if (!copy($logoSrc, $destAbs.'/logo.jpeg')) {
			throw new Exception('Falha ao copiar logo de '.$slug);
		}
	}

	$fotos = array();
	$n = 1;
	foreach ($imagens as $src) {
		$nome = sprintf('foto_%02d.jpeg', $n);
		if (!copy($src, $destAbs.'/'.$nome)) {
			throw new Exception('Falha ao copiar foto '.$src);
		}
		$fotos[] = array(
			'arquivo' => $destRel.'/'.$nome,
			'legenda' => $n === 1 ? 'Destaque' : 'Peça',
			'destaque' => $n === 1 ? 1 : 0,
		);
		$n++;
	}

	if (!$logoRel && !empty($fotos)) {
		$logoRel = $fotos[0]['arquivo'];
	}

	$destaque = !empty($fotos) ? $fotos[0]['arquivo'] : $logoRel;
	return array($logoRel, $destaque, $fotos);
}

function ceramistas_pt2_inserir(array $exp)
{
	$existe = DAO::expositores()->_slug($exp['slug'])->_loadAll('id ASC LIMIT 1');
	if ($existe && $existe->size()) {
		echo "  - já existe: {$exp['nome']} ({$exp['slug']})\n";
		return (int) $existe->id;
	}

	$dao = DAO::expositores();
	$dao->txtid = ceramistas_txtid($exp['slug']);
	$dao->nome = $exp['nome'];
	$dao->slug = $exp['slug'];
	$dao->resumo = $exp['resumo'];
	$dao->descricao = $exp['descricao'];
	$dao->categoria = $exp['categoria'];
	$dao->grupo = $exp['grupo'];
	$dao->logo = $exp['logo'];
	$dao->foto_destaque = $exp['foto_destaque'];
	$dao->instagram = $exp['instagram'];
	$dao->whatsapp = $exp['whatsapp'];
	$dao->ordem = $exp['ordem'];
	$dao->ativo = 1;
	$dao->created_on = date('Y-m-d H:i:s');
	$id = (int) $dao->_save();
	if ($id <= 0) {
		throw new Exception('Falha ao salvar: '.$exp['nome']);
	}

	$ordemFoto = 0;
	foreach ($exp['fotos'] as $foto) {
		$fotoDao = DAO::expositores_fotos();
		$fotoDao->expositor_id = $id;
		$fotoDao->arquivo = $foto['arquivo'];
		$fotoDao->legenda = $foto['legenda'];
		$fotoDao->ordem = $ordemFoto++;
		$fotoDao->destaque = (int) $foto['destaque'];
		$fotoDao->created_on = date('Y-m-d H:i:s');
		$fotoDao->_save();
	}

	echo "  - inserido: {$exp['nome']} / {$exp['slug']} (".count($exp['fotos'])." fotos)\n";
	return $id;
}

$pastas = array(
	'nino' => array('grupo' => 'artesao', 'categoria' => 'Cerâmica'),
	'telma' => array('grupo' => 'artesao', 'categoria' => 'Crochê'),
	'carolina' => array('grupo' => 'artesao', 'categoria' => 'Cerâmica'),
	'prya' => array('grupo' => 'artesao', 'categoria' => 'Cerâmica'),
	'lume' => array('grupo' => 'artesao', 'categoria' => 'Saboaria artesanal'),
	'atelieartesanaltropical' => array('grupo' => 'artesao', 'categoria' => 'Saboaria artesanal'),
	'Msytic' => array('grupo' => 'artesao', 'categoria' => 'Crochê'),
	'queijo' => array('grupo' => 'alimentacao', 'categoria' => 'Queijos e doces'),
	'flor_trigo' => array('grupo' => 'alimentacao', 'categoria' => 'Panificação'),
	'jc' => array('grupo' => 'alimentacao', 'categoria' => 'Café'),
	'marici' => array('grupo' => 'artesao', 'categoria' => 'Cerâmica'),
	'vania' => array('grupo' => 'artesao', 'categoria' => 'Cerâmica'),
	'rita' => array('grupo' => 'artesao', 'categoria' => 'Costura criativa'),
	'artsabor' => array('grupo' => 'alimentacao', 'categoria' => 'Confeitaria'),
);

try {
	$base = dirname(__FILE__).'/tmp/pt2';
	if (!is_dir($base)) {
		throw new Exception('Pasta não encontrada: tmp/pt2');
	}

	$max = DAO::doQuery("SELECT COALESCE(MAX(ordem), 0) AS m FROM expositores");
	$ordem = $max && isset($max->m) ? ((int) $max->m) + 10 : 50;

	echo "Importando tmp/pt2...\n";
	foreach ($pastas as $pasta => $meta) {
		$dir = $base.DIRECTORY_SEPARATOR.$pasta;
		$descFile = $dir.DIRECTORY_SEPARATOR.'descricao.txt';
		if (!is_file($descFile)) {
			echo "  - sem descricao.txt: {$pasta}\n";
			continue;
		}

		$info = ceramistas_pt2_parse(file_get_contents($descFile));
		if ($info['nome'] === '') {
			echo "  - sem nome: {$pasta}\n";
			continue;
		}

		$slug = url_amigavel($info['nome']);
		$slug = preg_replace('/_+/', '_', trim($slug, '_'));
		if ($slug === '') {
			$slug = url_amigavel($pasta);
		}

		list($logo, $destaque, $fotos) = ceramistas_pt2_copiar_fotos($dir, $slug);

		ceramistas_pt2_inserir(array(
			'slug' => $slug,
			'nome' => $info['nome'],
			'categoria' => $meta['categoria'],
			'grupo' => $meta['grupo'],
			'resumo' => $info['resumo'],
			'descricao' => $info['descricao'],
			'logo' => $logo,
			'foto_destaque' => $destaque,
			'instagram' => $info['instagram'],
			'whatsapp' => $info['whatsapp'],
			'ordem' => $ordem,
			'fotos' => $fotos,
		));
		$ordem += 10;
	}

	echo "Importação pt2 concluída.\n";
} catch (Exception $e) {
	echo 'Erro: '.$e->getMessage()."\n";
	exit(1);
}
