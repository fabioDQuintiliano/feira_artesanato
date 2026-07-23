// JavaScript Document

function verificaValidacao(form,aba){



	ret = 0;

	

	if(aba != '' && aba != null){

		aba = '.'+aba;	

	}else{

		aba = '';	

	}

	

	

	$('#'+form+' '+aba+' .validacao_text').each(function(){

		if($(this).val()=='' || $(this).val()=='0,00' || $(this).val()=='00/00/0000'){

			$(this).addClass('validacao_necessaria');

			$(this).parent('.system_item_form').find('label').addClass('validacao_necessaria_label')

			ret++;

		}else{

			$(this).removeClass('validacao_necessaria');

			$(this).parent('.system_item_form').find('label').removeClass('validacao_necessaria_label')

		}

	})

	

	

	$('#'+form+' '+aba+' .validacao_select').each(function(){

		if($(this).val()==''||$(this).val()=='-1'){

			$(this).addClass('validacao_necessaria');

			$(this).parent('.system_item_form').find('label').addClass('validacao_necessaria_label')

			ret++;

		}else{

			$(this).removeClass('validacao_necessaria');

			$(this).parent('.system_item_form').find('label').removeClass('validacao_necessaria_label')

		}

	})

	

	

	$('#'+form+' '+aba+' .validacao_lstp').each(function(){

		if($(this).val()==''||$(this).val()==0){

			

			id = $(this).attr('id')+'_LSTP';

			

			$("#"+id).addClass('validacao_necessaria');

			$("#"+id).parent('.system_item_form').find('label').addClass('validacao_necessaria_label')

			ret++;

		}else{

			id = $(this).attr('id')+'_LSTP';

			$("#"+id).removeClass('validacao_necessaria');

			$("#"+id).parent('.system_item_form').find('label').removeClass('validacao_necessaria_label')

		}

	})

	

	

	

	

	

	if(ret>0){

		$('.validacao_necessaria:eq(0)').focus();

		alerta('Preencha todos os campos abrigatórios');

		return false;

	}else{

		

		if(aba == ''){

			wait('on');

		}

		return true;	

	}

	

	

}







function number_format( number, decimals, dec_point, thousands_sep ) {



    var n = number, prec = decimals;

    n = !isFinite(+n) ? 0 : +n;

    prec = !isFinite(+prec) ? 0 : Math.abs(prec);

    var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;

    var dec = (typeof dec_point == "undefined") ? '.' : dec_point;

 

    var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

 

    var abs = Math.abs(n).toFixed(prec);

    var _, i;

 

    if (abs >= 1000) {

        _ = abs.split(/\D/);

        i = _[0].length % 3 || 3;

 

        _[0] = s.slice(0,i + (n < 0)) +

              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');

 

        s = _.join(dec);

    } else {

        s = s.replace('.', dec);

    }

 

    return s;

}



function dinheiro2float(val){

	if(val == ''){

		return 0;	

	}

	valor = val.replace('R$','').replace(/\./gi,'').replace(/\,/gi,'.')*1;

	return new Number (valor).toFixed(2);

}

function float2dinheiro(val){

	val = duasCasas(val);

	valor = String(val).replace(/\./gi,',');

	return  (valor);

	

}



function duasCasas(valor){

	return new Number (valor).toFixed(2);

}

function dateToSql(d,showTime=false){

	var date = [
	    d.getFullYear(),
	    ('00' + d.getMonth() + 1).slice(-2),
	    ('00' + d.getDate() + 1).slice(-2)
	].join('-');

	var time = [
	    ('00' + d.getHours()).slice(-2),
	    ('00' + d.getMinutes()).slice(-2),
	    ('00' + d.getSeconds()).slice(-2)
	].join(':');
	if(!showTime){
		return date;
	}

	return  dateTime = date + ' ' + time;
}
function addDias(date, days) {
  var result = new Date(date);
  result.setDate(result.getDate() + days);
  return result;
}
function addMes(datainfo,meses){

	

	dat = datainfo.split('/');

	datFormat = dat[2]+'-'+dat[1]+'-'+dat[0];

	d = Date.parse(datFormat);

	var data = d.add(meses).month();

	

	return data.toString('dd/MM/yyyy');

}



function alert(msg){

	alerta(msg);

}



function alerta(msg){

	wait('off')

	$("#waitAlertInnerMsgTxt").html(msg)

	$("#waitAlert").fadeIn(200);

}

function fechaAlert(){

	$("#waitLoad").hide();

	$("#waitAlert").hide();

	$("#waitAlertInnerMsgTxt").html('')

}







var dateDif = {

dateDiff: function(strDate1,strDate2){

return (((Date.parse(strDate2))-(Date.parse(strDate1)))/(24*60*60*1000)).toFixed(0);

}

}



function diasEntreDatas(dataInicial, dataFinal) 

{

	var mes, dataAtual, dataInicial, arrDataInicial, novaDataInicial, diasEntreDatas;

	mes = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

	

	arrDataFinal = dataFinal.split('/');

	arrDataInicial = dataInicial.split('/');

	novaDataInicial = mes[(arrDataInicial[1] - 1)] + ' ' + arrDataInicial[0] + ' ' + arrDataInicial[2];

	novaDataFinal = mes[(arrDataFinal[1] - 1)] + ' ' + arrDataFinal[0] + ' ' + arrDataFinal[2];

	diasEntreDatas = dateDif.dateDiff(novaDataInicial, novaDataFinal);

	return (diasEntreDatas);

}



function wait(aux,extra){

	if(aux == 'on' || aux == true){

		$("#waitLoad").show();

		if(extra == true){

			$('#loadingImg').hide();	

		}else{

			$('#loadingImg').show();	

		}

	}else{

		$("#waitLoad").fadeOut(150);	

	}

	

}
function setSizeWindow(){
	
}
function Get(theUrl)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}
/*--- JQUERY ---------------------------------------------------------------*/

$(function(){

	

	// $( "a" ).click(function( event ) {

	// 	if($(this).attr('href') != '' && $(this).attr('href') && $(this).attr('target') != '_blank' && $(this).attr('href').indexOf('tel:')<0){

			

	// 		event.preventDefault();

	// 		wait('on');

	// 		location.href = $(this).attr('href');

			

	// 	}

	 

	  

	// });	

	

	

	

	

	

	var cidVal = $("#cidade").val();
	var estVal = $("#estado").val();


	/*$("#cidade").html('<option value="">Selecione um estado</value>');

	$("#estado").live('change',function(){

		if($(this).val()!=''){

			$("#cidade").html('<option value="">Aguarde</value>');

		 	var valor = $(this).val();

			$.post(ROOT+'fn-buscaCidadesEst',{p1:valor,p2:cidVal},function(o){

				$("#cidade").html(o);

				$("#cidade").val(cidVal);	

			});	

			

		}

	})*/

	


	if(cidVal<=0 || cidVal == ''){
		$("select[name=cidade]").html('<option value="">Selecione um estado</value>');
	}
	$("select[name=estado]").live('change',function(){

		if($(this).val()!=''){

			

			$("select[name=cidade]").html('<option value="">Aguarde</value>');

		 	var valor = $(this).val();

			$.post(ROOT+'fn-buscaCidadesEst',{p1:valor,p2:cidVal},function(o){

				

				$("select[name=cidade]").html(o);	

			});	

			

		}

	})

	if(estVal > 0){
		$("#estado").trigger('change');
	}

	/*alert personalizado*/

	var htmlAlerts = '<div id="waitLoad"><div id="loadingImg"><img src="'+ROOT+'images/admin/loader_white.gif" width="40px" height="40px" /></div> </div><!-- waitLoad --><div id="waitAlert"><div id="waitAlertInnerMsg"><div id="waitAlertInnerMsgTxt"></div><div id="waitAlertInnerMsgBt" onclick="fechaAlert();">Ok</div></div></div><!-- waitAlert -->';

	

	if($('#waitAlert').length == 0){

		$('body').append(htmlAlerts);

	}

	

});



function ajax_load_class(classe,funcao, params={}){
	return new Promise((resolve,reject)=>{
		$.ajax({
		 method: "POST",
		 url: ROOT+"fn-ajax_load_class_function",
		 data:{p1:classe,p2:funcao,p3:JSON.stringify(params)},
		 dataType: 'json',
		})
		.done((ret)=>{
    		resolve(ret);
		})
		.fail((e) => {
		     reject(e)
		}); 
	})



	//	$.post(BASEDIR+'ajax-fn?function=ajax_load_class_function',{p1:classe,p2:funcao,p3:JSON.stringify(params)},after,"json");
}






function loadFuncao(funcao,params){
	return new Promise((resolve,reject)=>{
		$.ajax({
		 method: "POST",
		 url: ROOT+"fn-"+funcao,
		 data:params,
		 dataType: 'json',
		})
		.done((ret)=>{
    		resolve(ret);
		})
		.fail((e) => {
		     reject(e)
		}); 
	})


}
function confirma(t){

	return new Promise((resolve,reject)=>{
		bootbox.confirm(t, (result)=>{
	    	if(result){
	    		resolve();
	    	}else{
	    		reject();
	    	}
		})
	})
}
function toast(msg,titulo=''){
	$.toast({
	    heading: titulo,
	    text: msg,
	    position: 'bottom-right',
	    icon: 'success'
	})
}