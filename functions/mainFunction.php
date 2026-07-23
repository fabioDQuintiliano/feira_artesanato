<?php



	class Model{

		protected $db;
		public function __construct(){

			$this->db = new PDO('mysql:host='.HOST_BD.';dbname='.BANCO_BD.';charset=utf8mb4', USUARIO_BD, SENHA_BD);
			//$this->db = new PDO('mysql:host='.HOST_BD.';dbname='.BANCO_BD, USUARIO_BD, SENHA_BD);

		}

		

		public function insert($tabela, Array $dados ){

			$campos = implode(", ", array_keys($dados));

			$valores = "'".implode("', '", array_values($dados))."'";


			try{
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

				$this->db->query("INSERT INTO `{$tabela}` ({$campos}) VALUES ({$valores})");
				
				return $this->db->lastInsertId();
			}catch(PDOException $e){

				echo '<p style="color:#f00">'.$qr.'</p>';

				

				echo $e->getMessage();

				exit;	

			}

		}



		public function read( $tabela, $where = null, $limit = null, $offset = null, $orderby = null, $groupby = null ){

			$where = ($where != null ? "WHERE {$where}" : "");

			$limit = ($limit != null ? "LIMIT {$limit}" : "");

			$offset = ($offset != null ? "OFFSET {$offset}" : "");

			$orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");

			$groupby = ($groupby != null ? "GROUP BY {$groupby}" : "");

			

			$qr = "

					 SELECT * FROM `{$tabela}` {$where} {$groupby} {$orderby} {$limit} {$offset}

					 

				   

					 ";

			

			//var_dump($qr);

			try{

				

			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			$q = $this->db->query($qr);

			

			$q->setFetchMode(PDO::FETCH_ASSOC);

			$ret_query = $q->fetchAll();

			

			

			}catch(PDOException $e){

				echo '<p style="color:#f00">'.$qr.'</p>';

				

				echo $e->getMessage();

				exit;	

			}

				//exit;

				

				return $ret_query;

			

			

		}

		



		public function query($query){

			

			$qr = $query ;

			

			//var_dump($qr);

			$q = $this->db->query($qr);

			$q->setFetchMode(PDO::FETCH_ASSOC);

			$ret_query = $q->fetchAll();

			return $ret_query;

		}

		



		

		public function read_ds( $item , $tabela, $where = null, $limit = null, $offset = null, $orderby = null, $groupby = null ){

			$where = ($where != null ? "WHERE {$where}" : "");

			$limit = ($limit != null ? "LIMIT {$limit}" : "");

			$offset = ($offset != null ? "OFFSET {$offset}" : "");

			$orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");

			$groupby = ($groupby != null ? "GROUP BY {$groupby}" : "");

			$q = $this->db->query("SELECT {$item} FROM `{$tabela}` {$where} {$groupby} {$orderby} {$limit} {$offset}");

			$q->setFetchMode(PDO::FETCH_ASSOC);

			return $q->fetchAll();

		}



		public function update( $tabela, Array $dados, $where ){

			foreach( $dados as $ind => $val ){

				$campos[] = "{$ind} = '{$val}'";	

			}

			$campos = implode(", ", $campos );


			$this->db->query( "UPDATE `{$tabela}` SET {$campos} WHERE {$where}");



		}

		

		public function delete( $tabela, $where ){

			$this->db->query("DELETE FROM `{$tabela}` WHERE {$where}");

			}

		

		public function listaTabela(){

			$q = $this->db->query("SHOW TABLES");	

			$q->setFetchMode(PDO::FETCH_ASSOC);

			return $q->fetchAll();

		}

		

		public function listaCampos( $tabela ){

			$q = $this->db->query("SHOW COLUMNS FROM `{$tabela}`");	

			$q->setFetchMode(PDO::FETCH_ASSOC);

			return $q->fetchAll();

		}



		/*

		

		private $table;

		private $query = "(1=1) ";

		public function make($tabela){

			$this->table = $tabela;

		}

		

		public function load(){

			

			

			$vals = $this;

			$quer = "(1=1) ";

			

			foreach($vals as $k=>$v):

				

				

				if($k!='' && $k!='db' && $k!='table' && $k!='query')

				$quer .= " AND (".$k." = '".$v."')";

			

			endforeach;

			

			

			$this->query = $quer;

			$qry = "SELECT * FROM ".$this->table." WHERE  ".$this->query."";

			try{

				$q = $this->db->query($qry);

				$q->setFetchMode(PDO::FETCH_OBJ);

				$final = $q->fetchAll();

				return ($final);

			}catch(Exception $e){

				return $e;	

			}

			

			

		}*/

		

		

	}

?>