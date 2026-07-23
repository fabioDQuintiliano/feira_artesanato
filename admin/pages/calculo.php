<!--[CONTAINER-container-admin]-->
<!--[PAGETITLE-Administracao]-->
<!--[PAGEDESCRIPTION-Descrição da página]-->
<!--[PAGEKEYWORDS-]-->
	<?php
	$pessoa = DB::read("system_admin");
	$pessoa->id = $_SESSION['user_id'];
	$pessoa->load();
	?>

	
    <div class="container_full">
    	<div class="row_admin">

				<div class="msgBemVindoInicial">Bem vindo.</div>
				<div class="legenda"></div>
			
        </div>    
    </div><!-- container_full -->

   
	<div id="inner_container_geral_admin">
	    <div class="container_full">



		</div>
	</div>
