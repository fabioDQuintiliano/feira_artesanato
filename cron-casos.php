<?php
include_once('front_includes.php');


file_put_contents('arquivos/cron.txt', date("Y-m-d H:i:s"));
$limitBusca = 5000;
$executados = 0;
$caso = DB_Class::make("casos")->_loadAll("id LIMIT 15","IFNULL(notificado,0) = 0");
if($caso->size()){
	$pessoa = DB_Class::make("system_admin")->_id($caso->created_by)->_loadAll();
	do{
		$executados++;
		$aux = DB_Class::make("push_reg")->_loadAll("id LIMIT ".$limitBusca,"

				userid IN(SELECT id FROM system_admin WHERE perfil = 2 AND NOW() > proxima_notificacao )
				AND userid NOT IN(SELECT para FROM push_msg WHERE caso = '".$caso->id."')

			");
		$total = $aux->size();

		$mensagem = "Casos iJuris. Novo caso enviado. Acesse o aplicativo e responda agora mesmo!";
        $extra = "duvida__".$caso->id;


		if($aux->size()>0){
			do{
				$proxima_notificacao = addMinuto(date("Y-m-d H:i:s"),30);
				$pesAtu = DB_Class::make("system_admin")->_id($aux->userid);
				$pesAtu->proxima_notificacao = $proxima_notificacao;
				$pesAtu->update();

				enviaPush($aux->userid,$mensagem,$extra);
			}while($aux->next());
		}
		if($total < $limitBusca){
			$casoAtu = DB_Class::make("casos")->_id($caso->id)->_loadAll();
			$casoAtu->notificado = 1;
			$casoAtu->update();
		}
		if($executados >= $limitBusca){
			return;
			exit;
		}

	}while($caso->next());
}
?>		 