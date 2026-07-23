<?php
if(!empty($_POST)){
	$q = new Model;

	foreach($_POST['listItensForm'] as $k=>$v):
		$item = $q->read("fatura", "id = '".$v."'");
		
		if(!empty($item) && ($item[0]['pago'] != 1)):
			$dados = array();
			$dados = array(
				'pago'=>1,
				'data_pagamento'=>date('Y-m-d H:i:s'),
				'valor_pago'=>$item[0]['valor']
			);
			$q->update('fatura',$dados,"id = '".$v."'");
			
		endif;
	endforeach;
	
	
}

	
echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=http://".$_POST['onSucesso']."'>";
//header("location:http://".$_POST['onSucesso']);
exit;
?>