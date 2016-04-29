<?php
namespace yogi\framework\io\db\interfaces; 

interface IOrm {
	public function getColumns($table = null);
	
	public /* boolean */ function get($table = null, $limit = null, $offset = null);
	
	public /* boolean */ function getWhere($table = null, $whereArray , $limit = null, $offset = null);
	
	public /* boolean */ function delete($table = null);
	
	/**mbk
	 * 
	 * @throws Exception
	 * @param string $table
	 */
	public /* void */ function truncate($table = null);
	
	public /* boolean */ function countAll($table = null);
	
	public /* int */ function countAllresults();
	
	public /* void */ function set($field, $value);
	
	public /* boolean */ function insert ($table = null, $dataSet = array());
	
	public /* boolean */ function insertBatch ($table = null, $dataSet);
	
	public /* boolean */ function update ($table = null, $dataSet = array());
	
	public /* boolean */ function updateBatch ($table = null, $dataSet);
	
	public /* void */ function select ($select);
	
	public /* void */ function selectMax($field, $as = null);
	
	public /* void */ function selectMin($field, $as = null);
	
	public /* void */ function selectSum($field, $as = null);
	
	public /* void */ function selectAvg($field, $as = null);
	
	public /* void */ function from($table);
	
	public /* void */ function join($table, $on, $direction = "");
	
	public /* void */ function where($where);
	
	public /* void */ function whereOR ($where);
	
	public /* void */ function groupBy($groupBy);
	
	public /* void */ function distinct ();
	
	public /* void */ function orderBy ($field, $direction = 'DESC');
	
	public /* void */ function limit($limit, $offset = null);
	
	public /* string */ function getLastExecutedQuery ();
	
	public /* array */ function getResult ();
	
	public /* string */ function flushResult ($flushCache = false);
	
	public /* void */ function query ();
	
	public /* void */ function useQueryCache ();
	
	public /* void */ function dontUseQueryCache ();
	
	public /* int */ function getLastInsertId();
	
	public /* void */ function clearCache ();
}