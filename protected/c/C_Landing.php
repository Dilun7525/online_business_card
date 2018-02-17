<?php
/**
 * Created by PhpStorm.
 * User: dilun
 * Date: 02.01.18
 * Time: 18:28
 */

class C_Landing extends C_Base
{

	/**Функция отрабатывающая до основного метода*/
	protected function before()
	{
		parent::before();
		$this->title .="landing";
	}

	public function action_index()
	{

		$this->dataTemplate[]=
			[
			"template" => PATH_VIEW_R . "V_Landing.php",
			//"template" => PATH_VIEW_R . "test.php",
			"title" => $this->title,
		];

	}


}