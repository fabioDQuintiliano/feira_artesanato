<?php
	global $PERFIL_PERMISSOES;

	$PERFIL_PERMISSOES = perfilUser();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>


	
	<?php require_once('admin_header.php');?>

 	
</head>



<body class="g-sidenav-show">

	<?php
	if(is_file('script/byform/'.removeCaracteres($configTableList->nome).'.php')){
		require('script/byform/'.removeCaracteres($configTableList->nome).'.php');
	}
	?>

	<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
	    <div class="sidenav-header">
	      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
	      <div class="navbar-brand m-0" >
		<a class="link-logo" href="ROOT/admin">
	        	<img src="ROOT/images/logoAdmin.png" class="navbar-brand-img h-100" alt="main_logo">
	        	<span class="ms-1 font-weight-bold"><?=PROJETO_NOME?></span>
			</a>
	      </div>
	    </div>
	    <hr class="horizontal dark mt-0">


		<?php require_once('menu.php');?>
	</aside>
	<main class="main-content position-relative mt-1  ">
		<?php require_once('admin_navbar.php');?>

		<div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
					<?php require_once('admin_body.php');?>
				</div>
			</div>
		</div>

	</main>
	<?php require_once('admin_footer.php');?>
 	<script src="ROOT/admin/template/soft-ui-dashboard-main/assets/js/soft-ui-dashboard.js?v=1.0.3"></script>
</body>


</html>
