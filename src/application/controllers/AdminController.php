<?php

use UsersModel as Users;
use ItemsModel as Items;
//use Respect\Validation\Validator as v;

class AdminController extends BaseController {

    protected $csrf;

    public function __construct($container) {
        parent::__construct($container);
        $this->csrf = $container->csrf;

        //TODO - redirect user to login page if not logged in
        /*if(!$_SESSION['user']) {
            return $this->view->render($response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys($request),
                'email' => '',
                'errors' => []
            ]); 
        }*/
    }

    public function login($request, $response) {
        if($request->isGet()) { 
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
            $_SESSION['user'] = $user['id'];

            $itemsModel = new Items($this->db, $this->logger);
            $items = $itemsModel->getItemsByUserId($user['id']);

            //TODO - need to make this redirect to a home page, not just load up the view.
            return $response->withRedirect(
                $this->view->render($response, 'admin/main.phtml', [
                    'user' => $user,
                    'items' => $items,
                    'loggedIn' => true
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

    public function user($request, $response) {
        $userModel = new Users($this->db, $this->logger);
        $user = $userModel->getUserByUserId($_SESSION['user']);

        return $this->view->render($response, 'admin/user.phtml', [
            'user' => $user,
            'loggedIn' => true
        ]);
    }

    public function editUser($request, $response) {
        $userModel = new Users($this->db, $this->logger);
        $user = $userModel->getUserByUserId($_SESSION['user']);

        if($request->isGet()) {
            return $this->view->render($response, 'admin/edituser.phtml', [
                'csrf' => $this->getCsrfKeys($request),
                'user' => $user,
                'loggedIn' => true
            ]);
        }

        $params = $request->getParsedBody();

        $userUpdated = $userModel->updateUser($_SESSION['user'], $params);
        $user = $userModel->getUserByUserId($_SESSION['user']);

        return $this->view->render($response, 'admin/user.phtml', [
            'user' => $user,
            'loggedIn' => true,
            'success' => $userUpdated
        ]);

        /*$nameValidator = v::stringVal()->max(255)->validate($request->inputs['name']);
        $emailValidator = v::stringVal()->max(250)->validate($request->inputs['email']);
        $passwordValidator = v::stringVal()->length(8, 20)->validate($request->inputs['password']);
        $passwordConfirmValidator = v::stringVal()->length(8, 20)->equals($password)->validate($request->inputs['passwordConfirm']);

        echo $nameValidator;*/        
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