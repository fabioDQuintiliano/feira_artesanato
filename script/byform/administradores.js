// JavaScript Document
$(function(){

	$("#repetirSenhaInput").val($("#senha").val());
	$("#repetirSenhaInput").focusout(function(){
		if($("#senha").val()!=$(this).val()){
			alerta("As senhas devem ser iguais");	
			$("#senha").focus();
			$("#repetirSenhaInput").val('');
		}
			
	})
	
	$("#senha").focusout(function(){
		if($("#repetirSenhaInput").val()!=$(this).val() && $("#repetirSenhaInput").val()!=''){
			alerta("As senhas devem ser iguais");	
			$("#senha").focus();
			$("#repetirSenhaInput").val('');
		}
			
	})	
	$("#senha").keydown(function(){
		$("#repetirSenhaInput").val('');
	});
	$("#form_system_admin").submit(function(){
		if($("#senha").val()!=$("#repetirSenhaInput").val()){
			alerta("As senhas devem ser iguais");	
			return false;
			
		}
		
	});
});