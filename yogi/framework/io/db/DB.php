<?php
namespace yogi\framework\io\db; 
use \PDO;
use \PDOStatement;
use \PDOException;
use \PDORow;
use \Exception;
// $Id;
/**
 * 
 * This is an implementation of the ORM pattern.
 * @author Kristoffer "krol" Olsson - kristoffer.olsson@madebykrol.com
 * @version 1.0 - Stable
 * @license new bsd-license
 * @example  
 *
 */
class DB {
	
	protected /* PDO */ $pdo = null;
	protected /* String */ $driver = "";
	protected /* db */ $database = "";
	protected /* string */ $select = "*";
	protected /* string */ $table = '';
	protected /* string */ $where = '';
	protected /* string */ $limit = '';
	protected /* string */ $join = '';
	protected /* string */ $order = '';
	protected /* string */ $group = '';
	protected /* string */ $distinct  = '';
	protected /* string */ $update = '';
	
	protected /* array */ $insertFields = array();
	protected /* array */ $insertValues = array();
	protected /* int */ 	$bindFieldCounter = 1;
	protected /* array */ $whereBindValues = array();
	protected /* array */ $insertBindValues = array();
	protected /* array */ $orderBindValues = array();
	protected /* array */ $groupBindValues = array();
	protected /* array */ $updateBindValues = array();
	protected /* array */ $limitBindValues = array();
	
	protected /* boolean */ $useQueryCache = false;
	
	protected /* array */ $result = array();
	
	public function __construct($string) {
		
		$string = explode(";", $string);
		$connectionString = array();
		foreach($string as $str) {
			$str = explode("=", $str);
			$connectionString[$str[0]] = $str[1];
		}
		
		
		$pdoString = strtolower($connectionString['Driver']).":host=".$connectionString['Server'].";dbname=".$connectionString['Database'];
		$this->driver = strtolower($connectionString['Driver']);
		$this->host = $connectionString['Server'];
		$this->pdo = new PDO($pdoString, $connectionString['User'], $connectionString['Password']);
	}
	
	public function __destruct() {
		$this->pdo = null;
	}
	
	public function setPDO (PDO $db) {
		$this->pdo = $db;
	}
	
	public function getColumns($table) {
		$columns = $this->query('SHOW COLUMNS FROM '.$this->sanitize($table));
		$this->flushResult(true);
		return $columns;
	}
	
	/**
	 * Get data from $table with optional limit, offset
	 * @param string $table
	 * @param int $limit
	 * @param int $offset
	 * @return boolean
	 * @throws Exception
	 */
	public /* boolean */ function get($table = null, $limit = null, $offset = null) {
		
		$this->flushResult();
		if (isset($table)) {
			$this->table = $table;
		} 
		
		if (isset($limit)) {
			$this->limit($limit, $offset);
		}
		
		$select = $this->getSelectQuery();
		
		$statement = $this->pdo->prepare($select);
		
		if ($this->execute($statement) ){
			
			return $this->getResult();
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
		
	}
	
	/**
	 * Get data from table with a where clause.
	 * @param string $table
	 * @param array $where
	 * 		An array of fields to match for the query
	 * 		array(
	 * 			array('field1', '=', 'value1'),
	 * 			array('field2', '!=', 'value2'),
	 * 			'OR',
	 * 			array('field3', 'like', '%value3'),
	 *    );
	 *    As you can see there is only an OR between field2 and field3, if nothing is specified default AND is used.
	 * @param int $limit
	 * @param int $offset
	 * @return boolean
	 * @throws Exception
	 */
	
	public /* boolean */ function getWhere($table = null, $whereArray , $limit = null, $offset = null) {
		$statement = null;
		
		if(isset($table)){
			$this->table = $table;
		}
		
		$condition = "AND";
		for($i = 0; $i < count($whereArray); $i++) {
			if (is_string($whereArray[$i])) {
				$condition = $whereArray[$i];
				$i++;
			}
			if($condition == "AND") {
				$this->where($whereArray[$i]);
			}
			else {
				$this->whereOR($whereArray[$i]);
			}
		}
		
		if (isset($limit)) {
			$this->limit($limit, $offset);
		}
		
		$statement = $this->pdo->prepare($this->getSelectQuery());
		
		if ($this->execute($statement) ){
			
			return $this->getResult();
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}
	/**
	 * Deletes entries from $table with the WHERE clause if it's present
	 * @param string $table
	 * @return boolean
	 * @throws Exception
	 */
	public /* boolean */ function delete($table = null) {
		if (isset($table)) {
			$this->table = $table;
		}
		
		$statement = $this->pdo->prepare($this->getDeleteQuery());
		
		if ($this->execute($statement) ){
			
			return true;
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}

	
	/**
	 * Truncate whole table.
	 * @param unknown_type $table
	 * @return boolean
	 * @throws Exception
	 */
	public /* exception */ function truncate($table = null) {
		if (isset($table)) {
			$this->table = $table;
		}
		
		$statement = $this->pdo->prepare($this->getTruncateQuery());
		if ($this->execute($statement) ){
			
			return true;
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}
	
	/**
	 * Get the count of rows on atable.
	 * @param string $table
	 * @return boolean
	 * @throws Exception
	 */
	public /* boolean */ function countAll($table) {
		$statement = $this->pdo->prepare($this->getCountAllQuery());
		
		$this->execute($statement);
	}
	
	/**
	 * Get the count from the generated query.
	 * @return boolean
	 * @throws Exception
	 */
	public /* boolean */ function countAllResults() {
		$statement = $this->pdo->prepare($this->getCountAllResultsQuery());
		
		if ($this->execute($statement) ){
			
			return true;
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}
	
	/**
	 * @todo implement function.
	 * Adds to the where clause for select delete and update
	 * @param unknown_type $field
	 * @param unknown_type $value
	 */
	public /* void */ function set ($field, $value) {
		
	}
	
	/**
	 * Insert one row into the table.
	 * @param [string $table]
	 * @param array $dataSet
	 * 	@example array('title' => 'New Title', 'body' => 'My booodyyy')
	 * 
	 * @return boolean
	 * @throws Exception
	 */
	public /* boolean */ function insert ($table = null, $dataSet = array()) {
		if (isset($table)) {
			$this->table = $table;
		}
		
		$values = array();
		foreach($dataSet as $key => $val) {
			$this->insertFields[$key] = '`'.$key.'`';
			$values[] = $val;
		}
		
		$this->insertBindValues[] = $values;
		
		$statement = $this->pdo->prepare($this->getInsertQuery());
		
		if ($this->execute($statement) ){
			return true;
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}
	
	/**
	 * Insert multiple rows into the table.
	 * @param [string $table]
	 * @param array $dataSet
	 * 	@example array(array('title' => 'New Title', 'body' => 'My body'), array('title' => 'Second ny title', 'body' => 'myBody 2')
	 * 
	 * @return boolean
	 * @throws Exception
	 */
	
	public /* boolean */ function insertBatch ($table = null, $dataSet) {
		if (isset($table)) {
			$this->table = $table;
		}
		
		foreach ($dataSet as $insertSet) {
			foreach ($insertSet as $key => $val) {
				$this->insertFields[$key] = $key;
				
			}
		}
		
		$this->insertBindValues = $dataSet;
		$statement = $this->pdo->prepare($this->getInsertQuery());
		
		if ($this->execute($statement) ){
			
			return true;
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}
	
	/**
	 * Updates fields in table.
	 * @param [string $table]
	 * @param array $dataSet
	 * 	 getActiveDB()->update(null, array('title' => 'My New Title', 'body' => 'The awesome body'));
	 * 
	 * @return boolean
	 * @throws Exception
	 */
	public /* boolean */ function update ($table = null, $dataSet = array()) {
		
		if (isset($table)) {
			$this->table = $table;
		}
		$fields = array_keys($dataSet);
		
		for($i = 0; $i < count($fields); $i++) {
			$this->update .= $fields[$i]." = ?";
			if($i < (count($fields)-1)) {
				$this->update .= ", ";
			}
			$this->updateBindValues[] = $dataSet[$fields[$i]];
		}
		
		$statement = $this->pdo->prepare($this->getUpdateQuery());
		
		if ($this->execute($statement) ){
			return true;
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}
	
	/**
	 * 
	 * Update batch 
	 * @param [string $table]
	 * @param array $dataSet
	 * @return boolean
	 * @throws Exception
	 */
	
	public /* boolean */ function updateBatch ($table = null, $dataSet) {
		if (isset($table)) {
			$this->table = $table;
		}
		for($k = 0; $k < count($dataSet); $k++) {
			$fields = array_keys($dataSet[$k]);
			
			for($i = 0; $i < count($fields); $i++) {
				$this->update .= $fields[$i]." = {?}";
				if($i < (count($fields)-1)) {
					$this->update .= ", ";
				}
				$this->updateBindValues[] = $dataSet[$k][$fields[$i]];
			}
		}
		
		$statement = $this->pdo->prepare($this->getUpdateQuery());
		
		if ($this->execute($statement) ){
			
			return true;
		} else {
			$errorInfo = $statement->errorInfo();
			throw new Exception("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
			return false;
		}
	}
	
	/**
	 * Setting the select portion of the query
	 * @param string $select
	 * 	The SELECT porition of the query. default *
	 */
	public /* void */ function select ($select) {
		if($this->select == "*"){
			$this->select = "";
		} else {
			$this->select .= ', ';
		}
		$this->select .= $select;
		
	}
	
	/**
	 * Setting the selection portion of a query with a max wrapped.
	 * @param string $field
	 * 	The field to select a max value from
	 * @param string $as
	 * 	What name the maxfield will be associated with. default max_$field
	 */
	public /* void */ function selectMax($field, $as = null) {
		if(!isset($as)) {
			$as = "max_".$field;
		}
		
		$this->select .= ", MAX($field) as $as";
	}
	
	/**
	 * Setting the selection portion of a query with a min wrapped.
	 * @param string $field
	 * 	The field to select a min value from
	 * @param string $as
	 * 	What name the minfield will be associated with. default min_$field
	 */
	public /* void */ function selectMin($field, $as = null) {
		if(!isset($as)) {
			$as = "min_".$field;
		}
		$this->select .= ", MIN($field) as $as";
	}
	
	/**
	 * Setting the selection portion of a query with a sum wrapped.
	 * @param string $field
	 * 	The field to select a sum value from
	 * @param string $as
	 * 	What name the sumfield will be associated with. default sum_$field
	 */
	public /* void */ function selectSum($field, $as = null) {
		if(!isset($as)) {
			$as = "sum_".$field;
		}
		
		$this->select .= ", SUM($field) as $as";
	}
	
	/**
	 * Setting the selection portion of a query with a avg wrapped.
	 * @param string $field
	 * 	The field to select a avg value from
	 * @param string $as
	 * 	What name the avgfield will be associated with. default avg_$field
	 */
	public /* void */ function selectAvg($field, $as = null) {
		if(!isset($as)) {
			$as = "avg_".$field;
		}
		
		$this->select .= ", AVG($field) as $as";
	}
	
	/**
	 * Altering the from portino of the query
	 * @param string $table
	 * 	The table where we select from.
	 */
	public /* void */ function from($table) {
		$this->table = $table;
	}
	
	/**
	 * 
	 * Create a join statement and add it to the query
	 * @param string $table
	 * 	The joining table
	 * @param string $on
	 * 	How this table is joined.
	 * @param string $direction
	 * 	Optional direction, LEFT JOIN INNER OUTER ...
	 */
	public /* void */ function join($table, $on, $direction = "") {
		$this->join .= strtoupper($direction).' JOIN '.$table.' ON ('.$on.')';
	}
	
	/**
	 * Altering the where portion of the query
	 * @param array $where
	 * 	array('field1', 'operator', 'value1'),
	 */
	public /* void */ function where($where) {
		if (is_array($where)) {
			$this->where .= $this->getWhereString(array($where));
			$this->whereBindValues[] = $where[2];
		}
	}
	
	/**
	 * Altering the where portion of the query using an OR between fields
	 * @param array $where
	 * 	array('field1', 'operator', 'value1'),
	 */
	public /* void */ function whereOR ($where) {
		if (is_array($where)) {
			$this->where .= $this->getWhereString(array($where), 'OR');
			$this->whereBindValues[] = $where[2];
		}
	}
	
	/**
	 * Group content by
	 * @param mixed $groupBy
	 * 	Either string or array
	 */
	public /* void */ function groupBy($groupBy) {
		if ($this->group != "") {
			$this->group .= ', ';
		}
		if (is_array($groupBy)) {
			$i = 0;
			foreach ($groupBy as $groupField) {
				$this->group .= "?";
				if($i++ < (count($groupBy)-1)) {
					$this->group .", ";
				} 
			}
		} else {
			$this->group .= $groupBy;
		}
	}
	
	/** 
	 * Make the select query distinct for all fields
	 */
	public /* void */ function distinct () {
		$this->select = 'DISTINCT('.$this->select.')';
	}
	
	/**
	 * Make the query ordered by field in a direction 
	 * @param string $field
	 * 	Field / column name
	 * @param string $direction
	 * 	Default DESC
	 */
	public /* void */ function orderBy ($field, $direction = 'DESC') {
		if ($this->order != "" ) {
			$this->order .= ', ';
		} else {
			$this->order = "ORDER BY ";
		}
		$this->order .= "? ".$direction;
		
		$this->orderBindValues[] = $field;
	}
	
	/**
	 * Set the default limit count
	 * @param int $limit
	 * 	The amount of entries to return
	 * @param int $offset
	 * 	Where the selection should start.
	 */
	public /* void */ function limit($limit, $offset = null) {
		
		$this->limitBindValues = array();
		$limitString = "";
		
		if ($this->driver == "mysql") {
			
			if(isset($offset)) {
				$limitString = "LIMIT ? OFFSET ?";
				$this->limitBindValues[0] = $limit;
				$this->limitBindValues[1] = $offset;
			} else {
				$limitString = "LIMIT ?";
				$this->limitBindValues[0] = $limit;
			}
		}
		$this->limit = $limitString;
		
	} 
	
	/**
	 * Return last executed query
	 * @param string <class>::lastExecutedQuery
	 */
	public /* string */ function getLastExecutedQuery () {
		
	}
	
	/**
	 * Return the current resultset.
	 * @return array $this->result
	 */

	public /* array */ function getResult () {
		if(count($this->result)>0) { 
			return $this->result;
		} else {
			return false;
		}
	}
	
	/**
	 * Clean the result cache.
	 * @param string $query.
	 * 	the query using ? placeholders
	 * @param string <...> $param
	 * 	Multiple parameters
	 */
	public /* string */ function flushResult ($flushCache = false) {
		if	($flushCache)	{
			$this->clearCache();
		}
		$this->result = array();
	} 
	
	public /* void */ function query () {
		$args = func_get_args();
		$query = array_shift($args);
		
		$statement = $this->pdo->prepare($query);
		
		$i = 1;
		foreach ($args as $arg) {
			$statement->bindValue($i++, $arg);
		}
		
		if($this->execute($statement, false)) {
			$result = $this->getResult();
			$this->flushResult();
			$this->clearCache();
			return $result;
		}
	}
	
	public /* void */ function useQueryCache () {
		$this->useQueryCache = true;
	} 
	
	public /* void */ function dontUseQueryCache () {
		$this->useQueryCache = false;
	}
	
	public /* int */ function getLastInsertId() {
		return $this->pdo->lastInsertId();
	}
		
	public /* void */ function clearCache () {
		$this->select = "*";
		$this->table = "";
		$this->where = "";
		$this->limit = "";
		$this->join = "";
		$this->order = "";
		$this->group = "";
		$this->update = "";
		$this->insertFields = array();
		$this->insertValues = array();
		$this->whereBindValues = array();
		$this->insertBindValues = array();
		$this->orderBindValues = array();
		$this->groupBindValues = array();
		$this->updateBindValues = array();
		$this->limitBindValues  = array();
		$this->bindFieldCounter = 1;
	}
	
	
	/**
	 * Return the constructed limit string
	 * @param int $limit
	 * @param int $offset
	 * @return string $limit
	 */
	protected /* string */ function getLimitString ($limit = "", $offset = "") {
		$limit = "";
		
		return $limit;
	}
	
	/**
	 * Return the constructed where string.
	 * @param array $whereFields
	 * @return string $where
	 */
	protected /* string */ function getWhereString ($whereFields, $condition = "AND") {
		$where = "";
		if($this->where != "") {
			$where .= " ".$condition." ";
		} 
		foreach($whereFields as $field){
			$where .= $field[0]. " ".$field[1]." ?";
		}
	  return $where;
	}
	
	protected /* string */ function getCountAllQuery () {
		return "SELECT COUNT(*) FROM $this->table";
	}
	
	protected /* string */ function getCountResultQuery () {
		return "SELECT COUNT(*) FROM $this->table $this->join $this->where $this->group $this->order $this->limit";
	}
	
	protected /* string */ function getSelectQuery () {
		if(strpos($this->where, "WHERE") === FALSE && $this->where != "") {
			$this->where = "WHERE ".$this->where;
		}
		
		return str_replace("{table}", $this->table ,"SELECT $this->distinct $this->select FROM $this->table $this->join $this->where $this->group $this->order $this->limit");
	}
	
	protected /* string */ function getUpdateQuery () {
		if(strpos($this->where, "WHERE") === FALSE) {
			$this->where = "WHERE ".$this->where;
		}
		
		return "UPDATE $this->table SET $this->update $this->where";
	}
	
	protected /* string */ function getDeleteQuery () {
		if(strpos($this->where, "WHERE") === FALSE) {
			$this->where = "WHERE ".$this->where;
		}
		
		return "DELETE FROM $this->table $this->where";
	}
	
	protected /* string */ function getTruncateQuery () {
		return "TRUNCATE $this->table";
	}
	
	protected /* string */ function getInsertQuery () {
		$i = 0;
		$values = "";
		foreach ($this->insertBindValues as $valueSet) {
			$k = 0;
			$values .= "(";
			foreach ($valueSet as $value) {
				$values .= '?';
				if($k < (count($valueSet)-1)) {
					$values .= ", ";
				}
				$k++;
			}
			$values .= ")";
			if($i < (count($this->insertBindValues)-1)) {
				$values .= ", ";
			}
			$i++;
		}
		
		
		return "INSERT INTO $this->table (".join(", ", $this->insertFields).") VALUES $values";
	}

	protected function sanitize($input) {
		$input = $input;
		return $input;
	}
	
	protected /* boolean */ function execute(PDOStatement &$statement, $doBinding = true) {
		$returnVal = false;
		if ($doBinding) {
			foreach ($this->updateBindValues as $bindVal) { 
				$statement->bindValue($this->bindFieldCounter++, $this->sanitize($bindVal));
			}
			
			foreach ($this->whereBindValues as $bindVal) {
				$statement->bindValue($this->bindFieldCounter++, $this->sanitize($bindVal)); 
			}
			
			foreach ($this->insertBindValues as $bindVal) {
				foreach($bindVal as $val) {
					$statement->bindValue($this->bindFieldCounter++, $this->sanitize($val));
				}
			}
			
			foreach ($this->groupBindValues as $bindVal) {
				/** @Todo need to fix this */
			}
			
			foreach ($this->orderBindValues as $bindVal) {
				$statement->bindValue($this->bindFieldCounter++, $this->sanitize($bindVal));
			}
			
			foreach ($this->limitBindValues as $bindVal) {
				$statement->bindValue($this->bindFieldCounter++, (int)$bindVal, PDO::PARAM_INT);
			}
			
		}
		
		if ($statement->execute()) {
			
			while ($obj = $statement->fetchObject()) { 
				$this->result[] = $obj;
			}
			$returnVal = true;
			
		} 
		
		if (!$this->useQueryCache) {
			$this->clearCache();
		} else {
			$this->revertBoundCounter();
		}
		
		return $returnVal;
	}
	
	protected /* void */ function revertBoundCounter() {
		$this->bindFieldCounter = 1;
	}
	
}