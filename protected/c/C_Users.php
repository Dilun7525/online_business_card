<?php

/**Класс домашней страницы*/
class C_Home extends C_Base
{
	protected $modelUsers;
	protected $sortColumn;//login, email, surname, first_name, middle_name, role
	protected $sortType;  //ASC или DESC
	protected $hiddenErrorDiv = '';
	protected $resultError;
	protected $trueError;


	public function __construct()
	{
		parent::__construct();
		$this->modelUsers = M_Users::getInstance();
		$this->updateVarFromSESSION();
	}

	public function before()
	{
		$this->needLogin = true;
		parent::before();

		$this->title .= "Home";
	}

	/**Обновление необходимых переменных в сесии*/
	protected function updateVarFromSESSION()
	{
		parent::updateVarFromSESSION();
		$this->sortType = !empty($_SESSION["sortType"])
			? $_SESSION["sortType"] : null;
		$this->sortColumn = !empty($_SESSION["sortColumn"])
			? $_SESSION["sortColumn"] : null;
	}

	public function action_index()
	{
		if(!is_null($this->sortColumn) && is_null($this->sortType)) {
			$dataBD = $this->modelUsers->getUsers($this->sortColumn);
		} elseif(!is_null($this->sortColumn) && !is_null($this->sortType)) {
			$dataBD = $this->modelUsers->getUsers($this->sortColumn, $this->sortType);
		} else {
			$dataBD = $this->modelUsers->getUsers();
		}

		//Подготовка Header
		$this->dataTemplate = [[
			"template" => PATH_TEMPLATE . "header.php",
			"title" => $this->title,
			"login" => $this->user,
			"id" => $this->idUser,]];

		//Подготовка таблицы

		$i = 1;
		$iEnd = count($dataBD);
		foreach ($dataBD as $value) {
			$this->dataTemplate[] = [
				"template" => PATH_VIEW . "V_TableUsers.php",
				"i" => $i,
				"iEnd" => $iEnd,
				"trueAdmin" => $this->trueAdmin,
				"id" => $value["id"],
				"login" => $value["login"],
				"email" => $value["email"],
				"surname" => $value["surname"],
				"first_name" => $value["first_name"],
				"middle_name" => $value["middle_name"],
				"role" => $value["role"],
			];
			++$i;
		}

		//Подготовка Footer
		$this->dataTemplate[] = [
			"template" => PATH_TEMPLATE . "footer.php",];
	}

	protected function sorting($sortColumn)
	{
		$this->sortColumn = $sortColumn;
		$_SESSION["sortColumn"] = "login";
		$this->modelUsers->switchSortType();
		$this->redirect("/");
	}

	public function action_sort_login()
	{
		$this->sorting("login");
	}

	public function action_sort_family()
	{
		$this->sorting("surname");
	}

	public function action_sort_role()
	{
		$this->sorting("role");
	}

	public function action_show_profile()
	{
		$selfIdUsers = false;//это профиль зарегистрированного пользователя?
		$showUserID = $this->params[0];
		$user = $this->modelUsers->getUser($showUserID);

		if($this->idUser === $showUserID) {
			$selfIdUsers = true;
		}
		$user["selfIdUsers"] = $selfIdUsers;
		$user["roles"] =$this->modelUsers->getAllRole();

		//Подготовка Header
		$this->dataTemplate[] = [
			"template" => PATH_TEMPLATE . "header.php",
			"title" => $this->title,
			"login" => $this->user,
			"id" => $this->idUser,
		];

		$this->dataTemplate[] = $this->templateEdit($user);
	}

	protected function templateEdit($user = null)
	{
		if(is_null($user)) {
			$user["id"] = null;
			$user["login"] = "\" placeholder =\"Логин\"";
			$user["pass"] = "\" placeholder =\"Пароль\"";
			$user["email"] = "\" placeholder =\"Email\"";
			$user["surname"] = "\" placeholder =\"Фамилия\"";
			$user["first_name"] = "\" placeholder =\"Имя\"";
			$user["middle_name"] = "\" placeholder =\"Отчество\"";
			$user["role"] = "\" placeholder =\"Роль\"";
		}
		return [
			"template" => PATH_VIEW . "V_UserProfile.php",
			"trueAdmin" => $this->trueAdmin,
			"id" => $user["id"],
			"login" => $user["login"],
			"pass" => $user["pass"],
			"email" => $user["email"],
			"surname" => $user["surname"],
			"first_name" => $user["first_name"],
			"middle_name" => $user["middle_name"],
			"role" => $user["role"],
			"roles" => $user["roles"],
			"selfIdUsers" => $user["selfIdUsers"],
			"hiddenErrorDiv" => $this->hiddenErrorDiv,
			"resultError" => $this->resultError,];
	}

	public function action_edit_profile()
	{
		$showUserID = $this->params[0];
		$result = $this->modelUsers->validateRegistrationForm();
		$this->trueError = $result[0];

		if($this->trueError) {
			$this->resultError = $result[1];
			$this->action_show_profile();
		} else {
			$dateForm = $result[1];
			$dateForm["id"] = $showUserID;
			$result = $this->modelUsers->editProfile($dateForm);

			if(is_null($result)) {
				$this->trueError = true;
				$this->resultError = "Ошибка записи. <br/>Повторите попытку";
				$this->action_show_profile();
			} else {
				if($showUserID == $this->idUser) {
					$_SESSION["user"] = $dateForm["login"];
				}
				$this->redirect("/");
			}

		}
	}

	public function action_delete_profile()
	{
		$showUserID = $this->params[0];
		$this->modelUsers->deleteProfile($showUserID);
		$this->redirect("/");
	}

	public function action_create_profile()
	{
		$result = $this->modelUsers->validateRegistrationForm();
		$this->trueError = $result[0];

		if($this->trueError) {
			$this->resultError = $result[1];
			$this->action_show_profile();
		} else {
			//$this->update_profile($result[1]);
			$dateForm = $result[1];

			$result = $this->modelUsers->registrationUser($dateForm);

			if(is_null($result)) {
				$this->trueError = true;
				$this->resultError = "Ошибка записи. <br/>Повторите попытку";
				$this->action_show_profile();
			} else {
				$this->redirect("/");
			}

		}

	}


}
