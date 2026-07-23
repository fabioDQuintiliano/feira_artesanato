<?php
function checa_novas_postagens(){

	$sinc = new \Sistema\Sinc();
	$sinc->sincronizaImagens();

}
function acao_publicacoes($lista){




	if(logado_no_perfil(1) && $lista && sizeof($lista) > 0){


		foreach ($lista as $publicacao) {


			
			$dao = DAO::Postagem()->_id($publicacao['id'])->_loadAll();


		
			if($dao->size()){
				$aprovado = $publicacao['aprovado']*1;


				if($aprovado){

					$dao->exibir = 1;
					$dao->inativo = 0;
					$dao->aprovado_em = date('Y-m-d H:i:s');

				}else{

					$dao->exibir = 0;
					$dao->inativo = 1;
					$dao->aprovado_em = date('Y-m-d H:i:s');

				}

				$dao->update();

			}
		}

	}


	return true;

}
