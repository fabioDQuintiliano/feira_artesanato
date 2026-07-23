<?php
include 'front_includes.php';
require_once("functions/nusoap/nusoap.php");


  class Soap_wrapper extends soap_server{
      var $script_uri;
      public function __construct()
        {
              $page = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
              //$page = substr($page,0,strrpos($page,'/'));
              $this->script_uri='http://'.$page; 
			  parent::nusoap_server();  
			  $this->configureWSDL('testserver','urn:'.$this->script_uri);
			  $this->wsdl->addComplexType("ArrayOfString",
							 "complexType",
							 "array",
							 "",
							 "SOAP-ENC:Array",
							 array(),
							 array(array("ref"=>"SOAP-ENC:arrayType","wsdl:arrayType"=>"xsd:string[]")),
							 "xsd:string"); 
 
			  $this->wsdl->schemaTargetNamespace=$this->script_uri;
			  $this->register('authenticate',
								array('client'=>'xsd:string','key'=>'xsd:string'),
								array('return'=>'xsd:string'),
								'urn:'.$this->script_uri,
								'urn:'.$this->script_uri.'#authenticate'
			 
								);
		}
      public function service_start($data)
        {
			
           if(!empty($data)){
				/***********parsing header for authentication********/
				
				$AuthenticationInfo = array();
				$doc = new DOMDocument("1.0","utf8");
				$doc->loadXML($data);
				$xpath = new DOMXpath($doc);
				$usernameList = $xpath->query("//username");
				if($usernameList->length>0){
					$AuthenticationInfo["username"] = $usernameList->item(0)->nodeValue;
				}
				$passwordList = $xpath->query("//password");
				if($passwordList->length>0){
					$AuthenticationInfo["password"] = $passwordList->item(0)->nodeValue;
				}				
				/*********************************************************/  
				
				$this->validateUser($AuthenticationInfo); // function to validate the user
				
				$this->service($data); 
          }else{
				$this->service($data); 
		  }
      	}
      public function validateUser($auth)
        {
			
           if(!empty($auth[username])&& !empty($auth[password])){
		   		
                if(authenticate($auth[username],$auth[password]) == false) {
					$server->fault(401,'Authentication failed!'); 
					exit;
				}
		   }else{  
		   		$server->fault(401,'Authentication failed!'); 
		   }          
        }
		
  }


function authenticate($user,$pass){
	if($user == 'fabio'){
		
		return true;
	}
	return false;
}

function getData($id){
	return 'teste do fabio';	
}










	//$debug=1;
	$server=new Soap_wrapper();
	$server->configureWSDL("Oca WS","urn:ocaWs");
	/*********************************************************/ 
	$server->register("getData",
					array("name" => "xsd:string"),
					array("return" => "xsd:string"),
					"urn:getData",
					"urn:getData#getgetData",
					"rpc",
					"encoded"
					);
	
	$HTTP_RAW_POST_DATA=isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:'';
	$server->service_start($HTTP_RAW_POST_DATA);
	
 
?>