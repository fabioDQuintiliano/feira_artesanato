<?php
if($_GET['edit']){
	$dao = DAO::Cronograma()->_id($_GET['edit'])->_loadAll();
	if($dao->size()){
		myHeader("location:".ROOT."adm-cronograma/".$dao->txtid);
	}
	
}
?>