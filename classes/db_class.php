<?php 
require_once('configs/db_config.php');
   class mysql {
	var $db_host;
	var $db_user;
	var $db_pass;
	var $db_name;
	var $dbh;
	var $select;
	var $sql;
	function connect() {
		if (func_num_args() == 4) { 
			$db_host = func_get_arg(1); 
			$db_user = func_get_arg(2); 
			$db_pass = func_get_arg(3); 
			$db_name = func_get_arg(4);	
		} else {
			$db_host = SQL_HOST;
			$db_user = SQL_USER;
			$db_pass = SQL_PASS;
			$db_name = SQL_DB_NAME; 
		}
		if (is_resource($this->dbh) && func_num_args() != 4) {return true;}
		
		if (!$this->dbh = mysql_connect($db_host,$db_user,$db_pass)) {
            echo 'Could not connect';
            echo '<a href="'.$_SERVER['REQUEST_URI'].'">Refresh</a>';
			exit;

		} else {
			if (!mysql_select_db($db_name, $this->dbh)) {
            echo 'Could not connect';
            echo '<a href="'.$_SERVER['REQUEST_URI'].'">Refresh</a>';
			exit;
			} else {
				return true;
			} 
		}
	}
	
	function Query($sql) {
		if (!$this->dbh) {$this->connect();}
				$_SESSION['Q']=$_SESSION['Q']+1;
		if (!$this->results = mysql_query($sql, $this->dbh)) {
			return false;
		} else {
			return true;
		}
		
		
		
	}
	
	function FetchArray($qid = '') {
		if($qid == NULL) {
			$r = mysql_fetch_array($this->results);
		} else {
			$r = mysql_fetch_array($qid);
		}
		if(is_null($r)) {
			return false;
		} else {
			return $r;
		}
	}
	function getAffectedRows(){
		return mysql_affected_rows();
	}
	function FetchAssoc($qid = '') {
		if($qid == NULL) {
			$r = mysql_fetch_assoc($this->results);
		} else {
			$r = mysql_fetch_assoc($qid);
		}
		if(is_null($r)) {
           return false; 
		} else {
			return $r;
		}
	}

	
	function fetch_object($qid = '') {
		if($qid == NULL) {
			$r = mysql_fetch_object($this->results);
		} else {
			$r = mysql_fetch_object($qid);
		}
		if(is_null($r)) {
			return false;
		} else {
			return $r;
		}
	}
    function lastInsertID(){
    	return mysql_insert_id();
    }

	function end() {
		mysql_free_result($dbh);
	}
}

?>