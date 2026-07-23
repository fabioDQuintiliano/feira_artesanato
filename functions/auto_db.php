<?php
class Database{
	public static $db;
	public function instance($externo=''){
		//if(!self::$db){
			self::$db = $this->connect($externo);
		//}
		return self::$db;
	}

	private function connect($externo=''){
		if($externo == true){

			$db = new PDO('mysql:host='.HOST_BD_EX.';dbname='.BANCO_BD_EX, USUARIO_BD_EX, SENHA_BD_EX);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}else{
			$db = new PDO('mysql:host='.HOST_BD.';dbname='.BANCO_BD.';charset=utf8mb4', USUARIO_BD, SENHA_BD);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}



		
		
		return $db;
	}
}

class DB_Functions{
	public $db;
	private $data;
	private $data_append = array();
	private $data_where = array();
	private $report_mode = false;
	private $data_result;
	private $data_group_by;
	private $data_result_base;
	private $data_result_count = 0;
	var $queryBeenExecutedPdo;
	var $primaryKeysFromTablePdo  = array();

	private $table;
	public function __construct($tabela='',$externo=''){
		
		$db = new Database(); 
		$this->db = $db->instance($externo);
		if($tabela){
			$this->table = $tabela;
		}
	}
 	public function __call($name, $arguments)
    {
   
        if(substr($name, 0,1) == "_"){
        	
        	$this->data[substr($name, 1)] = $arguments;

        }else{
        	$this->data[$name] = $arguments;

        }

    	return $this;
    }
    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        echo "Calling static method '$name' "
             . implode(', ', $arguments). "\n";
    }

    public function __set($name, $value)
    {

        if($name){


	        if($this->data_result->$name){
	        	$this->data_result->$name = $value;
	        }else{
	        	
	        	if(!$this->data_result || $this->data_result == null){
	        		$this->data_result = (object)array();
	        	}
	        	$this->data_result->$name = $value;
	        	//echo $value;
	        }
	        if($this->data[$name]){
	        	$this->data[$name] = $value;
	        }else{
	        	
	        	if(!$this->data || $this->data == null){
	        		$this->data = array();
	        	}
	        	
	        	$this->data[$name] = $value;
	        
	        }
	    }
        
    }

    public function __get($name)
    {
        if($this->data_result->$name){
        	return $this->data_result->$name;
        }else{
        	return;
        }
    }    
    public function __isset($name)
    {
        echo "Is '$name' set?\n";
        return isset($this->data[$name]);
    }

    public function __invoke($x)
    {
        var_dump($x);
    }

    public function executeWhenError($e,$query){
 		
		echo "<br /><p style='color:#f00; background:#fff; padding:10px; margin:10px; border-radius:10px;'>";
		echo "<spam style='color:#666;'>".($e->getMessage())."</spam><br />";
		echo "<spam style='color:#000'>Query executada:</spam><br />";
		//echo $e->getFile();
		//echo $e->getTrace()[0]['args'][0];

		echo '<pre>';
		print_r($e->getTrace());
		echo '</p>';
		//exit;
    }
	
	public function _append($data,$alias){
		$this->data_append[] = array(
									"data"=>$data,
									"alias"=>$alias
								);
		return $this;
	}


	public function _where($wr){
		$this->data_where[] = $wr;
		return $this;
	}
	public function where($wr){
		$this->data_where[] = $wr;
		return $this;
	}
	public function _group($data){
		$this->data_group_by = $data;
		return $this;
	}

	public function _setReportMode($bool=false){
		$this->report_mode = $bool;
		return $this;
	}

	public function _load($oreder="",$where=""){
		$options = array("loadall"=>false);
		return $this->execLoad($oreder,$where,$options);
	}

	public function _loadAll($oreder="",$where=""){
		$options = array("loadall"=>true);
		return $this->execLoad($oreder,$where,$options);
	}

	public function load($oreder="",$where=""){
		$options = array("loadall"=>false);
		return $this->execLoad($oreder,$where,$options);
	}

	public function loadAll($oreder="",$where=""){
		$options = array("loadall"=>true);
		return $this->execLoad($oreder,$where,$options);
	}



	public function getWhere($where=null){

		$WR = "(1=1)";
	
		if($this->data_where && sizeof($this->data_where) > 0){
			foreach ($this->data_where as $busca) {
				$WR .= " AND ({$busca})";
			}
		}

		if($where){
			$WR .= " AND ({$where})";
		}




		return $WR;



	}


	private function execLoad($order="",$where="",$options=""){

		try{


			$where = $this->getWhere($where);
			//define quais campos serão retornados na query

			$camposAppend = array();
			//if($this->report_mode != true){
				// $q = new Model;		 
				// $campos_tabela = $q->listaCampos($this->table);

				// for($i = 0;$i<=count($campos_tabela);$i++){
				// 	if($campos_tabela[$i]['Field'] != '')
				// 	$camposAppend[] = $this->table.'.'.$campos_tabela[$i]['Field'];
				// }
				//$camposAppend[] = "*";
			//}

			foreach ($this->data_append as $key => $value) {
				$camposAppend[] = "(".$value["data"].") as ".$value["alias"];
			}


			//formata os parametros da consulta para montar a query;
			$camposSelect = "";

			if($this->data){
				foreach ($this->data as $key => $value) {
					$camposSelect .= " AND ".$key." = :".$key;
				}
			}
			$ordem = '';
			if($order){
				$ordem = " ORDER BY ".$order;
			}
			$group = '';
			if($this->data_group_by != ''){
				$group = " GROUP BY ".$this->data_group_by." ";
				$this->data_group_by = '';
			}
			//monta a query;


			$camposSql = ($camposAppend && sizeof($camposAppend)>0)?$this->table.'.*,'.implode(",",$camposAppend):$this->table.'.*';

			$this->queryBeenExecutedPdo = $this->db->prepare("SELECT ".$camposSql." FROM ".$this->table." WHERE (1=1) ".($camposSelect).($where != ""?" AND (".$where.")":"").($group).($ordem));
			

			//var_dump($this->data);
			//define os valores dos parametros da  query;
			if($this->data){

				foreach ($this->data as $key => $value) {
					if(is_array($value) && $value[0] != null){
						$val = $value[0];
					}else{
						$val = $value;
					}
					
					$valueType = gettype($val);
					$pdoType  = PDO::PARAM_STR;
					//verifica o tipo do valor do parametro;
					if($valueType == "integer"){
						$pdoType  = PDO::PARAM_INT;
					}else if($valueType == "boolean"){
						$pdoType  = PDO::PARAM_BOOL;
					}
					//seta o valor na query;
					$this->queryBeenExecutedPdo->bindValue(":".$key,$val,$pdoType);
					
				}
			}
			
			$this->queryBeenExecutedPdo->execute();
			$this->queryBeenExecutedPdo->setFetchMode(PDO::FETCH_OBJ);

			//if($options['loadall'] == true){

				$resultado = $this->queryBeenExecutedPdo->fetch();
				
				$this->data_result = $resultado;
				$this->data_result_base = $resultado;
				return $this;

			// }else{
			// 	return  $query->fetch();
			// }
			
		}catch (PDOException $e)
		{
			$this->executeWhenError($e,$query->queryString);
		}

	}
	public function next(){

		$resultado = $this->queryBeenExecutedPdo->fetch();
	 	if(!$resultado)return false;

		$this->data_result = $resultado;
		return $this;
		
	}
	public function size(){
		
		return $this->queryBeenExecutedPdo->rowCount();
	}
	private function getTableKeys(){
		if(count($this->primaryKeysFromTablePdo) > 0){
			return;
		}

		$infoTable = $this->db->prepare('SELECT information_schema.KEY_COLUMN_USAGE.COLUMN_NAME as COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE information_schema.KEY_COLUMN_USAGE.CONSTRAINT_NAME LIKE "PRIMARY" AND information_schema.KEY_COLUMN_USAGE.TABLE_SCHEMA LIKE "'.BANCO_BD.'" AND information_schema.KEY_COLUMN_USAGE.TABLE_NAME LIKE "'.$this->table.'"');
			
			
		$infoTable->execute();
		$infoTable->setFetchMode(PDO::FETCH_OBJ);
		$resultInfoTable = $infoTable->fetchAll();
		
		$auxKeys = array();
		foreach($resultInfoTable as $k=>$v){
			$auxKeys[] = $v->COLUMN_NAME;
		}

		$this->primaryKeysFromTablePdo = $auxKeys;
		return;
	}
	public function update($where=""){
		//$options = array("loadall"=>true);
		return $this->execUpdate($where,$options);
	}
	public function _update($where=""){
		//$options = array("loadall"=>true);
		return $this->execUpdate($where,$options);
	}
	private function execUpdate($where=""){
		try{

			
			$this->getTableKeys();
			
			$whereToUpdateQuery = "(1=1)";
			$whereToUpdateValues = array();
			if(count($this->primaryKeysFromTablePdo) > 0){
				
				foreach($this->primaryKeysFromTablePdo as $key=>$value){
					

					$whereToUpdateQuery .= " AND ".$value." = :whr".$value;
					$whereToUpdateValues[$value] = $this->data_result->$value;


				}

			}

			//formata os parametros da consulta para montar a query;
			$camposUpdate = array();
			foreach ($this->data_result as $key => $value) {
				if(in_array($key, $this->primaryKeysFromTablePdo)){continue;}
				$camposUpdate[] = $key." = :".$key;
			}



			$queryPart = implode(", ", $camposUpdate);		
			if($queryPart != ""){
				$queryPart = " SET ".$queryPart;
			}

			if($where != ''){
				$whereToUpdateQuery = $whereToUpdateQuery." AND (".$where.")";
			}
			
			//monta a query;
			//echo "UPDATE ".$this->table." ".$queryPart." WHERE ".$whereToUpdateQuery;
			
			$updateQuery = $this->db->prepare("UPDATE ".$this->table." ".$queryPart." WHERE ".$whereToUpdateQuery);
			$arrayValues = array();
			//define os valores dos parametros da  query;
			foreach ($this->data_result as $key => $value) {
				if(in_array($key, $this->primaryKeysFromTablePdo)){continue;}
				$arrayValues[":".$key] = $value;
				
			}
			//define os valores dos parametros da  query;
			foreach ($whereToUpdateValues as $key => $value) {
				$arrayValues[":whr".$key] = $value;
			}


			$resutlQuery = $updateQuery->execute($arrayValues);
			if($resutlQuery == true){
				return $this;
			}else{
				return false;
			}
			
		}catch (PDOException $e)
		{
			$this->executeWhenError($e,$query->queryString);
		}


	}
	public function save($where=""){
		//$options = array("loadall"=>true);
		return $this->execSave($options);
	}
	public function _save($where=""){
		//$options = array("loadall"=>true);
		return $this->execSave($options);
	}
	private function execSave(){
		try{
			$insertKeys = array();
			$insertValues = array();
			$valuesToInsert = array();
			foreach ($this->data_result as $key => $value) {
				//if(in_array($key, $this->primaryKeysFromTablePdo)){continue;}
				if($key != 'id'){

				$key = $key === ''?null:$key;
				$insertKeys[] = $key;
				$insertValues[] = ":".$key;
				$valuesToInsert[":".$key] = $value;
				}
			}

			// $qr = "INSERT INTO ".$this->table." (".implode(',',$insertKeys).") VALUES (".implode(',',$insertValues).")";
			// echo $qr;

		//	var_dump($valuesToInsert);
			$insertQuery = $this->db->prepare("INSERT INTO ".$this->table." (".implode(',',$insertKeys).") VALUES (".implode(',',$insertValues).")");

			$infoInsert = $insertQuery->execute($valuesToInsert);

			//$infoInsert = $insertQuery->fetch(PDO::FETCH_OBJ);
			if($infoInsert == true){
				return $this->db->lastInsertId();
			}else{
				return false;
			}
			

		}catch (PDOException $e)
		{
			$this->executeWhenError($e,$query->queryString);
		}

	}

	public function delete($where=""){
		//$options = array("loadall"=>true);
		return $this->execDelete($where,$options);
	}
	public function _delete($where=""){
		//$options = array("loadall"=>true);
		return $this->execDelete($where,$options);
	}
	private function execDelete($where){
		try{

			$keysToQuery = "(1=1)";
			$valuesToQuery = array();

			//var_dump($this->data_result);
			if($this->data_result->id){
				
				$keysToQuery .= " AND id = :id";
				$valuesToQuery[":id"] = $this->data_result->id;

			}else{

				if($this->data){
					foreach ($this->data as $key => $value) {
						
						$keysToQuery .= " AND ".$key." = :whr".$key;
						$valuesToQuery[":whr".$key] = $value[0];
						
					}
				}
				if($this->data_result){
					foreach ($this->data_result as $key => $value) {

						$keysToQuery .= " AND ".$key." = :".$key;
						$valuesToQuery[":".$key] = $value;
						
					}
				}
			}

			if(trim($where) != ''){
				$keysToQuery = $keysToQuery." AND (".$where.")";
			}
		
		//	echo "DELETE FROM ".$this->table." WHERE ".$keysToQuery;
			
			$deleteQuery = $this->db->prepare("DELETE FROM ".$this->table." WHERE ".$keysToQuery);

			$infoDelete = $deleteQuery->execute($valuesToQuery);

			if($infoDelete == true){
				return true;
			}else{
				return false;
			}

		}catch (PDOException $e)
		{
			$this->executeWhenError($e,$query->queryString);
		}

	}	
	public function doQuery($query){
		

		try{

			//monta a query;
			$this->queryBeenExecutedPdo = $this->db->prepare($query);
			
			
			$this->queryBeenExecutedPdo->execute();
			$this->queryBeenExecutedPdo->setFetchMode(PDO::FETCH_OBJ);


			$resultado = $this->queryBeenExecutedPdo->fetch();
			
			$this->data_result = $resultado;
			$this->data_result_base = $resultado;
			return $this;

			
		}catch (PDOException $e)
		{
			$this->executeWhenError($e,$query->queryString);
		}

	}
}
class DB_Class{

	public static function make($tabela,$externo=''){
		return $classe = new DB_Functions($tabela,$externo);
	}
	public static function doQuery($query){
		$classe = new DB_Functions();
		return $classe->doQuery($query);
	}

}
class DAO{


	
    public static function __callStatic($name, $arguments){
    	$tabela = strtolower($name);
    	return $classe = new DB_Functions($tabela,false);
    }

	public static function make($tabela,$externo=''){
		return $classe = new DB_Functions($tabela,$externo);
	}
	public static function doQuery($query){
		$classe = new DB_Functions();
		return $classe->doQuery($query);
	}

}

