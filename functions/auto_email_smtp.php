<?php

/*

--- EVIO DE E-MAIL SMTP

*/

function sendMail($para,$assunto,$mensagem,$anexo='',$de='')

{

    //DADOS SMTP

	

	

	$info = DB::read("system_config");

	$info->load();

	

    $smtp = trim($info->url_smtp);

    $usuario = trim($info->user_smtp);

    $senha = trim($info->pass_smtp);

	//$porta = 587;
	$porta = 25;

	

	if($de == ''){

		$de = trim($info->remetente_contato);

	}







	@include_once('classes/class.phpmailer.php');

	

	$mail             = new PHPMailer(); // defaults to using php "mail()"

	$mail->From       = $de;

	$mail->FromName   = (PROJETO_NOME);

	$mail->Subject    = ($assunto);
	$mail->do_debug = 1;

	//$mail->AltBody    = $mensagem; // optional, comment out and test

	$mail->MsgHTML(($mensagem));

	

	$para = explode(';',$para);

	for($i=0;$i<=count($para);$i++):

		if($para[$i]!=''){

			$mail->AddAddress($para[$i], $para[$i]);

		}

	endfor;

	



	if($anexo!=''){

		$mail->AddAttachment($anexo);  // attachment

	}



	if(!$mail->Send()) {

	  return "Erro " . $mail->ErrorInfo;

	} else {

	  return true;

	}



} 



?>