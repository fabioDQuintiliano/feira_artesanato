<?php 
//declare(strict_types=1);
namespace Sistema;
use \DAO;
class Projetos {
	

	static function getProjetosPermitidos($acao=false){
		$lista = [0];
		$dao = DAO::Projeto()->_created_by($_SESSION['user_id'])->_loadAll();
		if($dao->size()){
			do{
				$lista[] = $dao->id;
			}while($dao->next());
		}

		$dao2 = DAO::Projeto_pessoa()->_pessoa($_SESSION['user_id'])->_status(0)->_loadAll();
		if($dao2->size()){
			do{
				$lista[] = $dao2->projeto;
			}while($dao2->next());
		}

		return $lista;
	}
	static function getProjeto($id){
		$dao = DAO::Projeto()->_id($id)->_loadAll();
		if($dao->size()){

			return array(
				'id'=>$dao->id,
				'nome'=>$dao->nome,
				'imagem'=>imageUrl($dao->imagem)
			);
			
		}

		return false;
	}
	static function getProjetosRescentes(){
		$lista = [];

		$projetosPermitidos = self::getProjetosPermitidos();


		$dao = DAO::Projeto()->_where("id IN(".implode(',', $projetosPermitidos).")")->_loadAll();
		if($dao->size()){
			do{
			$lista[] =  array(
					'id'=>$dao->id,
					'nome'=>$dao->nome,
					'imagem'=>imageUrl($dao->imagem)
				);
			}while($dao->next());
			
			
		}

		return $lista;
	}
	

}