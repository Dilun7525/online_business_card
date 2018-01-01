<?php/**Класс модели домашней страницы * @param ["column1"=>"value1",...] * @param $where - условие для использования в драйвере БД*/class M_Home{	private $table = "";		// используемая таблица	private $dbInstance; 		// экземпляр драйвера БД	private static $instance;	// экземпляр класса	public static function getInstance()	{		if (self::$instance == null)			self::$instance = new M_Home();		return self::$instance;	}	protected function __construct()	{		$this->table = "menuPageSite";		$this->dbInstance = M_DB::getInstance(PATH_CONFIGS."db_install.txt");	}	/**	 * @return array $dataResponse - массив всех записей	 */	public function imgMenuGet()	{		$qury="SELECT * FROM $this->table";		return $this->dbInstance->select($qury);	}	/**	 * @param $dataRequest - массив, ключи - имена столбцов, значение - данные в базу	 * @return int id вставленной записи	 */	public function imgMenuAdd($dataRequest)	{		return $this->dbInstance->insert($this->table,$dataRequest);	}	/**	 * @param $dataRequest - массив, ключи - имена столбцов, значение - данные в базу	 * @param $where - условие (часть SQL запроса)	 * @return int кол-во затронутых строк	 */	public function imgMenuUpdate($dataRequest, $where)	{		return $this->dbInstance->update($this->table,$dataRequest,$where);	}	/**	 * @param @where - строка вида первичный ключ = число	 * @return int количество удаленных строк	 */	public function imgMenuDelete($where)	{		return $this->dbInstance->delete($this->table,$where);	}}