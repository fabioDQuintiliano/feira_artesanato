<div class="headPagesSys">
    <div class="headPagesSysInner">
    	<div class="headPagesSysInnerContent">
        	<a class="bt" href="<?=ROOT?>system-addinput/<?php echo $url[1];?>/?iframe=true&width=800&height=100%" rel="prettyPhoto">Adicionar Item</a>
        	<a href="<?=ROOT?>system-formGraph?form=<?php echo $url[1];?>">
            <input type="button" class="bt" value="Edtor Gráfico" />
            </a>
        </div><!-- headPagesSysInnerContent -->
    </div>
</div><!-- headPagesSys -->


<ul id="ordena">
<?php
if(isset($url[2]) && $url[2] == 'del' && !empty($url['3'])):
	$q->delete("system_inputs", "id = '".$url[3]."'");
	header('location:'.ROOT.'system-formInput/'.$url[1]);
endif;

$dados = $q->read("system_inputs", "system_form = '".$url[1]."'", null,null, "ordem");
foreach($dados as $l):
?>
<li>
	<div class="list_input" bloco="<?php echo $l['id']?>">
    	<div class="list_input_del" onclick="confirma('Tem certeza que deseja remover este campo?','<?=ROOT?>system-formInput/<?=$url[1]?>/del/<?=$l['id']?>/');"></div>
        
        <a href="<?=ROOT?>system-addinput/<?php echo $url[1];?>/<?php echo $l['id'];?>/?iframe=true&width=800&height=100%" rel="prettyPhoto">
    		<div class="list_input_edt"></div>
        </a>
        
    	<span class="list_input_titulo"><?=$l['nome']?></span>
        <br />

        <?php
        if($l['type']=='image'):
			echo '<img src="'.ROOT.$l['valor'].'" style="max-height:40px; max-width:40px;" />';
		endif;
		?>
        <br />
        <br />

        - <?=$l['type']?>
        <br />
        
        - <?php if(!empty($l['join_tabela'])):echo 'Join';else: echo linha('Valor: <strong>',$l['valor'],'</strong>');endif;?>
        <br />
        
        <?=linha('- Mascara: <strong>',$l['mascara'],'</strong>')?>

	</div>
 </li>
<?php
endforeach;
?>
</ul>



<script>
  $(function() {
    $( "#ordena" ).sortable({
      revert: true,
	  beforeStop: function( event, ui ) {
		  var posicao = 0;
		  $(".list_input").each(function(){
			 // alert(posicao);
			 //alert($(this).attr('bloco'));
			  $.post('<?php echo ROOT?>system/atualiza_posicao.php',{bloco:$(this).attr('bloco'),pos:posicao},function(o){
				  
				  });
			  posicao++;
		  })
		  
		  }
    });
  });
 </script>
