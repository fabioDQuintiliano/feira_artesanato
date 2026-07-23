<?php 
namespace Sistema;
use \DAO;

class Whatsapp {
	



	const cod_telefone = '679567641898863';
	const token = "EAAKQEH8nAhYBO7qTaNPOf7noZBvfIZC1Wj0fortRVR9w11TjrHrciuc4iZBP7vl41zoLIxt8zR001MEcildhBuJKKIkugGGn9lerVlK2ZCtnZBqOk6od8VXL7ykDkIj5GHRkUE2L1sjDZBZAJaPvzucPZBhKWxXpjqr5PDbLj7vQ72sjmcHICqglvIFJoNvCzPR88AZDZD";

	function execPost($postData){
    //$cod = '665389119986135';
    $cod = self::cod_telefone;
    $ch = curl_init();
    curl_setopt_array($ch, array(
       
      CURLOPT_URL => 'https://graph.facebook.com/v22.0/'.$cod.'/messages',
  
    ));

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Authorization: Bearer '.self::token,
      'Content-Type: application/json'
    ));

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));        
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);



    return $response;
	}
	function sendMessage($para,$mensagem){

    $postData = [];
    $postData['messaging_product'] = "whatsapp";
    $postData['to'] = $para;
    $postData['recipient_type'] = "individual";
    $postData['type'] = "text";
    $postData['text'] = array("body"=>$mensagem);
    return $this->execPost($postData);
		
	}

  function setRead($id){

    $postData = [];
    $postData['messaging_product'] = "whatsapp";
    $postData['status'] = "read";
    $postData['message_id'] = $id;
    $postData['typing_indicator'] = [
      'type' =>"text"
    ];


 
    return $this->execPost($postData);


  }
		
	

}