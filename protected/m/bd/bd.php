<?php
/**
 * Created by PhpStorm.
 * User: dilun
 * Date: 14.12.17
 * Time: 0:02
 */

class bd
{
	private $host = 'localhost';
	private $database = 'Tr_demo_site';
	private $user = 'Tr';
	private $password = 'xHVVWCRd4mAAqWrh';

	public $link;

	static public $ii = 0;


	/** Чтение из файла только 1 JSON строку
	 * @param    $fileNAme -имя файла
	 * @return mixed $strJson - переменную асоциативного массива
	 * @see bd#readFile()
	 */
	function readFile($fileNAme)
	{
		$fd = fopen($fileNAme, 'r') or die("не удалось открыть файл");
		$strJson = json_decode(fgets($fd));
		fclose($fd);
		return $strJson;
	}

	/*function __construct($host, $user, $password, $database)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}*/
	function __construct($fileNAme)
	{
		$strJson = $this->readFile($fileNAme);
		$this->host = $strJson->host;
		$this->user = $strJson->user;
		$this->password = $strJson->password;
		$this->database = $strJson->database;
	}

	function connect()
	{
		$link = mysqli_connect($this->host, $this->user, $this->password, $this->database)
		or die("Ошибка подключения к БД" . mysqli_error($link));
		$openConnect = true;
		mysqli_query($link, "SET NAMES utf8mb4");
		mysqli_set_charset($link, 'utf8mb4');

		echo "В БД вошли_" . ++bd::$ii . "<br>";


		mysqli_close($link);
		$link = '';
	}
}