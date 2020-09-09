<?php
namespace Core;

class Database{

	private $host = "localhost";
	private $db_database = "blog";
	private $db_username = "root";
	private $db_password = "";

	private $connect;

	// connect database
	public function connection()
	{
		$this->connect = mysqli_connect($this->host,$this->db_username,$this->db_password);
        if(!$this->connect){
            $error = mysqli_error($this->connect);
            exit("Kết nối thất bại".$error);
        }
        mysqli_select_db($this->connect,$this->db_database);
        mysqli_set_charset($this->connect,'utf8');
		return $this->connect;
	}


	//close database
	public function CloseConnection($conn)
	{
		$conn->close();
	}
}

?>