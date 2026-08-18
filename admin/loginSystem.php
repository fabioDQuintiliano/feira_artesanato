<?php

$loginVal = '';
if (!empty($_POST['login'])) {
	$loginVal = (string) $_POST['login'];
} elseif (!empty($_POST[':login'])) {
	$loginVal = (string) $_POST[':login'];
}

$senhaVal = '';
if (!empty($_POST['senha'])) {
	$senhaVal = (string) $_POST['senha'];
} elseif (!empty($_POST[':senha'])) {
	$senhaVal = (string) $_POST[':senha'];
}

if ($loginVal !== '' && $senhaVal !== ''):

	$log = loginSystem(addslashes($loginVal), addslashes($senhaVal));

	if ($log) {
		if (function_exists('exec_after_login')) {
			exec_after_login();
		}
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0'>";
		exit;
	}

	$_SESSION['resposta_no'] = 'Login ou senha inválidos.';

endif;

$info = new UserInfo;
$ip = $info->getIp();

$aux = DB::read('system_block');
$aux->ip = $ip;
$aux->load('', 'data > NOW()');

$bloqueado = ($aux->size() > 0 || (!empty($_SESSION[PROJETO_NOME]['temp_block']) && $_SESSION[PROJETO_NOME]['temp_block'] >= date('Y-m-d H:i:s')));

if ($bloqueado):
	$_SESSION[PROJETO_NOME]['temp_block'] = $aux->data ?: $_SESSION[PROJETO_NOME]['temp_block'];
	?>
	<div class="login-alert login-alert-danger" role="alert">
		<strong>Acesso bloqueado</strong>
		<p>O sistema foi bloqueado por excesso de tentativas. Entre em contato com a administração.</p>
	</div>
	<?php
else:
	$erroLogin = '';
	if (!empty($_SESSION['resposta_no'])) {
		$erroLogin = (string) $_SESSION['resposta_no'];
		unset($_SESSION['resposta_no']);
	}
	$okMsg = '';
	if (!empty($_SESSION['resposta_ok'])) {
		$okMsg = (string) $_SESSION['resposta_ok'];
		unset($_SESSION['resposta_ok']);
	}
	?>
	<header class="login-card-head">
		<span class="login-logo-frame login-logo-frame--card">
			<img src="<?php echo ROOT; ?>images/logoAdmin.png" alt="Logo 2º Encontro de Ceramistas - Aprendizes e artesãos" class="login-card-logo" />
		</span>
		<h2>Entrar</h2>
		<p>Use suas credenciais de administrador.</p>
	</header>

	<?php if ($okMsg !== ''): ?>
		<div class="login-alert login-alert-ok" role="status"><?php echo htmlspecialchars($okMsg, ENT_QUOTES, 'UTF-8'); ?></div>
	<?php endif; ?>

	<?php if ($erroLogin !== ''): ?>
		<div class="login-alert login-alert-danger" role="alert"><?php echo htmlspecialchars($erroLogin, ENT_QUOTES, 'UTF-8'); ?></div>
	<?php endif; ?>

	<form method="post" class="login-form" autocomplete="on">
		<label class="login-field">
			<span>Login</span>
			<input type="text" name="login" id="login" value="<?php echo htmlspecialchars($loginVal, ENT_QUOTES, 'UTF-8'); ?>" required autofocus autocomplete="username" />
		</label>

		<label class="login-field">
			<span>Senha</span>
			<input type="password" name="senha" id="senha" required autocomplete="current-password" />
		</label>

		<button type="submit" class="login-submit">Acessar painel</button>
	</form>
	<?php
endif;
?>
