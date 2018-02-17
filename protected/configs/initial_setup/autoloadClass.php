<?php
spl_autoload_register(function ($classname){
	switch ($classname[0]) {
		case 'C':
			if (file_exists(PATH_CONTROLLER_R . "$classname.php")) {
				include_once(PATH_CONTROLLER_R . "$classname.php");
			}
			break;
		case 'M':
			if (file_exists(PATH_MODEL_R . "$classname.php")) {
				include_once(PATH_MODEL_R . "$classname.php");
			}
			break;
			case 'U':
			if (file_exists(PATH_CONFIGS_R . "$classname.php")) {
				include_once(PATH_CONFIGS_R . "$classname.php");
			}
			break;
	}
});
