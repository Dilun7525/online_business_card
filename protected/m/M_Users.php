<?php

//
// Менеджер пользователей
//


class M_Users
{
	private static $instance;   // экземпляр класса
	private $driverDB;          // драйвер БД

	private $sid;                // идентификатор текущей сессии
	private $timeLiveSid;        // время существования ссесии
	private $uid;                // идентификатор текущего пользователя
	public $user = [            // авторизованный пользователь
		"id" => "",
		"login" => "",
		"pass" => "",
		"email" => "",
		"surname" => "",
		"first_name" => "",
		"middle_name" => "",
		"role" => "",
		"img" => "",
	];
	private $rememberUser = false;//Запоминать пользователя?
	private $sortType;          //ASC или DESC
	private $switchSorting;     //true или false

	/**Получение экземпляра класса*/
	public static function getInstance()
	{
		if(self::$instance == null)
			self::$instance = new M_Users();

		return self::$instance;
	}

	protected function __construct()
	{
		$this->driverDB = M_DB::getInstance(PATH_CONFIGS . "db_install.txt");

		$this->sid = null;
		$this->timeLiveSid = 60 * 20;
		$this->uid = null;

	}

	/**Валидация введенных данных в регистрационной форме
	 * Также проводиться проверка на отсутствие пользователя с указанным
	 * именем или email
	 * @return array [$trueError, $result]
	 */
	public function validateRegistrationForm()
	{
		$result = '';
		$trueError = false;
		$login = '';
		$pass = '';
		$email = '';
		foreach ($_POST as $k => $v) {
			$$k = $v;
		}

		if(strlen($login) <= 4) {
			$trueError = true;
			($login === '')
				? $result .= "Логин не может быть пустым" . "<br/>"
				: $result .= "Логин должен содержать 5 и более символов" . "<br/>";
		} else {
			if(!is_null($this->getUser($login, "login"))) {
				$trueError = true;
				return [$trueError, "Пользователь с таким логином уже существует"];
			}
		}

		if(strlen($pass) <= 5) {
			$trueError = true;
			($pass === '')
				? $result .= "Пароль не может быть пустым" . "<br/>"
				: $result .= "Пароль должен содержать 6 и более  символов" . "<br/>";
		}
		if(strlen($email) <= 6) {
			$trueError = true;
			($email === '')
				? $result .= "Email не может быть пустым" . "<br/>"
				: $result .= "Введите настоящий Email" . "<br/>";
		} else {
			if(!is_null($this->getUser($email, "email"))) {
				$trueError = true;
				return [$trueError, "Пользователь с такой почтой уже существует"];
			}
		}

		if($trueError) {
			return [$trueError, $result];
		}
		$this->user = $_POST;
		if(!empty($_POST["rememberUser"])) {
			$this->rememberUser = true;
		}
		return [$trueError, $_POST];


	}

	/**Валидация введенных данных в  форме входа
	 * @return array [$trueError, $result] -
	 * $result = либо idUser, либо описание ошибки
	 */
	public function validateInputForm()
	{
		$user = null;
		$trueError = false;
		$itemName = (!empty($_POST['login'])) ? $_POST['login'] : '';
		$pass = (!empty($_POST['pass'])) ? $_POST['pass'] : '';

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

		return [$trueError, $user["id"]];

	}

	/**Вставка пользователя в БД
	 * @param  $userParameters
	 * @return int id вставленной записи
	 */
	public function registrationUser($userParameters)
	{
		$role = (!empty($userParameters["role"]))
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

	/**Активация пользователя (сессия, куки)
	 * @param $idUser
	 */
	public function activationUser($idUser)
	{
		$this->user = $this->getUser($idUser);
		$this->uid = $idUser;

		// запоминаем имя и пароль
		if($this->rememberUser) {
			$expire = time() + 3600 * 24 * 100;
			setcookie('login', $this->user["login"], $expire);
			setcookie('password', $this->user["pass"], $expire);
		}

		// открываем сессию и запоминаем SID
		$this->sid = $this->OpenSession($this->user["id"]);
	}

	/**Извлечение пользователя по заданному условию
	 * @param        $valueField = $id  || $login  || $email
	 * @param string $searchOnField = "id" || "login" || "email"
	 * @return $user|null
	 */
	public function getUser($valueField, $searchOnField = "id")
	{
		$format = "SELECT users.id, login,  pass,  email, " .
			"surname,  first_name,  middle_name, role.role " .
			"FROM users INNER JOIN role ON users.role = role.id " .
			"WHERE users.%s = '%s'";

		$query = sprintf($format, $searchOnField, $valueField);
		$result = $this->driverDB->Select($query);
		return (!empty($result)) ? $result[0] : null;
	}

	/**Извлечение всех пользователей
	 * @param string $sortColumn - название колонки, по которой сортируется
	 * таблица
	 * @param string $sortType = "ASC" || "DESC"
	 * @return  $users|null
	 */
	public function getUsers($sortColumn = "id", $sortType = "ASC")
	{
		$format = "SELECT users.id, login,  pass,  email, " .
			"surname,  first_name,  middle_name, role.role " .
			"FROM users INNER JOIN role ON users.role = role.id " .
			"ORDER BY %s %s";

		$query = sprintf($format, $sortColumn, $sortType);
		$result = $this->driverDB->Select($query);
		return (!empty($result)) ? $result : null;
	}

	/**Переключатель направления сортировки таблицы*/
	public function switchSortType()
	{
		if($_SESSION["switchSorting"]) {
			$_SESSION["switchSorting"] = false;
			$_SESSION["sortType"] = "ASC";
		} else {
			$_SESSION["switchSorting"] = true;
			$_SESSION["sortType"] = "DESC";
		}
	}

	/**Извлечение роли пользователя по его id
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

	/**Извлечение id роли  по названию роли
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

	/**Извлечение списка ролей
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

	/** Правка профиля пользователя
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

	/** Удаление пользователя из БД
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

	/**Очистка таблицы сессий от истекших сессий*/
	public function clearSessions()
	{
		$min = date('Y-m-d H:i:s', time() - $this->timeLiveSid);
		$t = "time_last < '%s'";
		$where = sprintf($t, $min);
		$this->driverDB->delete('sessions', $where);

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

	/**Выход пользователя*/
	public function logout()
	{
		setcookie('login', '', time() - 1);
		setcookie('password', '', time() - 1);
		unset($_COOKIE['login']);
		unset($_COOKIE['password']);
		unset($_SESSION['sid']);
		$this->sid = null;
		$this->uid = null;
	}

	//
	// Получение пользователя
	// $id_user		- если не указан, брать текущего
	// результат	- объект пользователя
	//
	public function Get($id_user = null)
	{
		// Если id_user не указан, берем его по текущей сессии.
		if($id_user == null)
			$id_user = $this->GetUid();

		if($id_user == null)
			return null;

		// А теперь просто возвращаем пользователя по id_user.
		$t = "SELECT * FROM users WHERE id_user = '%d'";
		$query = sprintf($t, $id_user);
		$result = $this->msql->Select($query);
		return $result[0];
	}



	//
	// Проверка наличия привилегии
	// $priv 		- имя привилегии
	// $id_user		- если не указан, значит, для текущего
	// результат	- true или false
	//
	public function Can($priv, $id_user = null)
	{
		if($id_user == null)
			$id_user = $this->GetUid();

		if($id_user == null)
			return false;

		$t = "SELECT count(*) AS cnt FROM privs2roles p2r
			  LEFT JOIN users u ON u.id_role = p2r.id_role
			  LEFT JOIN privs p ON p.id_priv = p2r.id_priv 
			  WHERE u.id_user = '%d' AND p.name = '%s'";

		$query = sprintf($t, $id_user, $priv);
		$result = $this->msql->Select($query);

		return ($result[0]['cnt'] > 0);
	}


	/**Получение id  текущего пользователя
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
		$query = sprintf($t, mysqli_real_escape_string($sid));
		$result = $this->driverDB->select($query);

		// Если сессию не нашли - значит пользователь не авторизован.
		if(count($result) == 0)
			return null;

		// Если нашли - запоминим ее.
		$this->uid = $result[0]['id_user'];
		return $this->uid;
	}

	/**Получение id сессии
	 * @return SID
	 */
	/*private*/ function getSid()
	{
		// Проверка кеша.
		if($this->sid != null)
			return $this->sid;

		// Ищем SID в сессии.
		$sid = $_SESSION['sid'];

		// Если нашли, попробуем обновить time_last в базе. 
		// Заодно и проверим, есть ли сессия там.
		if($sid != null) {
			$session = array();
			$session['time_last'] = date('Y-m-d H:i:s');
			$t = "sid = '%s'";
			$where = sprintf($t, mysqli_real_escape_string($sid));
			$affected_rows = $this->driverDB->update('sessions', $session,
				$where);

			if($affected_rows == 0) {
				$t = "SELECT count(*) FROM sessions WHERE sid = '%s'";
				$query = sprintf($t, mysql_real_escape_string($sid));
				$result = $this->msql->Select($query);

				if($result[0]['count(*)'] == 0)
					$sid = null;
			}
		}

		// Нет сессии? Ищем логин и md5(пароль) в куках.
		// Т.е. пробуем переподключиться.
		if($sid == null && isset($_COOKIE['login'])) {
			$user = $this->getUser($_COOKIE['login']);

			if($user != null && $user['pass'] == $_COOKIE['password'])
				$sid = $this->OpenSession($user['id_user']);
		}

		// Запоминаем в кеш.
		if($sid != null)
			$this->sid = $sid;

		return $sid;
	}


	/**Открытие новой сессии
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

	/**Генерация случайной последовательности
	 * @param int $length - длина последовательности
	 * @return string     - полученная строка
	 */
	private function GenerateStr($length = 10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;

		while (strlen($code) < $length)
			$code .= $chars[mt_rand(0, $clen)];

		return $code;
	}
}
