<?php
/**
 * Adiciona grupo alimentacao e seed de pizza/cerveja.
 * Uso: docker compose exec app php migrate_ceramistas_alimentacao.php
 */
require_once __DIR__ . '/front_includes.php';

function ceramistas_txtid($seed)
{
	return substr(md5($seed . microtime(true) . mt_rand()), 0, 12);
}

function ceramistas_inserir_expositor(array $exp)
{
	$existe = DAO::expositores()->_slug($exp['slug'])->_loadAll('id ASC LIMIT 1');
	if ($existe && $existe->size()) {
		echo "  - já existe: {$exp['nome']}\n";
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
		throw new Exception('Falha ao salvar: ' . $exp['nome']);
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

	echo "  - inserido: {$exp['nome']}\n";
	return $id;
}

try {
	echo "Garantindo coluna grupo...\n";
	$col = DAO::doQuery("SELECT COUNT(*) AS total FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'expositores' AND COLUMN_NAME = 'grupo'");
	$temGrupo = $col && isset($col->total) && (int) $col->total > 0;
	if (!$temGrupo) {
		DAO::doQuery("ALTER TABLE expositores ADD COLUMN grupo VARCHAR(40) NOT NULL DEFAULT 'artesao' AFTER categoria");
		DAO::doQuery("ALTER TABLE expositores ADD KEY idx_expositores_grupo (grupo, ativo, ordem)");
		echo "Coluna grupo adicionada.\n";
	} else {
		echo "Coluna grupo já existe.\n";
	}

	echo "Classificando Bolachinhas como alimentação...\n";
	DAO::doQuery("UPDATE expositores SET grupo = 'alimentacao', categoria = 'Doces artesanais', ordem = 10 WHERE slug = 'isa_gui'");

	echo "Inserindo alimentação e cerveja...\n";

	ceramistas_inserir_expositor([
		'slug' => 'tomate_pelado',
		'nome' => 'Tomate Pelado Pizzas',
		'categoria' => 'Pizza artesanal',
		'grupo' => 'alimentacao',
		'resumo' => 'Massas fermentadas com tempo e carinho — pizza artesanal que transforma ingredientes simples em encontro.',
		'descricao' => "A Tomate Pelado Pizzas nasceu da certeza de que cozinhar é uma forma de amar.\n\nEm um determinado momento da minha vida, percebi que algo tão simples quanto farinha, água, fermento e tomate podia se transformar em uma verdadeira obra de arte. Mais do que misturar ingredientes, fazer pizza passou a ser uma maneira de criar momentos, despertar memórias e aproximar pessoas ao redor da mesa.\n\nFoi então que decidi viver daquilo que fazia meu coração vibrar.\n\nAcredito que Deus nos concede dons para servir ao próximo, e encontrei na gastronomia uma forma de exercer esse propósito. Cada massa fermentada lentamente, cada ingrediente escolhido com carinho e cada pizza que sai do forno carregam gratidão, dedicação e o desejo de proporcionar uma experiência especial.\n\nParticipar do 2º Encontro de Ceramistas é uma grande alegria, pois enxergamos muitas semelhanças entre a cerâmica e a pizza artesanal. Ambas exigem tempo, paciência, técnica e sensibilidade. Ambas transformam elementos simples em algo único, feito pelas mãos e pelo coração.\n\nSe hoje a Tomate Pelado Pizzas existe, é porque acreditamos que a boa comida tem o poder de conectar pessoas, criar lembranças e celebrar a vida.\n\nSeja muito bem-vindo à nossa mesa.\n\n\"Tudo quanto fizerem, façam de todo o coração, como para o Senhor.\"\nColossenses 3:23",
		'logo' => 'expositores/tomate_pelado/logo.jpeg',
		'foto_destaque' => 'expositores/tomate_pelado/foto_01.jpeg',
		'instagram' => 'tomatepeladopizzas',
		'whatsapp' => '15998035629',
		'ordem' => 20,
		'fotos' => [
			['arquivo' => 'expositores/tomate_pelado/foto_01.jpeg', 'legenda' => 'Pizza', 'destaque' => 1],
			['arquivo' => 'expositores/tomate_pelado/foto_02.jpeg', 'legenda' => 'Pizza', 'destaque' => 0],
			['arquivo' => 'expositores/tomate_pelado/foto_03.jpeg', 'legenda' => 'Pizza', 'destaque' => 0],
			['arquivo' => 'expositores/tomate_pelado/foto_04.jpeg', 'legenda' => 'Pizza', 'destaque' => 0],
			['arquivo' => 'expositores/tomate_pelado/foto_05.jpeg', 'legenda' => 'Pizza', 'destaque' => 0],
			['arquivo' => 'expositores/tomate_pelado/foto_06.jpeg', 'legenda' => 'Pizza', 'destaque' => 0],
		],
	]);

	ceramistas_inserir_expositor([
		'slug' => 'cevada_pura',
		'nome' => 'Cevada Pura',
		'categoria' => 'Cerveja artesanal',
		'grupo' => 'alimentacao',
		'resumo' => 'Chopp artesanal para brindar encontros — sabor, dedicação e confraternização.',
		'descricao' => "CEVADA PURA – UMA HISTÓRIA DE SABOR E DEDICAÇÃO\n\nÉ com muita alegria que participo do Encontro de Ceramistas levando a Cevada Pura, uma marca que faz parte da minha trajetória há anos.\n\nA Cevada Pura nasceu do sonho e da dedicação do meu irmão, que transformou seu conhecimento e sua paixão pelo universo cervejeiro em um produto de qualidade. Ao longo dos anos, tive a oportunidade de fazer parte dessa história, trabalhando com a venda do chopp artesanal e levando essa experiência para diferentes momentos e encontros.\n\nMais do que oferecer uma bebida, a Cevada Pura busca proporcionar momentos de confraternização, encontros entre amigos e experiências especiais em torno de um bom chopp.\n\nAcreditamos que existe uma conexão entre a produção artesanal e a valorização do feito à mão: assim como cada peça de cerâmica carrega a identidade e a criatividade de quem a produz, cada estilo de chopp possui características próprias, resultado de cuidado, dedicação e paixão.\n\nEstar presente neste evento é celebrar a arte, a criatividade e o encontro de pessoas que valorizam produtos feitos com história e propósito.\n\nCevada Pura – um chopp artesanal para brindar momentos especiais.",
		'logo' => 'expositores/cevada_pura/logo.jpeg',
		'foto_destaque' => 'expositores/cevada_pura/foto_01.jpeg',
		'instagram' => null,
		'whatsapp' => null,
		'ordem' => 30,
		'fotos' => [
			['arquivo' => 'expositores/cevada_pura/foto_01.jpeg', 'legenda' => 'Chopp', 'destaque' => 1],
			['arquivo' => 'expositores/cevada_pura/foto_02.jpeg', 'legenda' => 'Chopp', 'destaque' => 0],
			['arquivo' => 'expositores/cevada_pura/foto_03.jpeg', 'legenda' => 'Chopp', 'destaque' => 0],
			['arquivo' => 'expositores/cevada_pura/foto_04.jpeg', 'legenda' => 'Chopp', 'destaque' => 0],
			['arquivo' => 'expositores/cevada_pura/foto_05.jpeg', 'legenda' => 'Chopp', 'destaque' => 0],
		],
	]);

	echo "Migração de alimentação concluída.\n";
} catch (Exception $e) {
	echo "Erro: " . $e->getMessage() . "\n";
	exit(1);
}
