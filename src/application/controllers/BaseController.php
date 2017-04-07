<?php

class BaseController {

    protected $db;
    protected $logger;
    protected $view;
    protected $request;
    protected $response; 
    protected $csrf;   

    public function __construct($container) {  
        $this->db = $container->db;
        $this->logger = $container->logger;
        $this->view = $container->view; 
        $this->csrf = $container->csrf;    
        $this->request = $container->request;   
        $this->response = $container->response;
    }

    public function getCsrfKeys() {
        $this->request = $this->csrf->generateNewToken($this->request);

        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $this->request->getAttribute($nameKey);
        $value = $this->request->getAttribute($valueKey);

        return [
            'nameKey' => $nameKey, 
            'valueKey' => $valueKey, 
            'name' => $name, 
            'value' => $value
        ];
    }    

}