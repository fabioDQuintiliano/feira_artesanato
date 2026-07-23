<?php
namespace Sistema\Admin;

/**
 * Contrato base opcional para widgets de campo do CRUD admin.
 * Widgets legados em componente/Componente__* continuam válidos via duck typing
 * (ComponenteLoader adapta save/salvar/update e aridade dos métodos).
 *
 * Métodos opcionais de pós-save (implementar no widget quando necessário):
 * - afterInsert($id, $tabela, $campo, $PARAM = null)
 * - afterUpdate($id, $tabela, $campo, $PARAM = null)
 * - save|salvar|update(...) — legado, ainda suportado pelo loader
 *
 * @see docs/admin-form-components.md
 */
abstract class ComponenteCampo
{
	/**
	 * @param array $PARAM parametros_componente + nome_campo + campo_tabela
	 * @return string|void HTML do campo
	 */
	abstract public function exibe($tabela, $valor = null, $PARAM = null);

	/**
	 * Representação leve na grid (sem assets pesados).
	 * @return string|void
	 */
	public function listagem($tabela, $id, $valor = null, $PARAM = null)
	{
		return $valor;
	}

	/**
	 * @return string|void
	 */
	public function view($tabela, $valor = null, $PARAM = null)
	{
		return $valor;
	}

	/**
	 * Declara parâmetros aceitos (documentação / IDE).
	 * @return array<string,string> chave => descrição
	 */
	public static function parametros()
	{
		return array();
	}
}
