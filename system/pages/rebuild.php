<?php
/**
 * Endpoint autenticado para regenerar artefatos do painel.
 * URL: /system-rebuild
 *
 * Disponível mesmo com SYSTEM_IDE_ENABLED=0 e SYSTEM_CODEGEN=0.
 */

require_once __DIR__.'/../codegen_helpers.php';

$ok = false;
$error = '';

try {
	system_run_codegen();
	$ok = true;
} catch (Throwable $e) {
	$error = $e->getMessage();
}
?>
<style>
.rebuild-box{max-width:560px;margin:2rem auto;padding:1.5rem 2rem;font-family:system-ui,sans-serif;border:1px solid #dde3ea;border-radius:12px;background:#fff}
.rebuild-ok{color:#0f766e}.rebuild-err{color:#b91c1c}
.rebuild-box a{color:#0f766e}
.rebuild-meta{margin-top:1rem;font-size:.9rem;color:#64748b}
</style>
<div class="rebuild-box">
	<h2>Regeneração do painel</h2>
	<?php if ($ok): ?>
		<p class="rebuild-ok">Artefatos regenerados com sucesso.</p>
		<ul>
			<li><code>tables/def_*.php</code>, <code>_admin_menu.php</code>, <code>_admin_permissoes.php</code>, <code>_admin_def_tables.php</code></li>
			<li><code>functions/__list_functions.php</code></li>
			<li><code>admin/exe_system/</code> e <code>containers/exe_system/</code></li>
		</ul>
	<?php else: ?>
		<p class="rebuild-err">Falha ao regenerar: <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
	<?php endif; ?>
	<p class="rebuild-meta">
		Ambiente: <strong><?php echo htmlspecialchars(defined('APP_ENV') ? APP_ENV : '?', ENT_QUOTES, 'UTF-8'); ?></strong>
		· SYSTEM_CODEGEN=<?php echo (defined('SYSTEM_CODEGEN') && SYSTEM_CODEGEN) ? '1' : '0'; ?>
		· SYSTEM_IDE_ENABLED=<?php echo (defined('SYSTEM_IDE_ENABLED') && SYSTEM_IDE_ENABLED) ? '1' : '0'; ?>
	</p>
	<p><a href="<?php echo ROOT; ?>adm-home">Voltar ao painel</a>
		<?php if (defined('SYSTEM_IDE_ENABLED') && SYSTEM_IDE_ENABLED): ?>
			· <a href="<?php echo ROOT; ?>system-inicio">IDE</a>
		<?php endif; ?>
	</p>
</div>
