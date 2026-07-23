<?php

class Componente__url_amigavel{

	public function listagem($tabela,$id,$valor=null){

		

		return  $valor;

	}

	public function exibe($tabela,$valor=null,$PARAM=null){

		?>

        <input type="hidden" name="urlAmigaveCP" value="<?php echo trim($PARAM['campo'])?>"  />

		<?php



	}

	function view($tabela,$valor=''){

		return $valor;

	}

	

	function update($id,$tabela,$campo){

		

		$aux = DB::read($tabela);

		$aux->id = $id;

		$aux->load();

		



		$string = url_amigavel($_POST[$_POST['urlAmigaveCP']]);
		

		$aux->friendly_url = $string;

		$aux->update();
	}

	

	function salvar($id,$tabela){

		

		$aux = DB::read($tabela);

		$aux->id = $id;

		$aux->load();

		
		$string = url_amigavel($_POST[$_POST['urlAmigaveCP']]);
		$aux->friendly_url = $string;

		$aux->update();

		

	}

	

}

?>