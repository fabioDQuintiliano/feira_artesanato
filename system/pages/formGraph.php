<?php
if(count($_POST) > 0){
	$form = DB::read('system_inputs');
	$form->system_form = $_GET[':form'];
	$form->load();

	$array_replace = array('pWidth','mWidth','ggWidth','gWidth','autoWidth');
	$array_troca = array('','','','','');

	if($form->size()>0){do{
		
		$classe = str_replace($array_replace, $array_troca,$form->class);
		$form->class =  trim($classe).' '.trim($_POST['tamanho'][$form->id]);
		$form->secao = trim($_POST['sessao'][$form->id]);
		$form->linha_separadora = $_POST['linha'][$form->id]?$_POST['linha'][$form->id]:0;
		
		$form->update();
		
	}while($form->next());}
	
}
?>



<form method="post">

<div class="headPagesSys">
    <div class="headPagesSysInner">
    	<div class="headPagesSysInnerContent">
        	<a class="bt" href="<?=ROOT?>system-formInput/<?php echo $_GET[':form']?>">Voltar</a>
            <input type="submit" class="bt" value="Salvar" />
        </div><!-- headPagesSysInnerContent -->
    </div>
</div><!-- headPagesSys -->

<div id="containerGraph">
<?php
$formulario = $_GET[':form'];
$aux = DB::read('system_inputs');
$aux->system_form = $formulario;
$aux->exb_cadastro = 1;
$aux->load('ordem');


if($aux->size()>0){do{
?>
<input type="text" class="valueSessao linhaSeparaSessao" placeholder="Insira o nome da sessão" name="sessao[<?php echo $aux->id?>]" value="<?php echo $aux->secao?>" 
style="display:<?php echo ($aux->secao !=''?'block':'none')?>" />
<div class="<?php echo $aux->class?>">
	<input type="hidden" class="valueTamanho" name="tamanho[<?php echo $aux->id?>]" value="<?php echo $aux->class?>" />
	<input type="hidden" class="valueLinha" name="linha[<?php echo $aux->id?>]" value="<?php echo $aux->linha_separadora?>" />
	
    
    <label><?php echo $aux->nome?></label>
    <br />
	<p class="opcsGraph">
        <a class="itemT" opc="pWidth">P</a>
        <a class="itemT" opc="mWidth">M</a>
        <a class="itemT" opc="gWidth">G</a>
        <a class="itemT" opc="ggWidth">GG</a>
        <a class="itemT" opc="autoWidth">Auto</a>
        <br />
        <a class="itemL" opc="">Quebra</a>
        <a class="itemS" opc="">Sessão</a>
    </p><!-- opcsGraph -->
</div>
<?php if($aux->linha_separadora == 1){?>
	<div class="clear_system"></div>
<?php }?>
<?php	
}while($aux->next());}

?>

</div><!-- containerGraph -->
</form>
<script>
$(function(){
	
	$('.itemT').click(function(){
		
		var elemento = $(this).parent('.opcsGraph').parent('div');
		elemento.attr('class',$(this).attr('opc'));
		elemento.find('.valueTamanho').val($(this).attr('opc'));
		
	});
	$('.itemL').click(function(){
		
		var elemento = $(this).parent('.opcsGraph').parent('div');
		if(elemento.next().hasClass('clear_system')){
			elemento.next().remove();
			elemento.find('.valueLinha').val(0);

		}else{
			elemento.after('<div class="clear_system"></div>');	
			elemento.find('.valueLinha').val(1);
		}	
		
	});
	
	$('.itemS').click(function(){
		var elemento = $(this).parent('.opcsGraph').parent('div').prev('input');
		
		if(elemento.is(':visible')){
			elemento.hide();
			elemento.val('');
		}else{
			elemento.css('display','block');	
		}
		
	});
	
});

</script>
