<?php

use UsersModel as Users;
use ItemsModel as Items;

class HomeController extends BaseController{

    public function getUserItemsByUserName($request, $response, $args) {
        try {
            // Get User
            $userModel = new Users($this->db, $this->logger);
            $user = $userModel->getUserByUrlLink($args['user']);

            // If the user isn't found
            if(!$user) {
                return $this->view->render($response, 'error/error.phtml', [
                    'error' => 'Page Not Found!',
                    'message' => 'The user you are attempting to find does not have a profile. Please verify your URL.'
                ]);
            }

            // Get user items
            $itemsModel = new Items($this->db, $this->logger);
            $items = $itemsModel->getItemsByUserId($user['id']);

            return $this->view->render($response, 'home/main.phtml', [
                'user' => $user,
                'items' => $items
            ]);
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        }
    }

    public function seedItems() {
        $items = [
            [
                "style" => "Carly",
                "price" => 40.00
            ],
            [
                "style" => "Cassie",
                "price" => 23.00
            ],
            [
                "style" => "Classic",
                "price" => 25.00
            ],
            [
                "style" => "Irma",
                "price" => 25.00
            ],
            [
                "style" => "Julia",
                "price" => 32.00
            ],
            [
                "style" => "Kids Leggings",
                "price" => 16.00
            ],
            [
                "style" => "Lindsay",
                "price" => 35.00
            ],
            [
                "style" => "Madison",
                "price" => 33.00
            ],
            [
                "style" => "Maxi",
                "price" => 31.00
            ],
            [
                "style" => "OS Leggings",
                "price" => 18.00
            ],
            [
                "style" => "Patrick",
                "price" => 20.00
            ],
            [
                "style" => "Randy",
                "price" => 25.00
            ],
            [
                "style" => "TC Leggings",
                "price" => 18.00
            ],
            [
                "style" => "Tween Leggings",
                "price" => 16.00
            ]
        ];

        $stmt = $this->db->prepare("insert into items (user_id, name, price) values (:user_id, :name, :price)");
        $user_id = 1;
        $style = '';
        $price = 0.00;

        foreach($items as $item) {            
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':name', $style);
            $stmt->bindParam(':price', $price);

            try {
                $style = $item['style'];
                $price = $item['price'];
                $stmt->execute();
            } catch(Exception $e) {
                $this->logger->addInfo($e->getMessage());
            }
        }        
    }
}