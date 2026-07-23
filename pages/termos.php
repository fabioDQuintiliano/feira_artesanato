<!--[CONTAINER-padrao]-->
<?php $texto = DB_Class::make("textos")->_id(1)->_loadAll();?>



<h1 class="font-20"><?php echo $texto->titulo?></h1>

<div class="conteudo-termos">
	<p>
		<?php 
			$text =  $texto->texto;
			echo $text;
		?>		
	</p>
</div>