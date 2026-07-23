<?php

if(!empty($_POST[':login']) && !empty($_POST[':senha'])):	

	$log = loginSystem(addslashes($_POST[':login']), addslashes($_POST[':senha']));
    

    if(function_exists('exec_after_login')){
        exec_after_login();
    }
	

	echo "<META HTTP-EQUIV=REFRESH CONTENT='0'>";

	exit;

endif;

?>





<?php

$info = new UserInfo;
$ip = $info->getIp();



$aux = DB::read('system_block');

$aux->ip = $ip;

$aux->load("","data > NOW()");

if(($aux->size() > 0 || ($_SESSION[PROJETO_NOME]['temp_block']>=date("Y-m-d H:i:s") && $_SESSION[PROJETO_NOME]['temp_block'] != ''))):

	$_SESSION[PROJETO_NOME]['temp_block'] = $aux->data;

	

	?>

	<div class="alertMsgLogin">

		<img src="<?=ROOT?>images/admin/alert_ico.png" />

		<br />

		<br />

		O sistema foi bloqueado por excesso de erros ao tentar realizar o login.<br />

		Por favor, entre em contato com a administração.

	</div><!-- alertMsgLogin -->

	<?php

else:

?>

	<script>

    $(function(){

        $("#login").focus();

    })

    </script>

    

    <div id="rowLogin">

    <div id="system_login_box">

        <div id="logotipo"><img src="<?=ROOT?>images/logoLogin.png" /></div><!-- logotipo -->

    

        <div id="alinha_box_login">

      <form method="post">

            <p>

                <label for="login">Login</label>

                <br />

                <input type="text" name="login" id="login" />

            </p>

            <p>

                <label for="login">Senha</label>

                <br />

                <input type="password" name="senha" />

            </p>

            <p class="pbt">

                <input type="submit" value="Logar" />

            </p>

        </form>

        </div>

        

    </div>

    </div><!-- rowLogin -->

<?php

endif;

?>