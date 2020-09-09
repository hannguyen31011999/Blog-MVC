<?php
namespace Controllers\Models;

require_once(dirname(__FILE__).'/../Models/BaseModel.php');

class TypePostModel extends BaseModel
{
	const TABLE = "type_post";

	public function all()
	{
		return $this->getAll(self::TABLE);
	}

	public function findId($id)
	{
		return $this->findById(self::TABLE,$id);
	}

	public function create($array)
	{
		return $this->created(self::TABLE,$array);
	}

	public function update($id,$array)
	{
		return $this->updated(self::TABLE,$id,$array);
	}

	public function delete($id)
	{
		return $this->deleted(self::TABLE,$id);
	}

	public function returnTable()
	{
		return self::TABLE;
	}
}

?>