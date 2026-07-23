<div id="waitLoad" aria-hidden="true">
	<div class="admin-loader" role="status">
		<div class="admin-loader-spinner" aria-hidden="true"></div>
		<span>Carregando…</span>
	</div>
</div>

<!-- Confirm legado mantido no DOM por compatibilidade; UI usa Bootbox via AdminUI -->
<div id="confirm_box" hidden aria-hidden="true">
	<div id="quest_box"></div>
	<div id="bt_box_confirm">
		<input type="button" value="Sim" id="confirm_confirmar" class="confirm_confirmar">
		<input type="button" value="Não" id="confirm_cancelar">
	</div>
</div>

<?php if (!empty($_SESSION['resposta_ok'])): ?>
	<div id="admin-flash-ok" data-message="<?php echo htmlspecialchars($_SESSION['resposta_ok'], ENT_QUOTES, 'UTF-8'); ?>" hidden></div>
	<?php unset($_SESSION['resposta_ok']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['resposta_no'])): ?>
	<div id="admin-flash-no" data-message="<?php echo htmlspecialchars($_SESSION['resposta_no'], ENT_QUOTES, 'UTF-8'); ?>" hidden></div>
	<?php unset($_SESSION['resposta_no']); ?>
<?php endif; ?>

<div class="container-fluid px-0">
	<div class="row">
		<div class="col-12">
			<?php
			$EDIT = $_GET[':edit'];
			$ITEM = $_GET[':item'];
			$VIEW = $_GET[':view'];
			$ADD = $_GET[':new'];

			if (is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.php')) {
				$path_page_incluida = 'pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.php';
				if (is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.css.php')) {
					include ('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.css.php');
				}
				include ($path_page_incluida);
				if (is_file('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.vue.php')) {
					include ('pages/'.$pagina_solicitada.'/'.$pagina_solicitada.'.vue.php');
				}
			} else {
				if (is_file('admin/exe_system/'.$pagina_solicitada.'.php') && $pagina_solicitada != 'home') {
					require_once('admin/exe_system/'.$pagina_solicitada.'.php');
				} else if ($ITEM != '') {
					require_once("admin_content.php");
				}
			}
			?>
		</div>
	</div>
</div>
