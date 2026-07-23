<?php
//Remove o join com tabela
if($ITEM != '' && $ITEM != $_SESSION['geraJoinTabela'][$_GET[':item']]['ITEM_JOIN']){unset($_SESSION['geraJoinTabela'][$_GET[':item']]);}


?>

<?php
if($MAP['listar_pagina']!=''):
	if(is_file('admin/exe_system/'.$MAP['listar_pagina'].'.php')):
		include 'admin/exe_system/'.$MAP['listar_pagina'].'.php';
	else:
		echo 'arquivo inexistente <strong>('.$MAP['listar_pagina'].'.php)</strong>';
	endif;
else:?>



<div class="card mb-4">

	<?php

	if($EDIT!=''):

		if(!in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['edit'])){
			myHeader("Location:".ROOT."admin");		
			exit;
		}



	?>


		<div class="card-header pb-0">
	    	<h6 class="mb-0">Edição de <?php echo $configTableList->nome;?></h6>
	    	<small><?php echo $MAP['legenda'];?></small>
	    </div>
	    <div class="card-body px-0 pt-0 pb-2 mt-3 animated fadeIn">


	    	<?php loadFormNew($configTableList,$EDIT);?>
	    </div>

	<?php
	elseif($ADD != ''):

		if(!in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['add'])){

			myHeader("Location:".ROOT."admin");		
			exit;

		}
	?>
	    <div class="card-header pb-0">
	    	<h6 class="mb-0">Cadastro de <?php echo $configTableList->nome;?></h6>
	    	<small><?php echo $MAP['legenda'];?></small>
	    </div>
	    <div class="card-body px-0 pt-0 pb-2 mt-3">
	    	<?php loadFormNew($configTableList);?>
	    </div>

	<?php

		



	elseif($VIEW != ''):



		if(!in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['view'])){

			myHeader("Location:".ROOT."admin");		

			exit;

		}



	?>

		<div class="card-header pb-0">
	    	<h6 class="mb-0"><?php echo $configTableList->nome;?></h6>
	    	<small><?php echo $MAP['legenda'];?></small>
	    </div>
	    <div class="card-body px-0 pt-0 pb-2 mt-3">
	    	<?php viewForm($configTableList,$_GET[':view']);?>
	    </div>

	<?php



		



	else:

		if(!in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['list'])){
			myHeader("Location:".ROOT."admin");		
			exit;
		}

		?>






	   
		<div class="card-header pb-0">
	    	<h6 class="mb-0">Listagem de <?php echo $configTableList->nome;?></h6>
	    	<small><?php echo $MAP['legenda'];?></small>



		    <div class="row">

		        <div class="col-6">
		            <?php if($MAP['listar_pagina']==''):?>
		                <?php 

						if($configTableList->inserir != 1 && in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['add'])):?>

			                <a href="ROOT/adm-home?item=<?php echo $ITEM?>&new=1">

			                    <div class="bt_add"> 

			                        Adicionar Registro

			                    </div>

			                </a>

		                <?php endif;?>

		                <div class="iconBar">
		                    <?php if($configTableList->pdf == 1){ echo PDF($configTableList->nome);}?>
		                </div><!-- iconBar -->

		            <?php endif;?>

		        </div><!-- left_row_admin -->

		        <div class="col-6">

			        <?php 	
					if($MAP['listar_pagina']==''):
						require_once 'admin/filtro.php';
					endif;
					?>
				</div>
		    </div><!-- row_admin -->

	    </div>
	    <div class="card-body px-0 pt-0 pb-2 mt-3">
	    	<?php listFormNew($configTableList,'id DESC',$_GET['pgini'],$QUERY_FILTRO);?>
	    </div>
	   

	<?php endif;?>

</div>


<?php endif;?>