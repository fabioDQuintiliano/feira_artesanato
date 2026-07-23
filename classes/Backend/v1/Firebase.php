<?
namespace Backend\v1;
use \DAO,
	\Kreait\Firebase\Messaging;
class Firebase extends \Backend\Base{
	public function saveToken($body=null){
	
		$no_auth = $this->requireAuth($body);

		if($_SESSION['user_id']){
			$a = new \Firebase\NotificationFirebase();
			$a->subscribe_topic('device-'.$_SESSION['user_id'],$body->firebase_token);
			$INFO = array(
				'device_id' => $_SESSION['user_id']
			);
		}
		/*
			Nao é necessário executar nada aqui. A função $this->requireAuth já sava o token do firebase;
		*/
		return $this->setSuccess('ok', $INFO)->result();
	
	}
}
