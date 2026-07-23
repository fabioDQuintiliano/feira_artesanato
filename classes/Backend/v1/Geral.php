<?
namespace Backend\v1;
use \DAO;
class Geral extends \Backend\Base{
	public function contato($body=null){
		$no_auth = $this->requireAuth($body);
		/*if($no_auth){
			// return $no_auth;
			return $this->setError(true, 'fatura_detalhes_sem_login', 'Para ver os detalhes de sua fatura, é necessário estar logado')->result();
		}
		*/
		
		$dao = DAO::Contato();
		$dao->nome = $body->nome;
		$dao->email = $body->email;
		$dao->mensagem = $body->mensagem;
		$dao->status = 0;
		$dao->created_on = date("Y-m-d H:i:s");
		$dao->device = $_SESSION['device_id'];
		$dao->pessoa = $_SESSION['user_id'];
		$dao->save();


		return $this->setSuccess('ok', $INFO)->result();
	}

	public function contato_loja($body=null){
		$no_auth = $this->requireAuth($body);
		/*if($no_auth){
			// return $no_auth;
			return $this->setError(true, 'fatura_detalhes_sem_login', 'Para ver os detalhes de sua fatura, é necessário estar logado')->result();
		}
		*/
		
		$dao = DAO::Contato_loja();
		$dao->nome = $body->nome;
		$dao->telefone = $body->telefone;
		$dao->email = $body->email;
		$dao->instagram = $body->instagram;
		$dao->endereco = $body->endereco;
		$dao->status = 0;
		$dao->created_on = date("Y-m-d H:i:s");
		$dao->device = $_SESSION['device_id'];
		$dao->pessoa = $_SESSION['user_id'];
		$dao->save();


		return $this->setSuccess('ok', $INFO)->result();
	}

	public function get_banners($body=null){
		$no_auth = $this->requireAuth($body);
		$lista = array();
		$dao = DAO::Banner_app()->_ativo(1)->_loadAll("order_by");
		if($dao->size()){

			do{
				$lista[] = array(

						'imagem' => getImagem($dao->imagem),
						'link' => $dao->link,
						'busca' => $dao->busca

				);

			}while($dao->next());
		}
		return $this->setSuccess('ok', $lista)->result();
	}

	public static function checaTermos($pessoa){

		$daoTermos = DAO::textos()->_id(1)->_loadAll();
		if($daoTermos->size()){

			$dao = DAO::pessoa_termos();
			$dao->pessoa = $pessoa;
			$dao->where("data_termos >= '{$daoTermos->data}'");
			$dao->loadAll();

			if($dao->size()){

				return false;
			}


			return true;
		}


		return false;

	}
	public function aceitaTermos($body=null){
		$no_auth = $this->requireAuth($body);
		
		$daoTermos = DAO::textos()->_id(1)->_loadAll();
		
		$dao = DAO::pessoa_termos();
		$dao->pessoa = $_SESSION['user_id'];
		$dao->data = date("Y-m-d H:i:s");
		$dao->data_termos = $daoTermos->data;
		$dao->save();


		return $this->setSuccess('ok', $INFO)->result();
	}




	
}
