<?php
spl_autoload_register(function ($classname){
	switch ($classname[0]) {
		case 'C':
			if (file_exists(PATH_CONTROLLER . "$classname.php")) {
				include_once(PATH_CONTROLLER . "$classname.php");
			}
			break;
		case 'M':
			if (file_exists(PATH_MODEL . "$classname.php")) {
				include_once(PATH_MODEL . "$classname.php");
			}
			break;
	}
});
