<?php
function getTextoHome($id,$completo=false){

    $dao = DAO::paginas()->_where("IFNULL(tipo,0) = 0")->_id($id)->_loadAll();
    if($dao->size()){
    	if($completo){
        	return $dao->texto_completo;
    	}
        return nl2br($dao->texto);
    }

    return '';

}
