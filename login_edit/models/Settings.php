<?php
require_once 'libraries/Database.php';


class Settings {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }

    public function getAllowChangeRating() {
        $this->db->query('SELECT allow FROM settings WHERE id = 1');
        $row = $this->db->single();
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function setAllowValue($id, $allow) {
        $this->db->query('UPDATE settings SET allow=:allow WHERE id=:id');
        $this->db->bind(':allow', $allow);
        $this->db->bind(':id', $id);
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}