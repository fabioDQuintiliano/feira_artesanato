<?
namespace Sistema\Admin;

class Menu {

	public $icons = [
		'geral' => 'fas fa-dice-d6',
		'perfis' => 'fas fa-id-card-alt',
		'funcoes' => 'fas fa-users',
		'pessoas' => 'fas fa-users',
		'configuracoes' => 'fas fa-cog',
		'textos' => 'fas fa-file-alt',
		'postagens' => 'fas fa-newspaper',
		'banner' => 'fas fa-image',
		'informacoes' => 'fas fa-info-circle',
		'indicadores' => 'fas fa-sort-numeric-up-alt',
		'clientes' => 'fas fa-user-friends',
		'depoimentos' => 'fas fa-quote-left',
		'projetos' => 'fas fa-th-large',
		'categorias' => 'fas fa-list',
		'equipe' => 'fas fa-user-plus',
		'paginas' => 'fas fa-file-alt',
		'tarefas' => 'fas fa-tasks',
		'cronogramas' => 'fas fa-project-diagram',
		'estabelecimentos' => 'fas fa-store',
		'links' => 'fas fa-link',
		'encontro' => 'fas fa-palette',
		'expositores' => 'fas fa-store-alt',
		'fotos_dos_expositores' => 'fas fa-images',
		'programacao' => 'fas fa-calendar-alt',
		'atracoesmusicais' => 'fas fa-music',
		'atracoes_musicais' => 'fas fa-music',
		'configuracao' => 'fas fa-sliders-h',
		'encontroconfig' => 'fas fa-sliders-h',
		'usuarios' => 'fas fa-user-shield',
		'permissoes' => 'fas fa-key',
		'menus' => 'fas fa-bars',
		'fotos' => 'fas fa-images',
		'agenda' => 'fas fa-calendar-alt',
		'eventos' => 'fas fa-calendar-check',
		'tags' => 'fas fa-tags',
		'cidades' => 'fas fa-city',
		'estados' => 'fas fa-map',
	];
	public $iconsSubmenu = [];

	private static $infoMenu = null;
	
	function getMenu(){
		global $MAP,$PERFIL_PERMISSOES;

		if (!is_array(self::$infoMenu)) {
			$INFO_MENU = array();
			if (is_file('tables/_admin_menu.php')) {
				require 'tables/_admin_menu.php';
			}
			self::$infoMenu = is_array($INFO_MENU) ? $INFO_MENU : array();
		}

		$INFO_MENU = self::$infoMenu;
		if ($INFO_MENU) {
			uasort($INFO_MENU, array($this,'orderMenu'));
		}

		$mainManu = $INFO_MENU;
		$menusPermitidos = (isset($PERFIL_PERMISSOES['menu']) && is_array($PERFIL_PERMISSOES['menu'])) ? $PERFIL_PERMISSOES['menu'] : array();

		$objMenu = array();
		foreach($mainManu as $k=>$itemMenu){
			$itens = array();

			if(!empty($itemMenu['itens']) && count($itemMenu['itens'])>0)foreach($itemMenu['itens'] as $j=>$subMenu){

				$MAP['infoPages'][$subMenu['link']]=$subMenu;

				if(!in_array($subMenu['link'],$menusPermitidos)){continue;}

				
				$subMenu['id'] = removeCaracteres($j);
				$subMenu['icon'] = $this->getIconSub(removeCaracteres($j));
				$itens[] = $subMenu;	

			};

			

			if(sizeof($itens)>0){

				$secao = new \stdClass();
				$secao->nome = $k;
				$secao->id = removeCaracteres($k);
				$secao->icon = $this->getIcon(removeCaracteres($k));


				$obj = new \stdClass();
				$obj->item = $secao;
				$obj->subitens = $itens;
				$objMenu[] = $obj;


			}




		}

		return $objMenu;



		

	}
	function orderMenu($a, $b){

		return strnatcmp($a['order_by'], $b['order_by']);

	}

	function getIcon($nome){
		$key = strtolower((string) $nome);
		if(!empty($this->icons[$key])){
			return $this->icons[$key];
		}
		return 'fas fa-dice-d6';
	}
	function getIconSub($nome){
		$key = strtolower((string) $nome);
		if(!empty($this->iconsSubmenu[$key])){
			return $this->iconsSubmenu[$key];
		}
		if(!empty($this->icons[$key])){
			return $this->icons[$key];
		}
		return 'fas fa-circle';
	}
	

	 

}
