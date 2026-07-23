<?php


function do_auto_preinsert_loja(){

    

    $_POST['senha'] = encriptPassSystem($_POST['senha']);

}

function do_auto_preupdate_loja($id){



    $nova_senha = encriptPassSystem($_POST[':senha']);
    $dao = DAO::System_admin()->_id($id)->_loadAll();


  
    if($dao->size()){
        if($dao->senha != $_POST[':senha']){


             $_POST['senha_antiga'] = $_POST['senha'];
             $_POST['senha'] = $nova_senha;
            
       

        }   

    }


    


   

    

}

function do_auto_posinsert_loja($id){
    atualizaLojaInfo($id);
   // getInfoLoja($id);
}


function do_auto_posupdate_loja($id){
	atualizaLojaInfo($id);


    if($id == $_SESSION['user_id']){

        loginSystem($_SESSION['system_admin'],$_POST['senha_antiga']);

    }
 //   getInfoLoja($id);
}



function atualizaLojaInfo($id){
	$dao = DAO::System_admin()->_id($id)->_loadAll();
    if($dao->size()){

    	$daoCidade = DAO::Cidade()->_id($dao->cidade)->_loadAll();
    	$daoEstado = DAO::Estado()->_id($dao->estado)->_loadAll();
    	$endereco = $dao->logradouro;

    	if($dao->numero){
    		$endereco = $endereco.' '.$dao->numero;
    	}

    	$endereco_completo = $endereco;

    	if($dao->complemento){
    		$endereco_completo .= '. '.$dao->complemento;
    	}
    	
    	$endereco .= '. '.$daoCidade->nome.' - '.$daoEstado->uf;
    	$endereco_completo .= '. '.$daoCidade->nome.' - '.$daoEstado->uf;
    	$coordenadas = getCoordenadasGeo($endereco);

    	if($coordenadas){

    		$dao->latitude = $coordenadas->lat;
    		$dao->longitude = $coordenadas->lng;


    	}

    	$dao->endereco_completo = $endereco_completo;
    	$dao->perfil = 2;
    	$dao->update();
    }
}

function getInfoLoja($id){
    $dao = DAO::System_admin()->_id($id)->_loadAll();
    if($dao->size()){

        if($dao->instagram != '');
        $info = getInfoProfileInstagram($dao->instagram);

        if($info->username == $dao->instagram){
        
            $imagem = salvaImagemPerfil($info->profile_pic_url,$id);
            if($imagem){
                $dao->foto = $imagem;
            }

            $dao->instagram_id = $info->pk;
            $dao->instagram_full_name = $info->full_name;

            $dao->update();
        }
    }

   // exit;



//exit;
}
