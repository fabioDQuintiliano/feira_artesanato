<?php

if(!empty($_POST)):





	if(!empty($_POST['inside_formId'])):

	

		$q = new Model;

		//$form = $q->read("system_form","id = '".$_POST['inside_formId']."'");

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

					

					else:

						$dados[substr($k,7)] = $v;

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

			elseif($campos_tabela[$i]['Field'] == 'txtid' && empty($_POST['inside_formIdEdit'])):

				

				$dados['txtid'] = gen_uuid();

			

			endif;

		

		endfor;

			

			

		if(!empty($_POST['inside_formIdEdit'])):	

			$q->update($form['tabela'], $dados, "id = '".$_POST['inside_formIdEdit']."'");

			$idQueryInside = $_POST['insede_formIdEdit'];

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

			//chama a fumcao de pos insert.

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

	



	if(!in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['add'])){

		if($_SESSION['open_for_add'] != 'true'){
			myHeader("Location:".ROOT."admin");		
			exit;
		}

	}

	$form = $configTableList->TABLE_DEF;

	$TABLE_DEF_INPUT = $configTableList->TABLE_DEF_INPUT;



	$retFunctionPre = true;

	

	//EXECUTA A FUNÇÃO DE PRE-INSERT (SE EXISTIR)

	if(!empty($form['preinsert']) && function_exists($form['preinsert'])):

	

		$retFunctionPre = $form['preinsert']();

		

	endif;



	if($_POST['friendly_url'] != ''){

		//echo $_POST['friendly_url'];

		

		$_POST['friendly_url'] = removeCaracteres($_POST[$_POST['friendly_url']]);

	}

	





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

				

				else:

					$dados[$k] = addslashes($v);

				endif;

			endif;

			

			if(strpos($k,'joinTabelannFild') !== false):

				$djnn = explode('___',$k);

				$dados[$djnn[1]]=$v;

				

			endif;

			

		endforeach;

		

		

		unset($dados['formId']);

		

		/*

		-- adiciona a data de inserção automaticamente se o campo 'created_on' existir  na tabela

		*/

		

		if(verificaCampoTabela($form['tabela'],'created_on')){

			$dados['created_on'] = date("Y-m-d H:i:s");	

		}


		if(verificaCampoTabela($form['tabela'],'txtid')){

			$dados['txtid'] = gen_uuid();	

		}

		/*

		-- adiciona o usuario logado ou registro.

		*/

		if(verificaCampoTabela($form['tabela'],'created_by')){

			$dados['created_by'] = $_SESSION['user_id'];	

		}

		$id = $q->insert($form['tabela'], $dados);

		

		//se existir este $_POST, chama a função de pos update do gerador de parcelas

		if(!empty($_POST['GP_parcelas_list_gera_posupsave'])){

			auto_parcelas_pos_insert($form,$id,$_POST);	

		}

		

		

		//se existir este $_POST, chama a função de pos update do componente personalizado

		if(!empty($_POST['pos_update_componente'])){

		

			$_POST['pos_update_componente']($form,$_POST['formIdEdit'],$_POST);	

			

		}

	
		

		//mensagem de retorno

		$_SESSION['resposta_ok'] = 'Adicionado com sucesso!';

		

		// Componentes de campo (afterInsert/save) antes do posinsert do form
		if(!empty($_POST['componente__mapear']) && is_array($_POST['componente__mapear'])){

			\Sistema\Admin\ComponenteLoader::runAfterSave(
				$_POST['componente__mapear'],
				$id,
				$form['tabela'],
				'insert'
			);

		}

		//EXECUTA A FUNÇÃO DE POS-INSERT (SE EXISTIR)

		if(!empty($form['posinsert']) && function_exists($form['posinsert'])):

		

			$form['posinsert']($id);

			

		endif;

		

		

	endif;

	

	

	

	unset($_SESSION['open_for_add']);

	if($_POST['onsucesso']):

		echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=".$_POST['onsucesso']."'>";

		//header('location:'.$_POST['onsucesso'].'');

		exit;	

	endif;

	

	

endif;

?>

