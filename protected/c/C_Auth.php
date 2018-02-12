<?php//// Конттроллер страниц связанной с авторизацией и регистрацией пользователей.//class C_Auth extends C_Base{	protected $trueError = false;	protected $resultError = '';	protected $hiddenErrorDiv = '';	protected $modelUser;	public function __construct()	{		parent::__construct();		//$this->modelUser = M_Users::getInstance();	}	public function before()	{		parent::before();	}	public function action_login()	{		$this->title .= "Authorization";		if(!$this->trueError) {			$this->resultError = "Блок ошибок";			$this->hiddenErrorDiv = "hidden";		}		$this->dataTemplate[] = [			"template" => PATH_VIEW . "V_Auth.php",			"title" => $this->title,			"hiddenErrorDiv" => $this->hiddenErrorDiv,			"resultError" => $this->resultError,];	}	public function action_registration()	{		if(!$this->trueError) {			$this->resultError = "Блок ошибок";			$this->hiddenErrorDiv = "hidden";		}		$this->dataTemplate[] = [			"template" => PATH_VIEW . "V_Registration.php",			"title" => $this->title,			"hiddenErrorDiv" => $this->hiddenErrorDiv,			"resultError" => $this->resultError,];	}	public function action_validate_input_form()	{		$result = $this->modelUser->validateInputForm();		$this->trueError = $result[0];		if($this->trueError) {			$this->resultError = $result[1];			$this->action_login();		} else {			$idUser = $result[1];			$this->modelUser->activationUser($idUser);			$this->redirect("/");		}	}	public function action_validate_register_form()	{		$result = $this->modelUser->validateRegistrationForm();		$this->trueError = $result[0];		if($this->trueError) {			$this->resultError = $result[1];			$this->action_registration();		} else {			$idUser = $this->modelUser->registrationUser($result[1]);			if(is_numeric($idUser)) {				$this->modelUser->activationUser($idUser);				$this->redirect("/");			} else {				$this->trueError = true;				$this->resultError = "Пользователь не зарегистрирован.<br/>" .					"Попробуйте еще раз";				$this->action_registration();			}		}	}	public function action_exit_user()	{		$this->modelUser->logout();		$this->redirect("/");	}}