<?php
/**Драйвер для работы с базой данных
 */

class M_DB
{
//region regionVariableS
	public $link;

	private $host = '';
	private $database = '';
	private $user = '';
	private $password = '';

	private static $instance;
	private static $fileNameBefore;

//endregion

	protected function __construct($fileName)
	{
		self::$fileNameBefore = $fileName;
		$strJson = $this->readFile($fileName);
		$this->host = $strJson->host;
		$this->user = $strJson->user;
		$this->password = $strJson->password;
		$this->database = $strJson->database;
		$this->connect();
	}

	/** Singleton с дополнительной проверкой, поступаемого файла
	 *  (если файлы не равны, то экземпляр пересоздается)
	 * @param    $fileName -имя файла с конфигурацией DB
	 * @return   db#instance - экземпляр класса
	 * @see db#getInstance()
	 */
	public static function getInstance($fileName)
	{
		if (self::$instance === null ||
			self::$fileNameBefore !== $fileName) {

			self::$instance = new self($fileName);
		}
		return self::$instance;
	}

	/** Чтение из файла только 1 JSON строку
	 * @param    $fileName -имя файла
	 * @return mixed $strJson - переменную асоциативного массива
	 * @see db#readFile()
	 */
	private function readFile($fileName)
	{
		$fd = fopen($fileName, 'r') or die("не удалось открыть файл");
		$strJson = json_decode(fgets($fd));
		fclose($fd);
		return $strJson;
	}

	function connect()
	{
		$this->link = mysqli_connect($this->host, $this->user, $this->password, $this->database)
		or die("Ошибка подключения к БД" . mysqli_error($this->link));
		mysqli_query($this->link, "SET NAMES utf8mb4");
		mysqli_set_charset($this->link, 'utf8mb4');
	}

	function disconnect()
	{
		mysqli_close($this->link);
		$this->link = '';
	}

	/**
	 * @param $table - имя таблицы
	 * @param $object - массив, ключи - имена столбцов, значение - данные в базу
	 * @return int id вставленной записи
	 * @see db#insert($table, $object)
	 */
	public function insert($table, $object)
	{
		$columns = array();
		$values = array();

		foreach ($object as $key => $value) {
			$key = mysqli_real_escape_string($this->link, $key . '');
			$columns[] = "`$key`";
			if ($value === null) {
				$values[] = 'NULL';
			} else {
				$value = mysqli_real_escape_string($this->link, $value . '');
				$values[] = "'$value'";
			}
		}

		$columns = implode(', ', $columns);
		$values = implode(', ', $values);

		// Example: INSERT INTO `table` (`col1`, `col2`, `col3`) VALUES ('val1', 'val2', 'val3')

		$query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $columns, $values);

		$result = mysqli_query($this->link, $query);
		if (!$result) {
			die(mysqli_error($this->link));
		}
		return mysqli_insert_id($this->link);
	}


	/**
	 * @param $table - имя таблицы
	 * @param $object - массив, ключи - имена столбцов, значение - данные в базу
	 * @param $where - условие (часть SQL запроса)
	 * @return int кол-во затронутых строк
	 * @see db#update($table, $object, $where)
	 */
	public function update($table, $object, $where)
	{
		$sets = array();
		foreach ($object as $key => $value) {
			$key = mysqli_real_escape_string($this->link, $key . '');
			if ($value === null) {
				$sets[] = "`$key`=NULL";
			} else {
				$value = mysqli_real_escape_string($this->link, $value . '');
				$sets[] = "`$key`='$value'";
			}
		}
		$sets = implode(', ', $sets);

		// UPDATE `table` SET `col1` = 'val1', `col2` = 'val2'
		$query = sprintf("UPDATE `%s` SET %s WHERE %s", $table, $sets, $where);
		$result = mysqli_query($this->link, $query);
		if (!$result) {
			die(mysqli_error($this->link));
		}
		return mysqli_affected_rows($this->link);
	}

	/**
	 * @param $table - имя таблицы
	 * @param @where - строка вида первичный ключ = число
	 * @return int количество удаленных строк
	 * @see db#delete($table, $where)
	 */
	public function delete($table, $where)
	{
		$query = sprintf("DELETE FROM %s WHERE %s", $table, $where);
		$result = mysqli_query($this->link, $query);
		if (!$result) {
			die(mysqli_error($this->link));
		}
		return mysqli_affected_rows($this->link);
	}


	/** Выборка строк
	 * @param $query - полный текст SQL запроса
	 * @result array - массив полученных строк из БД
	 * @return array
	 * @see db#select($query)
	 */
	public function select($query)
	{
		$query = mysqli_real_escape_string($this->link, $query);
		$result = mysqli_query($this->link, $query);

		if (!$result) {
			die(mysqli_error($this->link));
		}
		$arr = [];
		while ($row = mysqli_fetch_assoc($result)) {
			$arr[] = $row;
		}
		return $arr;
	}
}
