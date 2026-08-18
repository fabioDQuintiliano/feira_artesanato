<?php

class Componente__upload_imagem_padrao{

	function listagem($tabela,$id,$valor=null){

		if(!empty($valor)):

			echo '<div style="text-align:left;"><img style="max-width:150px;" src="'.imageUrl($valor).'" /></div>';	

		endif;

	}

	function exibe($tabela,$valor=null,$PARAM=null){

		global $MAP;

		$paramh = trim($PARAM['h'])?trim($PARAM['h']):600;

		$paramw = trim($PARAM['w'])?trim($PARAM['w']):600;

		$paramview = trim($PARAM['view'])?trim($PARAM['view']):150;

		$campo = trim($PARAM['campo_tabela']);

		$campoJs = htmlspecialchars((string) $campo, ENT_QUOTES, 'UTF-8');

		if( $_GET[':edit'] != ''){

			$aux = DB::read($tabela);

			$aux->id = $_GET[':edit'];

			$aux->load();

			if($aux->size()>0){

				if($aux->$PARAM['campo_tabela']!=''){

					$mostraImagemArquivo = $aux->$PARAM['campo_tabela'];	

				}

			}

		}

		$previewAtual = '';

		if(!empty($valor)){

			if(is_file('images/upload/thumb_'.$valor)){

				$previewAtual = ROOT.'images/upload/thumb_'.$valor;

			}elseif(function_exists('imageUrl')){

				$previewAtual = imageUrl($valor);

			}

		}

		$temPreview = $previewAtual !== '';

		ob_start();

		?>

		<script>

		function removeImageUpload(campo){

			conf('Deseja remover esta imagem',function(){

				$(".input_"+campo).html('<input type="hidden" value="removerimagem" name="rm_'+campo+'" />');

				uploadImagemPadraoLimpar(campo);

			})

		}

		function uploadImagemPadraoHint(campo, texto, erro){

			var wrap = document.getElementById('wrap_upload_'+campo);

			var status = document.getElementById('status_colar_'+campo);

			if(wrap){

				wrap.classList.toggle('is-error', !!erro);

			}

			if(status){

				status.textContent = texto || '';

			}

		}

		function uploadImagemPadraoMeta(campo, file){

			var meta = document.getElementById('meta_colar_'+campo);

			if(!meta){

				return;

			}

			if(!file){

				meta.textContent = '';

				return;

			}

			var kb = file.size / 1024;

			var size = kb >= 1024 ? (kb / 1024).toFixed(1) + ' MB' : Math.max(1, Math.round(kb)) + ' KB';

			meta.textContent = (file.name || 'imagem') + ' · ' + size;

		}

		function uploadImagemPadraoLimpar(campo){

			var wrap = document.getElementById('wrap_upload_'+campo);

			var prev = document.getElementById('preview_colar_'+campo);

			var input = document.getElementById(campo);

			if(prev){

				if(prev.dataset.objectUrl){

					URL.revokeObjectURL(prev.dataset.objectUrl);

					delete prev.dataset.objectUrl;

				}

				prev.removeAttribute('src');

			}

			if(input){

				try { input.value = ''; } catch (err) {}

			}

			if(wrap){

				wrap.classList.remove('is-filled', 'is-drag', 'is-error');

			}

			uploadImagemPadraoMeta(campo, null);

			uploadImagemPadraoHint(campo, '');

		}

		function uploadImagemPadraoArquivo(input, file){

			if(!input || !file){

				return false;

			}

			if(String(file.type).indexOf('image/') !== 0){

				uploadImagemPadraoHint(input.id, 'Use uma imagem PNG, JPG ou WebP.', true);

				return false;

			}

			var dt = new DataTransfer();

			dt.items.add(file);

			input.files = dt.files;

			var wrap = document.getElementById('wrap_upload_'+input.id);

			var prev = document.getElementById('preview_colar_'+input.id);

			var rm = wrap ? wrap.querySelector('.input_'+input.id) : null;

			if(rm){

				rm.innerHTML = '';

			}

			if(prev){

				if(prev.dataset.objectUrl){

					URL.revokeObjectURL(prev.dataset.objectUrl);

				}

				prev.dataset.objectUrl = URL.createObjectURL(file);

				prev.src = prev.dataset.objectUrl;

			}

			if(wrap){

				wrap.classList.add('is-filled');

				wrap.classList.remove('is-drag', 'is-error');

			}

			uploadImagemPadraoMeta(input.id, file);

			uploadImagemPadraoHint(input.id, 'Pronta para salvar com o formulário.');

			return true;

		}

		function uploadImagemPadraoDoClipboard(e, campo){

			var clip = e.clipboardData || (e.originalEvent && e.originalEvent.clipboardData);

			if(!clip || !clip.items){

				return;

			}

			var input = document.getElementById(campo);

			for(var i = 0; i < clip.items.length; i++){

				if(clip.items[i].type.indexOf('image/') === 0){

					e.preventDefault();

					uploadImagemPadraoArquivo(input, clip.items[i].getAsFile());

					return;

				}

			}

		}

		function uploadImagemPadraoColar(campo){

			var zona = document.getElementById('colar_'+campo);

			if(navigator.clipboard && navigator.clipboard.read){

				navigator.clipboard.read().then(function(items){

					var achou = false;

					return Promise.all(items.map(function(item){

						return Promise.all(item.types.map(function(type){

							if(type.indexOf('image/') !== 0){

								return null;

							}

							return item.getType(type).then(function(blob){

								var ext = (type.split('/')[1] || 'png').replace('jpeg', 'jpg');

								var file = new File([blob], 'colar.'+ext, {type: type});

								achou = uploadImagemPadraoArquivo(document.getElementById(campo), file);

							});

						}));

					})).then(function(){

						if(!achou){

							uploadImagemPadraoHint(campo, 'Nada para colar. Copie uma imagem e tente de novo.', true);

							if(zona){ zona.focus(); }

						}

					});

				}).catch(function(){

					uploadImagemPadraoHint(campo, 'Clique na área e pressione Ctrl+V para colar.', true);

					if(zona){ zona.focus(); }

				});

				return;

			}

			uploadImagemPadraoHint(campo, 'Clique na área e pressione Ctrl+V para colar.', true);

			if(zona){ zona.focus(); }

		}

		function uploadImagemPadraoBind(campo){

			var input = document.getElementById(campo);

			var zona = document.getElementById('colar_'+campo);

			if(!input || input.dataset.colarBound){

				return;

			}

			input.dataset.colarBound = '1';

			input.setAttribute('accept', 'image/*');

			input.addEventListener('change', function(){

				if(input.files && input.files[0]){

					uploadImagemPadraoArquivo(input, input.files[0]);

				}

			});

			input.addEventListener('paste', function(e){ uploadImagemPadraoDoClipboard(e, campo); });

			if(!zona){

				return;

			}

			var dragDepth = 0;

			zona.addEventListener('paste', function(e){ uploadImagemPadraoDoClipboard(e, campo); });

			zona.addEventListener('keydown', function(e){

				if(e.key === 'Enter' || e.key === ' '){

					e.preventDefault();

					input.click();

				}

			});

			zona.addEventListener('click', function(e){

				if(e.target.closest('button, label, a')){

					return;

				}

				var wrap = document.getElementById('wrap_upload_'+campo);

				if(!wrap || !wrap.classList.contains('is-filled')){

					input.click();

				}

			});

			zona.addEventListener('dragenter', function(e){

				e.preventDefault();

				dragDepth++;

				zona.classList.add('is-drag');

			});

			zona.addEventListener('dragover', function(e){

				e.preventDefault();

				zona.classList.add('is-drag');

			});

			zona.addEventListener('dragleave', function(e){

				e.preventDefault();

				dragDepth--;

				if(dragDepth <= 0){

					dragDepth = 0;

					zona.classList.remove('is-drag');

				}

			});

			zona.addEventListener('drop', function(e){

				e.preventDefault();

				dragDepth = 0;

				zona.classList.remove('is-drag');

				var files = e.dataTransfer && e.dataTransfer.files;

				if(files && files[0]){

					uploadImagemPadraoArquivo(input, files[0]);

				}

			});

		}

		</script>

        	<label><?php echo $PARAM['nome_campo']?></label>

            <div class="item-input-form">

	            <input type="hidden" name="param_<?php echo $campo?>[w]" value="<?php echo $paramw?>" />

	            <input type="hidden" name="param_<?php echo $campo?>[h]" value="<?php echo $paramh?>" />

	            <input type="hidden" name="param_<?php echo $campo?>[v]" value="<?php echo $paramview?>" />

	            <div class="upload-imagem-padrao<?php echo $temPreview ? ' is-filled' : ''; ?>" id="wrap_upload_<?php echo $campoJs?>">

	            	<input class="upload-imagem-padrao__file" type="file" name="<?php echo $campo;?>" id="<?php echo $campoJs?>" accept="image/*" />

	            	<div class="upload-imagem-padrao__zona" id="colar_<?php echo $campoJs?>" tabindex="0" role="button" aria-label="Área para enviar ou colar imagem">

	            		<div class="upload-imagem-padrao__stage">

	            			<img class="upload-imagem-padrao__preview" id="preview_colar_<?php echo $campoJs?>" alt="Pré-visualização"<?php echo $temPreview ? ' src="'.htmlspecialchars($previewAtual, ENT_QUOTES, 'UTF-8').'"' : ''; ?> />

	            		</div>

	            		<div class="upload-imagem-padrao__empty">

	            			<span class="upload-imagem-padrao__icon" aria-hidden="true">

	            				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5.5-5.5L5 20"/><path d="M16 3v4M14 5h4"/></svg>

	            			</span>

	            			<strong>Arraste a imagem para cá</strong>

	            			<span>PNG, JPG ou WebP · até o tamanho do formulário</span>

	            			<div class="upload-imagem-padrao__chips">

	            				<label class="upload-imagem-padrao__chip" for="<?php echo $campoJs?>">Escolher</label>

	            				<button type="button" class="upload-imagem-padrao__chip" onclick="event.stopPropagation(); uploadImagemPadraoColar('<?php echo $campoJs?>')">Colar</button>

	            			</div>

	            		</div>

	            		<div class="upload-imagem-padrao__overlay">

	            			<label class="upload-imagem-padrao__chip upload-imagem-padrao__chip--solid" for="<?php echo $campoJs?>">Trocar</label>

	            			<button type="button" class="upload-imagem-padrao__chip" onclick="uploadImagemPadraoColar('<?php echo $campoJs?>')">Colar</button>

	            			<button type="button" class="upload-imagem-padrao__chip upload-imagem-padrao__chip--danger" onclick="removeImageUpload('<?php echo $campoJs?>')">Remover</button>

	            		</div>

	            		<div class="upload-imagem-padrao__drop">Solte para enviar</div>

	            	</div>

	            	<div class="upload-imagem-padrao__foot">

	            		<p class="upload-imagem-padrao__meta" id="meta_colar_<?php echo $campoJs?>" data-saved="<?php echo $temPreview ? 'Imagem atual' : ''; ?>"><?php echo $temPreview ? 'Imagem atual' : ''; ?></p>

	            		<p class="upload-imagem-padrao__status" id="status_colar_<?php echo $campoJs?>"></p>

	            	</div>

	            	<div class="input_<?php echo trim($campo)?>"></div>

	            </div>

            </div>

            <script>uploadImagemPadraoBind('<?php echo $campoJs?>');</script>

		<?php

		$ret = ob_get_clean();

		return $ret;

	}

	function save($registro,$tabela,$campo){

		$largItem =  $_POST['param_'.$campo]['w'];

		$altuItem =  $_POST['param_'.$campo]['h'];

		$viewItem =  $_POST['param_'.$campo]['v'];

		$aux = DB::read($tabela);

		$aux->id = $registro;

		$aux->load();

		if($_FILES[$campo]['size']>0){

			$arquivo = $_FILES[$campo];

			$ext = explode('.',$arquivo['name']);

			$ext = array_reverse($ext);

			$nomeImagem = md5(rand(0,9999).time()).'.'.$ext[0];

			$arquivoOrigem = $arquivo['tmp_name'];

			resizeImage($arquivoOrigem,$largItem,$altuItem,'images/upload/'.$nomeImagem);

			resizeImage($arquivoOrigem ,$viewItem,'','images/upload/thumb_'.$nomeImagem);

			$aux->$campo = $nomeImagem;

			$aux->update();

		}

		if($_FILES[$campo]['error'] == 1){

			$_SESSION['resposta_no'] = "A imagem seleciona é muito grande e por isso foi ignorada.";

		}

		return true;

	}

	function update($registro,$tabela,$campo){

		$largItem =  $_POST['param_'.$campo]['w'];

		$altuItem =  $_POST['param_'.$campo]['h'];

		$viewItem =  $_POST['param_'.$campo]['v'];

		$aux = DB::read($tabela);

		$aux->id = $registro;

		$aux->load();

		if($_POST['rm_'.$campo] == 'removerimagem'){

			if(is_file('images/upload/'.$aux->$campo)){

				unlink('images/upload/'.$aux->$campo);	

			}

			if(is_file('images/upload/thumb_'.$aux->$campo)){

				unlink('images/upload/thumb_'.$aux->$campo);	

			}

			$nomeImagem = '';	

			$aux->$campo = $nomeImagem;

		}

		if($_FILES[$campo]['size']>0){

			$arquivo = $_FILES[$campo];

			$ext = explode('.',$arquivo['name']);

			$ext = array_reverse($ext);

			$nomeImagem = md5(rand(0,9999).time()).'.'.$ext[0];

			$arquivoOrigem = $arquivo['tmp_name'];

			resizeImage($arquivoOrigem,$largItem,$altuItem,'images/upload/'.$nomeImagem);

			resizeImage($arquivoOrigem ,$viewItem,'','images/upload/thumb_'.$nomeImagem);

			$aux->$campo = $nomeImagem;

		}

		if($_FILES[$campo]['error'] == 1){

			$_SESSION['resposta_no'] = "A imagem seleciona é muito grande e por isso foi ignorada.";

		}

		$aux->update();

		return true;

	}

	function view($tabela,$valor=''){

		$name = explode("\n",$valor);

		return '<a target="_blank" href="ROOT/arquivos/'.$name[1].'">'.$name[0].'</a>';

	}

}

?>
