<?php 
//declare(strict_types=1);
namespace Sistema;
use \DAO;
class Tarefas {
	

	public static function get($projeto=false,$data=false){

		$projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('listar');
		$lista = [];
		$dao = DAO::Tarefa();
		if($projeto){
			$dao->projeto = $projeto;
		}
		

		$d = addDia(date('Y-m-d'),7);
		if($data == 'hoje'){

			//$d = addDia(date('Y-m-d'),7);

			$dao->where("data_final <='".$d."' OR data_final = '0000-00-00' OR data_final IS NULL");

		}else if($data == 'proximas'){

			$dao->where("data_final > '".$d."'");

		}

		$dao->status = 0;
		$dao->_append("CASE WHEN data_final IS NOT NULL AND data_final > 0 AND  DATE(data_final) <> '0000-00-00'  THEN 0 ELSE 1 END","ordem_inicial");
		$dao->_append("CASE WHEN data_final IS NOT NULL AND data_final > 0 AND  DATE(data_final) <> '0000-00-00'  THEN data_final ELSE created_on END","ordem");
		$dao->where("projeto IN(".implode(',', $projetosPermitidos).")");
		$dao->loadAll("ordem_inicial DESC, ordem");

		if($dao->size()){
			do{
				
				$lista[] = array(
					'id' => $dao->id,	
					'titulo' => $dao->titulo,	
					'descricao' => $dao->descricao,	
					'status' => self::geraStatus($dao->data_final),	
					'projeto' => \Sistema\Projetos::getProjeto($dao->projeto),	
					'data_final' => banco2date($dao->data_final,'dt'),	
					'cronograma' => self::cronogramaInfo($dao->cronograma)
				);
			}while($dao->next());

		}
		return $lista;
		
	}
	static function cronogramaInfo($id=false){
		if($id){
			$dao = DAO::Cronograma()->_id($id)->_loadAll();
			if($dao->size()){
				return array(
						'id' => $dao->id,
						'code' => $dao->txtid,
						'nome' => $dao->nome
					);
			}
		}

		return false;

	}
	static function geraStatus($d){

		if($d && $d != '0000-00-00 00:00:00'){
			$d = substr($d, 0,10);
			$amanha = addDia(date('Y-m-d'),1);
			
			
			$hoje   = date('Y-m-d');
			if($d > $amanha){
				return 'em_tempo';
			}else if($d < $hoje){
				return 'atrasado';
			}else{
				return 'alerta';
			}

		}

		return '';

	}

	function finalizaTarefa($id){

		$projetosPermitidos = \Sistema\Projetos::getProjetosPermitidos('finalizar');
		
		$dao = DAO::Tarefa();
		$dao->id = $id;
		$dao->status = 0;
		$dao->where("projeto IN(".implode(',', $projetosPermitidos).")");
		$dao->loadAll();
		if($dao->size()){
			$dao->status = 1;
			$dao->finalizado_em = date("Y-m-d H:i:s");
			$dao->update();
			return true;
		}

		return false;


	}
	

}