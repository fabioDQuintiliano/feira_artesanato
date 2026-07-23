<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?= htmlspecialchars(PROJETO_NOME, ENT_QUOTES, 'UTF-8') ?> · Acesso</title>
	<link rel="shortcut icon" href="<?php echo ROOT; ?>images/ico.png" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
	<link href="<?php echo ROOT; ?>admin/login.css" rel="stylesheet" type="text/css" />
	<style>
		:root {
			--login-brand-photo: url(<?php echo json_encode(ROOT . 'images/admin/bgLogin.jpg'); ?>);
		}
	</style>
	<script>
		var ROOT = <?php echo json_encode(ROOT); ?>;
	</script>
</head>
<body class="login-page">
	<div class="login-shell">
		<aside class="login-brand" aria-hidden="false">
			<div class="login-brand-inner">
				<p class="login-brand-kicker">Área restrita</p>
				<h1 class="login-brand-title"><?= htmlspecialchars(PROJETO_NOME, ENT_QUOTES, 'UTF-8') ?></h1>
				<p class="login-brand-copy">Acesse o painel para gerenciar conteúdos e configurações.</p>
			</div>
		</aside>

		<main class="login-main">
			<div class="login-card">
