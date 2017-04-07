<?php

use UsersModel as Users;
use ItemsModel as Items;
//use Respect\Validation\Validator as v;

class AdminController extends BaseController {  

    public function login() {
        if($this->request->isGet()) { 
            return $this->view->render($this->response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys(),
                'email' => '',
                'errors' => []
            ]);
        }

        $user_email = $this->request->getParam('email');
        $user_pass = $this->request->getParam('password');

        $userModel = new Users($this->db, $this->logger);
        $user = $userModel->getUserByEmail($user_email);

        if(!$user) {
            return $this->view->render($this->response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys(),
                'email' => $user_email,
                'errors' => [
                    'email' => 'User doesn\'t exist'
                ]
            ]);
        }

        if(password_verify($user_pass, $user['password'])) {
            $_SESSION['user'] = $user['id'];

            return $this->response->withRedirect('/admin/items');
        } else {
            return $this->view->render($this->response, 'admin/login.phtml', [
                'csrf' => $this->getCsrfKeys(),
                'email' => $user_email,
                'errors' => [
                    'password' => 'Incorrect password'
                ]
            ]);
        }
    }

    public function logout() {
        unset($_SESSION['user']);
        session_regenerate_id();

        return $this->response->withRedirect('/admin/login');
    }

    public function items() {
        $userModel = new Users($this->db, $this->logger);
        $user = $userModel->getUserByUserId($_SESSION['user']);

        $itemsModel = new Items($this->db, $this->logger);
        $items = $itemsModel->getItemsByUserId($user['id']);

        return $this->view->render($this->response, 'admin/items.phtml', [
            'user' => $user,
            'items' => $items,
            'loggedIn' => true
        ]);
    }

    public function user() {
        $userModel = new Users($this->db, $this->logger);
        $user = $userModel->getUserByUserId($_SESSION['user']);

        return $this->view->render($this->response, 'admin/user.phtml', [
            'user' => $user,
            'loggedIn' => true
        ]);
    }

    public function editUser() {
        $userModel = new Users($this->db, $this->logger);
        $user = $userModel->getUserByUserId($_SESSION['user']);

        if($this->request->isGet()) {
            return $this->view->render($this->response, 'admin/edituser.phtml', [
                'csrf' => $this->getCsrfKeys(),
                'user' => $user,
                'loggedIn' => true
            ]);
        }

        $params = $this->request->getParsedBody();

        /*$nameValidator = v::stringVal()->max(255)->validate($this->request->inputs['name']);
        $emailValidator = v::stringVal()->max(250)->validate($rthis->equest->inputs['email']);
        $passwordValidator = v::stringVal()->length(8, 20)->validate($this->request->inputs['password']);
        $passwordConfirmValidator = v::stringVal()->length(8, 20)->equals($password)->validate($this->request->inputs['passwordConfirm']);

        echo $nameValidator;*/        

        $userUpdated = $userModel->updateUser($_SESSION['user'], $params);

        if($userUpdated){
            //flash success            
        } else {
            //flash fail
        }

        return $this->response->withRedirect('/admin/user');        
    }     

    public function addItem() {   
        if($this->request->isGet()) {
            return $this->view->render($this->response, 'admin/additem.phtml', [
                'csrf' => $this->getCsrfKeys()
            ]);
        }

        $params = $this->request->getParsedBody();

        $itemsModel = new Items($this->db, $this->logger);
        $itemAdded = $itemsModel->addItem($params);

        if($itemAdded){
            //flash success            
        } else {
            //flash fail
        }

        return $this->response->withRedirect('/admin/items');   
    }   

    public function editItem($request, $response, $args) {
        $itemsModel = new Items($this->db, $this->logger);
        $item = $itemsModel->getItemById($args['id']);

        if($this->request->isGet()) {
            return $this->view->render($this->response, 'admin/edititem.phtml', [
                'csrf' => $this->getCsrfKeys(),
                'item' => $item
            ]);
        }

        $params = $this->request->getParsedBody();

        $itemUpdated = $itemsModel->updateItemById($args['id'], $params);

        if($itemUpdated){
            //flash success            
        } else {
            //flash fail
        }

        return $this->response->withRedirect('/admin/items');   
    }

    public function deleteItem($request, $response, $args) {
        $itemsModel = new Items($this->db, $this->logger);
        $item = $itemsModel->getItemById($args['id']);

        if($this->request->isGet()) {
            return $this->view->render($this->response, 'admin/deleteitem.phtml', [
                'csrf' => $this->getCsrfKeys(),
                'item' => $item
            ]);
        }

        $itemDeleted = $itemsModel->deleteItemById($args['id']);

        if($itemDeleted){
            //flash success            
        } else {
            //flash fail
        }

        return $this->response->withRedirect('/admin/items');  
    }

}