<?php

class UsersModel extends BaseModel {

    public function searchAllUsers($name) {
        try{
            $likeName = '%' . $name . '%';

            $stmt = $this->db->prepare("select name, email, uri_link from users where name like :name");
            $stmt->bindParam(':name', $likeName);
            $stmt->execute();

            $users = $stmt->fetchAll();

            if($stmt->rowCount() === 0) {
                return false;
            }

            return $users;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        } 
    }

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

    public function updatePasswordByUserId($user_id, $password) {
        $this->db->beginTransaction();
        
        try{
            $stmt = $this->db->prepare("update users set password = :password where id = :user_id");
            $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
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

    public function checkEmailExists($email) {
        try{
            $stmt = $this->db->prepare("select email from users where email = :email and id != :id");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $_SESSION['user']);
            $stmt->execute();

            $user = $stmt->fetch();

            if($stmt->rowCount() > 0) {
                return true;
            }

            return false;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        } 
    }

    public function checkUriExists($uri_link) {
        try{
            $stmt = $this->db->prepare("select uri_link from users where uri_link = :uri_link and id != :id");
            $stmt->bindParam(':uri_link', $uri_link);
            $stmt->bindParam(':id', $_SESSION['user']);
            $stmt->execute();

            $user = $stmt->fetch();

            if($stmt->rowCount() > 0) {
                return true;
            }

            return false;
        } catch(Exception $e) {
            $this->logger->addError($e->getMessage());
        } 
    }

}