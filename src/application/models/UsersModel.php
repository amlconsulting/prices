<?php

class UsersModel extends BaseModel {

    public function getUserByUrlLink($name) {
        try{
            $stmt = $this->db->prepare("select id, name, email from users where uri_link = :userName");
            $stmt->bindParam(':userName', $name);
            $stmt->execute();

            $user = $stmt->fetch();

            if($stmt->rowCount() === 0) {
                return false;
            }

            return $user;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        }        
    }

    public function getUserByUserId($user_id) {
        try{
            $stmt = $this->db->prepare("select id, name, email, password, uri_link from users where id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $user = $stmt->fetch();

            if($stmt->rowCount() === 0) {
                return false;
            }

            return $user;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        } 
    }

    public function getUserByEmail($email) {
        try{
            $stmt = $this->db->prepare("select id, name, email, password from users where email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch();

            if($stmt->rowCount() === 0) {
                return false;
            }

            return $user;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        } 
    }

    public function updateUser($user_id, $params) {
        $this->db->beginTransaction();
        
        try{
            $stmt = $this->db->prepare("update users set name = :name, email = :email, uri_link = :uri_link where id = :user_id");
            $stmt->bindParam(':name', $params['name']);
            $stmt->bindParam(':email', $params['email']);
            $stmt->bindParam(':uri_link', $params['uri_link']);
            $stmt->bindParam(':user_id', $user_id);
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