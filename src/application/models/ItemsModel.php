<?php

class ItemsModel extends BaseModel {

    public function getItemsByUserId($user_id) {
        try{
            $stmt = $this->db->prepare("select id, name, price from items where user_id = :id order by name");
            $stmt->bindValue(':id', $user_id);
            $stmt->execute();

            $items = $stmt->fetchAll();

            if(count($items) === 0) {
                return false;
            }

            return $items;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        }  
    }

}