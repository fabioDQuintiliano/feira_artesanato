<?php
class Instagram{

	protected $serviceUrl = 'https://api.instagram.com/';
	protected $redirectUri = ROOT.'instagram_auth';	
	//protected $scope = 'instagram_graph_user_profile,instagram_graph_user_media';
	protected $scope = 'user_profile,user_media';
	

	protected $client_id = "931527714021655";
	protected $app_secret = "1d16036d44f75f443640ebe24ab68e34";


	static $token = null;

	public function __construct(string $token = null)
    {
		/*if(INSTAGRAM_APP_ID && INSTAGRAM_APP_ID != ''){
			$this->client_id = INSTAGRAM_APP_ID;
			$this->app_secret = INSTAGRAM_APP_SECRECT;
		}else{
			$this->app_secret = $client_secret;
			$this->client_id = $client_id;
		}*/

		if($token){
			$this->token = $token;
		}
    }

	public function getLoginUrl(){
		/*
		 - retorna uma url para redirecionar o usuário para a tela de login
		*/
		$url = $this->serviceUrl.'oauth/authorize?client_id='.$this->client_id.'&redirect_uri='.urlencode($this->redirectUri).'&scope='.$this->scope.'&response_type=code&state=1';
		//$url = urlencode($url);
		//echo "<a target='_blank' href='".$url."'>".$url.'</a>';
		
		
		return $url;
	
	}
	public function getToken($code){
		
			
		/*
		rebebe o código e autorizaçõ e troca por um token de curta duração	
		*/
		$ret = $this->getTokenCode($code);
		if($ret && $ret->access_token){
			
			/*
			- troca o tokem de curta duração por um token de longa duração
			- esse tokem dura 60 dias;
			*/
			$tk = $this->getLongLiveToken($ret->access_token);
			
			if($tk->access_token){
				$result = new \stdClass();
				$result->user_id = $ret->user_id;
				$result->access_token = $tk->access_token;
				$result->expires_in = $tk->expires_in;
				$result->token_type = $tk->token_type;

				$tempo = time()+$tk->expires_in;
				$result->validade = date("Y-m-d H:i:s",$tempo);
			
				return $result;
			}else{
				return $tk;
			}
		}

		return $ret;
	}
	public function updateToken($code){
		
	
		if($code && $code != ''){
			
			/*
			- alutualiza o tokem por mais 60 dias
			- esse tokem dura 60 dias;
			*/
			$tk = $this->updateLongLiveToken($code);
			
			if($tk->access_token){
				$result = new \stdClass();
				$result->access_token = $tk->access_token;
				$result->expires_in = $tk->expires_in;
				$result->token_type = $tk->token_type;

				$tempo = time()+$tk->expires_in;
				$result->validade = date("Y-m-d H:i:s",$tempo);
			
				return $result;
			}
		}

		return false;
	}
	public function getTokenCode($code){
	
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $this->serviceUrl."oauth/access_token",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('client_id' => $this->client_id,'client_secret' => $this->app_secret,'grant_type' => 'authorization_code','redirect_uri' => $this->redirectUri,'code' => $code),
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: multipart/form-data; boundary=--------------------------780367731654051340650991"
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response);

			
	}

	public function getLongLiveToken($short_token){
		/*
		- troca o tokem de curta duração por um token de longa duração
		- esse token dura 60 dias;
		- deve ser renovado antes desses 60 dias terminarem.
		*/
		$url = "https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=".$this->app_secret."&access_token=".$short_token;
		
		$ret = $this->get($url);
		return $ret;
	}
	public function updateLongLiveToken($long_lived_token){
		/*
		- renova o token de longa duração
		- esse token dura mais 60 dias;
		- deve ser renovado antes desses 60 dias terminarem.
		*/
		$url = "https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=".$long_lived_token;
		
		$ret = $this->get($url);
		return $ret;
	}


	public function getUserProfile($user_id,$tk=false){

		if($tk==false && !$this->token){
			$dao = DAO::System_admin()->_where("instagram_session IS NOT NULL")->_loadAll();
			if($dao->size()){
				$tk = $dao->instagram_session;
			}

		}else{

			$tk = $this->token;

		}


		// var_dump($tk);

		 $campos = "account_type,id,media_count,username";
		 $url = "https://graph.instagram.com/".$user_id."?fields=".$campos."&access_token=".$tk;
		

		$ret = $this->get($url);
		return $ret;

	}
	public function getUserMedia($user_id){

	
		$tk = $this->token;
		$campos = "caption,id,media_type,media_url,permalink,timestamp,username";
		$url = "https://graph.instagram.com/".$user_id."/media?fields=".$campos."&access_token=".$tk;
		
		$ret = $this->get($url);
		return $ret;


	}
	
	public function get($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

		$data = curl_exec($ch);
		curl_close($ch);

		return json_decode($data);
	}

}

?>