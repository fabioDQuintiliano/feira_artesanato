<?php
function fn_pos_insert_cronograma($id){
	$dao = DAO::Cronograma()->_id($id)->_loadAll();
	if($dao->size()){
		location(ROOT."adm-cronograma/".$dao->txtid);
	}
	
	
}
function fn_pos_update_cronograma($id){
	
	
}
function fn_pos_delete_cronograma($id){
	$dao = DAO::Tarefa()->_cronograma($id)->_loadAll();
	if($dao->size()){
		do{
			
			$dao->delete();
		}while($dao->next());
	}
}

function compartilhaCronograma($id,$tabela){
	$dao = DAO::Cronograma()->_id($id)->_loadAll();
	return "<a href='ROOT/adm-compartilhar/".$dao->txtid."'><i class=\"fas fa-share-alt\" style='color:#fff'></i> Compartilhar</a>";
}
?>