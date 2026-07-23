
function loadShow(){
	$(".backdropFullPage").fadeIn(100);
}
function loadHide(){
	$(".backdropFullPage").fadeOut(100);
}

//-------------------
//insere uma tela de loading antes de redirecionar para outr página.
var BaseUrlAtualLoadingSetPast = window.location.protocol + "//" + window.location.host + "/" + window.location.pathname;
//if(BaseUrlAtualLoadingSetPast && (BaseUrlAtualLoadingSetPast.indexOf('admin_home.php')>=0 || BaseUrlAtualLoadingSetPast.indexOf('wrapper_home.php')>=0)){
	$(function(){
		
		if($(".backdropFullPage").length == 0){
			var img = '<?xml version="1.0" encoding="utf-8"?><svg width=\'100px\' height=\'100px\' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ripple"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><g> <animate attributeName="opacity" dur="2s" repeatCount="indefinite" begin="0s" keyTimes="0;0.33;1" values="1;1;0"></animate><circle cx="50" cy="50" r="40" stroke="#2980b9" fill="none" stroke-width="3" stroke-linecap="round"><animate attributeName="r" dur="2s" repeatCount="indefinite" begin="0s" keyTimes="0;0.33;1" values="0;22;44"></animate></circle></g><g><animate attributeName="opacity" dur="2s" repeatCount="indefinite" begin="1s" keyTimes="0;0.33;1" values="1;1;0"></animate><circle cx="50" cy="50" r="40" stroke="#3498db" fill="none" stroke-width="3" stroke-linecap="round"><animate attributeName="r" dur="2s" repeatCount="indefinite" begin="1s" keyTimes="0;0.33;1" values="0;22;44"></animate></circle></g></svg>';
			var html = '<div class="backdropFullPage" style="display:none;position: fixed;width: 100%;height: 100%;background: #000;z-index: 9999;opacity: 0.5;top: 0;left: 0;right: 0;bottom: 0;"><div style="position:absolute;top:50%;left:50%;margin-top:-50px; margin-left:-50px;">'+img+'</div></div>';
			$('body').prepend(html);
		}
	})
	function execAfterOk(){
		$(".backdropFullPage").show();
	}
	window.onbeforeunload = execAfterOk;
//}
$(document).click(function(event) {
   var elemento = event.target+"";
   if(elemento && elemento.indexOf("download.php") >=0){
   	setTimeout(function(){
   		$(".backdropFullPage").fadeOut();
   	},500)
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
		bootbox.setLocale('pt')

		bootbox.confirm(t, (result)=>{
	    	if(result){
	    		resolve();
	    	}else{
	    		reject();
	    	}
		})
	})
}
function alert(t){

	return new Promise((resolve,reject)=>{
		bootbox.setLocale('pt')

		bootbox.alert(t)
		resolve();
	})
}