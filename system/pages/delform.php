<?php
if($_POST['form_id']!=''):
	$q = new Model;
	
	$q->delete("system_form","id = '".$_POST['form_id']."'");
	$q->delete("system_inputs","system_form = '".$_POST['form_id']."'");
	
	$dado = $q->read("admin_submenu","form  = '".$_POST['form_id']."'");
	
	if(count($dado)>0):
		$q->delete("admin_menu_submenu","submenu = '".$dado[0]['id']."'");
		$q->delete("admin_submenu","form = '".$_POST['form_id']."'");
	endif;
	 $URL = ROOT."system-form";
	echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
	//header("Location:".ROOT."system-form");
	exit;
endif;
?>
<div style="width:500px; margin:0 auto; padding-top:100px;">
<form method="post">
	Tem certeza que deseja deletar este formulário? Esta acão não poderá ser desfeita.
    <br />
	<input type="hidden" value="<?php echo $url['1']?>" name="form_id" />
	<input type="submit" value="Sim, quero deletar" />
	<input type="button" value="Cancelar" onclick="location.href='<?=ROOT?>system-form'" style="cursor:pointer;" />

</form>
</div>