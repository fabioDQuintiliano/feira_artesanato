<?php
define("PHP_LOGIN_FACE_APPID",555199051531137); 
define("PHP_LOGIN_FACE_APPSECRET","c6c8711d7a7ef85b56c04dec00a66087"); 
define("BUSINESS_USER_ID","17841405309211844"); 



function getFb(){
    $fb = new \Facebook\Facebook([
      'app_id' => PHP_LOGIN_FACE_APPID,
      'app_secret' => PHP_LOGIN_FACE_APPSECRET,
      'default_graph_version' => 'v3.2',
      ]);

     return $fb;;

}
function getTokenAcesso(){
    $fb = getFb();
   
    $helper = $fb->getRedirectLoginHelper();

    try {
      return $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return false;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      return false;
      
    }

}
function getHashtagId($fb,$token,$hashtag){
    $data = $fb->get('/ig_hashtag_search?user_id='.BUSINESS_USER_ID.'&q='.$hashtag, $token);

    $data = $data->getGraphEdge();
    $id = false;
    foreach ($data as $key => $value) {
       $id = $value['id'];
    }
    return $id;
}
function getHashtagPosts($fb,$token,$hashtag_id){
    $data = $fb->get("/{$hashtag_id}/recent_media?user_id=".BUSINESS_USER_ID."&fields=id,media_url,caption", $token);

    $data = $data->getGraphEdge();

    $lista = array();
    foreach ($data as $key => $value) {
        
        $lista[] = array(
            'id' => $value['id'],
            'url' => $value['media_url'],
            'caption' => $value['caption']
        );
      
    }
    return $lista;
}
function getMediaInfo($fb,$token,$media_id){
    $data = $fb->get("/{$media_id}/instagram_usertags", $token);



    return $data;
    $data = $data->getGraphEdge();

    $lista = array();
    foreach ($data as $key => $value) {
        
        $lista[] = array(
            'id' => $value['id'],
            'url' => $value['media_url'],
            'caption' => $value['caption']
        );
      
    }
    return $lista;
}





function getInfoProfileInstagram($perfil){

    $dados = file_get_contents_curl("https://www.instagram.com/web/search/topsearch/?context=blended&query=".$perfil);
    return ($dados['content']->users[0]->user);
}
function getInfoProfileImages($instagram_id){

    $dados = file_get_contents_curl('https://instagram.com/graphql/query/?query_id=17888483320059182&variables={"id":"'.$instagram_id.'","first":20,"after":null}');
    
    if($dados['content']->data->user->edge_owner_to_timeline_media->edges){
        return $dados['content']->data->user->edge_owner_to_timeline_media->edges;
    }
    return false;

}

?>