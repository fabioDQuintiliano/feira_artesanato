<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administração</title>

<link rel="shortcut icon" href="ROOT/images/ico.png" />

<script src="ROOT/script/jquery-1.9.0.js"></script>
<script src="ROOT/script/jquery-migrate-1.0.0.js"></script>


<script type="text/javascript" src="ROOT/script/prettyPhoto.js"></script>
<script type="text/javascript" src="ROOT/script/jquery.meio.mask.js"></script>

<script type="text/javascript" src="ROOT/script/jquery-ui.js"></script>
<script type="text/javascript" src="ROOT/script/date.js"></script>
<script type="text/javascript" src="ROOT/script/script.js"></script>
<script type="text/javascript" src="ROOT/script/Chart.min.js"></script>


<link href="ROOT/admin/css-admin.css" rel="stylesheet" type="text/css" />
<link href="ROOT/css/jquery.ui.all.css" rel="stylesheet" type="text/css" />
<link href="ROOT/css/prettyPhoto.css" rel="stylesheet" type="text/css" />


(-((--HEAD_INCLUDES--))-)

<?php
global $MAP;
$urlAnterior = $_SESSION['urlAnterior'];
$MAP['urlAnterior'] = $urlAnterior;
$_SESSION['urlAnterior'] = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'];

?>

<script>
var ROOT = 'ROOT/';

// desabilita a navegação por backspace
$(document).unbind('keydown').bind('keydown', function (event) {
    var doPrevent = false;
    if (event.keyCode === 8) {
		
        var d = event.srcElement || event.target;
        if ((d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'PASSWORD' || d.type.toUpperCase() === 'FILE')) 
             || d.tagName.toUpperCase() === 'TEXTAREA') {
            doPrevent = d.readOnly || d.disabled;
			
        }
        else {
            doPrevent = true;
			location.href='<?php echo $MAP['urlAnterior']?>';
        }
		
		
		
    }

    if (doPrevent) {
        event.preventDefault();
    }
});
/*
//impede o usuário de retornar para a página anterior - a menos que use links internos
function noBack(){window.history.forward()}

noBack();

window.onload=noBack;

window.onpageshow=function(evt){if(evt.persisted)noBack()}

window.onunload=function(){void(0)}
*/
/*----*/

$(function() {
	
	$("a[rel^='prettyPhoto']").prettyPhoto({social_tools:''});
	
	
	$(".itens_menu_admin").on('click',function(){
		if($(this).hasClass('ativaHide') == true){
			
			
			if($("#container_principal").css('display')=='none'){
				$("#container_principal").fadeIn(100);
			}else{
				$("#container_principal").fadeOut(100);	
			}
			//vague.toggleblur();
		}else{
			$("#container_principal").fadeOut(100);
			//vague.blur();
		}
		
	});
		
	
	
	

	$(".tableList thead th").each(function(){
		if($(this).html()=="Código"){
			$(this).css('width',50);
		}
		
	});
	
	
	$( ".Datepicker" ).datepicker({
		showOn: "button",
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		nextText: 'Próximo',
		prevText: 'Anterior',		
		numberOfMonths: 3,
		buttonImage: "<?=ROOT?>images/admin/calendar.gif",
		buttonImageOnly: true
	}); 
	
	$( ".mask_type_data" ).datepicker({
		showOn: "button",
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		nextText: 'Próximo',
		prevText: 'Anterior',		
		numberOfMonths: 3,
		buttonImage: "<?=ROOT?>images/admin/calendar.gif",
		buttonImageOnly: true
	}); 
	$('.mask_type_data').setMask('99/99/9999');
	$('.mask_type_hora').setMask('99:99');
	$('.mask_type_cep').setMask('99.999-999');
	$('.mask_type_cpf').setMask('999.999.999-99');
	$('.mask_type_rg').setMask('**.***.***-*');
	$('.mask_type_cnpj').setMask('99.999.999/9999-99');
	$('.mask_type_decimal').setMask('decimal');
	
});


/*function de confirm*/
/*function confirma(pergunta,acao,funcao){
	if(pergunta != null){
		$("#quest_box").html(pergunta);	
		$("#confirm_box").show();
		$("#confirm_confirmar").click(function(){
			
			if(funcao){
				eval(funcao+"()")
				funcao = null;
				$("#confirm_box").hide();
				$("#quest_box").html('');	
			}else{
				location.href='ROOT/'+acao;
			}
		});
		$("#confirm_cancelar").click(function(){
			$("#confirm_box").hide();
			$("#quest_box").html('');	
		});
		
	}
	return false;
	 
}/**/

var FUNCTION;

function conf(pergunta,fn){
	FUNCTION = fn;
	if(pergunta != null){
		
		$("#quest_box").html(pergunta);	
		$("#confirm_box").show();

	}
	return false;
}
$(function(){

	$(".confirm_confirmar").click(function(){
		if(FUNCTION()){
			FUNCTION = '';
		}
		
		$("#confirm_box").hide();
		$("#quest_box").html('');
		return false;	
	});
	
	$("#confirm_cancelar").click(function(){
		$("#confirm_box").hide();
		$("#quest_box").html('');
		return false;	
	});
});
function wait(){
	
	$("#waitLoad").show();
}
</script>
</head>

<body>
	<div id="waitLoad">
    	<div id="loadingImg"><img src="ROOT/images/admin/loader_white.gif" width="40px" height="40px" /></div>
    </div><!-- waitLoad -->
	<div id="confirm_box">
    	<div id="quest_box"></div>
        <div id="bt_box_confirm">
        	<input type="button" value="Sim" id="confirm_confirmar" class="confirm_confirmar" >
        	<input type="button" value="Não" id="confirm_cancelar" >
        </div>
    </div>

    <?php
    if($_SESSION['resposta_ok']!=''):
	?>
    <div class="mensagemSucesso">
        <table align="center" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><img src="ROOT/images/admin/ok_ico.png" /></td>
                <td><?php echo $_SESSION['resposta_ok'];?></td>
            </tr>
        </table>
        <script>
     	$(function(){
			setTimeout(function(){
				$('.mensagemSucesso').slideUp();
			},10000);	
		});
    	</script>
    </div><!-- mensagemSucesso -->
    <?php
		unset($_SESSION['resposta_ok']);
    endif;
	?>
        [CONTENT-PLACE]
</body>
</html>
<style type="text/css">
html{ background:#fff;}
body{ background:#fff;}
</style>