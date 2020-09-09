<?php

namespace Controllers\Models;

require_once(dirname(__DIR__).'/../Core/Database.php');
use \Core\Database;
class BaseModel extends Database
{
	private $connect;
	private $disconect;

	public function __construct()
	{
		$this->connect = $this->connection();
	}


	// Lấy tất cả bản ghi trong table
	protected function getAll($table)
	{
		$query = "SELECT * FROM ".$table;
		$result = $this->connect->query($query);
		$data  = [];
		if ($result) {
		  // output data of each row
			while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
				$data[] = $row;
			}
		}else {
			die('0 record return');
		}
		$this->CloseConnection($this->connect);
		return $data;
	}

	// Lấy bản ghi theo id
	protected function findById($table,$id)
	{
		$query = "SELECT * FROM $table WHERE id = $id";
		$result = $this->connect->query($query);
		$data  = [];
		if ($result->num_rows > 0) {
			$data[] = $result->fetch_assoc();
		}else {
			die('0 record return');
		}
		$this->CloseConnection($this->connect);
		return $data;
	}

	protected function created($table , $data = [])
	{
		$columns = implode(',', array_keys($data));
		// $values = implode(',', array_values($data));
		$values = array_map(function($value){
			return "'" . $value . "'";
		},array_values($data));
		$values = implode(',',$values);
		$query = "INSERT INTO ${table} (${columns}) VALUES (${values})";
		$result = $this->connect->query($query);
		if($result==false)
		{
			die('Error create value');
		}
		$this->CloseConnection($this->connect);
	}

	protected function updated($table , $id ,$data = [])
	{
		$dataSets = [];
		foreach ($data as $key => $value) {
			$dataSets[] = "${key} = '".$value."'";
		}
		$dataSetString = implode(',' , $dataSets);
		$query = "UPDATE ${table} SET ${dataSetString} WHERE id = ${id}";
		$result = $this->connect->query($query);
		if($result==false)
		{
			die('Error create value');
		}
		$this->CloseConnection($this->connect);
	}

	protected function deleted($table,$id)
	{
		$query = "DELETE FROM ${table} WHERE id = ${id}";
		$result = $this->connect->query($query);
		if($result==false)
		{
			die('Error create value');
		}
		$this->CloseConnection($this->connect);
	}
}

?>