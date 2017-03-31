<?php

define("DS", DIRECTORY_SEPARATOR);
define("ROOT", getcwd() . DS . '..' . DS . '..' . DS);
define("LOG_PATH", ROOT . 'logs' . DS);
define("VENDOR_PATH", ROOT . 'vendor' . DS);
define("SOURCE_PATH", ROOT . 'src' . DS);
define("APP_PATH", SOURCE_PATH . 'application' . DS);
define("CONFIG_PATH", APP_PATH . 'config' . DS);
define("CONTROLLER_PATH", APP_PATH . 'controllers' . DS);
define("MODEL_PATH", APP_PATH . 'models' . DS);
define("VIEW_PATH", APP_PATH . 'views' . DS);
define("INTERFACE_PATH", APP_PATH . 'interfaces' . DS);