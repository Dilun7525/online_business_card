<?php

/**
 * Менеджер пользователей
*/
class M_Users
{
	//region regionVariableS
	private static $instance;    // экземпляр класса
	private $driverDB;           // драйвер БД

	private $sid;                  // идентификатор текущей сессии
	private $timeLiveSid = 60 * 20;// время существования ссесии
	private $uid;                  // идентификатор текущего пользователя
	private $pathCookie = "/";       // место деиствия куков
	private $domainCookie;
	private $user = [              // авторизованный пользователь
		"id" => "",
		"login" => "",
		"pass" => "",
		"email" => "",
		"surname" => "",
		"first_name" => "",
		"middle_name" => "",
		"role" => "",
		"photo_user" => "",
	];
	public $passportUser = [         // данные, необходимые для других классов
		"authorization" => false,    // состояние авторизации
		"login" => "",
		"trueAdmin" => false,        //  пользователь - это администратор?
		"userPhoto" => "",
		"userId" => "",
	];

	private $rememberUser = false;  //Запоминать пользователя?
	//endregion regionVariableS

	/**Получение экземпляра класса*/
	public static function getInstance()
	{
		if(self::$instance == null)
			self::$instance = new M_Users();

		return self::$instance;
	}

	protected function __construct()
	{
		$this->driverDB = M_DB::getInstance(PATH_CONFIGS_R . "db_install.txt");
		$this->login();
		$this->clearSessions();
	}

	/**
	 * Извлечение пользователя по заданному условию
	 * @param        $valueField = $id  || $login  || $email
	 * @param string $searchOnField = "id" || "login" || "email"
	 * @return $user|null
	 */
	public function getUser($valueField, $searchOnField = "id")
	{
		$format = "SELECT users.id, login,  pass,  email, " .
			"surname,  first_name,  middle_name, role.role, photo_user " .
			"FROM users INNER JOIN role ON users.role = role.id " .
			"WHERE users.%s = '%s'";

		$query = sprintf($format, $searchOnField, $valueField);
		$result = $this->driverDB->Select($query);
		return (!empty($result)) ? $result[0] : null;
	}

	/**
	 * Извлечение всех пользователей (часть при наличии дополнительного условия)
	 * @param string $sortColumn - название колонки, по которой сортируется
	 * таблица
	 * @param string $sortType = "ASC" || "DESC"
	 * @param null   $where - дополнительное условие
	 * @return null $users|null
	 */
	public function getUsers($sortColumn = "id",
							 $sortType = "ASC",
							 $where = null)
	{
		$format = "SELECT users.id, login,  pass,  email, " .
			"surname,  first_name,  middle_name, role.role, photo_user " .
			"FROM users INNER JOIN role ON users.role = role.id ";

		if(!is_null($where)) {

			$where = trim($where);
			$format .= " " . $where . " ";
		}

		$format .= "ORDER BY %s %s";

		$query = sprintf($format, $sortColumn, $sortType);
		$result = $this->driverDB->Select($query);
		return (!empty($result)) ? $result : null;
	}

	/**
	 * Извлечение роли пользователя по его id
	 * @param $id
	 * @return $role|null
	 */
	public function getRoleUser($id)
	{
		$format = "SELECT role.role " .
			"FROM users INNER JOIN role ON users.role = role.id " .
			"WHERE users.id = '%s'";

		$query = sprintf($format, $id);
		$result = $this->driverDB->Select($query);
		return (!empty($result)) ? $result[0] : null;
	}

	/**
	 * Извлечение id роли  по названию роли
	 * @param $valueRole
	 * @return  $idRole|null
	 */
	public function getIdRole($valueRole)
	{
		$format = "SELECT id FROM role WHERE role = '%s'";
		$query = sprintf($format, $valueRole);
		$idRole = $this->driverDB->Select($query);
		return (!empty($idRole[0])) ? $idRole[0]["id"] : null;
	}

	/**
	 * Извлечение списка ролей
	 * @return array|null
	 */
	public function getAllRole()
	{
		$query = "SELECT role FROM role";
		$result = $this->driverDB->Select($query);
		$roles = [];
		if(!empty($result)) {
			foreach ($result as $value) {
				$roles[] = $value["role"];
			}
			return $roles;
		} else {
			return null;
		}
	}

	/**Вход пользователя*/
	public function login()
	{
		$uid = $this->getUid();
		if(is_null($uid)) {
			return null;
		}

		$this->user = $this->getUser($uid);
		$this->fillingPassportUser();
	}

	/**Заполнение паспорта пользователя*/
	protected function fillingPassportUser()
	{
		$this->passportUser["authorization"] = true;
		$this->passportUser["login"] = $this->user["login"];
		$this->passportUser["userPhoto"] = $this->user["photo_user"];
		$this->passportUser["userId"] = $this->user["id"];

		if($this->user["role"] === "администратор") {
			$this->passportUser["trueAdmin"] = true;
		}
	}

	/**Выход пользователя*/
	public function logout()
	{
		setcookie('login', '', time() - 1, "/");
		setcookie('password', '', time() - 1, "/");
		unset($_COOKIE['login']);
		unset($_COOKIE['pass']);
		unset($_SESSION['sid']);
		$this->sid = null;
		$this->uid = null;
	}

	/**
	 * Получение id  текущего пользователя
	 * @return UID
	 */
	public function getUid()
	{
		// Проверка кеша.
		if($this->uid != null)
			return $this->uid;

		// Берем по текущей сессии.
		$sid = $this->GetSid();

		if($sid == null)
			return null;

		$t = "SELECT id_user FROM sessions WHERE sid = '%s'";
		$sid = mysqli_real_escape_string($this->driverDB->link, $sid);
		$query = sprintf($t, $sid);
		$result = $this->driverDB->select($query);

		// Если сессию не нашли - значит пользователь не авторизован.
		if(count($result) == 0)
			return null;

		// Если нашли - запоминим ее.
		$this->uid = $result[0]['id_user'];
		return $this->uid;
	}

	/**
	 * Получение id сессии
	 * @return SID
	 */
	private function getSid()
	{
		// Проверка кеша.
		if($this->sid != null)
			return $this->sid;

		// Ищем SID в сессии.
		$sid = (!empty($_SESSION['sid'])) ? $_SESSION['sid'] : null;

		// Если нашли, попробуем обновить time_last в базе.
		// Заодно и проверим, есть ли сессия там.
		if($sid != null) {
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s');
			$t = "sid = '%s'";
			$sid = mysqli_real_escape_string($this->driverDB->link, $sid);
			$where = sprintf($t, $sid);
			$affected_rows = $this->driverDB->update('sessions', $session,
				$where);

			if($affected_rows == 0) {
				$sid = null;
			}
		}

		// Нет сессии? Ищем логин и hash пароля в куках.
		// Т.е. пробуем переподключиться.

		if($sid == null && isset($_COOKIE['login'])) {
			$user = $this->getUser($_COOKIE['login'], "login");

			if($user != null && $user['pass'] == $_COOKIE['pass'])
				$sid = $this->OpenSession($user['id']);
		}

		// Запоминаем в кеш.
		if($sid != null)
			$this->sid = $sid;

		return $sid;
	}

	/**
	 * Открытие новой сессии
	 * @param $id_user
	 * @return string - результат SID
	 */
	private function OpenSession($id_user)
	{
		// генерируем SID
		$sid = $this->GenerateStr(16);

		// вставляем SID в БД
		$now = date('Y-m-d H:i:s');
		$session = array();
		$session['id_user'] = $id_user;
		$session['sid'] = $sid;
		$session['time_start'] = $now;
		$session['time_last'] = $now;
		$this->driverDB->insert('sessions', $session);

		// регистрируем сессию в PHP сессии
		$_SESSION['sid'] = $sid;

		// возвращаем SID
		return $sid;
	}

	/**Очистка таблицы сессий от истекших сессий*/
	public function clearSessions()
	{
		$min = date('Y-m-d H:i:s', time() - $this->timeLiveSid);
		$t = "time_last < '%s'";
		$where = sprintf($t, $min);
		$this->driverDB->delete('sessions', $where);

	}

	/**
	 * Генерация случайной последовательности
	 * @param int $length - длина последовательности
	 * @return string     - полученная строка
	 */
	protected function GenerateStr($length = 10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;

		while (strlen($code) < $length)
			$code .= $chars[mt_rand(0, $clen)];

		return $code;
	}

	/**
	 * Хеширование паролей встроенными средствами в PHP
	 * (password_hash(pass, algorithm, options))
	 * @param $pass
	 * @return bool|string - hash или false при не удаче
	 */
	protected function hashPass($pass)
	{
		$options = ['cost' => $this->costComplication(),];
		return password_hash($pass, PASSWORD_BCRYPT, $options);
	}

	/**
	 * Данный код замерит скорость выполнения операции хеширования для вашего
	 * сервера с разными значениями алгоритмической сложности для определения
	 * максимального его значения, не приводящего к деградации
	 * производительности. Хорошее базовое значение лежит в диапазоне 8-10.
	 * Данный скрипт ищет максимальное значение, при котором хеширование
	 * уложится в 50 миллисекунд. Но не менее задонного уровня ($limitCost)
	 * @param  int $limitCost - уровент алгоритмической сложности (default=10)
	 * @return int  алгоритмическую сложность
	 */
	protected function costComplication($limitCost = 10)
	{
		$timeTarget = 0.05; // 50 миллисекунд.

		$cost = 8;
		do {
			$cost++;
			$start = microtime(true);
			password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
			$end = microtime(true);
		} while (($end - $start) < $timeTarget);

		if($limitCost > $cost) {
			return $limitCost;
		}
		return $cost;
	}

	/**
	 * Валидация введенных данных в регистрационной форме
	 * Также проводиться проверка на отсутствие пользователя с указанным
	 * именем или email
	 * @param bool $update - Если true, то проверка login & email не выводит
	 * предупреждение о наличии подобных данных в БД
	 * @return array [$trueError, $result|$userForm]
	 */
	public function validateRegistrationForm($update = false)
	{
		$userForm = [];
		//Инициализация переменных
		$result = '';
		$trueError = false;

		$userForm["login"] = (!empty($_POST["login"]))
			? $_POST["login"] : "";
		$userForm["email"] = (!empty($_POST["email"]))
			? $_POST["email"] : "";
		$userForm["nameUser"] = (!empty($_POST["nameUser"]))
			? $_POST["nameUser"] : "";
		$userForm["middleNameUser"] = (!empty($_POST["middleNameUser"]))
			? $_POST["middleNameUser"] : "";
		$userForm["surnameUser"] = (!empty($_POST["surnameUser"]))
			? $_POST["surnameUser"] : "";
		$userForm["role"] = (!empty($_POST["role"]))
			? $_POST["role"] : null;

		//Чистим данные от возможных вредных выражений
		$userForm = array_map([$this, "cleanString"], $userForm);

		//Пишим пароль -его не надо чистить, он хешуруется до добаления в БД
		$userForm["pass"] = (!empty($_POST["pass"]))
			? $_POST["pass"] : "";


		//Проверки на правильность введенных данных
		if(strlen($userForm["login"]) <= 4) {
			$trueError = true;
			($userForm["login"] === '')
				? $result .= "Логин не может быть пустым" . "<br/>"
				: $result .= "Логин должен содержать 5 и более символов" . "<br/>";
		} else {
			if(!is_null($this->getUser($userForm["login"], "login"))
				&& !$update) {
				$trueError = true;
				return [$trueError, "Пользователь с таким логином уже существует"];
			}
		}

		if(strlen($userForm["pass"]) <= 5) {
			$trueError = true;
			($userForm["pass"] === '')
				? $result .= "Пароль не может быть пустым" . "<br/>"
				: $result .= "Пароль должен содержать 6 и более  символов" . "<br/>";
		}
		if(strlen($userForm["email"]) <= 6) {
			$trueError = true;
			($userForm["email"] === '')
				? $result .= "Email не может быть пустым" . "<br/>"
				: $result .= "Введите настоящий Email" . "<br/>";
		} else {
			if(!is_null($this->getUser($userForm["email"], "email"))
				&& !$update) {
				$trueError = true;
				return [$trueError, "Пользователь с такой почтой уже существует"];
			}
		}

		if($trueError) {
			return [$trueError, $result];
		}
		$this->user = $userForm;

		//Надо ли запомнить пользователя?
		if(!empty($_POST["rememberUser"])) {
			$this->rememberUser = true;
		}

		return [$trueError, $userForm];


	}

	/**
	 * Валидация введенных данных в  форме входа
	 * @return array [$trueError, $result] -
	 * $result = либо idUser, либо описание ошибки
	 */
	public function validateInputForm()
	{
		$user = null;
		$trueError = false;
		$itemName = (!empty($_POST['login'])) ? $_POST['login'] : '';
		$pass = (!empty($_POST['pass'])) ? $_POST['pass'] : '';

		//Чистим данные от возможных вредных выражений
		$itemName = $this->cleanString($itemName);


		if(!empty($byLogin = $this->getUser($itemName, "login"))) {
			$user = $byLogin;
		} else if(!empty($byEmail = $this->getUser($itemName, "email"))) {
			$user = $byEmail;
		} else {
			$trueError = true;
			return [$trueError, "Неверно введено имя, email или пароль"];
		}

		if(!password_verify($pass, $user["pass"])) {
			$trueError = true;
			return [$trueError, "Неверно введено имя, email или пароль"];
		}
		if(!empty($_POST["rememberUser"])) {
			$this->rememberUser = true;
		}
		return [$trueError, $user["id"]];

	}

	/**Очистка входящих данных от возможных аттак
	 * @param string $value
	 * @return string
	 */
	public function cleanString($value = "")
	{
		//Отсеивание пустых данных
		if(strlen($value) === 0) {
			return $value;
		}

		$value = trim($value);
		$value = stripslashes($value);
		$value = strip_tags($value);
		$value = htmlspecialchars($value);

		//Выражение начинается с Заглавной буквы?
		$oneUcFirst = false;
		$oneChar = IntlChar::ord($value[0]);
		if($oneChar > 65 && $oneChar < 90) {
			$oneUcFirst = true;
		}

		$value = strtolower($value);
		//Удаление из строки опасных выражений
		$expressions = U_List_prohibited_expressions::expressions;
		foreach ($expressions as $k) {
			$value = str_replace($k, "", $value);
		}
		//удаляем символ ";"
		$value = str_replace(";", "", $value);

		//Преобразуем первый символ в верхний регистр
		if($oneUcFirst) {
			$value = ucfirst($value);
		}

		return $value;
	}

	/**
	 * Вставка пользователя в БД
	 * @param  $userParameters
	 * @return int id вставленной записи
	 */
	public function registrationUser($userParameters)
	{
		$role = (!is_null($userParameters["role"]))
			? $this->getIdRole($userParameters["role"])
			: $this->getIdRole("пользователь");
		$pass = $this->hashPass($userParameters["pass"]);
		$dataTable = [
			"login" => $userParameters["login"],
			"pass" => $pass,
			"email" => $userParameters["email"],
			"first_name" => $userParameters["nameUser"],
			"middle_name" => $userParameters["middleNameUser"],
			"surname" => $userParameters["surnameUser"],
			"role" => $role,];

		return $this->driverDB->insert("users", $dataTable);
	}

	/**
	 * Активация пользователя (сессия, куки)
	 * @param $idUser
	 */
	public function activationUser($idUser)
	{
		$this->user = $this->getUser($idUser);
		$this->uid = $idUser;
		$this->fillingPassportUser();

		// запоминаем имя и пароль
		if($this->rememberUser) {
			$expire = time() + 3600 * 24 * 100;
			setcookie('login', $this->user["login"],
				$expire, $this->pathCookie, $this->domainCookie);
			setcookie('pass', $this->user["pass"],
				$expire, $this->pathCookie, $this->domainCookie);
		}

		// открываем сессию и запоминаем SID
		$this->sid = $this->OpenSession($idUser);
	}

	/**
	 * Правка профиля пользователя
	 * @param $userParameters
	 * @return null 1|null
	 */
	public function editProfile($userParameters)
	{
		$id = $userParameters["id"];
		$role = ($this->getIdRole($userParameters["role"]))
			? $this->getIdRole($userParameters["role"])
			: $this->getIdRole("пользователь");

		$object = [
			"login" => $userParameters["login"],
			"pass" => $userParameters["pass"],
			"email" => $userParameters["email"],
			"first_name" => $userParameters["nameUser"],
			"middle_name" => $userParameters["middleNameUser"],
			"surname" => $userParameters["surnameUser"],
			"role" => $role,];

		$where = "id = $id";
		$result = $this->driverDB->update("users", $object, $where);

		return (!empty($result)) ? $result : null;
	}

	/**
	 * Удаление пользователя из БД
	 * @param $idUser
	 * @return 1|null
	 */
	public function deleteProfile($idUser)
	{
		$table = "users";
		$where = "id = $idUser";
		$result = $this->driverDB->delete($table, $where);

		return (!empty($result)) ? $result : null;
	}

	/**
	 * Активация сортировки таблицы
	 * sortColumn    - login, email, surname, first_name, middle_name, role
	 * sortType        - ASC или DESC
	 */
	public function activationSort()
	{
		if(empty($_SESSION["switchSorting"] ||
			is_bool($_SESSION["switchSorting"]))) {
			$_SESSION["switchSorting"] = false;
		}

		if(empty($_SESSION["sortType"])) {
			$_SESSION["sortType"] = "DESC";
		}

		if(empty($_SESSION["sortColumn"])) {
			$_SESSION["sortColumn"] = null;
		}
	}

	/**Переключатель направления сортировки таблицы*/
	public function switchSortType()
	{
		if(empty($_SESSION["switchSorting"])) {
			$this->activationSort();
		}

		if($_SESSION["switchSorting"]) {
			$_SESSION["switchSorting"] = false;
			$_SESSION["sortType"] = "DESC";
		} else {
			$_SESSION["switchSorting"] = true;
			$_SESSION["sortType"] = "ASC";
		}
	}
}
