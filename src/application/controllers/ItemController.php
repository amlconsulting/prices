<?php


class ItemController extends BaseController {

    public function getAllByUserId($user_id){
        $stmt = $this->db->query("select id, name, price from items where user_id = 1");
        $items = $stmt->fetchAll();

        echo json_encode($items);
    }
    
}