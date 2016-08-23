<?php

namespace CodeExperts\Db;

class Entity
{
	/**
	 * @var String
	 */
	protected $table;

	/**
	 * @var Pdo
	 */
	protected $conn;

	/**
	 * Set $pdo
	 */
	public function __construct(\PDO $conn)
	{
		$this->conn = $conn;
	}

	/**
	 * Set Table
	 */
	public function setTable($table)
	{
		$this->table = $table;
	}

	/**
	 * Save data in database (Insert Or Update)
	 * @return bool
	 */
	public function save(array $data)
	{
		if(array_key_exists('id', $data)) {
			return $this->update($data['id'], $data);
		} else {
			return $this->insert($data);
		}
	}

	/**
	 * Insert data in database
	 * @return bool
	 */
	private function insert(array $data = array())
	{
		foreach($data as $key => $value) {
			$fields[] = $key;
			$bind[]   = ':' . $key;
		}

		$fields = implode(', ', $fields);
		$bind   = implode(', ', $bind);

		$sql = "INSERT INTO " . $this->table . "(" . $fields . ") 
	              	    VALUES(" . $bind . ")";

		try{
			$insert = $this->conn->prepare($sql);

			foreach($data as $key => $value) {
				
				$insert->bindValue(":" . $key, $value, !is_int($value)? \PDO::PARAM_STR : \PDO::PARAM_INT);
			}

			if($insert->execute()) {
				return $this->conn->lastInsertId();
			}

			return false;

		} catch(PDOexception $e) {
			return 'Error to insert data in database';
		}
	}

	/**
	 * Update data in database
	 * @return bool
	 */
	private function update($id, array $datas)
	{
		$datasSql = '';
		
		foreach($datas as $key => $a) {
			$datasSql .= $key . ' = ' . ':' . $key . ', ';
		}

		$datasSql = substr($datasSql, 0, -2);

		$sql = "UPDATE " . $this->table . " SET " . $datasSql . " WHERE id = :id";

		try{
			$update = $this->conn->prepare($sql);

			$update->bindValue(':id', $id, \PDO::PARAM_INT);

			foreach($datas as $key => $value) {
				$update->bindValue(":" . $key, $value, !is_int($value)? \PDO::PARAM_STR : \PDO::PARAM_INT);
			}

			return $update->execute();

		} catch(PDOexception $e) {
			return false;
		}
	}
	
	/**
	 * Get all datas in bd
	 * @return array with datas
	 */
	public function getAll($fields = '*', $limit = null)
	{
		$sql = "SELECT " . $fields .  " FROM " . $this->table;

		if(!is_null($limit))
			$sql .= " LIMIT " . $limit;

		try {
			$select = $this->conn->prepare($sql);
			$select->execute();

		} catch(PDOexception $e) {
			return false;
		}

		return $select->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Get especific data
	 * 
	 */
	public function where($where, $fields = '*', $more = 'AND', $fetch = 'all')
	{
		$whereSql = '';

		foreach($where as $key => $w) {
			$whereSql .= $whereSql ? ' ' . $more . ' ' : '';
			$whereSql .= $key . ' = :' . $key;
		}

		$sql = "SELECT " . $fields .  " FROM " . $this->table . " WHERE " . $whereSql;
		
		try {
			$select = $this->conn->prepare($sql);
			foreach($where as $key => $value) {
				$select->bindValue(":" . $key, $value, !is_int($value)? \PDO::PARAM_STR : \PDO::PARAM_INT);
			}		
			$select->execute();

		} catch(PDOexception $e) {
			return false;
		}

		if($fetch == 'all') {
			return $select->fetchAll(\PDO::FETCH_ASSOC);
		}

		if($fetch == 'one') {
			return $select->fetch();			
		}
	}

	/**
	 * Find one
	 */
	public function find($id)
	{
		$where = ['id' => $id];

		return $this->where($where, '*', 'AND', 'one');
	}
	
	/**
	 * Delete data
	 * @param   [id] [data's id]
	 */
	public function delete($id) 
	{
		$sql = "DELETE FROM " . $this->table . " WHERE id = :id";

		try {
			$delete = $this->conn->prepare($sql);
			$delete->bindValue(':id', $id, \PDO::PARAM_INT);

			if($delete->execute()) {
				return true;
			}

		} catch(PDOexception $e) {
			return false;
		}
	}
	/**
	 * Count total itens in especific table
	 * @return int total of registries
	 */
	public function total()
	{
	    return count($this->getAll());
	}
}
