<?php

global $INFO_QUERY_FILTRO,$MAP;

if (!empty($_GET['limpar_filtro'])) {
	unset($_SESSION['INFO_QUERY_FILTRO'][$configTableList->id]);
}

if(isset($_SESSION['INFO_QUERY_FILTRO'][$configTableList->id])){
	$INFO_QUERY_FILTRO = $_SESSION['INFO_QUERY_FILTRO'][$configTableList->id];
} else {
	$INFO_QUERY_FILTRO = array();
}

$INFO_QUERY_FILTRO['TABELA'] = $configTableList->id;

if(count($_POST)>0){
	foreach($_POST as $k=>$v):
		if($v != '' ){
			$INFO_QUERY_FILTRO[$k] = $v;
		}else{
			if(!empty($INFO_QUERY_FILTRO[$k]))
			unset($INFO_QUERY_FILTRO[$k]);
		}
		unset($_POST[$k]);
	endforeach;
	unset($_POST);
}

$_SESSION['INFO_QUERY_FILTRO'][$configTableList->id] = $INFO_QUERY_FILTRO;

$q = new Model;
require("tables/def_".$MAP['def_file'].".php");
$fil = $TABLE_DEF_INPUT;

$num_fil = 0;
$filtrosAtivos = 0;

// Conta filtros ativos (para badge / abrir painel)
if (is_array($INFO_QUERY_FILTRO)) {
	foreach ($INFO_QUERY_FILTRO as $fk => $fv) {
		if ($fk === 'TABELA' || $fv === '' || $fv === null) {
			continue;
		}
		if (is_array($fv) && count(array_filter($fv, 'strlen')) === 0) {
			continue;
		}
		$filtrosAtivos++;
	}
}

ob_start();

// --- Filtros adicionais ---
if(!empty($MAP["filtro_adicional"])):
	foreach($MAP["filtro_adicional"] as $filtro_adicional):
		include 'admin/filtro/'.$filtro_adicional;
		$nFiltro = explode('__',str_replace('.php','',$filtro_adicional));
		$nameclass_filtro = 'executaFiltro_'.$nFiltro[1];
		$filEXEC = new $nameclass_filtro;
		$nomeFiltro = $filEXEC->nomeFiltro();
		$htmlExtra = $filEXEC->input($nomeFiltro, isset($INFO_QUERY_FILTRO[$nomeFiltro]) ? $INFO_QUERY_FILTRO[$nomeFiltro] : null);
		$htmlExtra = str_replace(
			array('<th>', '</th>', '<td>', '</td>'),
			array('<label class="adm-filtro-label">', '</label>', '<div class="adm-filtro-control">', '</div>'),
			$htmlExtra
		);
		echo '<div class="adm-filtro-field">'.$htmlExtra.'</div>';
		$num_fil++;
	endforeach;
endif;

// --- Filtros dos campos ---
if($fil && count($fil)>0)
foreach($fil as $filtro):
	if($filtro['exb_filtro']!=1)continue;
	$num_fil++;
	$fieldId = ($filtro['id_input']!=''?$filtro['id_input']:($filtro['campo_tabela']!=''?$filtro['campo_tabela']:'idinput_'.$filtro['id']));
	$campoVal = isset($INFO_QUERY_FILTRO[$filtro['campo_tabela']]) ? $INFO_QUERY_FILTRO[$filtro['campo_tabela']] : '';
	?>
	<div class="adm-filtro-field">
		<label class="adm-filtro-label" for="<?php echo htmlspecialchars($fieldId, ENT_QUOTES, 'UTF-8'); ?>">
			<?php echo htmlspecialchars($filtro['nome'], ENT_QUOTES, 'UTF-8'); ?>
		</label>
		<div class="adm-filtro-control">
			<?php
			if($filtro['type'] == 'select'):
				global $MAP,$listapar;
				if(!empty($listapar) && in_array($MAP['tabela'].'----'.$filtro['campo_tabela'],$listapar)):
				?>
					<script>
					$(function(){
						$("#<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTP'?>").keyup(function(){
							val = $(this).val();
							$.post('<?=ROOT?>fn-carrega_lista',{p0:'<?=$MAP['tabela']?>',p1:'<?=$filtro['campo_tabela']?>',p2:val},function(a){
								$("#<?php echo  $filtro['campo_tabela'].'_'.($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTPBOX';?>").html(a);
								$(".select_auto").click(function(){
									$("#<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id'])?>").val($(this).attr('valor'));
									$("#<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTP'?>").val($(this).attr('nome'))
									$("#<?php echo $filtro['campo_tabela'].'_'.($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTPBOX';?>").html('');
								})
							})
						})
					})
					</script>
					<input type="text" name="<?php echo $filtro['campo_tabela']?>_LSTP" id="<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTP'?>" class="autocomplete_lista_input" value="<?php echo htmlspecialchars((string)($INFO_QUERY_FILTRO[$filtro['campo_tabela'].'_LSTP'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off"/>
					<input type="hidden" name="<?php echo $filtro['campo_tabela']?>" id="<?php echo ($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id'])?>" value="<?php echo htmlspecialchars((string)$campoVal, ENT_QUOTES, 'UTF-8'); ?>" />
					<div class="list_auto_comp" id="<?php echo  $filtro['campo_tabela'].'_'.($filtro['id_input']!=''?$filtro['id_input']:'idinput_'.$filtro['id']).'_LSTPBOX';?>"></div>
				<?php
				else:
				?>
					<select name="<?php echo htmlspecialchars($filtro['campo_tabela'], ENT_QUOTES, 'UTF-8'); ?>" id="<?php echo htmlspecialchars($fieldId, ENT_QUOTES, 'UTF-8'); ?>">
						<option value="">Todos</option>
						<?php
						if($filtro['caracteristica'] == 2):
							$dados_join = $q->read($filtro['join_tabela']);
							foreach($dados_join as $list_join):
								$sele = ($list_join[$filtro['join_chave_extrangeira']] == $campoVal) ? 'selected="selected"' : '';
								echo '<option '.$sele.' value="'.htmlspecialchars((string)$list_join[$filtro['join_chave_extrangeira']], ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars((string)$list_join[$filtro['join_campo_exibido']], ENT_QUOTES, 'UTF-8').'</option>';
							endforeach;
						else:
							if(!empty($filtro['valor'])):
								$dados_select = explode(',',$filtro['valor']);
								for($i = 0; $i<count($dados_select); $i++):
									$sele = ($i == $campoVal && $campoVal !== '') ? 'selected="selected"' : '';
									echo '<option '.$sele.' value="'.$i.'">'.htmlspecialchars($dados_select[$i], ENT_QUOTES, 'UTF-8').'</option>';
								endfor;
							endif;
						endif;
						?>
					</select>
				<?php
				endif;
			else:
				if($filtro['mascara'] == 'data'):
				?>
					<div class="adm-filtro-range">
						<input type="text" name="de_<?php echo $filtro['campo_tabela']?>" placeholder="De" value="<?php echo htmlspecialchars((string)($INFO_QUERY_FILTRO['de_'.$filtro['campo_tabela']] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo ($filtro['mascara']!=''?'mask_type_'.$filtro['mascara']:'')?>" />
						<span class="adm-filtro-range-sep">até</span>
						<input type="text" name="ate_<?php echo $filtro['campo_tabela']?>" placeholder="Até" class="<?php echo ($filtro['mascara']!=''?'mask_type_'.$filtro['mascara']:'')?>" value="<?php echo htmlspecialchars((string)($INFO_QUERY_FILTRO['ate_'.$filtro['campo_tabela']] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" />
					</div>
				<?php
				else:
				?>
					<input type="text" id="<?php echo htmlspecialchars($fieldId, ENT_QUOTES, 'UTF-8'); ?>" name="<?php echo htmlspecialchars($filtro['campo_tabela'], ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlspecialchars((string)$campoVal, ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo ($filtro['mascara']!=''?'mask_type_'.$filtro['mascara']:'')?>" placeholder="Buscar…" />
				<?php
				endif;
			endif;
			?>
		</div>
	</div>
	<?php
endforeach;

$camposHtml = ob_get_clean();
$filtroPanelId = 'admFiltro_'.preg_replace('/[^a-zA-Z0-9_]/', '', (string)$configTableList->id);
$storageKey = 'adm_filtro_open_'.$configTableList->id;
?>

<?php if ($num_fil > 0): ?>
<div class="adm-filtro-wrap" id="<?php echo htmlspecialchars($filtroPanelId, ENT_QUOTES, 'UTF-8'); ?>" data-storage-key="<?php echo htmlspecialchars($storageKey, ENT_QUOTES, 'UTF-8'); ?>" data-has-active="<?php echo $filtrosAtivos > 0 ? '1' : '0'; ?>">
	<button type="button" class="btn btn-sm btn-outline-secondary mb-0 adm-filtro-toggle" id="<?php echo htmlspecialchars($filtroPanelId, ENT_QUOTES, 'UTF-8'); ?>_btn" aria-expanded="false" aria-controls="<?php echo htmlspecialchars($filtroPanelId, ENT_QUOTES, 'UTF-8'); ?>_body">
		<i class="fas fa-search me-1"></i>
		<span class="adm-filtro-toggle-label">Filtros</span>
		<?php if ($filtrosAtivos > 0): ?>
			<span class="adm-filtro-badge"><?php echo (int)$filtrosAtivos; ?></span>
		<?php endif; ?>
		<i class="fas fa-chevron-down ms-1 adm-filtro-chevron"></i>
	</button>

	<div class="adm-filtro-panel" id="<?php echo htmlspecialchars($filtroPanelId, ENT_QUOTES, 'UTF-8'); ?>_body" hidden>
		<form method="post" class="adm-filtro-form">
			<div class="adm-filtro-grid">
				<?php echo $camposHtml; ?>
			</div>
			<div class="adm-filtro-actions">
				<button type="submit" class="btn btn-sm bg-gradient-info mb-0">
					<i class="fas fa-filter me-1"></i> Filtrar
				</button>
				<a href="ROOT/adm-home?item=<?php echo htmlspecialchars((string)$_GET[':item'], ENT_QUOTES, 'UTF-8'); ?>&limpar_filtro=1" class="btn btn-sm btn-outline-secondary mb-0 adm-filtro-clear">
					Limpar
				</a>
			</div>
		</form>
	</div>
</div>

<script>
(function () {
	var wrap = document.getElementById(<?php echo json_encode($filtroPanelId); ?>);
	if (!wrap) return;
	var btn = document.getElementById(<?php echo json_encode($filtroPanelId.'_btn'); ?>);
	var body = document.getElementById(<?php echo json_encode($filtroPanelId.'_body'); ?>);
	var key = wrap.getAttribute('data-storage-key');
	var hasActive = wrap.getAttribute('data-has-active') === '1';
	var label = btn.querySelector('.adm-filtro-toggle-label');

	function setOpen(open) {
		wrap.classList.toggle('is-open', open);
		btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (open) {
			body.removeAttribute('hidden');
		} else {
			body.setAttribute('hidden', 'hidden');
		}
		if (label) {
			label.textContent = open ? 'Ocultar filtros' : 'Filtros';
		}
		try {
			localStorage.setItem(key, open ? '1' : '0');
		} catch (e) {}
	}

	var stored = null;
	try { stored = localStorage.getItem(key); } catch (e) {}
	var initial = hasActive || stored === '1';
	if (stored === '0' && !hasActive) initial = false;
	if (stored === null && !hasActive) initial = false;
	setOpen(initial);

	btn.addEventListener('click', function () {
		setOpen(!wrap.classList.contains('is-open'));
	});
})();
</script>
<?php endif; ?>

<?php
//MONTA A QUERY DA BUSCA.
$QUERY_FILTRO = " (1=1) ";

if(count($INFO_QUERY_FILTRO)>1):

	if(!empty($MAP['filtro_adicional'])):
		foreach($MAP['filtro_adicional'] as $filtro_adicional):
			$nFiltro = explode('__',str_replace('.php','',$filtro_adicional));
			$nameclass_filtro = 'executaFiltro_'.$nFiltro[1];
			if (!class_exists($nameclass_filtro, false)) {
				include 'admin/filtro/'.$filtro_adicional;
			}
			$filEXEC = new $nameclass_filtro;
			$nomeFiltro = $filEXEC->nomeFiltro();
			$QUERY_FILTRO .= $filEXEC->query(isset($INFO_QUERY_FILTRO[$nomeFiltro]) ? $INFO_QUERY_FILTRO[$nomeFiltro] : null);
		endforeach;
	endif;

	if($fil && count($fil)>0)
	foreach($fil as $filtro):
		if($filtro['exb_filtro']!=1)continue;

		$valorCampo = $INFO_QUERY_FILTRO[$filtro['campo_tabela']] ?? '';
		$deCampo = $INFO_QUERY_FILTRO['de_'.$filtro['campo_tabela']] ?? '';
		$ateCampo = $INFO_QUERY_FILTRO['ate_'.$filtro['campo_tabela']] ?? '';
		$caracteristica = (string)($filtro['caracteristica'] ?? '');

		if(($valorCampo != '' || ($deCampo != '' && $ateCampo != '')) && ($valorCampo!='0,00')):

			// Select com join: o <option value> já é a FK (id), não o texto exibido
			if($filtro['type'] == 'select'):
				$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." = '".addslashes((string)$valorCampo)."'";

			elseif($filtro['type'] != 'select' && $caracteristica === '2'):
				$vals = is_array($valorCampo) ? $valorCampo : array($valorCampo);
				$vals = array_map(function ($v) { return "'".addslashes((string)$v)."'"; }, $vals);
				$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." IN (".implode(',',$vals).")";

			 else:
				 if($filtro['mascara'] == 'data'):
					$QUERY_FILTRO .= " AND ( ".$filtro['campo_tabela']." BETWEEN '".date2banco($deCampo)."' AND '".date2banco($ateCampo)."' )";
				 elseif($filtro['mascara'] == 'decimal'):
					$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." LIKE '%".substr(dinheiroToFloat($valorCampo),0,-3)."%'";
				 else:
					$QUERY_FILTRO .= " AND ".$filtro['campo_tabela']." LIKE '%".addslashes((string)$valorCampo)."%'";
				 endif;
			endif;
		endif;
	endforeach;

endif;
?>
