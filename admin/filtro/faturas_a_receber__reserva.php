<?php
class executaFiltro_reserva extends Model{
	
	 function nomeFiltro(){
		$filtroName = 'codigo_hospedagem';
		return $filtroName;
	}
	 function input($name,$valor=null){
		ob_start();
		?>	
        	<th>Código da reserva</th>
        	<td><input type="text" value="<?php echo $valor?>" name="<?php echo $name?>" /></td>

		<?php
		$ret = ob_get_clean();
		return $ret;
	}
	 function query($valor=null){
			
			if($valor!=''):
				return "AND (referencia = 'reservas' AND cod_referencia = '".$valor."')";
			else:
				return false;
			endif;
	}
	
}
?>