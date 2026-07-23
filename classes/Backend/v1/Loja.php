<?
namespace Backend\v1;
use \DAO;
class Loja extends \Backend\Base{
	public static function getInfo($id){
		$info = array();	
		$dao = DAO::System_admin()->_id($id)->_loadAll();
		if($dao->size()){

			$info = array(

					'id'=>$dao->id,
					'descricao' => $dao->nome,
					'nome' => $dao->nome,
					'imagem' => getImagem($dao->foto),
					'instagram' => $dao->instagram,
					'desconto' => ($dao->desconto && $dao->desconto)>0?$dao->desconto:0,
					'whatsapp_call_number' => numWhatsap($dao->whatsapp),
					'is_mine' => ($dao->id == $_SESSION['user_id'])?true:false
				);
		}


		return $info;
	
	}
}
