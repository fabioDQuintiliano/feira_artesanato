<?
namespace Backend\v1;
use \DAO;
class Postagens extends \Backend\Base{
	public function get($body=null){
		$no_auth = $this->requireAuth($body);
		/*if($no_auth){
			// return $no_auth;
			return $this->setError(true, 'fatura_detalhes_sem_login', 'Para ver os detalhes de sua fatura, é necessário estar logado')->result();
		}
		*/
		$por_pagina = 10;
		$page = $body->page?$body->page:0;
		$limit = $por_pagina*$page;

		$id_postagem = $body->id_postagem;




		$INFO = array();
		$lista = array();
		$dao = DAO::postagem();

		if($id_postagem){
			$dao->id = $id_postagem;
		}else{

			$dao->_inativo(0)
				->_exibir(1);
		}

		$dao->where("imagem IS NOT NULL");
		$dao
			//->_group("instagram_code")
			->_loadAll("data DESC LIMIT {$limit},{$por_pagina}");



		if($dao->size()){

			do{

				//var_dump($dao->imagem);
				
				$imagem = getImagem($dao->imagem);

				if($dao->largura == '' || $dao->altura == ''){
					list($width, $height, $type, $attr) = getimagesize($imagem);	
					$dao->largura = $width;
					$dao->altura = $height;
					$dao->update();
				}

				$valor = false;
				if($dao->valor){
					$valor = array(

								'de' =>  false, 
								'valor' =>  floatToDinheiro2_rs($dao->valor) 
							);
				}
				
				$lista[] = array(

						'id'=>$dao->id,
						'descricao' => $dao->descricao,
						
						'imagem' => array(
								'full' => getImagem($dao->imagem),
								'thumb' => getImagem($dao->imagem,true,true)
							),
						'largura' => $dao->largura,
						'altura' => $dao->altura,
						'code' => $dao->instagram_code,
						'loja' => \Backend\v1\Loja::getInfo($dao->pessoa),
						'valor' => $valor
						
					);


			}while($dao->next());

		}

		if(!$id_postagem){
			$INFO['lista'] = $lista;
			if($page <= 0){
				$INFO['selecionados'] = self::getSelecionadoPessoa($body);
			}
		}else{

			if(sizeof($lista)){
				$INFO = $lista[0];
			}
		}

		$termos = \Backend\v1\Geral::checaTermos($_SESSION['user_id']);

		if($termos){

			$INFO['termos'] = array(
				'change'=> true,
				'msg' => 'Estamos atualizando nossos termos de utilização. Para continuar acessando o Tricô, revise e aceite nossos termos atualizados.'

			);

		}else{
			
			$INFO['termos'] = false;

		}

		return $this->setSuccess('ok', $INFO)->result();
	}


	public  function getSelecionados($body=null){
		$no_auth = $this->requireAuth($body);
		$INFO['lista'] = self::getSelecionadoPessoa($body);
		return $this->setSuccess('ok', $INFO)->result();
	}
	public  function getSelecionadoPessoa($body=null){

		//$no_auth = $this->requireAuth($body);


		$buscas = self::getBuscas();
		$view = self::getViews();

		//var_dump($view);

		$lista = array();
		$dao = DAO::postagem()
			->_inativo(0)
			->_exibir(1)
			//->_group("instagram_code")
			;

		$dao->where("imagem IS NOT NULL");

		if($buscas && $view){
		//	$dao->where("MATCH (busca) AGAINST ('{$buscas}' IN BOOLEAN MODE) ");
			$buffer = str_replace(array("\r", "\n"), '', 	implode(' ', $view->busca));

			$dao->_append("(MATCH (busca) AGAINST ('{$buffer} {$buscas}' IN BOOLEAN MODE)) ","relevancia");


		}else{
			if($buscas){

				$dao->_append("(MATCH (busca) AGAINST ('{$buscas}' IN BOOLEAN MODE) )","relevancia");
			}
			if($view){
				$buffer = str_replace(array("\r", "\n"), '', 	implode(' ', $view->busca));
				$dao->_append("(MATCH (busca) AGAINST ('{$buffer}' IN BOOLEAN MODE)) ","relevancia");

			}else{
				$dao->_append("0","relevancia");
			}
		}
		$dao->_append("altura","altura");
		$dao->_append("largura","largura");
		$dao->_append("descricao","descricao");
		$dao->_append("instagram_code","instagram_code");
		$dao->_append("pessoa","pessoa");
		$dao->_append("valor","valor");
		$dao->_append("imagem","imagem");
		$dao->_append("id","id");

		$dao->loadAll("relevancia DESC LIMIT 10");

		if($dao->size()){

			do{

				$imagem = getImagem($dao->imagem);
				//	var_dump($imagem);
				if($dao->largura == '' || $dao->altura == '' && $imagem){
				/*	list($width, $height, $type, $attr) = getimagesize($imagem);	
					$dao->largura = $width;
					$dao->altura = $height;
					$dao->update();*/
				}


				$valor = false;
				if($dao->valor){
					$valor = array(

								'de' =>  false, 
								'valor' =>  floatToDinheiro2_rs($dao->valor) 
							);
				}

				
				$lista[] = array(

						'id'=>$dao->id,
						'relevancia'=>$dao->relevancia,
						'descricao' => $dao->descricao,
						'imagem' => array(
								'full' => getImagem($dao->imagem),
								'thumb' => getImagem($dao->imagem,true,true)
							),
						'largura' => $dao->largura,
						'altura' => $dao->altura,
						'code' => $dao->instagram_code,
						'loja' => \Backend\v1\Loja::getInfo($dao->pessoa),
						'valor' => $valor
					);


			}while($dao->next());

		}

		return $lista;



	}

	public function getBuscas(){

		$dao = DAO::Buscas();
		if($_SESSION['user_id']){

			$dao->where("pessoa = {$_SESSION['user_id']} OR device = {$_SESSION['device_id']}");
		}else{
			$dao->device = $_SESSION['device_id'];
		}
		//$dao->_group("busca");
		$dao->where("resultados > 0");
		$dao->loadAll("id DESC LIMIT 5");

		$p = array();
		if($dao->size()){

			do{

				$p[] = '*'.$dao->busca.'*'; 

			}while($dao->next());
		}

		if(sizeof($p)>0){
		//	echo implode(' ', $p);

			return implode(' ', $p);
		}
		return false;
	}

	public function buscar($body=null){
		$no_auth = $this->requireAuth($body);
		/*if($no_auth){
			// return $no_auth;
			return $this->setError(true, 'fatura_detalhes_sem_login', 'Para ver os detalhes de sua fatura, é necessário estar logado')->result();
		}
		*/
		$por_pagina = 10;
		$page = $body->page?$body->page:0;
		$limit = $por_pagina*$page;

		$busca = trim($body->busca);

		$b = explode(' ', $busca);
		if(sizeof($b)>0){

			foreach ($b as $k=>$p) {

				if(trim($p) != ''){
					$b[$k] = $p.'* ';
				}
			}

			$busca = implode(' ', $b);
		}
		//echo $busca;

		$INFO = array();
		$lista = array();
		$dao = DAO::postagem()
			->_where("imagem IS NOT NULL")
			->_inativo(0)
			->_exibir(1)

			->_where(" MATCH (busca) AGAINST ('{$busca}' IN BOOLEAN MODE) ")
			->_loadAll("");
		
		$qtdResults = $dao->size();
		if($qtdResults>0){

			do{

				$imagem = getImagem($dao->imagem);

				if($dao->largura == '' || $dao->altura == ''){
					list($width, $height, $type, $attr) = getimagesize($imagem);	
					$dao->largura = $width;
					$dao->altura = $height;
					$dao->update();
				}



				$valor = false;
				if($dao->valor){
					$valor = array(

								'de' =>  false, 
								'valor' =>  floatToDinheiro2_rs($dao->valor) 
							);
				}

				
				$lista[] = array(

						'id'=>$dao->id,
						'descricao' => $dao->descricao,
						'imagem' => array(
								'full' => getImagem($dao->imagem),
								'thumb' => getImagem($dao->imagem,true,true)
							),
						'largura' => $dao->largura,
						'altura' => $dao->altura,
						'code' => $dao->instagram_code,
						'valor' => $valor,
						'loja' => \Backend\v1\Loja::getInfo($dao->pessoa)
					);


			}while($dao->next());

		}
		
		$INFO['lista'] = $lista;
		self::salvaBusca($body,$qtdResults);
		
		return $this->setSuccess('ok', $INFO)->result();
	}

	public function getViews(){

		$dao = DAO::postagem_visualizado();
		if($_SESSION['user_id']){
			$dao->where("pessoa = {$_SESSION['user_id']} OR device = {$_SESSION['device_id']}");
		}else{
			$dao->device = $_SESSION['device_id'];
		}
		//$dao->_append("COUNT(id)","total");
		//$dao->_setReportMode(false);
		$dao->_group('postagem');
		$dao->loadAll("MAX(id) DESC LIMIT 5");

		$p = array();
		$ids = array();
		if($dao->size()){

			do{
				//var_dump($dao->id);
				//$postagem = DAO::Postagem()->_id($dao->postagem)->_loadAll();
				$p[] = $dao->busca; 
				$ids[] = $dao->postagem;
 
			}while($dao->next());

			$ret = new \stdClass();
			$ret->ids = $ids;
			$ret->busca = $p;
			return $ret;

		}

		return false;

	}
	public function getLoja($body=null){
		$no_auth = $this->requireAuth($body);
	
		$por_pagina = 10;
		$page = $body->page?$body->page:0;
		$limit = $por_pagina*$page;

		$id_loja = $body->id;




		$INFO = array();
		$lista = array();
		$dao = DAO::postagem()
			->_pessoa($id_loja)
			->_inativo(0)
			->_exibir(1)
			//->_group("instagram_code")
			->_where("imagem IS NOT NULL")
			->_loadAll("data DESC LIMIT {$limit},{$por_pagina}");


		if($dao->size()){
			$infoLoja =  \Backend\v1\Loja::getInfo($id_loja);

			do{

				$imagem = getImagem($dao->imagem);

				if($dao->largura == '' || $dao->altura == ''){
					list($width, $height, $type, $attr) = getimagesize($imagem);	
					$dao->largura = $width;
					$dao->altura = $height;
					$dao->update();
				}


				$valor = false;
				if($dao->valor){
					$valor = array(

								'de' =>  false, 
								'valor' =>  floatToDinheiro2_rs($dao->valor) 
							);
				}

				
				$lista[] = array(

						'id'=>$dao->id,
						'descricao' => $dao->descricao,
						'imagem' => array(
								'full' => getImagem($dao->imagem),
								'thumb' => getImagem($dao->imagem,true,true)
							),
						'largura' => $dao->largura,
						'altura' => $dao->altura,
						'code' => $dao->instagram_code,
						'loja' => $infoLoja,
						'valor' => $valor
					);


			}while($dao->next());

		}
		if($page == 0){
			$INFO['loja'] = $infoLoja;
		}
		$INFO['lista'] = $lista;
		
	

		return $this->setSuccess('ok', $INFO)->result();
	}


	public function viewPostagem($body=null){
		$no_auth = $this->requireAuth($body);

		$INFO = array();
		if($body->id){
			$prod = DAO::Postagem()->_id($body->id)->_loadAll();

			$dao = DAO::postagem_visualizado();
			$dao->busca = $prod->busca;
			$dao->postagem = $body->id;
			$dao->device = $_SESSION['device_id'];
			if($_SESSION['user_id']){
				$dao->pessoa = $_SESSION['user_id'];
			}
			$dao->compra = $body->comprar?1:0;
			$dao->created_on = date('Y-m-d H:i:s');
			$dao->save();
		
			$INFO['selecionados'] = self::getSelecionadoPessoa();

			if($body->comprar){
				$mensagem = "";
				$code = $this->getCode($body->id);

				$urlPostagem = ROOT.'p/'.$body->id;
			
				$mensagem = "Olá. Vi esse produto no Tricô. ".$urlPostagem.". Gostaria de saber mais sobre valores e como comprar.\n\r";
				$mensagem .= "Esse é o código do meu desconto: ".$code;

				$INFO['mensagem'] =  $mensagem;

			}

		}

		return $this->setSuccess('ok', $INFO)->result();
	}

	public function salvaBusca($body,$size=0){

		if($body->busca != ''){
			$dao = DAO::Buscas();
			$dao->busca = $body->busca;
			$dao->resultados = $size;
			$dao->device = $_SESSION['device_id'];
			if($_SESSION['user_id']){
				$dao->pessoa = $_SESSION['user_id'];
			}
			$dao->created_on = date('Y-m-d H:i:s');
			$dao->save();
		}
	}


	public function denunciar($body=null){
		$no_auth = $this->requireAuth($body);
		/*if($no_auth){
			// return $no_auth;
			return $this->setError(true, 'fatura_detalhes_sem_login', 'Para ver os detalhes de sua fatura, é necessário estar logado')->result();
		}
		*/
		
		$dao = DAO::Denuncias();
		$dao->postagem = $body->postagem;
		$dao->nome = $body->nome;
		$dao->email = $body->email;
		$dao->motivo = $body->tipo;
		$dao->status = 0;
		$dao->created_on = date("Y-m-d H:i:s");
		$dao->save();
		
		return $this->setSuccess('ok', true)->result();
	}



	public function postDelete($body=null){
		$no_auth = $this->requireAuth($body);

		$dao = DAO::postagem()->_id($body->id)->_loadAll();

		if($dao->size() && $dao->pessoa == $_SESSION['user_id']){
			$dao->inativo = 1;
			$dao->update();
		}

		return $this->setSuccess('ok', true)->result();



	}


	function geraCode(){

		do{
			$cod = rand(100,999999);
			$dao = DAO::postagem_usuario_codigo()
				->_codigo($cod)
				//->_status(0)
				->_loadAll();
		}while($dao->size() > 0);



	
		return $cod;
	}
	public function getCode($postagem){

		$dao = DAO::postagem_usuario_codigo()->_postagem($postagem)->_pessoa($_SESSION['user_id'])->_status(0)->_loadAll();
	
		if($dao->size() > 0){
			

			return $dao->codigo;

		}else{

			
			$codigo = $this->geraCode();
			$salva =  DAO::postagem_usuario_codigo();
			$salva->pessoa = $_SESSION['user_id'];
			$salva->postagem = $postagem;
			$salva->created_on = date("Y-m-d H:i:s");
			$salva->status = 0;
			$salva->codigo = $codigo;
			$salva->save();

			return $codigo;
		}
	}
}
