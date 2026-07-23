<?
class WsPackJson{

	public $ERROR = 0;
	public $ERROR_MSG = "";
	public $ERROR_TYPE = "";
	public $MSG = '';
	public $INFO = array();
	function getBody(){
		$body = app_body();
		if(GLOBAL_CHARSET!='UTF-8')utf8_decode_deep($body);
		return $body;
	}
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

	function result(){
        return json_result(array(
            'error'=>$this->ERROR,
            'error_msg'=>(GLOBAL_CHARSET!='UTF-8'&&$this->ERROR_MSG!=null)?utf8_decode($this->ERROR_MSG):$this->ERROR_MSG,
            'error_type'=>$this->ERROR_TYPE,
            'data'=>$this->INFO,
            'msg'=>(GLOBAL_CHARSET!='UTF-8'&&$this->MSG!=null)?utf8_decode($this->MSG):$this->MSG
         ));
	}

}

class RestClassMeta{
	static $rs = Array();

	static function extractParams($callback){
		$reflection = new ReflectionFunction($callback);
		$arguments  = $reflection->getParameters();
		$params=Array();
		foreach($arguments as $p){
			if($p->name=='W'||$p->name=='body')continue;
			$params[] = '$'.$p->name;
		}
		return implode(',',$params);
	}

	static function auth(&$body=null){
		if($body==null) $body=new stdClass();

		$_SESSION['USER_ID']=null;
		$_SESSION['user_id']=null;
		$_SESSION['TOKEN']=null;
		$_SESSION['logado']=null;
		if( !function_exists('apache_request_headers') ) {
		        function apache_request_headers() {
		                $arh = array();
		                $rx_http = '/\AHTTP_/';
		                foreach($_SERVER as $key => $val) {
		                        if( preg_match($rx_http, $key) ) {
		                                $arh_key = preg_replace($rx_http, '', $key);
		                                $rx_matches = array();
		                                // do some nasty string manipulations to restore the original letter case
		                                // this should work in most cases
		                                $rx_matches = explode('_', strtolower($arh_key));
		                                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
		                                        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
		                                        $arh_key = implode('-', $rx_matches);
		                                }
		                                $arh[$arh_key] = $val;
		                        }
		                }
		                if(isset($_SERVER['CONTENT_TYPE'])) $arh['Content-Type'] = $_SERVER['CONTENT_TYPE'];
		                if(isset($_SERVER['CONTENT_LENGTH'])) $arh['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
		                return( $arh );
		        }
		}

		$headers = apache_request_headers();
		//var_dump($headers);
		if($_SERVER['REDIRECT_HTTP_Authorization'] && !$headers['Authorization'] &&  !$headers['authorization'])
			$headers['Authorization'] = $_SERVER['REDIRECT_HTTP_Authorization'];


		if($headers['Authorization']){
			if(strpos($headers['Authorization'],';')!==false){
				$tkp = explode(';', $headers['Authorization']);
				if(!$body->uuid) $body->uuid = $tkp[0];
				if(!$body->token) $body->token = $tkp[1];
			}else{
				if(!$body->token) $body->token = $headers['Authorization'];
			}
		}
		if($headers['authorization']){
			if(strpos($headers['authorization'],';')!==false){
				$tkp = explode(';', $headers['authorization']);
				if(!$body->uuid) $body->uuid = $tkp[0];
				if(!$body->token) $body->token = $tkp[1];
			}else{
				if(!$body->token) $body->token = $headers['authorization'];
			}
		}


		/***CHECK TOKEN***/
		if(!validToken($body->uuid,$body->token) ){
			$_SESSION['USER_ID']=null;
			$_SESSION['user_id']=null;
			$_SESSION['TOKEN']=null;
			$_SESSION['logado']=null;
			return false;
		}
		if(!$body->uuid) $body->uuid=$_SESSION['VALID_USER_ID'];
		$_SESSION['USER_ID']=$body->uuid;
		$_SESSION['user_id']=$body->uuid;
		$_SESSION['TOKEN']=$body->token;
		$_SESSION['logado']=$_SESSION['USER_ID']?1:null;
		/***CHECK TOKEN***/		


		


		return true;

	}


	static function setVersion($route){
		 global $REST_VERSION;
		 if($REST_VERSION){
			 if (preg_match('/^\/rest\/v[0-9]+\//', $route) == 0) {
				$route = str_replace('/rest/', '/rest/'.$REST_VERSION.'/', $route);
			 }
		 }
		 return $route;
	}


	static function preProcessNodeA($mode,$route,$fnparams=Array(),$bodyparams=null,$auth=null){
		$W = new WsPackJson();
		if($bodyparams==null) $bodyparams=new StdClass();
		$body = $bodyparams;
		
		if($auth!=null){
			$tkp = explode(';', $auth);
			if(!$body->uuid) $body->uuid = $tkp[0];
			if(!$body->token) $body->token = $tkp[1];
		}
		if(!RestClassMeta::auth($body)) 			
			return $W->setError(true, 'invalid')->result();
	

		if($mode=='post')$route='POSTA'.$route;
		if($mode=='get')$route='GETA'.$route;
		if($mode=='delete')$route='DELETEA'.$route;
		if($mode=='put')$route='PUTA'.$route;
		if($mode=='node')$route='NODEA'.$route;


		$callback = RestClassMeta::$rs[$route];

		if($callback == null){
			echo '{"error":500,"error_msg":"Erro interno","error_type":"not_exists '.$route.'"}';
			return;
		}

		if($mode=='get'||$mode=='delete')
			return call_user_func_array($callback, array_merge(Array($W),$fnparams));
		else
			return call_user_func_array($callback, array_merge(Array($body, $W),$fnparams));

	}
 
	static function preProcessNode($mode,$route,$fnparams=Array(),$bodyparams=null){
		$W = new WsPackJson();
		if($bodyparams==null) $bodyparams=new StdClass();
		$body = $bodyparams;

	
		if($mode=='post')$route='POST'.$route;
		if($mode=='get')$route='GET'.$route;
		if($mode=='delete')$route='DELETE'.$route;
		if($mode=='put')$route='PUT'.$route;
		if($mode=='node')$route='NODE'.$route;

		$callback = RestClassMeta::$rs[$route];
		if($callback == null){
			echo '{"error":500,"error_msg":"Erro interno","error_type":"not_exists '.$route.'"}';
			return;
		}

		if($mode=='get'||$mode=='delete')
			return call_user_func_array($callback, array_merge(Array($W),$fnparams));
		else
			return call_user_func_array($callback, array_merge(Array($body, $W),$fnparams));

	}
 


	static function preProcessA($route,$get){
		$W = new WsPackJson();
		$body = $W->getBody();
		
		if(!RestClassMeta::auth($body)) 			
			return $W->setError(true, 'invalid')->result();
	
		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($body, $W),$get));

	}
 
	static function preProcess($route,$get){
		$W = new WsPackJson();
		$body = $W->getBody();

		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($body, $W),$get));

	}

	static function preProcessPuA($route,$get){
		$W = new WsPackJson();
		$body = $W->getBody();
		
		
		if(!RestClassMeta::auth($body)) 			
			return $W->setError(true, 'invalid')->result();
		
		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($body, $W),$get));


	}

	static function preProcessPu($route,$get){
		$W = new WsPackJson();
		$body = $W->getBody();

		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($body, $W),$get));


	}

	static function preProcessG($route,$get){
		$W = new WsPackJson();

		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($W),$get));

	}

	static function preProcessGA($route,$get){
		$W = new WsPackJson();

		if(!RestClassMeta::auth($body)) 			
			return $W->setError(true, 'invalid')->result();

		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($W),$get));

	}

	static function preProcessD($route,$get){
		$W = new WsPackJson();


		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($W),$get));


	}

	static function preProcessDA($route,$get){
		$W = new WsPackJson();

		if(!RestClassMeta::auth()) 			
			return $W->setError(true, 'invalid')->result();

		$callback = RestClassMeta::$rs[$route];
		return call_user_func_array($callback, array_merge(Array($W),$get));


	}


	static function addNodeAuth($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["NODEA".$route]=$callback;

	}

	static function addNode($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["NODE".$route]=$callback;

	}


	static function addPostAuth($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["POSTA".$route]=$callback;
		global $app;

		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);

		eval('$app->post($route,function ('.$params.') { return RestClassMeta::preProcessA("POSTA'.$route.'",Array('.$params.')); });');

	}

	static function addPost($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["POST".$route]=$callback;
		global $app;

		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);

		eval('$app->post($route,function ('.$params.') { return RestClassMeta::preProcess("POST'.$route.'",Array('.$params.')); });');

	}
	static function addGet($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["GET".$route]=$callback;
		global $app;

		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);

		eval('$app->get($route,function ('.$params.') { return RestClassMeta::preProcessG("GET'.$route.'",Array('.$params.')); });');

	}
	static function addGetAuth($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["GETA".$route]=$callback;
		global $app;

		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);

		eval('$app->get($route,function ('.$params.') { return RestClassMeta::preProcessGA("GETA'.$route.'",Array('.$params.')); });');

	}

	static function addDelete($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["DELETE".$route]=$callback;
		global $app;

		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);


		eval('$app->delete($route,function ('.$params.') { return RestClassMeta::preProcessD("DELETE'.$route.'",Array('.$params.')); });');

	}

	static function addDeleteAuth($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["DELETEA".$route]=$callback;
		global $app;

		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);


		eval('$app->delete($route,function ('.$params.') { return RestClassMeta::preProcessDA("DELETEA'.$route.'",Array('.$params.')); });');

	}

	static function addPutAuth($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["PUTA".$route]=$callback;
		global $app;
		//token, uuid.
		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);

		eval('$app->put($route,function ('.$params.') { return RestClassMeta::preProcessPuA("PUTA'.$route.'",Array('.$params.')); });');

	}

	static function addPut($route,$callback){
		$route=RestClassMeta::setVersion($route);

		global $semacao;
		RestClassMeta::$rs["PUT".$route]=$callback;
		global $app;
		//token, uuid.
		$app->options($route,$semacao);

		$params = RestClassMeta::extractParams($callback);


		eval('$app->put($route,function ('.$params.') { return RestClassMeta::preProcessPu("PUT'.$route.'",Array('.$params.')); });');

	}




	static function triggerNode($mode, $route, $fnparams, $bodyparams, $auth=null){
		global $semacao;
		

		if($auth!=null)
			return RestClassMeta::preProcessNodeA($mode,$route,(Array)$fnparams, $bodyparams, $auth);
		else
			return RestClassMeta::preProcessNode($mode,$route,(Array)$fnparams, $bodyparams);

	}

}






function getAToken($user,$device, $minutos){

	$ses = DAO::Session();
	$ses->created_by=$user;
	$ses->device = $device;
	$ses->loadAll("","expires_on > '".date('Y-m-d H:i:s')."'");
	if($ses->size()>0){
		$ses->expires_on = addMinuto(date('Y-m-d H:i:s'), $minutos);
		$ses->update();
		return $ses->token;
	}else{
		$ses = DAO::Session();
		$ses->created_by=$user;
		$ses->device = $device;
		$ses->created_on=date("Y-m-d H:i:s");
		$ses->expires_on = addMinuto(date('Y-m-d H:i:s'), $minutos);
		$ses->token = md5(microtime(1).$user);
		$ses->device = $device;
		$ses->save();
		return $ses->token;
	}
}



function refreshAToken($user=null,$token,$minutos){


	$ses = DAO::Session();
	if($user)
		$ses->created_by=$user;
	$ses->token = $token;
	$ses->loadAll();
	if($ses->size()>0){
		$ses->expires_on = addMinuto(date('Y-m-d H:i:s'), $minutos);
		$ses->update();
		return $ses->token;
	}
}


function validToken($user=null,$token,$device=null){

	$ses = DAO::Session();
	if($user){
		$ses->created_by=$user;
	}
	
	$ses->token=$token;
	if($device){
		$ses->device = $device;
	}

	$ses->loadAll("","expires_on > '".date('Y-m-d H:i:s')."'");


	if($ses->size()>0){
		$_SESSION['VALID_USER_ID']=$ses->created_by;
		return true;
	}else{
		return false;
	}
}

function dropAToken($user=null,$token){

	$ses = DAO::Session();
	if($user)
		$ses->created_by=$user;
	$ses->token=$token;
	return $ses->delete();

}
function dropATokenOthers($user,$token){

	$ses = DAO::Session();
	$ses->created_by=$user;
	return $ses->delete("token <> '".$token."'");

}
function dropATokenAll($user,$token){

	$ses = DAO::Session();
	$ses->created_by=$user;
	return $ses->delete("");

}


