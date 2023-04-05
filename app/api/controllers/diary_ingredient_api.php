<?php
class API
{

	private $pdo;

	public function __construct()
	{
		$db = new Database();
		$this->pdo = $db->getConnect();
	}

	public function search($vars)
	{
		$name = $vars["name"];
		$stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE ingredientName LIKE :name");
		$stmt->bindValue(':name', '%' . $name . '%', PDO::PARAM_STR);
		$stmt->execute();
		$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

		echo json_encode($ingredients);
	}

	public function getSingleIngredient($vars)
	{
		$id = $vars["id"];
		$stmt = $this->pdo->prepare("SELECT * FROM `ingredients` WHERE ingredientId  = :id");
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$ingredients = $stmt->fetch(PDO::FETCH_ASSOC);
		
		echo json_encode($ingredients);
	}
}
