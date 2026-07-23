<?php



class DB extends DB_Classe{

	public static function read($tabela){

		return $classe = new DB_Classe($tabela);

	}

	public static function doQuery($query){

		return $classe = new DB_ClasseDo($query);

	}

	

}



class DB_ClasseDo{

	protected $DB_BUSCA_DB;

	var $q;

	var $error;

	

	public function __construct($query){

		

		$this->DB_BUSCA_DB = new PDO('mysql:host='.HOST_BD.';dbname='.BANCO_BD.';charset=utf8mb4', USUARIO_BD, SENHA_BD);

			

		try

		{

			

			$this->DB_BUSCA_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$this->DB_BUSCA_DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			

			$this->q = $this->DB_BUSCA_DB->query($query);

			$this->error = false;

			return $this->q;

			//$q->setFetchMode(PDO::FETCH_OBJ);//FETCH_OBJ

			//$this->DB_BUSCA_RESULTS = $q->fetchAll();

			

		}

		catch (PDOException $e)

		{

			//echo '<p style="color:#f00">'.$qr.'</p>';

			//echo '<pre>';

			$this->error = $e->errorInfo[2];

			return ($e->errorInfo[2]);

			//exit;	

			

			

		}

	}

	public function fetchAll(){

		if($this->error == false){

			//$this->q->setFetchMode(PDO::FETCH_OBJ);//FETCH_OBJ

			return $this->q->fetchAll();

		}else{

			return false;	

		}

	}

	public function getError(){

		if($this->error != false){

			return $this->error;	

		}

	}



}

class DB_Classe{



	protected $DB_BUSCA_TABLE;

	protected $DB_BUSCA_DB;

	protected $DB_BUSCA_RESULTS;

	protected $DB_BUSCA_COUNT_NEXT = 0;

	var $vals;

	var $DB_BUSCA_WHERE = '(1=1)';



	

	public function __construct($tabela){
		$this->vals = new \stdClass();
	
		@$this->DB_BUSCA_TABLE = $tabela;

		$this->DB_BUSCA_DB = new PDO('mysql:host='.HOST_BD.';dbname='.BANCO_BD.';charset=utf8mb4', USUARIO_BD, SENHA_BD);

	}

	

 

	public function __set($name, $value) {

		  $methodName = 'set'.ucfirst($name);

		  if (method_exists($this, $methodName)):

			$this->$methodName($value);

		  else:

			@$this->{$name} = $value;

			@$this->vals->$name = $value;

			

		  endif;

    }

 

 

    public function __get($name) {

		$methodName = 'get'.ucfirst($name);

		if (method_exists($this, $methodName))

			return $this->$methodName($value);

		else

			

			return $this->$name;

    }  	

    

	

	

	

	

	public function load($ordemLimit=null,$sqlAdicional=null){

		

		if(!empty($this->vals))

		foreach($this->vals as $k=>$v):

			$this->DB_BUSCA_WHERE .= " AND {$k} = '{$v}' ";			

		endforeach;

		

	

		$orderby = ($ordemLimit != "" ? "ORDER BY {$ordemLimit}" : "");

		

		$this->DB_BUSCA_WHERE = ($sqlAdicional!=""?$this->DB_BUSCA_WHERE.' AND ('.$sqlAdicional.')':$this->DB_BUSCA_WHERE);

		

		$qr = "SELECT * FROM ".$this->DB_BUSCA_TABLE." WHERE  ".$this->DB_BUSCA_WHERE." {$orderby}";

				

			

			

		try

		{

			

			$this->DB_BUSCA_DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$this->DB_BUSCA_DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			

			$q = $this->DB_BUSCA_DB->query($qr);

			$q->setFetchMode(PDO::FETCH_OBJ);//FETCH_OBJ

			$this->DB_BUSCA_RESULTS = $q->fetchAll();

			

		}

		catch (PDOException $e)

		{

			//print $e->getMessage();

			

			echo '<p style="color:#f00">'.$qr.'</p>';

			

			echo $e->getMessage();

			exit;	

			

			

		}

		

		

		

			

		if(!empty($this->DB_BUSCA_RESULTS))

		foreach($this->DB_BUSCA_RESULTS[$this->DB_BUSCA_COUNT_NEXT] as $k=>$v){

			$this->$k=$v;

		}

		$this->DB_BUSCA_COUNT_NEXT++;

		return;

	

		

		

		

			

	}

	

	public function save(){

		$DB_SAVE = array();

		if(!empty($this->vals)):

			foreach($this->vals as $k=>$v):

				$DB_SAVE[$k]= $this->$k;			

			endforeach;

	

			$campos = implode(", ", array_keys($DB_SAVE));

			$valores = "'".implode("', '", array_values($DB_SAVE))."'";

			//return "INSERT INTO ".$this->DB_BUSCA_TABLE." ({$campos}) VALUES ({$valores})";

			$this->DB_BUSCA_DB->query("INSERT INTO ".$this->DB_BUSCA_TABLE." ({$campos}) VALUES ({$valores})");

			return $this->DB_BUSCA_DB->lastInsertId();

		else:

			return false;

		endif;

	}

	

	public function delete($sqlAdicional=null){

		if(!empty($this->vals)):

			$this->DB_BUSCA_WHERE = '(1=1)';

			foreach($this->vals as $k=>$v):

				$this->DB_BUSCA_WHERE .= " AND {$k} = '".$this->$k."' ";			

			endforeach;

			

			$this->DB_BUSCA_WHERE = ($sqlAdicional!=null?$this->DB_BUSCA_WHERE.' AND ('.$sqlAdicional.')':$this->DB_BUSCA_WHERE);

			

			$this->DB_BUSCA_DB->query("DELETE FROM ".$this->DB_BUSCA_TABLE." WHERE ".$this->DB_BUSCA_WHERE."");

			return true;

		else:

			return false;

		endif;

			

	}

	

	public function update(){		

		

		

		if(!empty($this->vals)):		

			$DB_UPDATE = array();

			$PRICHAVEWHERE = '';

			foreach( $this->vals as $ind => $val ){

				if($ind == 'id'){

					$PRICHAVEWHERE = " id = '".addslashes($this->$ind)."'";

				}else{

					$DB_UPDATE[] = "{$ind} = '".addslashes($this->$ind)."'";	

				}

			}

			if($PRICHAVEWHERE != ''){

				$this->DB_BUSCA_WHERE = $PRICHAVEWHERE;

			}

			$campos = implode(", ", $DB_UPDATE);

			$qr = "UPDATE ".$this->DB_BUSCA_TABLE." SET {$campos} WHERE ".$this->DB_BUSCA_WHERE."";

			

			try{

				$this->DB_BUSCA_DB->query($qr);

			}catch (PDOException $e){

				echo '<p style="color:#f00">'.$qr.'</p>';

				echo $e->getMessage();

				exit;	

			

			}

						

			return $campos;

		else:

			return false;

		endif;

	}

	

	

	public function next(){

		

		if($this->DB_BUSCA_COUNT_NEXT<count($this->DB_BUSCA_RESULTS)){

			if(!empty($this->DB_BUSCA_RESULTS[$this->DB_BUSCA_COUNT_NEXT]))		

			foreach($this->DB_BUSCA_RESULTS[$this->DB_BUSCA_COUNT_NEXT] as $k=>$v){

				$this->$k=$v;

			}

			$this->DB_BUSCA_COUNT_NEXT++;

			return true;

		}else{

			if(!empty($this->DB_BUSCA_RESULTS[0]))	

			foreach($this->DB_BUSCA_RESULTS[0] as $k=>$v){

				$this->$k=$v;

			}

			

			return false;	

		}

		

	

			

	}

	

	public function size(){

		$NUM_RESULTS = count($this->DB_BUSCA_RESULTS);

		if($NUM_RESULTS>0){

			return $NUM_RESULTS;

		}else{

			return 0;	

		}

			

	}

	

	

	

	

	public function join($item1,$item2){

		

		return $this->$item1;

		

	}

	

}



?>