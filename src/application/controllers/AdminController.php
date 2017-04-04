<?php

use UsersModel as Users;
use ItemsModel as Items;

//TODO - need to have a logout link in nav instead of contact link

class AdminController extends BaseController {

    protected $csrf;

    public function __construct($container) {
        parent::__construct($container);
        $this->csrf = $container->csrf;
    }

    public function login($request, $response) {
        if($request->getMethod() === 'GET') {    
            return $this->view->render($response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys($request),
                'email' => '',
                'errors' => []
            ]);
        }

        $user_email = $request->getParam('email');
        $user_pass = $request->getParam('password');

        $userModel = new Users($this->db, $this->logger);
        $user = $userModel->getUserByEmail($user_email);

        if(!$user) {
            return $this->view->render($response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys($request),
                'email' => $user_email,
                'errors' => [
                    'email' => 'User doesn\'t exist'
                ]
            ]);
        }

        if(password_verify($user_pass, $user['password'])) {
            $_SESSION['user'] = $user->id;

            $itemsModel = new Items($this->db, $this->logger);
            $items = $itemsModel->getItemsByUserId($user['id']);

            //TODO - need to make this redirect to a home page, not just load up the view.
            return $response->withRedirect(
                $this->view->render($response, 'admin/main.phtml', [
                    'user' => $user,
                    'items' => $items
                ])
            );
        } else {
            return $this->view->render($response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys($request),
                'email' => $user_email,
                'errors' => [
                    'password' => 'Incorrect password'
                ]
            ]);
        }
    }

    public function logout($request, $response) {
        unset($_SESSION['user']);
        session_regenerate_id();

         return $this->view->render($response, 'admin/login.phtml', [
            'csrf' => $this->getCsrfKeys($request),
            'email' => '',
            'errors' => []
        ]);
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