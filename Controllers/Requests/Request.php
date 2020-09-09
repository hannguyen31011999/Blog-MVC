<?php
namespace Controllers\Requests;
require_once(dirname(__DIR__).'/../Core/Database.php');
use \Core\Database;
class Request extends Database
{
	private $connect;

	public function __construct()
	{
		$this->connect = $this->connection();
	}

	private function unique($table,$field,$name)
	{

		$query = "SELECT * FROM ${table} WHERE ${field} like '%${name}%'";
		$result = $this->connect->query($query);
		$row = $result->fetch_assoc();
		if(!empty($row))
			return true;
		return false;
	}

	private function min($string,$value)
	{
		if(strlen($string)>$value)
			return true;
		return false;
	}

	private function max($string,$value)
	{
		if(strlen($string)>$value)
			return true;
		return false;
	}

	private function email($string)
	{
		for ($i=0; $i < strlen($string); $i++) { 
			if($string[$i]==="@")
				return false;
		}
		return true;
	}

	private function numeric($string)
	{
		$dem = 0;
		for ($i=0; $i < strlen($string); $i++) { 
			if($string[$i]==="0"||$string[$i]==="1"||$string[$i]==="2"||$string[$i]==="3"||$string[$i]==="4"||$string[$i]==="5"||$string[$i]==="6"||$string[$i]==="7"||$string[$i]==="8"||$string[$i]==="9")
				$dem++;

		}
		if($dem===strlen($string))
			return false;
		return true;
	}
	
	public static function make($request,$rule,$messenger)
	{
		$obj = new Request();
		$keyRequest = [];
		$functionRule = [];
		$table;
		$arrTable = [];
		$fieldRule;
		$errors = [];
		// Lấy key request
		foreach ($request as $key => $value) {
			$keyRequest[] = $key;
		}

		// duyệt key request
		foreach ($keyRequest as $value) {
			// duyệt rule
			foreach ($rule as $keyRule => $valueRule) {
				//kiểm tra key request với key rule
				if($value==$keyRule)
				{	
					// dùng hàm explode lấy những hàm validate vd: required,unique...
					$functionRule[$keyRule] = explode("|", $valueRule);
					//duyệt hàm validate
					foreach ($functionRule as $valuefunctionRule) {
						// duyệt hàm validate
						foreach ($valuefunctionRule as $key1 => $value1) {
							// lấy những validate có chứa :
							$arrTable[] = explode(":", $value1);
						}
					}
					// duyệt validate có chứa :
					foreach ($arrTable as $key => $value) {
						// so sánh giá trị validate nếu trùng với tên hàm thì xử lí
						if($value[0]=='required')
						{
							if(empty($request[$keyRule]))
							{
								$errors[$keyRule] = $messenger[$keyRule.'.required'];
								break;
							}
						}
						elseif ($value[0]=='unique')
						{
							if(array_key_exists(1,$value))
							{
								$fieldRule = explode(",",$value[1]);
								if($obj->unique($fieldRule[0],$fieldRule[1],$request[$keyRule]))
									$errors[$keyRule] = $messenger[$keyRule.'.unique'];
							}
							continue;
						}
						elseif (strcmp($value[0],'min'))
						{
							continue;
						}
						elseif (strcmp($value[0],'max')) 
						{
							continue;
						}
						elseif (strcmp($value[0],'numeric')) 
						{
							continue;
						}
						else
						{
							continue;
						}
					}
				}
			}
		}
		return $errors;
	}
}
?>