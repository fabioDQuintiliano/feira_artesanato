<?php 
namespace Sistema;
use \DAO;

class Chatbot {
	
	
	public function saveMessage($num,$txt,$origem=0,$total_tokens=0){
		//return;

		/*
		$origem: 0 = pessoa. 1 = chatbot
		*/

		$contato = $this->setContato($num);
		$dao = DAO::Mensagem();
		$dao->mensagem = $txt;
		$dao->contato = $contato;
		$dao->origem = $origem;
		$dao->created_on = date("Y-m-d H:i:s");
		$dao->tokens = $total_tokens;
		$dao->created_by = $_SESSION['user_id'];
		$id_item = $dao->save();

		return $id_item;
		
	}
	public function setContato($num){

		if($num && $num != ''){
			$dao = DAO::Contato();
			$dao->numero = $num;
			$dao->loadAll();


			if($dao->size() == 0){
				$dao->txtid = gen_uuid();
				$dao->numero = $num;
				$dao->created_on = date("Y-m-d H:i:s");
				$dao->created_by = $_SESSION['user_id'];
				$id_item = $dao->save();

				return $id_item;
			}else{
				return $dao->id;
			}
		}else{
			return false;
		}
	}
	public function getHistorico($num,$horas=18){
		//busca mensagens das ultimas 24 horas;

		if($num != ''){

			$contato = $this->setContato($num);
		
			$data = somaDataAMD(date('Y-m-d H:i:s'), 0, 0, 0, ($horas * -1));
			$dao = DAO::Mensagem();
			$dao->contato = $contato;
			$dao->where("created_on >= '".$data."'");
			$dao->loadAll();

			$lista = array();
			if($dao->size()){
				do{
					$lista[] = array(
						'mensagem' => $dao->mensagem,
						'origem' => $dao->origem

					);
				}while($dao->next());
			}

			return $lista;
		}else{
			return [];
		}

	}


	public function  parse($mInicial){


		$m = str_replace('```', '', $mInicial);
		$m = str_replace('json', '', $m);
   	$ret =  json_decode($m, true);



   	if($ret){
   		return $ret;
   	}else{
   		return $mInicial;
   	}

  }
	

}