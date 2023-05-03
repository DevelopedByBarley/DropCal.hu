<?php
require_once 'app/api/models/api_model.php';

class APIController
{

	private $pdo;
	private $loginChecker;
	private $APIModel;
	public function __construct()
	{
		$db = new Database();
		$this->pdo = $db->getConnect();
		$this->loginChecker = new LoginChecker();
		$this->APIModel = new APIModel();
	}

	public function search($vars)
	{
		$this->loginChecker->checkUserIsLoggedInOrRedirect();
		$userId = $_SESSION["userId"] ?? null;
		$ingredients = $this->APIModel->searchIngredient($userId, $vars["name"]);

		echo json_encode($ingredients);
	}

	public function getSingleIngredient($vars)
	{
		$this->loginChecker->checkUserIsLoggedInOrRedirect();
		$ingredient = $this->APIModel->getIngredient($vars["id"]);

		echo json_encode($ingredient);
	}

	public function addDiaryIngredient()
	{
		$this->loginChecker->checkUserIsLoggedInOrRedirect();
		$body = json_decode(file_get_contents('php://input'), true);
		$this->APIModel->addIngredient($body);
	}

	public function getDiaryIngredient($vars)
	{
		$this->loginChecker->checkUserIsLoggedInOrRedirect();
		$this->APIModel->getDiaryIngredientById($vars["id"]);
	}

	public function updateDiaryIngredient($vars)
	{
		$this->loginChecker->checkUserIsLoggedInOrRedirect();
		$body = json_decode(file_get_contents('php://input'), true);
		$isSuccess = $this->APIModel->updateIngredientForDiary($vars["id"], $body);

		if ($isSuccess) {
			echo json_encode([
				"state" => true
			]);
		} else {
			echo json_encode([
				"state" => false
			]);
		}
	}

	public function deleteDiaryIngredient($vars)
	{
		$this->loginChecker->checkUserIsLoggedInOrRedirect();
		$id = isset($vars["id"]) ? $vars["id"] : null;
		$isSuccess = $this->APIModel->deleteIngredientForDiary($id);

		echo json_encode([
			"state" => $isSuccess
		]);
	}
}
