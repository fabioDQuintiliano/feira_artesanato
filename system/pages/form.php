<div class="headPagesSys">
    <div class="headPagesSysInner">
    	<div class="headPagesSysInnerContent">
        	<a href="<?=ROOT?>system-addForm">
            <input type="button" class="bt" value="Novo Formulário" />
            </a>
        </div><!-- headPagesSysInnerContent -->
    </div>
</div><!-- headPagesSys -->




<?php
$form = DB::read("system_form");
$form->load();
if($form->size()>0){do{
?>	
	<div class="formList">
    	<div class="titulo"><?php echo $form->nome?></div>
        <div class="menu" title="Aba do menu onde este formulário é exibido"><?php echo ($form->menu!=''?$form->menu:'Geral')?></div><!-- menu -->
        <div class="info">
            
            
            <?php if($form->listar_pagina != ''){ ?>
				<div class="listPg">
                	<?php if(!is_file('admin/exe_system/'.$form->listar_pagina.'.php')):?>
                        <div class="none" title="Esta página ainda não foi criada">-</div>
                    <?php endif;?>
                	pg: <?php echo $form->listar_pagina?>
                </div>
			<?php }else{?>
                <div class="row">
                    <div class="item">Byform:</div><!-- item -->
                    <div class="desc"><?php echo removeCaracteres($form->nome)?></div><!-- desc -->
                </div><!-- row -->
                
            <?php }?>
            
        </div><!-- info -->
        <div class="opcs">
            <a href="system-formInput/<?php echo $form->id?>/" title="Itens do formulário"><img src="system/img/ico_listi.png"></a>
            <a href="system-addForm/<?php echo $form->id?>/" title="Editar formulário"><img src="system/img/ico_edti.png"></a>
            <a href="system-addForm/<?php echo $form->id?>/copiar/" title="Duplicar formulário"><img src="system/img/ico_copyi.png"></a>
            <a href="system-delform/<?php echo $form->id?>/" title="Deletar formulário"><img src="system/img/del_icoi.png"></a>
        </div><!-- opcs -->
    </div><!-- formList -->

<?php	
}while($form->next());}
/*
?>




<table align="center" border="1" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#f1f1f1" cellpadding="5" width="900">
	<tr style="font-size:14px; font-weight:bold; text-transform:uppercase;" align="center">
    	<td width="100">Cod.</td>
    	<td width="250">Id</td>
    	<td width="250">Nome</td>
    	<td width="200">Classes</td>
    	<td width="50">Method</td>
    	<td>Action</td>
    	<td></td>
    	<td></td>
    	<td></td>
	</tr>
    <?php
    $f = $q->read("system_form");
	foreach($f as $list_f):
	?>
	<tr align="center">
    	<td><?php echo $list_f['id']?></td>
    	<td><?php echo $list_f['id_form']?></td>
    	<td><?php echo $list_f['nome']?></td>
    	<td><?php echo $list_f['class']?></td>
    	<td><?php echo $list_f['method']?></td>
    	<td><?php echo $list_f['action']?></td>
    	<td><a href="system-delform/<?php echo $list_f['id']?>/copiar/">Deletar</a></td>
    	<td><a href="system-addForm/<?php echo $list_f['id']?>/copiar/">Copiar</a></td>
    	<td><a href="system-addForm/<?php echo $list_f['id']?>/">Editar</a></td>
    	<td><a href="system-formInput/<?php echo $list_f['id']?>/">Itens</a></td>
	</tr>
    <?php endforeach;?>

</table>
*/?>