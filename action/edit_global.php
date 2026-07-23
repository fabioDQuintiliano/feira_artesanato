<?php
if(!empty($_POST)):


	if(!empty($_POST['inside_formId'])):

	

		$q = new Model;

		require_once("tables/def_".$_POST['inside_formId'].".php");

		$form = $TABLE_DEF;		

		

		//verifica se já existe registro do formulario "inside"

		if(!empty($_POST['inside_formIdEdit'])):

			//EXECUTA A FUNÇÃO DE PRE-UPDATE(SE EXISTIR)

			if(!empty($form['preupdate']) && function_exists($form['preupdate'])):

			

				$form['preupdate']($_POST['inside_formIdEdit']);

				

			endif;

		else:

			//EXECUTA A FUNÇÃO DE PRE-INSERT(SE EXISTIR)

			if(!empty($form['preinsert']) && function_exists($form['preinsert'])):

			

				$form['preinsert']($_POST);

				

			endif;

		endif;

		

		$dados = array();

		foreach($_POST as $k=>$v):

			if(substr($k,0,7)=='inside_'):

				

				$input = $TABLE_DEF_INPUT[substr($k,7)];

				if(!empty($input)):

					if(!empty($input) && preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $v)):

						

						$dado = substr($v,6,4).'-'.substr($v,3,2).'-'.substr($v,0,2);

						$dados[substr($k,7)] = $dado;

						

					elseif(!empty($input) && $input['mascara'] == 'decimal'):

					

						$dado = str_replace(array('.',','),array('','.'),$v);

						$dados[substr($k,7)] = $dado;

					elseif(is_array($v)):

					

						$dados[substr($k,7)] = serialize($v);

						

					else:

						$dados[$k] = addslashes($v);

					endif;

					

				endif;

			endif;

		endforeach;

		



		/*

		-- adiciona a data de edição automaticamente se o campo 'edited_on' existir  na tabela

		*/

		$campos_tabela = $q->listaCampos($form['tabela']);

			

		for($i = 0;$i<=count($campos_tabela);$i++):

		

			if($campos_tabela[$i]['Field'] == 'edited_on' && !empty($_POST['inside_formIdEdit'])):

			

				$dados['edited_on'] = date("Y-m-d H:i:s");

				

			elseif($campos_tabela[$i]['Field'] == 'created_on' && empty($_POST['inside_formIdEdit'])):

				

				$dados['created_on'] = date("Y-m-d H:i:s");

			

			endif;

		

		endfor;

			

			

		if(!empty($_POST['inside_formIdEdit'])):	

			$q->update($form['tabela'], $dados, "id = '".$_POST['inside_formIdEdit']."'");

			$idQueryInside = $_POST['inside_formIdEdit'];

		else:

			$idQueryInside = $q->insert($form['tabela'], $dados);

		endif;

		

		/*

		//se existir este $_POST, chama a função de pos update do gerador de parcelas

		if(!empty($_POST['GP_parcelas_list_gera_posupsave'])){

		

			auto_parcelas_pos_update($form,$_POST['inside_formIdEdit'],$_POST);	

			

		}

		*/

		

		

		if(!empty($_POST['inside_formIdEdit'])):		

			//chama a fumcao de pos update.

			if(!empty($form['posupdate']) && function_exists($form['posupdate'])):

				

				$form['posupdate']($_POST['inside_formIdEdit']);

		

			endif;	

		else:

			//chama a funcao de pos insert.

			if(!empty($form['posinsert']) && function_exists($form['posinsert'])):

				

				$form['posinsert']($idQueryInside);

		

			endif;	

		endif;

	

		//pasa o id da query do prmulário para um item de post

		$_POST['idFormInside'] = $idQueryInside;



	endif;





	/*

	----- trata do formurario principal.-----------------------------------------------------------------------------------------------------------------------------------------------

	----- trata do formurario principal.-----------------------------------------------------------------------------------------------------------------------------------------------

	*/

	global $MAP,$PERFIL_PERMISSOES;

	$q = new Model;



	$configTableList = getInfoItem($_POST['formId']);

	

	$PERFIL_PERMISSOES = perfilUser();

	

	if(!in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['edit'])){

		myHeader("Location:".ROOT."admin");		

		exit;

	}

	

	

	$form = $configTableList->TABLE_DEF;

	$TABLE_DEF_INPUT = $configTableList->TABLE_DEF_INPUT;





	$retFunctionPre = true;

	//EXECUTA A FUNÇÃO DE PRE-UPDATE(SE EXISTIR)


	if(!empty($form['preupdate']) && function_exists($form['preupdate'])):

		$retFunctionPre = $form['preupdate']($_POST['formIdEdit']);

	endif;

	



	if($retFunctionPre !== false):

		$dados = array();

		foreach($_POST as $k=>$v):

			

			

			$input = $TABLE_DEF_INPUT[$k];

		

			if(!empty($input)):

				if(!empty($input) && !is_array($v) && preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $v)):

					

					$dado = substr($v,6,4).'-'.substr($v,3,2).'-'.substr($v,0,2);

					$dados[$k] = $dado;

					

				elseif(!empty($input) && $input['mascara'] == 'decimal'):

				

					$dado = str_replace(array('.',','),array('','.'),$v);

					$dados[$k] = $dado;

				

				elseif(is_array($v)):

				

					$dados[$k] = serialize($v);

					

				else:

					$dados[$k] = addslashes($v);

				endif;

				

				

			endif;

			

		

		endforeach;

		

	

		//exit;

		

		unset($dados['formId']);

		unset($dados['formIdEdit']);

		

		

		

		/*

		-- adiciona a data de edição automaticamente se o campo 'edited_on' existir  na tabela

		*/

		$campos_tabela = $q->listaCampos($form['tabela']);

			

		for($i = 0;$i<=count($campos_tabela);$i++):

		

			if($campos_tabela[$i]['Field'] == 'edited_on'):

			

				$dados['edited_on'] = date("Y-m-d H:i:s");

			

			endif;

		

		endfor;

		

		if(!empty($dados))

		$q->update($form['tabela'], $dados, "id = '".$_POST['formIdEdit']."'");

		

		

		//se existir este $_POST, chama a função de pos update do gerador de parcelas

		if(!empty($_POST['GP_parcelas_list_gera_posupsave'])){

		

			auto_parcelas_pos_update($form,$_POST['formIdEdit'],$_POST);	

			

		}

		

		//se existir este $_POST, chama a função de pos update do componente personalizado

		if(!empty($_POST['pos_update_componente'])){

		

			$_POST['pos_update_componente']($form,$_POST['formIdEdit'],$_POST);	

			

		}

		

		//mensagem de retorno

		$_SESSION['resposta_ok'] = 'Alterado com sucesso!';

		

		

		//chama a fumcao de pos update.

		if(!empty($form['posupdate']) && function_exists($form['posupdate'])):

			

			$form['posupdate']($_POST['formIdEdit']);

	

		endif;

	

		if($_POST['componente__mapear'] && count($_POST['componente__mapear'])>0){

			$COMPONENTES_POSSALVA = $_POST['componente__mapear'];

			for($ic = 0;$ic<=count($COMPONENTES_POSSALVA);$ic++){

				

				if($COMPONENTES_POSSALVA[$ic] != ''){

					

					$DADOS_COMP = explode('__',$COMPONENTES_POSSALVA[$ic]);

					

					$CLASS_EXIBE_COMPONENTE = 'Componente__'.$DADOS_COMP[0];

					if(!class_exists($CLASS_EXIBE_COMPONENTE)){

						include "componente/".$DADOS_COMP[0].".php";

					}

					$EXIBE_COMPONENTE = new $CLASS_EXIBE_COMPONENTE;

					//passa como parametro o nome da tabela, o id do registro e o valor do campo.

					if(method_exists($EXIBE_COMPONENTE,'update')){


						//var_dump($EXIBE_COMPONENTE);
						$EXIBE_COMPONENTE->update($_POST['formIdEdit'],$form['tabela'],$DADOS_COMP[1]);
					//	exit;

					}

						

				}

					

			}

			

			

		}

		

		

		

	endif;


	if($_POST['onsucesso']):

		echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=".$_POST['onsucesso']."'>";

		//header('location:'.$_POST['onsucesso'].'');

		exit;	

	endif;



	

endif;

?>