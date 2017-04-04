<?php

class AdminController extends BaseController {

    public function login($request, $response) {
        if($request->getMethod() === 'GET') {
            return $this->view->render($response, 'admin/login.phtml');
        }
    }

}