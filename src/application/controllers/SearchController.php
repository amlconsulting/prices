<?php

use UsersModel as Users;

class SearchController extends BaseController {

    public function search($request, $response) {
        $users = [];

        $params = $this->request->getParsedBody();

        if(isset($params['name'])) {
            $usersModel = new Users($this->db, $this->logger);
            $users = $usersModel->searchAllUsers($params['name']);
        }

        return $this->view->render($response, 'home/search.phtml', [
            'csrf' => $this->getCsrfKeys(),
            'search' => $params['name'],
            'users' => $users
        ]);
    }

}