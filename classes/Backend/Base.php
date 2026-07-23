<?
namespace Backend;
use \DAO;
require_once('rest/auto__base.php');

class Base{

	public $W;

	public $ERROR = 0;
	public $ERROR_MSG = "";
	public $ERROR_TYPE = "";
	public $MSG = '';
	public $INFO = array();

	function setError($error, $error_type='', $error_msg='',  $msg='' ,$INFO=Array()){
		$this->ERROR = $error;
		$this->ERROR_TYPE = $error_type;
		$this->ERROR_MSG = $error_msg;
		$this->INFO = $INFO;
		$this->MSG = $msg;
		return $this;
	}
	function setInfo($msg='', $INFO=Array()){
		$this->INFO = $INFO;
		$this->MSG = $msg;
		return $this;
	}
	function setSuccess($msg='', $INFO=Array()){
		$this->ERROR = 0;
		$this->ERROR_TYPE = '';
		$this->ERROR_MSG = '';
		$this->setInfo($msg,$INFO);
		return $this;
	}
	function json_result($no){
		if(GLOBAL_CHARSET!='UTF-8')utf8_encode_deep($no);
		return json_encode($no);
	}

	function result(){
        return $this->json_result(array(
            'error'=>$this->ERROR,
            'error_msg'=>$this->ERROR_MSG,
            'error_type'=>$this->ERROR_TYPE,
            'data'=>$this->INFO,
            'msg'=>$this->MSG
         ));
	}

    public function __construct(){

	}

	function requireAuth($body=null){

		$acesso = \RestClassMeta::auth($body);
		$this->getDeviceId($body);
		if(!$acesso)
			return $this->setError(true, 'invalid')->result();
		else
			return null;
	}

	function getDeviceId($body){
		if($body->device_info && $body->device_info->device){
			$dao = DAO::Devices()->_device($body->device_info->device)->_loadAll("ID DESC");
			if(!$dao->size()){
				if($_SESSION['user_id']){
					$dao->pessoa = $_SESSION['user_id'];
				}
				$dao->device = $body->device_info->device;
				$dao->created_on = date('Y-m-d H:i:s');

				if($body->firebase_token){
					$dao->firebase_token = $body->firebase_token;
				}
				$id = $dao->save();

				$_SESSION['device_id'] = $id;

			}else{
				if($_SESSION['user_id']){
					$dao->pessoa = $_SESSION['user_id'];
					if($body->firebase_token){
						$dao->firebase_token = $body->firebase_token;
					}
					$dao->update();
				}else if($body->firebase_token){
					$dao->firebase_token = $body->firebase_token;
					$dao->update();
				}
				$_SESSION['device_id'] = $dao->id;
			}
		}
	}

	static function process($mode, $path, $fnparams,$body=null, $auth=null){
		if(substr($path,0,1)!='/')$path='/'.$path;

		$pt = explode('/', $path);

		if($auth!=null){
			$tkp = explode(';', $auth);
			if(!$body->uuid) $body->uuid = $tkp[0];
			if(!$body->token) $body->token = $tkp[1];
		}

		$what = "\\Backend\\".$pt[1]."\\".$pt[2];
		$be = new $what();
		$method = $pt[3];
		
		return $be->$method($body);
	}

}