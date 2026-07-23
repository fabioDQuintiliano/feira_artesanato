<?php
class executaFiltro_tipo extends Model{
	
	 function nomeFiltro(){
		$filtroName = 'tipo_receita';
		return $filtroName;
	}
	 function input($name,$valor=null){
		ob_start();
		?>	
        	<th>Código da reserva</th>
        	<td>
            <select name="<?php echo $name?>">
            	<option value="">Todos</option>
            	<option value="receita" <?php echo ($valor=='receita'?'selected="selected"':'')?>>Receitas</option>
            	<option value="reservas" <?php echo ($valor=='reservas'?'selected="selected"':'')?>>Reservas</option>
            	<option value="hospedagem" <?php echo ($valor=='hospedagem'?'selected="selected"':'')?>>Hospedagens</option>
            </select>
           
            </td>

		<?php
		$ret = ob_get_clean();
		return $ret;
	}
	 function query($valor=null){
			
			if($valor!=''):
				return "AND (referencia = '".$valor."')";
			else:
				return false;
			endif;
	}
	
}
?>