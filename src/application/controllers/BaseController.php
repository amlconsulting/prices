<?php

class BaseController {

    protected $db;
    protected $logger;
    protected $view;

    public function __construct($container) {  
        $this->db = $container->db;
        $this->logger = $container->logger;
        $this->view = $container->view;
    }

}