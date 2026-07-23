

    <?php
    
	/* busca as permissoes do usuário logado */

	global $PERFIL_PERMISSOES;

	$PERFIL_PERMISSOES = perfilUser();

	?>

	

    <div id="waitLoad">

    	<div id="loadingImg"><img src="ROOT/images/admin/loader_white.gif" width="40px" height="40px" /></div>

    </div><!-- waitLoad -->

    

	<div id="confirm_box">

    	<div id="quest_box"></div>

        <div id="bt_box_confirm">

        	<input type="button" value="Sim" id="confirm_confirmar" class="confirm_confirmar" >

        	<input type="button" value="Não" id="confirm_cancelar" >

        </div>

    </div>



	<!--BLANCKOFF-INICIO-->

	

	<?php 

	include 'exe_system/barraLateralAdmin.php';

	?>

	<div id="container_geral_admin">

    <div id="logo_emp">

        <?php

		echo '<a href="'.ROOT.'adm-inicial"><img src="ROOT/images/logoAdmin.png" style="border:none;" /></a>';

		?>

    </div>

	<?php include 'menu.php';?>

    

    <!--BLANCKOFF-FIM-->

    

    <?php

    if($_SESSION['resposta_ok']!=''):

	?>

    <div class="mensagemSucesso">

        <table align="center" cellpadding="0" cellspacing="0" border="0">

            <tr>

                <td><img src="ROOT/images/admin/ok_ico.png" /></td>

                <td><?php echo $_SESSION['resposta_ok'];?></td>

            </tr>

        </table>

        <script>

     	$(function(){

			setTimeout(function(){

				$('.mensagemSucesso').slideUp();

			},10000);	

		});

    	</script>

    </div><!-- mensagemSucesso -->

    <?php

		unset($_SESSION['resposta_ok']);

    endif;

	?>

    <?php

    if($_SESSION['resposta_no']!=''):

	?>

    <div class="mensagemNoSucesso">

        <table align="center" cellpadding="0" cellspacing="0" border="0">

            <tr>

                <td><img src="ROOT/images/admin/no_ico.png" /></td>

                <td><?php echo $_SESSION['resposta_no'];?></td>

            </tr>

        </table>

        <script>

     	$(function(){

			setTimeout(function(){

				$('.mensagemNoSucesso').slideUp();

			},10000);	

		});

    	</script>

    </div><!-- mensagemSucesso -->

    <?php

		unset($_SESSION['resposta_no']);

    endif;

	?>



	



 	<div id="container_principal">

        <div id="inner_container_geral_admin">

        [CONTENT-PLACE]

        </div>

    </div>





	</div><!-- container_geral_admin -->

    <!--BLANCKOFF-INICIO-->

