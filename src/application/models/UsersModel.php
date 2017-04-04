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

}