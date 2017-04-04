<?php

class AdminController extends BaseController {

    protected $csrf;

    public function __construct($container) {
        parent::__construct($container);
        $this->csrf = $container->csrf;
    }

    public function login($request, $response) {
        if($request->getMethod() === 'GET') {    
            return $this->view->render($response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys($request)
            ]);
        }

        echo "Made it";
    }

    private function getCsrfKeys($request) {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return [
            'nameKey' => $nameKey, 
            'valueKey' => $valueKey, 
            'name' => $name, 
            'value' => $value
        ];
    }

}