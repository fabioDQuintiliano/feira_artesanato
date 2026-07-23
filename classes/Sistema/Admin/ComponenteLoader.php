<?php
namespace Sistema\Admin;

/**
 * Loader centralizado de widgets em componente/{slug}.php.
 */
class ComponenteLoader
{
	const ROOT_DIR = 'componente';

	/**
	 * Valida slug do componente (evita path traversal).
	 */
	public static function isValidSlug($slug)
	{
		return is_string($slug) && $slug !== '' && (bool) preg_match('/^[a-zA-Z0-9_]+$/', $slug);
	}

	/**
	 * Parse de parametros_componente (linhas chave=valor; só o primeiro =).
	 */
	public static function parseParams($raw)
	{
		$PARAM = array();
		if ($raw === null || $raw === '') {
			return $PARAM;
		}
		$lines = preg_split('/\r\n|\r|\n/', (string) $raw);
		if (!is_array($lines)) {
			return $PARAM;
		}
		foreach ($lines as $line) {
			$line = trim($line);
			if ($line === '' || strpos($line, '=') === false) {
				continue;
			}
			$parts = explode('=', $line, 2);
			$key = trim($parts[0]);
			if ($key === '') {
				continue;
			}
			$PARAM[$key] = isset($parts[1]) ? trim($parts[1]) : '';
		}
		return $PARAM;
	}

	/**
	 * Monta $PARAM a partir da definição do input.
	 */
	public static function buildParams(array $inputDef)
	{
		$PARAM = self::parseParams(isset($inputDef['parametros_componente']) ? $inputDef['parametros_componente'] : '');
		if (!empty($inputDef['nome'])) {
			$PARAM['nome_campo'] = $inputDef['nome'];
		}
		if (!empty($inputDef['campo_tabela'])) {
			$PARAM['campo_tabela'] = $inputDef['campo_tabela'];
		}
		return $PARAM;
	}

	public static function className($slug)
	{
		return 'Componente__'.$slug;
	}

	public static function filePath($slug)
	{
		return self::ROOT_DIR.'/'.$slug.'.php';
	}

	/**
	 * @return object|null
	 */
	public static function load($slug)
	{
		if (!self::isValidSlug($slug)) {
			return null;
		}
		$path = self::filePath($slug);
		if (!is_file($path)) {
			return null;
		}
		$class = self::className($slug);
		if (!class_exists($class, false)) {
			include $path;
		}
		if (!class_exists($class, false)) {
			return null;
		}
		return new $class();
	}

	/**
	 * Hidden por campo (sempre), para pós-save em insert/edit.
	 */
	public static function hiddenMapInput($slug, $campoTabela)
	{
		if (!self::isValidSlug($slug) || $campoTabela === null || $campoTabela === '') {
			return '';
		}
		if (!preg_match('/^[a-zA-Z0-9_]+$/', (string) $campoTabela)) {
			return '';
		}
		return '<input type="hidden" name="componente__mapear[]" value="'
			.htmlspecialchars($slug.'__'.$campoTabela, ENT_QUOTES, 'UTF-8')
			.'" />';
	}

	/**
	 * Renderiza o componente.
	 *
	 * @param string $mode exibe|listagem|view
	 * @return string
	 */
	public static function render(array $inputDef, $mode, $tabela, $valor = null, $idRegistro = null)
	{
		$slug = isset($inputDef['mapear_componente']) ? trim((string) $inputDef['mapear_componente']) : '';
		if ($slug === '') {
			return '';
		}
		if (!self::isValidSlug($slug) || !is_file(self::filePath($slug))) {
			return '<span class="text-danger">Erro ao mapear componente <strong>'
				.htmlspecialchars($slug, ENT_QUOTES, 'UTF-8')
				.'.php</strong></span>';
		}

		$PARAM = self::buildParams($inputDef);
		$obj = self::load($slug);
		if (!$obj) {
			return '<span class="text-danger">Classe inválida para componente <strong>'
				.htmlspecialchars($slug, ENT_QUOTES, 'UTF-8')
				.'</strong></span>';
		}

		$html = '';
		if ($mode === 'exibe') {
			$html .= self::hiddenMapInput($slug, isset($inputDef['campo_tabela']) ? $inputDef['campo_tabela'] : '');
			$result = self::callMethod($obj, 'exibe', array($tabela, $valor, $PARAM));
			$html .= self::stringify($result);
			return $html;
		}

		if ($mode === 'listagem') {
			$result = self::callMethod($obj, 'listagem', array($tabela, $idRegistro, $valor, $PARAM));
			return self::stringify($result);
		}

		if ($mode === 'view') {
			$result = self::callMethod($obj, 'view', array($tabela, $valor, $PARAM));
			return self::stringify($result);
		}

		return '';
	}

	/**
	 * Executa pós-save de todos os componentes enviados no POST.
	 *
	 * @param string $mode insert|update
	 */
	public static function runAfterSave($postedMap, $id, $tabela, $mode = 'insert', array $paramsByCampo = array())
	{
		if (!is_array($postedMap) || count($postedMap) === 0) {
			return;
		}

		$seen = array();
		foreach ($postedMap as $token) {
			$token = trim((string) $token);
			if ($token === '' || isset($seen[$token])) {
				continue;
			}
			$seen[$token] = true;

			if (!preg_match('/^([a-zA-Z0-9_]+)__([a-zA-Z0-9_]+)$/', $token, $m)) {
				continue;
			}
			$slug = $m[1];
			$campo = $m[2];
			$obj = self::load($slug);
			if (!$obj) {
				continue;
			}

			$PARAM = isset($paramsByCampo[$campo]) && is_array($paramsByCampo[$campo])
				? $paramsByCampo[$campo]
				: array('campo_tabela' => $campo);

			self::dispatchAfterSave($obj, $id, $tabela, $campo, $PARAM, $mode);
		}
	}

	/**
	 * Compat: afterInsert/afterUpdate → save/salvar → update.
	 */
	public static function dispatchAfterSave($obj, $id, $tabela, $campo, $PARAM, $mode)
	{
		$mode = ($mode === 'update') ? 'update' : 'insert';

		if ($mode === 'insert' && method_exists($obj, 'afterInsert')) {
			self::callMethod($obj, 'afterInsert', array($id, $tabela, $campo, $PARAM));
			return;
		}
		if ($mode === 'update' && method_exists($obj, 'afterUpdate')) {
			self::callMethod($obj, 'afterUpdate', array($id, $tabela, $campo, $PARAM));
			return;
		}

		if ($mode === 'insert') {
			if (method_exists($obj, 'save')) {
				self::callMethod($obj, 'save', array($id, $tabela, $campo, $PARAM));
				return;
			}
			if (method_exists($obj, 'salvar')) {
				self::callMethod($obj, 'salvar', array($id, $tabela, $campo, $PARAM));
				return;
			}
			// alguns widgets só implementam update
			if (method_exists($obj, 'update')) {
				self::callMethod($obj, 'update', array($id, $tabela, $campo, $PARAM));
			}
			return;
		}

		// update
		if (method_exists($obj, 'update')) {
			self::callMethod($obj, 'update', array($id, $tabela, $campo, $PARAM));
			return;
		}
		if (method_exists($obj, 'save')) {
			self::callMethod($obj, 'save', array($id, $tabela, $campo, $PARAM));
			return;
		}
		if (method_exists($obj, 'salvar')) {
			self::callMethod($obj, 'salvar', array($id, $tabela, $campo, $PARAM));
		}
	}

	/**
	 * Chama método respeitando aridade (PHP 8+ rejeita args extras).
	 */
	public static function callMethod($obj, $method, array $args)
	{
		if (!method_exists($obj, $method)) {
			return null;
		}
		$rm = new \ReflectionMethod($obj, $method);
		$n = $rm->getNumberOfParameters();
		if ($n < count($args)) {
			$args = array_slice($args, 0, $n);
		}
		return $rm->invokeArgs($obj, $args);
	}

	private static function stringify($result)
	{
		if ($result === null || $result === true) {
			return '';
		}
		if (is_scalar($result)) {
			return (string) $result;
		}
		return '';
	}
}
