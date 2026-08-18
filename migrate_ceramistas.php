<?php
/**
 * Cria tabelas e popula dados iniciais do site do 2º Encontro de Ceramistas.
 * Uso: php migrate_ceramistas.php
 *      (ou: docker compose exec app php migrate_ceramistas.php)
 */
require_once __DIR__ . '/front_includes.php';

function ceramistas_query($sql)
{
	DAO::doQuery($sql);
}

function ceramistas_txtid($seed)
{
	return substr(md5($seed . microtime(true) . mt_rand()), 0, 12);
}

try {
	echo "Aplicando schema...\n";
	$schema = file_get_contents(__DIR__ . '/sql/ceramistas_schema.sql');
	$statements = preg_split('/;\s*[\r\n]+/', $schema);
	foreach ($statements as $stmt) {
		$clean = preg_replace('/^\s*--.*$/m', '', $stmt);
		$clean = trim($clean);
		if ($clean === '') {
			continue;
		}
		ceramistas_query($clean);
	}
	echo "Schema OK.\n";

	$existentes = DAO::expositores()->_loadAll('id ASC LIMIT 1');
	if ($existentes && $existentes->size()) {
		echo "Expositores já existem. Seed ignorado.\n";
		echo "Migração concluída.\n";
		exit(0);
	}

	echo "Inserindo expositores e fotos...\n";

	$expositores = [
		[
			'slug' => 'beleza_bruta',
			'nome' => 'Ateliê Beleza Bruta',
			'categoria' => 'Concreto e decoração',
			'grupo' => 'artesao',
			'resumo' => 'Peças únicas em concreto, feitas à mão, que revelam a beleza dos materiais rústicos.',
			'descricao' => "O Ateliê Beleza Bruta nasceu em 2025, criado pela artista e professora Ju Pedreira, com o propósito de revelar a beleza que existe nos materiais rústicos e robustos. Cada peça é única e inteiramente feita à mão, utilizando moldes obtidos de materiais recicláveis que ganham nova vida no processo criativo.\n\nA base do trabalho é o concreto, enriquecido com elementos naturais, criando uma variedade de objetos de decoração e artísticos que despertam sentidos e memórias.\n\nVelas\nAs velas são aromáticas com base em concreto, explorando diferentes tamanhos, essências e cores. Cada uma é pensada para dialogar com elementos naturais, criando composições que despertam os sentidos e contam histórias.\n\nMandalas e incensários\nAs mandalas e os incensários nascem do encontro entre a força do concreto e a delicadeza do gesto manual. Cada peça é pintada à mão, uma a uma, em um processo lento e intuitivo, onde cores, formas e símbolos se organizam. Não existem peças iguais — cada mandala é um exercício de presença, intenção e equilíbrio.\n\nVasos\nOs vasos revelam a beleza do concreto em formas orgânicas e atemporais. Criados para acolher plantas, flores ou simplesmente ocupar o espaço como objetos de contemplação, cada peça é moldada e finalizada manualmente, valorizando texturas, imperfeições e a singularidade de cada criação.\n\nBowls\nOs bowls unem funcionalidade e expressão artística. Feitos em concreto e finalizados à mão, transitam entre o uso cotidiano e a decoração, recebendo velas, joias, pequenos objetos ou simplesmente compondo ambientes com sua presença marcante.\n\nBijuterias\nAs bijuterias transformam o concreto em leveza. Desenvolvidas com uma técnica especial que reduz o peso sem perder a resistência do material, são pintadas e finalizadas à mão, tornando cada peça exclusiva.",
			'logo' => 'expositores/beleza_bruta/logo.jpeg',
			'foto_destaque' => 'expositores/beleza_bruta/artesa.jpeg',
			'instagram' => 'jupedreira.atelie',
			'whatsapp' => '16991480570',
			'ordem' => 1,
			'fotos' => [
				['arquivo' => 'expositores/beleza_bruta/artesa.jpeg', 'legenda' => 'Artista', 'destaque' => 1],
				['arquivo' => 'expositores/beleza_bruta/foto_01.jpeg', 'legenda' => 'Peça', 'destaque' => 0],
				['arquivo' => 'expositores/beleza_bruta/foto_02.jpeg', 'legenda' => 'Peça', 'destaque' => 0],
				['arquivo' => 'expositores/beleza_bruta/foto_03.jpeg', 'legenda' => 'Peça', 'destaque' => 0],
				['arquivo' => 'expositores/beleza_bruta/foto_04.jpeg', 'legenda' => 'Peça', 'destaque' => 0],
				['arquivo' => 'expositores/beleza_bruta/foto_05.jpeg', 'legenda' => 'Peça', 'destaque' => 0],
				['arquivo' => 'expositores/beleza_bruta/foto_06.jpeg', 'legenda' => 'Peça', 'destaque' => 0],
			],
		],
		[
			'slug' => 'iara',
			'nome' => 'Atelier Iara Nicola',
			'categoria' => 'Cerâmica e artes',
			'grupo' => 'artesao',
			'resumo' => 'Argila, madeira, aquarela e ilustração — arte em cada detalhe, com aulas e oficinas.',
			'descricao' => "No Atelier Iara Nicola, a arte está presente em cada detalhe. Aqui, a argila ganha forma, a madeira recebe novas cores, a delicadeza da aquarela conta histórias e as ilustrações nascem da imaginação. Além de criar peças autorais, compartilho esse conhecimento por meio de aulas e oficinas, acreditando que a arte transforma, aproxima pessoas e desperta talentos. Tudo o que faço carrega dedicação, sensibilidade e, acima de tudo, muito amor pela arte.",
			'logo' => 'expositores/iara/logo.jpeg',
			'foto_destaque' => 'expositores/iara/foto_01.jpeg',
			'instagram' => 'atelier_iara_nicola',
			'whatsapp' => '35997010196',
			'ordem' => 2,
			'fotos' => [
				['arquivo' => 'expositores/iara/foto_01.jpeg', 'legenda' => 'Obra', 'destaque' => 1],
				['arquivo' => 'expositores/iara/foto_02.jpeg', 'legenda' => 'Obra', 'destaque' => 0],
				['arquivo' => 'expositores/iara/foto_03.jpeg', 'legenda' => 'Obra', 'destaque' => 0],
				['arquivo' => 'expositores/iara/foto_04.jpeg', 'legenda' => 'Obra', 'destaque' => 0],
				['arquivo' => 'expositores/iara/foto_05.jpeg', 'legenda' => 'Obra', 'destaque' => 0],
				['arquivo' => 'expositores/iara/foto_06.jpeg', 'legenda' => 'Obra', 'destaque' => 0],
				['arquivo' => 'expositores/iara/foto_07.jpeg', 'legenda' => 'Obra', 'destaque' => 0],
				['arquivo' => 'expositores/iara/foto_08.jpeg', 'legenda' => 'Obra', 'destaque' => 0],
			],
		],
		[
			'slug' => 'isa_gui',
			'nome' => 'Bolachinhas Caseiras Isa & Gui',
			'categoria' => 'Doces artesanais',
			'grupo' => 'alimentacao',
			'resumo' => 'Bolachinhas caseiras feitas com dedicação, carinho e o desejo de levar sabor especial à mesa.',
			'descricao' => "Olá! Meu nome é Isabel e sou a pessoa por trás das Bolachinhas Caseiras Isa & Gui.\n\nHá seis anos transformei uma paixão em profissão. Cada bolachinha que produzo é feita com dedicação, carinho e o desejo de levar um sabor especial para a mesa de cada cliente. Sou responsável por toda a produção, pelo atendimento e pelos pedidos, enquanto meu esposo, que trabalha em outra profissão, é meu grande parceiro nas entregas e me incentiva diariamente nessa caminhada.\n\nHoje tenho a alegria de dizer que vivo da renda das bolachinhas caseiras. Essa conquista é resultado de muito trabalho, persistência e amor pelo que faço. Acredito que o verdadeiro ingrediente de um produto artesanal é o cuidado colocado em cada detalhe.\n\nSou formada em Técnico de Enfermagem, mas não atuo na área. A vida me conduziu ao empreendedorismo, e encontrei na produção artesanal uma forma de realizar meus sonhos, servir às pessoas e construir uma história da qual me orgulho muito.\n\nParticipar deste encontro é uma oportunidade de compartilhar minha trajetória, aprender com outros empreendedores e mostrar que, com dedicação e paixão, um sonho pode se transformar em um negócio que alimenta não apenas famílias, mas também esperança e realização.\n\nÉ um prazer estar aqui. Obrigada por fazer parte dessa história.",
			'logo' => 'expositores/isa_gui/logo.jpeg',
			'foto_destaque' => 'expositores/isa_gui/artesa.jpeg',
			'instagram' => 'bolachinhas_caseira_',
			'whatsapp' => null,
			'ordem' => 3,
			'fotos' => [
				['arquivo' => 'expositores/isa_gui/artesa.jpeg', 'legenda' => 'Isabel', 'destaque' => 1],
				['arquivo' => 'expositores/isa_gui/foto_01.jpeg', 'legenda' => 'Produto', 'destaque' => 0],
				['arquivo' => 'expositores/isa_gui/foto_02.jpeg', 'legenda' => 'Produto', 'destaque' => 0],
				['arquivo' => 'expositores/isa_gui/foto_03.jpeg', 'legenda' => 'Produto', 'destaque' => 0],
				['arquivo' => 'expositores/isa_gui/foto_04.jpeg', 'legenda' => 'Produto', 'destaque' => 0],
				['arquivo' => 'expositores/isa_gui/foto_05.jpeg', 'legenda' => 'Produto', 'destaque' => 0],
				['arquivo' => 'expositores/isa_gui/foto_06.jpeg', 'legenda' => 'Produto', 'destaque' => 0],
			],
		],
	];

	foreach ($expositores as $exp) {
		$txtid = ceramistas_txtid($exp['slug']);
		$dao = DAO::expositores();
		$dao->txtid = $txtid;
		$dao->nome = $exp['nome'];
		$dao->slug = $exp['slug'];
		$dao->resumo = $exp['resumo'];
		$dao->descricao = $exp['descricao'];
		$dao->categoria = $exp['categoria'];
		$dao->grupo = isset($exp['grupo']) ? $exp['grupo'] : 'artesao';
		$dao->logo = $exp['logo'];
		$dao->foto_destaque = $exp['foto_destaque'];
		$dao->instagram = $exp['instagram'];
		$dao->whatsapp = $exp['whatsapp'];
		$dao->ordem = $exp['ordem'];
		$dao->ativo = 1;
		$dao->created_on = date('Y-m-d H:i:s');
		$expositorId = (int) $dao->_save();
		if ($expositorId <= 0) {
			throw new Exception('Falha ao salvar expositor: ' . $exp['nome']);
		}

		$ordemFoto = 0;
		foreach ($exp['fotos'] as $foto) {
			$fotoDao = DAO::expositores_fotos();
			$fotoDao->expositor_id = $expositorId;
			$fotoDao->arquivo = $foto['arquivo'];
			$fotoDao->legenda = $foto['legenda'];
			$fotoDao->ordem = $ordemFoto++;
			$fotoDao->destaque = (int) $foto['destaque'];
			$fotoDao->created_on = date('Y-m-d H:i:s');
			$fotoDao->_save();
		}
		echo "  - {$exp['nome']}\n";
	}

	echo "Inserindo programação...\n";
	$itens = [
		['titulo' => 'Abertura do Encontro', 'descricao' => 'Boas-vindas, apresentação dos expositores e início da feira no entorno do Caramanchão.', 'dia' => '2026-09-05', 'hora_inicio' => '09:00:00', 'hora_fim' => '10:00:00', 'local' => 'Praça da Matriz', 'categoria' => 'abertura', 'icone' => 'sun', 'ordem' => 1, 'destaque' => 1],
		['titulo' => 'Oficina ao vivo de cerâmica', 'descricao' => 'Experiência criativa para todas as idades — participe, aprenda e encante-se!', 'dia' => '2026-09-05', 'hora_inicio' => '10:30:00', 'hora_fim' => '12:00:00', 'local' => 'Espaço oficinas', 'categoria' => 'oficina', 'icone' => 'pottery', 'ordem' => 2, 'destaque' => 1],
		['titulo' => 'Circulação pelos expositores', 'descricao' => 'Conheça peças autorais, conversas com artesãos e sabores da região.', 'dia' => '2026-09-05', 'hora_inicio' => '12:00:00', 'hora_fim' => '14:00:00', 'local' => 'Calçadão Pedro Furlan', 'categoria' => 'feira', 'icone' => 'market', 'ordem' => 3, 'destaque' => 0],
		['titulo' => 'Rock and roll vintage', 'descricao' => 'Clássicos leves, agradáveis e cheios de boas lembranças — música para criar e conversar.', 'dia' => '2026-09-05', 'hora_inicio' => '15:00:00', 'hora_fim' => '17:00:00', 'local' => 'Palco principal', 'categoria' => 'musica', 'icone' => 'music', 'ordem' => 4, 'destaque' => 1],
		['titulo' => 'Oficina de massinhas e pintura', 'descricao' => 'Oficina à tarde, preparada por crianças, para crianças: cores, formas e criar com as mãos.', 'dia' => '2026-09-05', 'hora_inicio' => '14:00:00', 'hora_fim' => null, 'local' => 'Espaço Kids', 'categoria' => 'kids', 'icone' => 'kids', 'ordem' => 5, 'destaque' => 0],
		['titulo' => 'MPB ao entardecer', 'descricao' => 'Canções que acolhem, emocionam e celebram a riqueza da nossa cultura.', 'dia' => '2026-09-05', 'hora_inicio' => '17:30:00', 'hora_fim' => '19:30:00', 'local' => 'Palco principal', 'categoria' => 'musica', 'icone' => 'music', 'ordem' => 6, 'destaque' => 1],
		['titulo' => 'Reabertura e feira livre', 'descricao' => 'Segundo dia com exposição, gastronomia e troca de saberes.', 'dia' => '2026-09-06', 'hora_inicio' => '09:00:00', 'hora_fim' => '10:30:00', 'local' => 'Praça da Matriz', 'categoria' => 'feira', 'icone' => 'market', 'ordem' => 1, 'destaque' => 0],
		['titulo' => 'Oficina criativa em família', 'descricao' => 'Atividade prática com argila e materiais naturais para todas as idades.', 'dia' => '2026-09-06', 'hora_inicio' => '10:30:00', 'hora_fim' => '12:00:00', 'local' => 'Espaço oficinas', 'categoria' => 'oficina', 'icone' => 'pottery', 'ordem' => 2, 'destaque' => 1],
		['titulo' => 'Sabores e cerveja artesanal', 'descricao' => 'Delícias locais e brinde à arte que conecta.', 'dia' => '2026-09-06', 'hora_inicio' => '12:00:00', 'hora_fim' => '15:00:00', 'local' => 'Área gastronômica', 'categoria' => 'sabores', 'icone' => 'taste', 'ordem' => 3, 'destaque' => 0],
		['titulo' => 'Brinquedos na praça', 'descricao' => 'Cantinho especial com brinquedos para os pequenos brincarem e aproveitarem a tarde.', 'dia' => '2026-09-06', 'hora_inicio' => '14:00:00', 'hora_fim' => '18:00:00', 'local' => 'Espaço Kids', 'categoria' => 'kids', 'icone' => 'kids', 'ordem' => 4, 'destaque' => 0],
		['titulo' => 'Shows e encerramento', 'descricao' => 'Música ao vivo, celebração dos artesãos e fechamento do encontro.', 'dia' => '2026-09-06', 'hora_inicio' => '16:00:00', 'hora_fim' => '19:00:00', 'local' => 'Palco principal', 'categoria' => 'musica', 'icone' => 'music', 'ordem' => 4, 'destaque' => 1],
	];

	foreach ($itens as $item) {
		$dao = DAO::programacao();
		$dao->txtid = ceramistas_txtid($item['titulo'] . $item['dia']);
		$dao->titulo = $item['titulo'];
		$dao->descricao = $item['descricao'];
		$dao->dia = $item['dia'];
		$dao->hora_inicio = $item['hora_inicio'];
		$dao->hora_fim = $item['hora_fim'];
		$dao->local = $item['local'];
		$dao->categoria = $item['categoria'];
		$dao->icone = $item['icone'];
		$dao->ordem = $item['ordem'];
		$dao->destaque = $item['destaque'];
		$dao->ativo = 1;
		$dao->created_on = date('Y-m-d H:i:s');
		$dao->_save();
	}

	echo "Migração concluída com sucesso.\n";
} catch (Exception $e) {
	echo "Erro na migração: " . $e->getMessage() . "\n";
	exit(1);
}
