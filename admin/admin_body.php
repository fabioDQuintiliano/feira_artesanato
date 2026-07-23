    <?php
//$_SESSION['resposta_no'] = 'Teste';
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

	
    <!--BLANCKOFF-FIM-->

    <?php

    if($_SESSION['resposta_ok']!=''):

	?>

        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">

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

                </div>
            </div>
        </div>
    <?php

		unset($_SESSION['resposta_ok']);

    endif;

	?>

    <?php

    if($_SESSION['resposta_no']!=''):

	?>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="mensagemNoSucesso">

                        <table align="center" cellpadding="0" cellspacing="0" border="0">

                            <tr>

                              

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

                </div>
            </div>
        </div>

    <?php
		unset($_SESSION['resposta_no']);
    endif;

	?>



    <div class="container-fluid ">
        <div class="row">
            <div class="col-12">


                
               


                <?php 
            	$EDIT = $_GET[':edit'];

            	$ITEM = $_GET[':item'];

            	$VIEW = $_GET[':view'];

            	$ADD = $_GET[':new'];

                if(is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.php')){


                    


                        $path_page_incluida = 'pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.php';
                        if(is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.css.php')){
                            include ('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.css.php');
                        }
                        include ($path_page_incluida);
                        if(is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.vue.php')){
                            include ('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.vue.php');
                        }

                   
                }else{
                    

                	if(is_file('admin/exe_system/'.$pagina_solicitada.'.php') && $pagina_solicitada != 'home'){
                        
                        //echo '<div class="card-body">';
                		require_once('admin/exe_system/'.$pagina_solicitada.'.php');
                       //echo '</div>';

                	}else if($ITEM != ''){
                       // echo '<div class="card mb-4"><div class="card-body">';
                		require_once("admin_content.php");
                    //     echo '</div></div>';


                	}
                }

            	?>
         
                </div>
            </div>
        </div>
    </div>

    <!--BLANCKOFF-INICIO-->

