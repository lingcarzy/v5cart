<?php
class DB {
	private $driver;
	
	public function __construct($driver, $hostname, $username, $password, $database) {
		if (file_exists(DIR_DATABASE . $driver . '.php')) {
			require_once(DIR_DATABASE . $driver . '.php');
		} else {
			exit('Error: Could not load database file ' . $driver . '!');
		}
				
		$this->driver = new $driver($hostname, $username, $password, $database);
	}
		
  	public function query($sql) {
		return $this->driver->query($sql);
  	}
	
	public function escape($value) {
		return $this->driver->escape($value);
	}
	
  	public function countAffected() {
		return $this->driver->countAffected();
  	}

  	public function getLastId() {
		return $this->driver->getLastId();
  	}	
	public function runSql($sql) {
		return $this->driver->runSql($sql);
	}
	public function queryOne($sql) {
		return $this->driver->queryOne($sql);
	}
	
	public function queryArray($sql, $key = NULL, $value = NULL) {
		return $this->driver->queryArray($sql, $key, $value);
	}
	 
	public function get($table, $where = NULL, $field = '*') {
		return $this->driver->get($table, $where, $field);
	}
	public function insert($table, $data) {
		return $this->driver->insert($table, $data);
	}
	public function update($table, $data, $idField, $where = '') {
		return $this->driver->update($table, $data, $idField, $where);
	}
	public function delete($table,  $where = NULL) {
		return $this->driver->delete($table, $where);
	}
	public function getSqlQueries() {
		return $this->driver->getSqlQueries();
	}
	public function getQueries() {
		return $this->driver->getQueries();
	}
}
?>