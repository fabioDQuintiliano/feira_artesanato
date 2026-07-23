// JavaScript Document
function confirma(pergunta, url){
	decisao = confirm(pergunta);
	if (decisao){
		window.location=url;
	} else {
	}
}