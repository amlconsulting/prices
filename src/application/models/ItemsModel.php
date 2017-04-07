<?php

class ItemsModel extends BaseModel {

    public function getItemsByUserId($user_id) {
        try{
            $stmt = $this->db->prepare("select id, name, price from items where user_id = :user_id order by name");
            $stmt->bindValue(':user_id', $user_id);
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

    public function getItemById($id) {
        try{
            $stmt = $this->db->prepare("select id, name, price from items where id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $item = $stmt->fetch();

            if(count($item) === 0) {
                return false;
            }

            return $item;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        }  
    }

    public function addItem($params) {
        $this->db->beginTransaction();

        try{
            $stmt = $this->db->prepare("insert items (user_id, name, price) values (:user_id, :name, :price)");
            $stmt->bindValue(':user_id', $_SESSION['user']);
            $stmt->bindValue(':name', $params['name']);
            $stmt->bindValue(':price', $params['price']);
            $stmt->execute();

            $this->db->commit();

            return true;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
            $this->db->rollback();

            return false;
        } 
    }

    public function updateItemById($id, $params) {
        $this->db->beginTransaction();

        try{
            $stmt = $this->db->prepare("update items set name = :name, price = :price where id = :id");
            $stmt->bindValue(':name', $params['name']);
            $stmt->bindValue(':price', $params['price']);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $this->db->commit();

            return true;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
            $this->db->rollback();

            return false;
        } 
    }

    public function deleteItemById($id) {
        $this->db->beginTransaction();

        try{
            $stmt = $this->db->prepare("delete from items where id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $this->db->commit();

            return true;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
            $this->db->rollback();

            return false;
        } 
    }

}