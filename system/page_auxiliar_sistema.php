<style type="text/css">
#open_help{ background:url(<?php echo ROOT?>system/img/open_help_ico.png) no-repeat; height:20px; width:26px; position: fixed; left:5px; bottom:5px; cursor:pointer; z-index:99999;}
#aux_sidebar{ background:url(<?php echo ROOT?>system/img/back_alpha.png) repeat; overflow:hidden; position: fixed; height:0px; width:100%; bottom:0px; right:0px; z-index:9999; white-space:nowrap;}
.aux_itens_ico{ float:left; margin:15px 5px 5px 15px; cursor:pointer; border:none;}
.aux_itens_ico img{ border:none; height:48px;}
</style>
<script>
if (typeof window.jQuery !== 'undefined') {
$(function(){
	$("#open_help").click(function(){
		
		if($("#aux_sidebar").css('height').replace('px','') > 0){
			$("#aux_sidebar").animate({height:'0'},300);
		}else{
			$("#aux_sidebar").animate({height:'150'},200);
		}
	});
	$("#aux_sidebar").mouseleave(function(){
			$("#aux_sidebar").animate({height:'0'},200);
	})
	
	
	
	document.onkeyup=function(e){
		
		if(e.which != 32){
			pressedCtrl = false;
		}
	}
	
	document.onkeydown=function(e){
		
		
		
	if(e.which == 17)
		pressedCtrl = true; 

	if(e.which == 32 && pressedCtrl == true) { 
		//pressedCtrl = false; 
		if($("#aux_sidebar").css('height').replace('px','') > 0){
			$("#aux_sidebar").animate({height:'0'},300);
		}else{
			$("#aux_sidebar").animate({height:'150'},200);
		}
	}
}
	
});
}
</script>

<div id="aux_sidebar">
	
    <div class="aux_itens_ico">
    	<a target="_blank" href="<?php echo ROOT?>admin" title="Página inicial do Sistema">
    	<img src="<?php echo ROOT?>system/img/home_ico.png" />
        </a>
    </div>

    
    <div class="aux_itens_ico">
    	<a target="_blank" href="<?php echo ROOT?>system-form" title="Formulários">
    	<img src="<?php echo ROOT?>system/img/form_ico.png" />
        </a>
    </div>

    <div class="aux_itens_ico">
    	<a target="_blank" href="<?php echo ROOT?>system-addform" title="Adicionar Formulário">
    	<img src="<?php echo ROOT?>system/img/form_add_ico.png" />
        </a>
    </div>
    
    <?php if($MAP['id_form_list'] != ''):?>
    <div class="aux_itens_ico">
    	<a target="_blank" href="<?php echo ROOT?>system-addform/<?=$MAP['id_form_list']?>/" title="Editar Formulário Atual">
    	<img src="<?php echo ROOT?>system/img/form_edit_ico.png" />
        </a>
    </div>
    <?php endif;?>
	<?php if($MAP['id_form_list'] != ''):?>
    <div class="aux_itens_ico">
    	<a target="_blank" href="<?php echo ROOT?>system-formInput/<?=$MAP['id_form_list']?>" title="Listar itens">
    	<img src="<?php echo ROOT?>system/img/form_itens_ico.png" />
        </a>
    </div>
	<?php endif;?>
    
    
    <div class="aux_itens_ico">
    	<a target="_blank" href="<?php echo ROOT?>system-rebuild" title="Regenerar artefatos do painel">
    	<img src="<?php echo ROOT?>system/img/atualizar_ico.png" />
        </a>
    </div>
    
    <div class="aux_itens_ico" style="float:right;">
    	<a target="_blank" href="<?php echo ROOT?>publish.php" title="Atualizar">
    	<img src="<?php echo ROOT?>system/img/atualizar_ico.png" />
        </a>
    </div>
    
</div><!-- aux_sidebar -->

<div id="open_help">
</div>