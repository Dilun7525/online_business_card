<?php
/**
 * Created by PhpStorm.
 * User: dilun
 * Date: 02.01.18
 * Time: 18:28
 */

class C_Error_404 extends C_Base
{

	/**Функция отрабатывающая до основного метода*/
	protected function before()
	{
		parent::before();
		$this->title .="404 error";
	}

	public function action_index()
	{

		$this->dataTemplate[]=
			[
			"template" => PATH_VIEW . "V_Error_404.php",
			"title" => $this->title,
		];

	}


}