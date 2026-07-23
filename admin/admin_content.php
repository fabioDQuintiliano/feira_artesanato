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
	    	<div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
	    		<div>
			    	<h6 class="mb-0">Edição de <?php echo htmlspecialchars($configTableList->nome, ENT_QUOTES, 'UTF-8');?></h6>
			    	<small><?php echo htmlspecialchars((string)($MAP['legenda'] ?? ''), ENT_QUOTES, 'UTF-8');?></small>
	    		</div>
	    		<a href="ROOT/adm-home?item=<?php echo htmlspecialchars($ITEM, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm btn-outline-secondary mb-0">
	    			<i class="fas fa-arrow-left me-1"></i> Voltar
	    		</a>
	    	</div>
	    </div>
	    <div class="card-body pt-3 pb-3 animated fadeIn">
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
	    	<div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
	    		<div>
			    	<h6 class="mb-0">Cadastro de <?php echo htmlspecialchars($configTableList->nome, ENT_QUOTES, 'UTF-8');?></h6>
			    	<small><?php echo htmlspecialchars((string)($MAP['legenda'] ?? ''), ENT_QUOTES, 'UTF-8');?></small>
	    		</div>
	    		<a href="ROOT/adm-home?item=<?php echo htmlspecialchars($ITEM, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm btn-outline-secondary mb-0">
	    			<i class="fas fa-arrow-left me-1"></i> Voltar
	    		</a>
	    	</div>
	    </div>
	    <div class="card-body pt-3 pb-3">
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
	    	<div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
	    		<div>
			    	<h6 class="mb-0"><?php echo htmlspecialchars($configTableList->nome, ENT_QUOTES, 'UTF-8');?></h6>
			    	<small><?php echo htmlspecialchars((string)($MAP['legenda'] ?? ''), ENT_QUOTES, 'UTF-8');?></small>
	    		</div>
	    		<a href="ROOT/adm-home?item=<?php echo htmlspecialchars($ITEM, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm btn-outline-secondary mb-0">
	    			<i class="fas fa-arrow-left me-1"></i> Voltar
	    		</a>
	    	</div>
	    </div>
	    <div class="card-body pt-3 pb-3">
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
	    	<h6 class="mb-0">Listagem de <?php echo htmlspecialchars($configTableList->nome, ENT_QUOTES, 'UTF-8');?></h6>
	    	<small><?php echo htmlspecialchars((string)($MAP['legenda'] ?? ''), ENT_QUOTES, 'UTF-8');?></small>

		    <div class="adm-card-toolbar">
		        <div class="d-flex flex-wrap align-items-center gap-2">
		            <?php if($MAP['listar_pagina']==''):?>
		                <?php 
						if($configTableList->inserir != 1 && in_array(removeCaracteres($configTableList->nome),$PERFIL_PERMISSOES['add'])):?>
			                <a href="ROOT/adm-home?item=<?php echo $ITEM?>&new=1" class="btn bg-gradient-info btn-sm mb-0">
			                    <i class="fas fa-plus me-1"></i> Adicionar registro
			                </a>
		                <?php endif;?>
		                <div class="iconBar d-inline-flex align-items-center">
		                    <?php if($configTableList->pdf == 1){ echo PDF($configTableList->nome);}?>
		                </div>
		                <div class="adm-filtro-slot">
					        <?php 	
							require_once 'admin/filtro.php';
							?>
						</div>
		            <?php endif;?>
		        </div>
		    </div>
	    </div>
	    <div class="card-body pt-3 pb-3">
	    	<?php listFormNew($configTableList,'id DESC',$_GET['pgini'],$QUERY_FILTRO);?>
	    </div>
	   

	<?php endif;?>

</div>


<?php endif;?>