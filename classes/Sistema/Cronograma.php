<?php 
//declare(strict_types=1);
namespace Sistema;
use \DAO;
class Cronograma {
	static function getTotalCronogramas(){
		$projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('listar');
		$daoCronograma = DAO::Cronograma()->_where("projeto IN(".implode(',', $projetosPermitidos).")")->_loadAll();

		return $daoCronograma->size()*1;

	}
	static function getCronograma($code){
		$projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('listar');
		$daoCronograma = DAO::Cronograma()->_where("projeto IN(".implode(',', $projetosPermitidos).")")->_txtid($code)->_loadAll();

		if($daoCronograma->size()){

			$ret = new \stdClass();

			$ret->id = $daoCronograma->id;
			$ret->txtid = $daoCronograma->txtid;
			$ret->projeto = $daoCronograma->projeto;
			$ret->nome = $daoCronograma->nome;
			$ret->publico = intval($daoCronograma->publico);

			return $ret;

		}
		return false;

	}
	static function setCompartilhamento($code){
		$projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('listar');
		$daoCronograma = DAO::Cronograma()->_where("projeto IN(".implode(',', $projetosPermitidos).")")->_txtid($code)->_loadAll();

		if($daoCronograma->size()){

			if($daoCronograma->publico == 1){
				$daoCronograma->publico = 0;
			}else{
				$daoCronograma->publico = 1;
			}
			$daoCronograma->update();

			return ['publico'=>intval($daoCronograma->publico)];

		}
		return false;

	}
	public static function get($code){

		$projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('listar');
		$lista = [];

		$cronograma = self::getCronograma($code);
		$itens_salvos = [];

		if($cronograma){
			$dao = DAO::Tarefa();
			$dao->cronograma = $cronograma->id;
			$dao->status = 0;
			$dao->where("projeto IN(".implode(',', $projetosPermitidos).")");
			$dao->loadAll("chave");



			if($dao->size()){
				$linha = 1;
				do{
					if($dao->chave && $dao->chave > 0){
						$linha = $dao->chave;
					}
					$lista[] = array(
						'id' => $linha,	
						'txtid' => $dao->txtid,	
						'pai' => $dao->pai?$dao->pai:'',	
						'nome' => $dao->titulo,	
						'inicio' => substr($dao->data_inicial,0,10),	
						'termino' => substr($dao->data_final,0,10),	
						
					);

					$dados = new \stdClass();
					$dados->id = $dao->id;
					$dados->chave = $linha;
					$dados->pai = $dao->pai != ''?intval($dao->pai):0;
					$itens_salvos[] = $dados;
					
					$linha++;
				}while($dao->next());

			}
		}

		if($lista && sizeof($lista)>0){

			foreach($lista as $key=>$tarefa){
				$chavePai = self::getChavePai($itens_salvos,$tarefa['pai']);
				if($chavePai){
					$lista[$key]['pai'] = $chavePai;
				}
			}

		}


		return ['tarefas' => $lista, 'cronograma' => $cronograma];
		
	}
	static function salvar($cronograma,$tarefas){


		$cronograma = self::getCronograma($cronograma);



		if($cronograma){
			$ids = [];
			$projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('listar');

		//	var_dump($tarefas);
			$itens_salvos = [];

			foreach ($tarefas as $tarefa) {
				if($tarefa->nome == ''){
				//	$tarefa->nome = 'Tarefa '.$tarefa->id;
				}
				if($tarefa->nome != ''){

					$id_item = false;
					$dao = DAO::Tarefa()
						->_where("projeto IN(".implode(',', $projetosPermitidos).")")
						->_cronograma($cronograma->id);

					if($tarefa->txtid){
						$dao->txtid = $tarefa->txtid;
					}else{
						$chave_busca = $tarefa->oldkey?$tarefa->oldkey:$tarefa->id;
						$dao->chave = $chave_busca;
					}

					$dao->loadAll();

					$dao->chave = $tarefa->id;
					$dao->data_inicial = $tarefa->inicio;
					$dao->data_final = $tarefa->termino;
					$dao->projeto = $cronograma->projeto;
					$dao->titulo = $tarefa->nome;
					$dao->cronograma = $cronograma->id;


					if(!$tarefa->txtid && $dao->size() == 0){

						$dao->status = 0;
						$dao->txtid = gen_uuid();
						$dao->created_on = date("Y-m-d H:i:s");
						$dao->created_by = $_SESSION['user_id'];
						$id_item = $dao->save();

					}else{
					//	var_dump($dao->size());

						if($dao->size()){
							$dao->edited_on = date("Y-m-d H:i:s");
							$dao->edited_by = $_SESSION['user_id'];
							$dao->update();
							$id_item = $dao->id;
						}else{	
							$dao->status = 0;
							$dao->txtid = gen_uuid();
							$dao->created_on = date("Y-m-d H:i:s");
							$dao->created_by = $_SESSION['user_id'];
							$id_item = $dao->save();

						}

					}

					//var_dump($tarefa->pai);
					if($id_item){
						$ids[] = $id_item;
						$dados = new \stdClass();
						$dados->id = $id_item;
						$dados->chave = $tarefa->id;
						$dados->pai = ($tarefa->pai && $tarefa->pai != '')?intval($tarefa->pai):0;
						$itens_salvos[] = $dados;
					}
				}

			}

			
			foreach($itens_salvos as $tarefa){
	

				$dao = DAO::Tarefa()->_id($tarefa->id)
					->_where("projeto IN(".implode(',', $projetosPermitidos).")")
					->_cronograma($cronograma->id)
					->_loadAll();
				if($dao->size()){
					$id_pai = self::getIdPai($itens_salvos,$tarefa->pai);
		
					if($id_pai){

						$dao->pai = $id_pai;


					}else{
						$dao->pai = 0;

					}
						$dao->update();

				}
				
			}


			$del = DAO::Tarefa()
				->_where("projeto IN(".implode(',', $projetosPermitidos).")")
				->_cronograma($cronograma->id);
			if(sizeof($ids) > 0){
				$del->where("id NOT IN(".implode(',',$ids).")");
			}
			$del->loadAll();
			if($del->size()){
				do{
					$del->delete();
				}while($del->next());
			}

			
		}


	}
	static function getIdPai($lista,$chave=0){
		//var_dump($lista,$chave);
		if(sizeof($lista)> 0 && $chave && $chave != ''){

			foreach($lista as $task){
				if($task->chave != '' && $task->chave == $chave){
					return $task->id;
				}
			}
		}

		return false;

	}
	static function getChavePai($lista,$id=0){
		if(sizeof($lista)> 0 && $id){

			foreach($lista as $task){
				if($task->id == $id){
					return $task->chave;
				}
			}
		}

		return false;

	}


	public static function getShared($code){


		/*

		retorna os dados de um cronograma, contanto que ele seja publico

		*/
		
		$lista = [];


		$daoCronograma = DAO::Cronograma()->_txtid($code)->_publico(1)->_loadAll();

		if($daoCronograma->size()){

			$ret = new \stdClass();

			$ret->id = $daoCronograma->id;
			$ret->txtid = $daoCronograma->txtid;
			$ret->projeto = $daoCronograma->projeto;
			$ret->nome = $daoCronograma->nome;
			$ret->publico = intval($daoCronograma->publico);

			$cronograma = $ret;

			$itens_salvos = [];

			if($cronograma){
				$dao = DAO::Tarefa();
				$dao->cronograma = $cronograma->id;
				$dao->loadAll("chave");



				if($dao->size()){
					$linha = 1;
					do{
						if($dao->chave && $dao->chave > 0){
							$linha = $dao->chave;
						}
						$lista[] = array(
							'id' => $linha,	
							'txtid' => $dao->txtid,	
							'pai' => $dao->pai?$dao->pai:'',	
							'nome' => $dao->titulo,	
							'status' => $dao->status,	
							'inicio' => substr($dao->data_inicial,0,10),	
							'termino' => substr($dao->data_final,0,10),	
							
						);

						$dados = new \stdClass();
						$dados->id = $dao->id;
						$dados->chave = $linha;
						$dados->pai = $dao->pai != ''?intval($dao->pai):0;
						$itens_salvos[] = $dados;
						
						$linha++;
					}while($dao->next());

				}
			}

			if($lista && sizeof($lista)>0){

				foreach($lista as $key=>$tarefa){
					$chavePai = self::getChavePai($itens_salvos,$tarefa['pai']);
					if($chavePai){
						$lista[$key]['pai'] = $chavePai;
					}
				}

			}


			return ['tarefas' => $lista, 'cronograma' => $cronograma];

		}
		return false;
		
	}


	

}