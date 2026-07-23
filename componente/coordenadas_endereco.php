<?php
class Componente__coordenadas_endereco{
	function listagem($tabela,$id,$valor=null){
		
		return $valor;
						
	}
	function exibe($tabela,$valor=null,$PARAM=null){
		global $MAP;
		$mostraNomeArquivo = '';
		if( $_GET[':edit'] != ''){
			$aux = DB::read($tabela);
			$aux->id = $_GET[':edit'];
			$aux->load();
			
			
			if($aux->size()>0){
				
				$nomeEXB = explode("\n",$aux->$PARAM['campo_tabela']);
				
				if($nomeEXB[0]!=''){
					$mostraNomeArquivo = $nomeEXB[0];	
				}
					
			}
		}
		ob_start();
		
		
		?>
        	<label><?php echo $PARAM['nome_campo']?></label>
            <br />
            <textarea name="<?php echo $PARAM['campo_tabela']?>" id="<?php echo $PARAM['campo_tabela']?>" ></textarea>
            
          
           
		<?php
		$ret = ob_get_clean();
		return $ret;
	}
	
	function save($registro,$tabela,$campo){
		
	
		return;
		
	}
	function update($registro,$tabela,$campo){
		
		
		return;
		
	}
	function view($tabela,$valor=''){
		return $valor;
	}
	
}
?>

