<?
namespace Backend\v0;
use \DAO, \Mailjet\Resources;


class PhpLogin extends \Backend\Base{


	 public function checkProvider($body=null){


		$login  = $body->login;
		$provider = $body->provider;

		$pes = DAO::System_admin()->_login($login)->_provider($provider->fonte.":".$provider->pid)->_loadAll();
		if($pes->size() > 0){
			return $this->setSuccess('ok', array())->result();
		}else{
            return $this->setError(true, 'not_found')->result();
		}

	 }


	public function login($body=null){

		
		$login  = $body->login;
		$senha  = $body->password;
		$role  = $body->role?$body->role:1;
		$device = $body->device;
		$provider = $body->provider;

	
		if($provider!=null){

			$pes = DAO::System_admin()->_login($login)->_provider($provider->fonte.":".$provider->pid)->_loadAll("","pessoa.(perfil).id = '".$role."'|");
			if($pes->size() > 0){
				if($pes->validado==1){

					if($provider->fonte=='facebook'){
						if($this->_phpLogin_validaTokenFB($provider->pid, $provider->ptoken)){
							$token = getAToken($pes->id,$device, PHP_LOGIN_EXPIRES_MINS);				
							$INFO = array('token'=>$token, 'uuid'=>$pes->id);
							return $this->setSuccess('ok', $INFO)->result();
						}else{
				            return $this->setError(true, 'fb-locked')->result();
						}
					}else{
						$token = getAToken($pes->id,$device, PHP_LOGIN_EXPIRES_MINS);				
						$INFO = array('token'=>$token, 'uuid'=>$pes->id);
						return $this->setSuccess('ok', $INFO)->result();
					}

				}else{
		            return $this->setError(true, 'locked')->result();
				}

			}else{
	            return $this->setError(true, 'wrong')->result();
			}

		}else{
			//var_dump($login);
			//var_dump(encriptPassSystem($senha));
			$pes = DAO::System_admin()->_login($login)->_senha(encriptPassSystem($senha))->_loadAll();
			if($pes->size() > 0){
				if($pes->ativo==1){
					$token = getAToken($pes->id,$device, PHP_LOGIN_EXPIRES_MINS);				

					$INFO = array('token'=>$token, 'uuid'=>$pes->id);
					return $this->setSuccess('ok', $INFO)->result();

				}else{
		            return $this->setError(true, 'locked')->result();
				}
			}else{
	            return $this->setError(true, 'wrong')->result();
			}			
		}


	}


	//token, uuid.
	public function refresh($body=null){
		$no_auth = $this->requireAuth($body);
		if($no_auth) return $no_auth;


		$token  = $body->token;
		$user  = $body->uuid;
		$token = refreshAToken($user,$token, PHP_LOGIN_EXPIRES_MINS);	
		$INFO = Array('token'=>$token);
		return $this->setSuccess('ok', $INFO)->result();
	}



	//token, uuid.
	public function session($body=null){
		$no_auth = $this->requireAuth($body);
		if($no_auth) return $no_auth;

		$token  = $body->token;
		$user  = $body->uuid;
		$INFO = Array('token'=>$token);
		return $this->setSuccess('ok', $INFO)->result();

	}


	//token, uuid.
	public function logout($body=null){
		$no_auth = $this->requireAuth($body);
		if($no_auth) return $no_auth;

		$token  = $body->token;
		$user  = $body->uuid;
		$param = $body->params;
		$pes = $_SESSION['user_id'];
		//var_dump($pes);
		$INFO = Array('n'=>dropAToken($user,$token));
		if($pes){
			if($param->deleteAllData == 'can_delete'){
				$del = DAO::Devices()->_pessoa($pes)->_loadAll();
				if($del->size()){
					do{
						$del->delete();
					}while($del->next());
				}
				$del = DAO::Buscas()->_pessoa($pes)->_loadAll();
				if($del->size()){
					do{
						$del->delete();
					}while($del->next());
				}

				$del = DAO::System_admin()->_id($pes)->_loadAll();
				if($del->size()){
					do{
						$del->delete();
					}while($del->next());
				}

			}
		}
		return $this->setSuccess('ok', $INFO)->result();
	}


	//token, uuid.
	public function logoutOthers($body=null){
		$no_auth = $this->requireAuth($body);
		if($no_auth) return $no_auth;
		
		$token  = $body->token;
		$user  = $body->uuid;
		$INFO = Array('n'=>dropATokenOthers($user,$token));
		return $this->setSuccess('ok', $INFO)->result();
	}



	//token, uuid.
	public function logoutAll($body=null){
		$no_auth = $this->requireAuth($body);
		if($no_auth) return $no_auth;

		$token  = $body->token;
		$user  = $body->uuid;
		$INFO = Array('n'=>dropATokenAll($user,$token));
		return $this->setSuccess('ok', $INFO)->result();
	}


	//token, uuid.
	public function profile($body=null){
		

		$no_auth = $this->requireAuth($body);
		if($no_auth) return $no_auth;

		$token  = $body->token;
		$user  = $body->uuid;

		$pes = DAO::System_admin()->_id($user)->_loadAll();
		if($pes->size()){
			$o = array(
				'id' => $pes->id,
				'nome' => $pes->nome,
				'login' => $pes->login,
				'notificacoes' => $pes->notificacoes,
				'whatsapp' => $pes->whatsapp
			);
			$INFO = $o;
			return $this->setSuccess('ok', $INFO)->result();
		}else{
            return $this->setError(true, 'not_found')->result();
		}
	}

	public function validateUsername($body=null){
		$username=$body->username;
		
		$pes = new \DAO_Pessoa($username,"pes_login");
		if(!$username || $pes->size()>0){
            return $this->setError(true, 'in_use')->result();
		}else{
			return $this->setSuccess('ok', Array())->result();
		}
	}


	public function validateEmail($body=null){
		$email=$body->email;	
		
		$pes = new \DAO_Pessoa($email,"email");
		if(!$email || $pes->size()>0){
            return $this->setError(true, 'in_use')->result();
		}else{
			return $this->setSuccess('ok', Array())->result();
		}

	}


	function _registerPessoaUpdate($pesid, $fields, $type=null){

		$pes = DAO::System_admin()->_id($pesid)->_loadAll();
		$noIn = array('perfil','login','ativo','senha','password','password_confirm','nova_senha');
		foreach($fields as $key => $val){
			if(!in_array($key, $noIn)){
				$pes->{$key}=$val;
			}
		}

		if($fields->senha != '' && $fields->nova_senha != ''){
			//var_dump(encriptPassSystem($fields->senha) , $pes->senha);
			if(encriptPassSystem($fields->senha) == $pes->senha){

				//var_dump('ddrr');
				$pes->senha = encriptPassSystem($fields->nova_senha);
			}else{
				//var_dump('ddrrsss');
				//var_dump('ddrr');
				return 'senha_incorreta';
			}
		}

					
		$pes->update();


		

		return true;
	}


	public function register($body=null){
		
		$login  = $body->login;
		$senha  = $body->password;
		$email  = $body->email;
		$role = $body->role;
		$device  = $body->deviceid;
		$props = $body->props;
		$fields = $body->fields;

		$provider = $body->provider;
		
		
		$pes = DAO::System_admin()->_login($login)->_loadAll();
		if(!$login||$pes->size()>0){
            return $this->setError(true, 'in_use')->result();
		}else{
			$pes = DAO::System_admin()->_email($email)->_loadAll();
			if(!$email||$pes->size()>0){
				return $this->setError(true, 'in_use')->result();
			}else{


		
				//CREATE USER.
				$pes = DAO::System_admin();
				$pes->login=$login;

				if($provider!=null){
					$pes->senha=null;
					//$pes->pes_senha2=null;
					$pes->provider=$provider->fonte.":".$provider->pid;
				}else{
					$pes->senha=encriptPassSystem($senha);
					//$pes->pes_senha2=encriptPassSystem($senha);
				}
				$pes->email=$email;
				$pes->created_on=date("Y-m-d H:i:s");
				$pes->ativo = 1;
				$pes->perfil = 3;
				$pes->notificacoes = 1;
				$id = $pes->save();

				//salva o aceite de termos
				$daoTermos = DAO::textos()->_id(1)->_loadAll();
				$dao = DAO::pessoa_termos();
				$dao->pessoa = $id;
				$dao->data = date("Y-m-d H:i:s");
				$dao->data_termos = $daoTermos->data;
				$dao->save();


				@$this->_registerPessoaUpdate($id, $fields, 'insert');
				
				$INFO=Array();
				$INFO['uuid']=$id;
				$token = getAToken($id,$device, PHP_LOGIN_EXPIRES_MINS);				
				$INFO['token']=$token;
				
				return $this->setSuccess('ok', $INFO)->result();

			}
		}
	}

	function _enviaSMS($user,$msgcod){
		return 'sent '.$msgcod;
	}

	function _enviaEmail($user,$msgcod){
		return 'sent '.$msgcod;
	}

	function _sendValidateMethod($user,$mode){
		if($mode=='sms'){
			return enviaSMS($user,strtoupper(substr(md5($user."SMS"),0,6)));
		}
		if($mode=='email'){
			return enviaEmail($user,strtoupper(substr(md5($user."EMAIL"),0,16)));
		}
		
	}
	function _checkValidateMethod($user,$mode,$code){
		if($mode=='sms')
			return strtoupper($code) == strtoupper(substr(md5($user."SMS"),0,6));
		if($mode=='email')
			return strtoupper($code) == strtoupper(substr(md5($user."EMAIL"),0,16));
		return false;	
	}




	public function confirmSend($body=null){
		$username = $body->login;
		$mode = $body->mode;

		$pes = (new \DAO_Pessoa())->_login($username)->_loadAll('','validado is null OR validado=0');
		if($pes->size()<=0 ){
			return $this->setError(true, 'not_found')->result();
		}else{					
			$INFO = Array('valid'=>$this->_sendValidateMethod($pes->id,$mode));
			return $this->setSuccess('ok', $INFO)->result();
		}
	}



	public function confirmEmail($body=null){
		$username = $body->login;
		$code = $body->code;


		$pes = (new \DAO_Pessoa())->_login($username)->_loadAll('','validado is null OR validado=0');
		if($pes->size()<=0){
			return $this->setError(true, 'not_found')->result();
		}else{
			$check= $this->_checkValidateMethod($pes->id,'email',$code);
			if(!$check){
				return $this->setError(true, 'wrong')->result();
			}else{
				$pes->validado=1;
				$pes->update();
				return $this->setSuccess('ok', Array())->result();
			}
		}
	}

	public function confirmSms($body=null){
		$username = $body->login;
		$code = $body->code;		

		$pes = (new \DAO_Pessoa())->_login($username)->_loadAll('','validado is null OR validado=0');
		if($pes->size()<=0){
			return $this->setError(true, 'not_found')->result();
		}else{
			$check= $this->_checkValidateMethod($pes->id,'sms',$code);
			if(!$check){
				return $this->setError(true, 'wrong')->result();
			}else{
				$pes->validado=1;
				$pes->update();
				return $this->setSuccess('ok', Array())->result();
			}
		}
	}



	function _enviaEmailDeSenha($pes,$resetcod){
		//SE A CONTA FOR COM PROVIDER INFORMAR O USUARIO E NAO ENVIAR EMAIL	

		
		return 'sent '.$resetcod;
	}

	function _sendSenhaResetEmail($pes){
		
		return $this->_enviaEmailDeSenha($pes,strtoupper(md5($pes->id."RESET".$pes->senha.microtime().rand(0,5555))));
		
	}
	function _checkResetPasswordCode($pes,$code){
		return strtoupper($code) == strtoupper(substr(md5($pes->id."RESET".$pes->pes_senha),0,16));
	}


	public function forgotPassword($body=null){
		$username = $body->login;
		
		$pes = DAO::System_admin()->_login($username)->_loadAll();
		if($pes->size()<=0 ){
			return $this->setError(true, 'not_found')->result();
		}else{					
			$INFO = Array('pwd'=>$this->_sendSenhaResetEmail($pes));
			return $this->setSuccess('ok', $INFO)->result();
		}
	}



	public function passwordReset($body=null){
		$username = $body->login;
		$code = $body->code;	

		$password = $body->password;
		
		$pes = (new \DAO_Pessoa())->_login($username)->_loadAll();
		if($pes->size()<=0 ){
			return $this->setError(true, 'not_found')->result();
		}else{
			$check= $this->_checkResetPasswordCode($pes,$code);
			if(!$check){
				return $this->setError(true, 'wrong')->result();
			}else{
				$pes->pes_senha=$password;
				$pes->pes_senha2=$password;
				$pes->update();
				return $this->setSuccess('ok',Array())->result();
			}
		}

	}


	public function profileEdit($body=null){
		$no_auth = $this->requireAuth($body);
		if($no_auth) return $no_auth;

		$login  = $body->login;
		//$senha  = $body->password;
		$email  = $body->email;

		$fields = $body->fields;
		

		$token  = $body->token;
		$user  = $body->uuid;


		$pes = DAO::System_admin()->_id($user)->_loadAll();
		if($pes->size()==0){
			return $this->setError(true, 'not_found')->result();
		}else{

			if($login){//trocar login.					
				$pes->login = $login;
				//TODO: email informando troca
			}
			if($senha){//trocar senha.
				//$pes->senha=encriptPassSystem($senha);
				//$pes->pes_senha2=encriptPassSystem($senha);
				//TODO: email informando troca					
			}
			if($email){//trocar email.
				$pes->email = $email;
				//TODO: email informando troca					
			}
			$pes->edited_on = date("Y-m-d H:i:s");
			$pes->update();

			if($fields){
				$infoPass = $this->_registerPessoaUpdate($pes->id, $fields, 'edit');
				
				if($infoPass === 'senha_incorreta'){
				//	var_dump('sdasdasdasdasdasd');
					return $this->setError(true, 'senha_incorreta','Senha incorreta. Verifique sua senha atual e tente novamente.')->result();
				}
			}

			//$pes->edited_on = date("Y-m-d H:i:s");
			//$pes->update();
					
			$pes = DAO::System_admin()->_id($user)->_loadAll();
			$o = array(
				'id' => $pes->id,
				'nome' => $pes->nome,
				'login' => $pes->login,
				'notificacoes' => $pes->notificacoes,
				'whatsapp' => $pes->whatsapp

			);

			return $this->setSuccess('ok', $o)->result();
		}
			

	}



	function _phpLogin_validaTokenFB($id='',$token=''){


		if(!$id || !$token) return false;

		$appID = PHP_LOGIN_FACE_APPID;
		$appSECRET = PHP_LOGIN_FACE_APPSECRET;

		//solicita o token do aplicativo
		$url = "https://graph.facebook.com/oauth/access_token?client_id=".$appID."&client_secret=".$appSECRET."&grant_type=client_credentials";
		$tokenAPP = json_decode(file_get_contents($url));

		//solicita informações sobre o token do usuário
		$urlValida = "https://graph.facebook.com/debug_token?input_token=".$token."&access_token=".$tokenAPP->access_token;
		$dados = json_decode(file_get_contents($urlValida));
		//var_dump($dados);

		if($dados){
			if($dados->data->is_valid == true){
				if($dados->data->user_id == $id && $dados->data->app_id == $appID){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}

	}

}
