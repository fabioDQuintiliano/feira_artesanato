<?php
class executaFiltro_receita extends Model{
	
	 function nomeFiltro(){
		$filtroName = 'codigo_receita';
		return $filtroName;
	}
	 function input($name,$valor=null){
		ob_start();
		?>	
        	<th>Código da receita</th>
        	<td><input type="text" value="<?php echo $valor?>" name="<?php echo $name?>" /></td>

		<?php
		$ret = ob_get_clean();
		return $ret;
	}
	 function query($valor=null){
			
			if($valor!=''):
				return "AND (referencia = 'receita' AND cod_referencia = '".$valor."')";
			else:
				return false;
			endif;
	}
	
}
?>