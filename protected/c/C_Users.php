<?php

/**Класс домашней страницы*/
class C_Users extends C_Base
{
//region regionVariableS
 	/**
 	 * Переменные из базового класса:
 	 * $modelUser;	  // модель пользователей
 	 * $title;        // заголовок страницы
 	 * $needLogin;    // необходима ли авторизация
 	 * $authorization;// состояние авторизации
 	 * $userLogin;    // авторизованный пользователь
 	 * $userPhoto;    // фото пользователя
 	 * $trueAdmin;    // пользователь - это администратор?
 	 * $dataTemplate; // массив шаблонов:
 	 */


	protected $hiddenErrorDiv = '';
	protected $resultError;
	protected $trueError;

	//endregion regionVariableS

	public function __construct()
	{
		parent::__construct();
		$this->modelUser->activationSort();
	}

	public function before()
	{
		$this->needLogin = true;
		parent::before();

		$this->title .= "Пользователи";
	}


	/**Отображение списка пользователей*/
	public function action_list_users()
	{
		$sortColumn=$_SESSION["sortColumn"];
		$sortType=$_SESSION["sortType"]	;

		//Блок определения сортировки таблицы
		if(!is_null($sortColumn) && !is_null($sortType)) {
			$dataBD = $this->modelUser->getUsers($sortColumn, $sortType);
		} else {
			$dataBD = $this->modelUser->getUsers();
		}

		//Подготовка Header
		$this->dataTemplate = [[
			"template" => PATH_TEMPLATE . "header.php",
			"title" => $this->title,
			"logo" => "logo.png",
			"authorization" => $this->authorization,
			"login" => $this->userLogin,
			"userPhoto" => $this->userPhoto,
		]];


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
			"template" => PATH_TEMPLATE . "footer.php",
			"logo" => "logo.png",
		];


	}

	protected function sorting($sortColumn)
	{
		$_SESSION["sortColumn"] = $sortColumn;
		$this->modelUser->switchSortType();
		$this->redirect("/users/list_users");
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
		$user = $this->modelUser->getUser($showUserID);

		if($this->userLogin === $showUserID) {
			$selfIdUsers = true;
		}
		$user["selfIdUsers"] = $selfIdUsers;
		$user["roles"] =$this->modelUser->getAllRole();

		//Подготовка Header
		$this->dataTemplate = [[
			"template" => PATH_TEMPLATE . "header.php",
			"title" => $this->title,
			"logo" => "logo.png",
			"authorization" => $this->authorization,
			"login" => $this->userLogin,
			"userPhoto" => $this->userPhoto,
		]];

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
		$result = $this->modelUser->validateRegistrationForm();
		$this->trueError = $result[0];

		if($this->trueError) {
			$this->resultError = $result[1];
			$this->action_show_profile();
		} else {
			$dateForm = $result[1];
			$dateForm["id"] = $showUserID;
			$result = $this->modelUser->editProfile($dateForm);

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
		$this->modelUser->deleteProfile($showUserID);
		$this->redirect("/");
	}

	public function action_create_profile()
	{
		$result = $this->modelUser->validateRegistrationForm();
		$this->trueError = $result[0];

		if($this->trueError) {
			$this->resultError = $result[1];
			$this->action_show_profile();
		} else {
			//$this->update_profile($result[1]);
			$dateForm = $result[1];

			$result = $this->modelUser->registrationUser($dateForm);

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
