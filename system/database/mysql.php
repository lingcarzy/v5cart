<?php
final class MySQL {
	private $link;
	private $queries = 0;
	private $sqls = array();
	private $hostname;
	private $database;
	public function __construct($hostname, $username, $password, $database) {
		$this->hostname = $hostname;
		$this->database = $database;
		if (!$this->link = mysql_connect($hostname, $username, $password)) {
      		trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
    	}

    	if (!mysql_select_db($database, $this->link)) {
      		trigger_error('Error: Could not connect to database ' . $database);
    	}
		
		mysql_query("SET NAMES 'utf8'", $this->link);
		mysql_query("SET CHARACTER SET utf8", $this->link);
		mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->link);
		mysql_query("SET SQL_MODE = ''", $this->link);
  	}
		
  	public function query($sql, $prefix = true) {
		
		if ($prefix) $sql = str_replace('@@', DB_PREFIX, $sql);
		
		$this->queries++;
		$this->sqls[] = $sql;
		
		$resource = mysql_query($sql, $this->link);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;
    	
				$data = array();
		
				while ($result = mysql_fetch_assoc($resource)) {
					$data[$i] = $result;
    	
					$i++;
				}
				
				mysql_free_result($resource);
				
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;
				$query->total = 0;
				
				unset($data);
				
				return $query;	
    		} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . mysql_error($this->link) . '<br />Error No: ' . mysql_errno($this->link) . '<br />' . $sql);
			exit();
    	}
  	}
	
	public function escape($value) {
		return mysql_real_escape_string($value, $this->link);
	}
	
  	public function countAffected() {
    	return mysql_affected_rows($this->link);
  	}

  	public function getLastId() {
    	return mysql_insert_id($this->link);
  	}	
	
	public function __destruct() {
		mysql_close($this->link);
	}
	
	///////////////////////////////////////////////////
	// Extend functions
	///////////////////////////////////////////////////
	public function queryOne($sql, $prefix = true) {
		global $log;
		
		if ($prefix) $sql = str_replace('@@', DB_PREFIX, $sql);
		
		$this->queries++;
		$this->sqls[] = $sql;
    	$query = mysql_query($sql, $this->link);
    	if (is_resource($query)) {
        	if(mysql_num_rows($query) > 0) {
        		$row = mysql_fetch_assoc($query);
        		if(count($row) == 1) {
        			$row = array_pop($row);
        		}
        		mysql_free_result($query);
        		return $row;
        	}
        } else {
            $log->write(mysql_error($this->link));
			$log->write($sql);
        }
    	return null;
    }
    
    public function queryArray($sql, $key = NULL, $value = NULL, $prefix = true) {
		global $log;
		
		if ($prefix) $sql = str_replace('@@', DB_PREFIX, $sql);
		
		$this->queries++;
		$this->sqls[] = $sql;
        $query = mysql_query($sql, $this->link);
        $data = array();
        if (is_resource($query)) {
			if (mysql_num_fields($query) > 1) {
				while($row = mysql_fetch_assoc($query)) {
					$data[$row[$key]] = $value ? $row[$value] : $row;
				}
			}
			else {
				while($row = mysql_fetch_assoc($query)) {
					$data[] = array_shift($row);
				}
			}           
    		mysql_free_result($query);
	    } else {
	        $log->write(mysql_error($this->link));
			$log->write($sql);
	    }
		return $data;
    }
	
	public function get($table, $where = NULL, $field = '*') {
		$_FIELDS = $this->_getFields(DB_PREFIX . $table);
		
		if(is_array($where)) {
			$_where = array();
			foreach ($where as $k => $v) {
				$_where[] = "`$k` = " .$this->_getValue($_FIELDS[$k], $v); 
			}
			$where = implode(' AND ', $_where);
    	}
		
		if ($where) $where = " WHERE $where";
		return $this->queryOne("SELECT $field FROM `@@$table` $where LIMIT 1");
	}
	
	public function insert($table, $data) {
    	$sql = 	$this->getInsertSql(DB_PREFIX . $table, $data);
    	if ($this->runSql($sql, false) > 0) {
			return mysql_insert_id($this->link);
		}
		return 0;
    }
    
    public function update($table, $data, $where = '1') {
    	$sql = $this->getUpdateSql(DB_PREFIX . $table, $data, $where);
    	return $this->runSql($sql, false);
    }
	
	/**
	 * @param $table table name
	 * @param $where string
	 */
	public function delete($table,  $where = NULL) {
		$_FIELDS = $this->_getFields(DB_PREFIX . $table);
		
		if(is_array($where)) {
			$_where = array();
			foreach ($where as $k => $v) {
				$_where[] = "`$k` = " .$this->_getValue($_FIELDS[$k], $v); 
			}
			$where = implode(' AND ', $_where);
    	}
		
		if ($where) $where = " WHERE $where";
		return $this->runSql("DELETE FROM `"  . DB_PREFIX . "$table` $where", false);
	}
	
	public function runSql($sql, $prefix = true) {
		global $log;
		
		if ($prefix) $sql = str_replace('@@', DB_PREFIX, $sql);
		
		$this->queries++;
		$this->sqls[] = $sql;
    	if (mysql_query($sql, $this->link)) {
    	    return mysql_affected_rows($this->link);
        }
        else {
            $log->write(mysql_error($this->link));
            $log->write($sql);
            return FALSE;
        }
    }
	
	public function getQueries() {
		return $this->queries;
	}
	
	public function getSqlQueries() {
		return $this->sqls;
	}
	
	private function getUpdateSql($table, $data, $where) {
		$t = array();
		$_FIELDS = $this->_getFields($table);
		
		if(is_array($where)) {
			$_where = array();
			foreach ($where as $k => $v) {
				$_where[] = "`$k` = " .$this->_getValue($_FIELDS[$k], $v); 
			}
			$where = implode(' AND ', $_where);
    	} elseif(empty($where)){
    		$where = "1";
    	}
		
    	foreach($data as $field => $value) {
			if (!isset($_FIELDS[$field]) || !is_scalar($value)) continue;
			if ($value === 'NOW()') {
				$t[] = "`$field` = NOW()";
			}
    		else $t[] = "`$field` = ".$this->_getValue($_FIELDS[$field], $value);
    	}
    	return "UPDATE `$table` SET ".implode(", ", $t)." WHERE $where";
    }
    
    private function getInsertSql($table, $data) {
    	$fields = array();
    	$values = array();
    	$sql = '';
		$_FIELDS = $this->_getFields($table);
		
    	if (isset($data[0])) { // multi-insert
    		foreach ($data[0] as $field => $value) {
				if (!isset($_FIELDS[$field]) || !is_scalar($value)) continue;
				$fields[] = "`$field`";
				if ($value === 'NOW()') {
					$values[] = "NOW()";
				}
				else $values[] = $this->_getValue($_FIELDS[$field], $value);
    		}
    		$sql = "INSERT INTO `$table` (".implode(",",$fields).") VALUES(".implode(",",$values).")";
    		for ($i = 1, $j = count($data); $i < $j; $i++) {
				$values = array();
				foreach ($data[$i] as $field => $value) {
					if (!isset($_FIELDS[$field]) || !is_scalar($value)) continue;
					if ($value === 'NOW()') {
						$values[] = "NOW()";
					}
					else $values[] = $this->_getValue($_FIELDS[$field], $value);
				}
				$sql .= ", (". implode(",", $values) .")";
    		}
    	} else {
    		foreach($data as $field => $value) {
				if (!isset($_FIELDS[$field]) || !is_scalar($value)) continue;
    			$fields[] = "`$field`";
    			if ($value === 'NOW()') {
					$values[] = "NOW()";
				}
				else $values[] = $this->_getValue($_FIELDS[$field], $value);
    		}
    		$sql = "INSERT INTO `$table` (".implode(",",$fields).") VALUES(".implode(",",$values).")";
    	}
    	return $sql;
    }
	
	protected function _getValue($type, $val) {
        $fieldType = strtolower($type);
        if (false === strpos($fieldType,'bigint') && false !== strpos($fieldType, 'int')) {
            $val = intval($val);
		} elseif (false !== strpos($fieldType, 'float') || false !== strpos($fieldType, 'double') 
			|| false !== strpos($fieldType, 'decimal')){
            $val = floatval($val);
		} elseif (false !== strpos($fieldType, 'bool')){
            $val   =  (bool)$val;
        }
		
		if (is_string($val)) {
            $val =  '\''.mysql_real_escape_string($val, $this->link).'\'';
		} elseif (is_bool($val)){
            $val =  $val ? '1' : '0';
		} elseif (is_null($val)){
            $val =  'null';
        }
		
		return $val;
    }
	
	protected function _getFields($tableName) {
		$info = cache_read($tableName . '.php', 'table');
		if ($info) return $info;
		
        $result =   $this->query("SHOW COLUMNS FROM `$tableName`");
        $info   =   array();
        if($result->num_rows) {
            foreach ($result->rows as $key => $val) {
                $info[$val['Field']] = $val['Type'];
            }
        }
		cache_write($tableName . '.php', $info, 'table');
        return $info;
    }
}
?>