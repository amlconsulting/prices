<?php

class BaseModel {

    protected $db;
    protected $logger;

    public function __construct($db, $logger) {
        $this->db = $db;
        $this->logger = $logger;
    }

}