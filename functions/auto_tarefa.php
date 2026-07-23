<?php
function fn_pos_insert_tarefa($id){

	
	$dao = DAO::tarefa()->_id($id)->_loadAll();
	if($dao->size()){
		$dao->status = 0;
		$dao->update();
	}


	

	if($_GET['projeto']){
		header("location:".ROOT);
	}
	if($_GET['fromtask']){
		header("location:".ROOT);
	}
		

}
function fn_pos_update_tarefa($id){




	if($_GET['fromtask']){
		header("location:".ROOT);
	}
}
