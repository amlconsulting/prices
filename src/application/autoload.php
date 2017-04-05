<?php

$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();

spl_autoload_register(function($classname) {
    if(substr($classname, -10) == "Controller") {
        require CONTROLLER_PATH . "$classname.php";
    } elseif(substr($classname, -5) == "Model") {
        require MODEL_PATH . "$classname.php";
    } elseif(substr($classname, -6) == 'Helper') {
        require HELPER_PATH . "$classname.php";
    }
});