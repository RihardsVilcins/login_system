<?php
class DB {
	// store instance of a database 
	private static $_instance = null;
	// store pdo object, query stores last query thats executed, error represents whether query failed, results stores data which we did select, count checks have there been any results returned 
	private $_pdo, $_query, $_error = false, $_results, $_count = 0;


	// connect to database, runs if class is instantiated
	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}

	// checks if object is instantiated, if not then instatiates it, if has then returns instance
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}


	// prepare sql string
	public function query($sql, $parameters = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if(count($parameters)) {
				foreach($parameters as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}

			// if query has executed succesfully stores result set
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}

		return $this;
	}

	// makes it easier to perform a query
	public function action($action, $table, $where = array()) {
		if(count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=');

			$field 	    = $where[0];
			$operator   = $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}

		return false;
	}

	// selects values in database
	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	// deletes values in database	
	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	// inserts values in database
	public function insert($table, $fields = array()) {
			$keys = array_keys($fields);
			$values = '';
			$x = 1;

			foreach($fields as $field) {
				$values .= "?";
				if($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}
			

			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) ."`) VALUES ({$values})";

			if(!$this->query($sql, $fields)->error()) {
				return true;
			}
		return false;
	}

	// updates values in database
	public function update($table, $id, $fields) {
		$set = '';
		$x = 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
				if($x < count($fields)) {
					$set .= ',';
				}
				$x++;
		}


		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

		if($this->query($sql, $fields)->error()) {
			return true;
		}

			return false;
	}

	// returns results
	public function results() {
		return $this->_results;
	}


	// returns first result
	public function first() {
		return $this->_results[0];
	}

	// is there has been an error method returns true
	public function error() {
		return $this->_error;
	}

	// retrieves results
	public function count() {
		return $this->_count;
	}
}
