<?php
/**
 * Módulo admin: configuração geral do 2º Encontro de Ceramistas.
 * Instalar: docker compose exec -u www-data app php system/instalar_modulo.php ceramistas_config
 */
return array(
	'id' => 'ceramistas_config',
	'titulo' => 'Configuração geral do encontro de ceramistas',
	'doc' => 'docs/sql-ceramistas.md',
	'schema_sql' => dirname(__DIR__, 2).'/sql/ceramistas_schema.sql',
	'forms' => array(
		array(
			'arquivo_def' => 'ceramistas_config',
			'tabela' => 'ceramistas_config',
			'nome' => 'Configuração',
			'legenda' => 'Data, local e WhatsApp do encontro',
			'menu' => 'Encontro',
			'link' => 'configuracao',
			'inserir' => 1,
			'deletar' => 1,
			'omitir_permissoes' => array('add', 'del'),
			'preupdate' => 'auto_preupdate_ceramistas_config',
			'pre_listagem' => 'auto_pre_listagem_ceramistas_config',
			'campos' => array(
				array(
					'nome' => 'Início',
					'campo_tabela' => 'data_inicio',
					'type' => 'text',
					'mascara' => 'data',
					'exb_listagem' => 1,
					'validacao' => 'text',
					'secao' => 'Quando',
					'ordem' => 1,
				),
				array(
					'nome' => 'Fim',
					'campo_tabela' => 'data_fim',
					'type' => 'text',
					'mascara' => 'data',
					'exb_listagem' => 1,
					'validacao' => 'text',
					'secao' => 'Quando',
					'ordem' => 2,
				),
				array('nome' => 'Local principal', 'campo_tabela' => 'local', 'type' => 'text', 'class' => ' gWidth', 'exb_listagem' => 1, 'validacao' => 'text', 'secao' => 'Onde', 'ordem' => 3),
				array('nome' => 'Complemento', 'campo_tabela' => 'local_complemento', 'type' => 'text', 'class' => ' gWidth', 'secao' => 'Onde', 'ordem' => 4),
				array('nome' => 'Cidade', 'campo_tabela' => 'cidade', 'type' => 'text', 'exb_listagem' => 1, 'validacao' => 'text', 'secao' => 'Onde', 'ordem' => 5),
				array('nome' => 'UF', 'campo_tabela' => 'uf', 'type' => 'text', 'secao' => 'Onde', 'ordem' => 6),
				array('nome' => 'Endereço (texto do contato)', 'campo_tabela' => 'endereco', 'type' => 'textarea', 'class' => ' ggWidth', 'secao' => 'Onde', 'ordem' => 7),
				array('nome' => 'Mapa (lat,lng ou endereço)', 'campo_tabela' => 'mapa_query', 'type' => 'text', 'class' => ' ggWidth', 'secao' => 'Onde', 'ordem' => 8),
				array('nome' => 'WhatsApp', 'campo_tabela' => 'whatsapp', 'type' => 'text', 'exb_listagem' => 1, 'validacao' => 'text', 'secao' => 'Contato', 'ordem' => 9),
				array('nome' => 'Mensagem inicial do WhatsApp', 'campo_tabela' => 'mensagem_whatsapp', 'type' => 'textarea', 'class' => ' ggWidth', 'secao' => 'Contato', 'ordem' => 10),
			),
		),
	),
);
